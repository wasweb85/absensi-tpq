<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Models\PresensiSiswaModel;
use App\Models\SiswaModel;
use CodeIgniter\I18n\Time;

class Dashboard extends BaseController
{
    protected $presensiModel;
    protected $siswaModel;

    public function __construct()
    {
        $this->presensiModel = new PresensiSiswaModel();
        $this->siswaModel = new SiswaModel();
    }

    public function index()
    {
        $idSiswa = session()->get('siswa_id');
        
        // Ambil filter dari request (default bulan ini)
        $filterWaktu = $this->request->getVar('waktu') ?? 'bulan';
        $filterStatus = $this->request->getVar('status') ?? 'all';

        // Ambil Data Riwayat (filtered for the table)
        $riwayat = $this->presensiModel->getRiwayatSiswa($idSiswa, $filterWaktu, $filterStatus);

        // Hitung Statistik kartu atas — sesuai filter waktu
        $filteredSummary = $this->presensiModel->getRiwayatSiswa($idSiswa, $filterWaktu, 'all');
        $summary = ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpha' => 0];
        foreach ($filteredSummary as $r) {
            if (!isset($r['id_kehadiran'])) continue;
            if ($r['id_kehadiran'] == 1) $summary['hadir']++;
            elseif ($r['id_kehadiran'] == 2) $summary['sakit']++;
            elseif ($r['id_kehadiran'] == 3) $summary['izin']++;
            elseif ($r['id_kehadiran'] == 4) $summary['alpha']++;
        }

        // Get Jadwal and Seragam
        $siswaModel = new \App\Models\SiswaModel();
        $siswa = $siswaModel->find($idSiswa);
        $idKelas = $siswa['id_kelas'] ?? 0;

        $jadwalModel = new \App\Models\JadwalPelajaranModel();
        $seragamModel = new \App\Models\SeragamModel();
        $kelasModel = new \App\Models\KelasModel();

        $rawJadwal = $jadwalModel->getByKelas($idKelas);
        $jadwalMingguan = [
            'Senin' => [], 'Selasa' => [], 'Rabu' => [], 'Kamis' => [], 'Jumat' => [], 'Sabtu' => []
        ];
        foreach ($rawJadwal as $j) {
            $jadwalMingguan[$j['hari']][] = $j;
        }

        $seragam = $seragamModel->getAllByHari();
        $kelasInfo = $kelasModel->getKelas($idKelas);

        $data = [
            'title'        => 'Dashboard Siswa',
            'riwayat'      => $riwayat,
            'summary'      => $summary,
            'filterWaktu'  => $filterWaktu,
            'filterStatus' => $filterStatus,
            'ctx'          => 'dashboard',
            'user'         => session()->get('nama_siswa'),
            'jadwal'       => $jadwalMingguan,
            'seragam'      => $seragam,
            'kelasInfo'    => $kelasInfo
        ];

        return view('siswa/dashboard', $data);
    }

    public function riwayat()
    {
        $idSiswa = session()->get('siswa_id');
        if (!$idSiswa) return redirect()->to('login-siswa');

        // Filter & Date Logic
        $filterStatus = $this->request->getVar('status') ?? 'all';
        $bulan = $this->request->getVar('m') ?? date('m');
        $tahun = $this->request->getVar('y') ?? date('Y');

        // Data for summary & analysis (Target Month)
        // Note: Forcing model to specific month/year by passing 'custom' (need model update or custom logic)
        $db = \Config\Database::connect();
        $builder = $db->table('tb_presensi_siswa');
        $builder->select("tb_presensi_siswa.*, 
            CASE 
                WHEN id_kehadiran = 1 THEN 'Hadir'
                WHEN id_kehadiran = 2 THEN 'Sakit'
                WHEN id_kehadiran = 3 THEN 'Izin'
                WHEN id_kehadiran = 4 THEN 'Tanpa Keterangan'
                ELSE 'Belum Absen'
            END as nama_kehadiran");
        $builder->where('id_siswa', $idSiswa);
        $builder->where('MONTH(tanggal)', $bulan);
        $builder->where('YEAR(tanggal)', $tahun);
        $allBulan = $builder->get()->getResultArray();

        $summary = ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpha' => 0];
        
        foreach ($allBulan as $r) {
            if ($r['id_kehadiran'] == 1) $summary['hadir']++;
            elseif ($r['id_kehadiran'] == 2) $summary['sakit']++;
            elseif ($r['id_kehadiran'] == 3) $summary['izin']++;
            elseif ($r['id_kehadiran'] == 4) $summary['alpha']++;
        }
        
        $totalDays = count($allBulan);
        $attendanceRate = $totalDays > 0 ? round(($summary['hadir'] / $totalDays) * 100) : 0;
        
        $stats = [
            'hadir' => $totalDays > 0 ? round(($summary['hadir'] / $totalDays) * 100) : 0,
            'izin'  => $totalDays > 0 ? round((($summary['izin'] + $summary['sakit']) / $totalDays) * 100) : 0,
            'alpha' => $totalDays > 0 ? round(($summary['alpha'] / $totalDays) * 100) : 0,
        ];

        // Calendar Data (Map day of month to status id)
        $calendar = [];
        foreach ($allBulan as $r) {
            $day = (int)date('j', strtotime($r['tanggal']));
            $calendar[$day] = $r['id_kehadiran'];
        }

        $siswaModel = new \App\Models\SiswaModel();
        $siswa = $siswaModel->find($idSiswa);
        $kelasModel = new \App\Models\KelasModel();
        $kelasInfo = $kelasModel->getKelas($siswa['id_kelas'] ?? 0);

        // Calculate filtered riwayat for the log list
        $riwayat = $allBulan;
        if ($filterStatus != 'all') {
            $riwayat = array_filter($allBulan, function($row) use ($filterStatus) {
                return $row['id_kehadiran'] == $filterStatus;
            });
        }

        $data = [
            'title'          => 'Riwayat Kehadiran Saya',
            'riwayat'        => $riwayat, // Filtered log list
            'summary'        => $summary,
            'stats'          => $stats,
            'attendanceRate' => $attendanceRate,
            'calendar'       => $calendar,
            'filterStatus'   => $filterStatus,
            'ctx'            => 'riwayat',
            'user'           => session()->get('nama_siswa'),
            'kelasInfo'      => $kelasInfo,
            'nis'            => session()->get('nis'),
            'currentMonth'   => date('F Y', strtotime("$tahun-$bulan-01")),
            'daysInMonth'    => date('t', strtotime("$tahun-$bulan-01")),
            'startDay'       => date('N', strtotime("$tahun-$bulan-01")), // 1 (Mon) to 7 (Sun)
            'm'              => $bulan,
            'y'              => $tahun,
        ];

        return view('siswa/riwayat', $data);
    }

    public function profil()
    {
        $idSiswa = session()->get('siswa_id');
        if (!$idSiswa) return redirect()->to('login-siswa');

        $siswa = $this->siswaModel->find($idSiswa);
        $kelasModel = new \App\Models\KelasModel();
        $kelasInfo = $kelasModel->getKelas($siswa['id_kelas'] ?? 0);

        // Get Attendance Rate
        $allLogs = $this->presensiModel->getRiwayatSiswa($idSiswa, 'bulan', 'all');
        $sum = ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpha' => 0];
        foreach ($allLogs as $l) {
            if ($l['id_kehadiran'] == 1) $sum['hadir']++;
            elseif ($l['id_kehadiran'] == 2) $sum['sakit']++;
            elseif ($l['id_kehadiran'] == 3) $sum['izin']++;
            elseif ($l['id_kehadiran'] == 4) $sum['alpha']++;
        }
        $totalSesi = count($allLogs);
        $rate = $totalSesi > 0 ? round(($sum['hadir'] / $totalSesi) * 100) : 0;

        $data = [
            'title'          => 'Profil Saya',
            'user'           => $siswa['nama_siswa'],
            'nis'            => $siswa['nis'],
            'kelasInfo'      => $kelasInfo,
            'attendanceRate' => $rate,
            'summary'        => $sum,
            'ctx'            => 'profil'
        ];

        return view('siswa/profil', $data);
    }
}
