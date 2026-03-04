<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Models\PresensiSiswaModel;
use CodeIgniter\I18n\Time;

class Dashboard extends BaseController
{
    protected $presensiModel;

    public function __construct()
    {
        $this->presensiModel = new PresensiSiswaModel();
    }

    public function index()
    {
        $idSiswa = session()->get('siswa_id');
        
        // Ambil filter dari request (default bulan ini)
        $filterWaktu = $this->request->getVar('waktu') ?? 'bulan';
        $filterStatus = $this->request->getVar('status') ?? 'all';

        // Ambil Data Riwayat
        $riwayat = $this->presensiModel->getRiwayatSiswa($idSiswa, $filterWaktu, $filterStatus);

        // Hitung Statistik (Untuk Kartu Atas - Default Bulan Ini)
        // Anda bisa buat query count manual atau loop dari data $riwayat jika filternya bulan
        // Ini contoh sederhana hitung dari data yang ditarik:
        $stats = [
            'H' => 0, 'S' => 0, 'I' => 0, 'A' => 0
        ];
        foreach ($riwayat as $r) {
            if(isset($r['id_kehadiran'])) {
                // Asumsi ID: 1=Hadir, 2=Sakit, 3=Izin, 4=Alpha
                if($r['id_kehadiran'] == 1) $stats['H']++;
                elseif($r['id_kehadiran'] == 2) $stats['S']++;
                elseif($r['id_kehadiran'] == 3) $stats['I']++;
                elseif($r['id_kehadiran'] == 4) $stats['A']++;
            }
        }

        $data = [
            'title' => 'Dashboard Siswa',
            'riwayat' => $riwayat,
            'stats' => $stats,
            'filterWaktu' => $filterWaktu,
            'filterStatus' => $filterStatus,
            'ctx'   => 'dashboard',
            'user' => session()->get('nama_siswa')
        ];

        return view('siswa/dashboard', $data);
    }
}
