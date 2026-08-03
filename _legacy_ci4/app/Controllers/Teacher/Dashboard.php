<?php

namespace App\Controllers\Teacher;

use App\Controllers\BaseController;
use App\Models\KelasModel;
use App\Models\SiswaModel;
use App\Models\PresensiSiswaModel;
use CodeIgniter\I18n\Time;

use App\Models\KehadiranModel;

class Dashboard extends BaseController
{
    protected KelasModel $kelasModel;
    protected SiswaModel $siswaModel;
    protected PresensiSiswaModel $presensiSiswaModel;
    protected KehadiranModel $kehadiranModel;

    public function __construct()
    {
        $this->kelasModel = new KelasModel();
        $this->siswaModel = new SiswaModel();
        $this->presensiSiswaModel = new PresensiSiswaModel();
        $this->kehadiranModel = new KehadiranModel();
    }

    public function index()
    {
        $user = user();
        
        // Ensure user is a teacher (all teachers have id_guru)
        if (empty($user->id_guru)) {
            return redirect()->to('admin')->with('error', 'Akses ditolak. Anda bukan Guru.');
        }

        // Get class where the teacher is Wali Kelas
        $kelas = $this->kelasModel->getKelasByWali($user->id_guru);

        $now = Time::now();
        $today = $now->toDateString();
        
        $hariIndo = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
        $hariIni = $hariIndo[date('l')];

        $data = [
            'title' => 'Dashboard Guru',
            'ctx' => 'dashboard',
            'hariIni' => $hariIni,
            'isWaliKelas' => !empty($kelas)
        ];

        if (!empty($kelas)) {
            $data['kelas'] = $kelas;
            $data['summary'] = [
                'total_siswa' => $this->siswaModel->getSiswaCountByKelas($kelas['id_kelas']),
                'hadir_hari_ini' => count($this->presensiSiswaModel->getPresensiByKehadiran('1', $today, $kelas['id_kelas'])),
                'sakit_hari_ini' => count($this->presensiSiswaModel->getPresensiByKehadiran('2', $today, $kelas['id_kelas'])),
                'izin_hari_ini' => count($this->presensiSiswaModel->getPresensiByKehadiran('3', $today, $kelas['id_kelas'])),
                'alfa_hari_ini' => count($this->presensiSiswaModel->getPresensiByKehadiran('4', $today, $kelas['id_kelas']))
            ];

            // Weekly chart data for Wali Kelas
            $dateRange = [];
            for ($i = 6; $i >= 0; $i--) {
                if ($i == 0) {
                    $formattedDate = "Hari ini";
                } else {
                    $t = $now->subDays($i);
                    $formattedDate = "{$t->getDay()} " . substr($t->toFormattedDateString(), 0, 3);
                }
                array_push($dateRange, $formattedDate);
            }
            $data['dateRange'] = $dateRange;
            $data['grafikKehadiran'] = $this->presensiSiswaModel->getAttendanceTrend(7, $kelas['id_kelas']);
            
            // Fetch class schedule for today (what the class is studying)
            $jadwalModel = new \App\Models\JadwalPelajaranModel();
            $data['jadwalKelasHariIni'] = $jadwalModel->select('tb_jadwal_pelajaran.*, m.nama_mapel, g.nama_guru')
                        ->join('tb_mapel m', 'm.id_mapel = tb_jadwal_pelajaran.id_mapel', 'left')
                        ->join('tb_guru g', 'g.id_guru = tb_jadwal_pelajaran.id_guru', 'left')
                        ->where('tb_jadwal_pelajaran.id_kelas', $kelas['id_kelas'])
                        ->where('tb_jadwal_pelajaran.hari', $hariIni)
                        ->orderBy('tb_jadwal_pelajaran.jam_mulai', 'ASC')
                        ->findAll();
        }

        // Fetch personal teaching schedule for today (where THIS teacher is teaching)
        $jadwalModel = new \App\Models\JadwalPelajaranModel();
        $data['jadwalMengajar'] = $jadwalModel->select('tb_jadwal_pelajaran.*, m.nama_mapel, k.jilid, k.index_kelas')
                    ->join('tb_mapel m', 'm.id_mapel = tb_jadwal_pelajaran.id_mapel', 'left')
                    ->join('tb_kelas k', 'k.id_kelas = tb_jadwal_pelajaran.id_kelas', 'left')
                    ->where('tb_jadwal_pelajaran.id_guru', $user->id_guru)
                    ->where('tb_jadwal_pelajaran.hari', $hariIni)
                    ->orderBy('tb_jadwal_pelajaran.jam_mulai', 'ASC')
                    ->findAll();

        return view('teacher/dashboard', $data);
    }
    /**
     * Show attendance management page for the Wali Kelas.
     */
    public function attendance()
    {
        $user = user();
        if (!is_wali_kelas()) {
            return redirect()->to('teacher/dashboard')->with('error', 'Anda bukan Wali Kelas.');
        }

        $kelas = $this->kelasModel->getKelasByWali($user->id_guru);
        if (empty($kelas)) {
            return redirect()->to('teacher/dashboard')->with('error', 'Anda belum ditugaskan sebagai Wali Kelas.');
        }

        $data = [
            'title' => 'Manajemen Kehadiran',
            'ctx' => 'attendance',
            'kelas' => $kelas,
            'date' => Time::now()->toDateString()
        ];

        return view('teacher/attendance', $data);
    }

    public function getAttendanceList()
    {
        $idKelas = $this->request->getVar('id_kelas');
        $namaKelas = $this->request->getVar('kelas'); // Just passed back to view
        $tanggal = $this->request->getVar('tanggal');

        $result = $this->presensiSiswaModel->getPresensiByKelasTanggal($idKelas, $tanggal);
        $lewat = Time::parse($tanggal)->isAfter(Time::today());

        $data = [
            'data' => $result,
            'kelas' => $namaKelas,
            'lewat' => $lewat
        ];

        return view('teacher/absen/list_absen_siswa', $data);
    }

    public function getEditModal()
    {
        $idPresensi = $this->request->getVar('id_presensi');
        $idSiswa = $this->request->getVar('id_siswa');

        $data = [
            'presensi' => $this->presensiSiswaModel->getPresensiById($idPresensi),
            'listKehadiran' => $this->kehadiranModel->getAllKehadiran(),
            'data' => $this->siswaModel->getSiswaById($idSiswa)
        ];

        return view('teacher/absen/modal_ubah_kehadiran', $data);
    }

    public function updateSingleAttendance()
    {
        $idKehadiran = $this->request->getVar('id_kehadiran');
        $idSiswa = $this->request->getVar('id_siswa');
        $idKelas = $this->request->getVar('id_kelas');
        $tanggal = $this->request->getVar('tanggal');
        $jamMasuk = $this->request->getVar('jam_masuk');
        $jamKeluar = $this->request->getVar('jam_keluar');
        $keterangan = $this->request->getVar('keterangan');

        // Check if attendance exists
        $cek = $this->presensiSiswaModel->cekAbsen($idSiswa, $tanggal);

        // Update or Insert (updatePresensi handles logic if first arg is ID or null/false)
        /* 
           wait, presensiSiswaModel->updatePresensi(idPresensi, ...)
           cekAbsen returns ID if exists, OR false.
           If false, we pass null to create new.
        */
        $result = $this->presensiSiswaModel->updatePresensi(
            $cek == false ? null : $cek,
            $idSiswa,
            $idKelas,
            $tanggal,
            $idKehadiran,
            $jamMasuk ?: null,
            $jamKeluar ?: null,
            $keterangan
        );

        $response['nama_siswa'] = $this->siswaModel->getSiswaById($idSiswa)['nama_siswa'];
        $response['status'] = $result ? true : false;

        return $this->response->setJSON($response);
    }

    /**
     * Show manual attendance page with inline radio buttons.
     * All teachers can access this — wali kelas defaults to their class,
     * others can pick any class from the dropdown.
     */
    public function absenManual()
    {
        $user = user();

        $allKelas = $this->kelasModel->getDataKelas();

        // Determine selected class
        $idKelas = $this->request->getVar('id_kelas');

        // If wali kelas and no class chosen yet, default to theirs
        if (empty($idKelas) && is_wali_kelas()) {
            $myKelas = $this->kelasModel->getKelasByWali($user->id_guru);
            $idKelas = $myKelas['id_kelas'] ?? null;
        }

        $siswa = [];
        $selectedKelas = null;
        $existingAttendance = [];

        $today = $this->request->getVar('tanggal') ?? Time::now()->toDateString();

        if (!empty($idKelas)) {
            foreach ($allKelas as $k) {
                if ($k['id_kelas'] == $idKelas) {
                    $selectedKelas = $k;
                    break;
                }
            }

            $siswa = $this->siswaModel->getSiswaByKelas($idKelas);

            foreach ($siswa as $s) {
                $presensi = $this->presensiSiswaModel->getPresensiByIdSiswaTanggal($s['id_siswa'], $today);
                if ($presensi) {
                    $existingAttendance[$s['id_siswa']] = $presensi['id_kehadiran'];
                }
            }
        }

        $data = [
            'title'              => 'Absensi Manual',
            'ctx'                => 'absen-manual',
            'allKelas'           => $allKelas,
            'selectedKelas'      => $selectedKelas,
            'idKelas'            => $idKelas,
            'siswa'              => $siswa,
            'date'               => $today,
            'existingAttendance' => $existingAttendance,
        ];

        return view('teacher/absen_manual', $data);
    }

    /**
     * Save batch manual attendance from inline radio buttons.
     */
    public function saveManualAttendance()
    {
        $tanggal   = $this->request->getVar('tanggal');
        $idKelas   = $this->request->getVar('id_kelas');
        $kehadiran = $this->request->getVar('kehadiran'); // array: [id_siswa => id_kehadiran]

        if (empty($idKelas)) {
            return $this->response->setJSON(['status' => false, 'message' => 'Kelas tidak dipilih.']);
        }

        if (empty($kehadiran) || !is_array($kehadiran)) {
            return $this->response->setJSON(['status' => false, 'message' => 'Data kehadiran kosong.']);
        }

        $successCount = 0;
        $failCount    = 0;

        foreach ($kehadiran as $idSiswa => $idKehadiran) {
            $cek = $this->presensiSiswaModel->cekAbsen($idSiswa, $tanggal);

            $result = $this->presensiSiswaModel->updatePresensi(
                $cek == false ? null : $cek,
                $idSiswa,
                $idKelas,
                $tanggal,
                $idKehadiran,
                null,
                null,
                ''
            );

            if ($result) {
                $successCount++;
            } else {
                $failCount++;
            }
        }

        return $this->response->setJSON([
            'status'  => true,
            'message' => "Berhasil menyimpan $successCount data absensi." . ($failCount > 0 ? " Gagal: $failCount." : ''),
        ]);
    }
}
