<?php

namespace App\Controllers\Teacher;

use App\Controllers\BaseController;
use App\Models\JadwalPelajaranModel;
use App\Models\KelasModel;

class JadwalPelajaran extends BaseController
{
    protected JadwalPelajaranModel $jadwalModel;
    protected KelasModel $kelasModel;

    public function __construct()
    {
        $this->jadwalModel = new JadwalPelajaranModel();
        $this->kelasModel  = new KelasModel();
    }

    public function index()
    {
        $user = user();
        if (empty($user->id_guru)) {
            return redirect()->to('admin')->with('error', 'Akses ditolak. Anda bukan Guru.');
        }

        $allKelas = $this->kelasModel->getDataKelas();
        
        // Default to teacher's class if they are a wali kelas, otherwise first class
        $idKelas = $this->request->getVar('id_kelas');
        if (empty($idKelas)) {
            $myKelas = $this->kelasModel->getKelasByWali($user->id_guru);
            $idKelas = !empty($myKelas) ? $myKelas['id_kelas'] : ($allKelas[0]['id_kelas'] ?? null);
        }

        $jadwal = [];
        $selectedKelas = null;
        if (!empty($idKelas)) {
            $jadwal = $this->jadwalModel->getByKelas($idKelas);
            foreach ($allKelas as $k) {
                if ($k['id_kelas'] == $idKelas) {
                    $selectedKelas = $k;
                    break;
                }
            }
        }

        // Group by day and time for grid
        // Rows will be unique time slots (jam_mulai - jam_selesai)
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
            'title'         => 'Jadwal Pelajaran Mingguan',
            'ctx'           => 'jadwal-pelajaran',
            'allKelas'      => $allKelas,
            'selectedKelas' => $selectedKelas,
            'idKelas'       => $idKelas,
            'slots'         => $slots,
            'hariList'      => $hariList,
            'grid'          => $grid
        ];

        return view('teacher/jadwal/index', $data);
    }
}
