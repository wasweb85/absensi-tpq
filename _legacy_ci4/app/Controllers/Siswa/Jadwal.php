<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Models\JadwalPelajaranModel;
use App\Models\SiswaModel;
use App\Models\KelasModel;
use App\Models\SeragamModel;

class Jadwal extends BaseController
{
    protected JadwalPelajaranModel $jadwalModel;
    protected SiswaModel $siswaModel;
    protected KelasModel $kelasModel;
    protected SeragamModel $seragamModel;

    public function __construct()
    {
        $this->jadwalModel = new JadwalPelajaranModel();
        $this->siswaModel  = new SiswaModel();
        $this->kelasModel  = new KelasModel();
        $this->seragamModel = new SeragamModel();
    }

    public function index()
    {
        $idSiswa = session()->get('siswa_id');
        if (!$idSiswa) {
            return redirect()->to('login-siswa')->with('error', 'Silahkan login terlebih dahulu.');
        }

        $siswa = $this->siswaModel->find($idSiswa);
        $idKelas = $siswa['id_kelas'] ?? 0;
        
        if (!$idKelas) {
            return redirect()->to('siswa/dashboard')->with('error', 'Anda belum terdaftar di kelas manapun.');
        }

        $selectedKelas = $this->kelasModel->getKelas($idKelas);
        $jadwal = $this->jadwalModel->getByKelas($idKelas);

        // Group by day and time for grid
        $slots = [];
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $grid = [];

        foreach ($jadwal as $j) {
            $timeRange = substr($j['jam_mulai'], 0, 5) . ' - ' . substr($j['jam_selesai'], 0, 5);
            if (!in_array($timeRange, $slots)) {
                $slots[] = $timeRange;
            }
            $grid[$timeRange][$j['hari']] = $j;
        }

        // Sort slots by start time
        usort($slots, function($a, $b) {
            return strcmp($a, $b);
        });

        $data = [
            'title'         => 'Jadwal Pelajaran Saya',
            'ctx'           => 'jadwal-pelajaran',
            'kelasInfo'     => $selectedKelas,
            'slots'         => $slots,
            'hariList'      => $hariList,
            'grid'          => $grid,
            'seragam'       => $this->seragamModel->getAllByHari(),
            'nis'           => session()->get('nis'),
            'user'          => session()->get('nama_siswa'),
            'tahun_ajaran'  => $this->generalSettings->school_year ?? '2023/2024',
            'semester'      => 'Ganjil'
        ];

        return view('siswa/jadwal/index', $data);
    }
}
