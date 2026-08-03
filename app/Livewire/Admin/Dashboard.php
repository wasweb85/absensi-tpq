<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\User;
use App\Models\PresensiSiswa;
use App\Models\PresensiGuru;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $selectedKelas = '';

    public function render()
    {
        $today = Carbon::today()->toDateString();
        
        $totalSiswa = Siswa::count();
        $totalGuru = Guru::count();
        $totalKelas = Kelas::count();
        $totalPetugas = User::where('is_superadmin', 1)->count();
        $kelasOptions = Kelas::withCount('siswa')->get();

        // Absensi Siswa
        $siswaQuery = PresensiSiswa::whereDate('tanggal', $today);
        if ($this->selectedKelas) {
            $siswaQuery->whereHas('siswa', function($q) {
                $q->where('id_kelas', $this->selectedKelas);
            });
        }
        
        $siswaHadir = (clone $siswaQuery)->where('id_kehadiran', \App\Models\Kehadiran::HADIR)->count();
        $siswaSakit = (clone $siswaQuery)->where('id_kehadiran', \App\Models\Kehadiran::SAKIT)->count();
        $siswaIzin = (clone $siswaQuery)->where('id_kehadiran', \App\Models\Kehadiran::IZIN)->count();
        $siswaAlfa = (clone $siswaQuery)->where('id_kehadiran', \App\Models\Kehadiran::ALPHA)->count();

        // Absensi Guru
        $guruHadir = PresensiGuru::whereDate('tanggal', $today)->where('id_kehadiran', \App\Models\Kehadiran::HADIR)->count();
        $guruSakit = PresensiGuru::whereDate('tanggal', $today)->where('id_kehadiran', \App\Models\Kehadiran::SAKIT)->count();
        $guruIzin = PresensiGuru::whereDate('tanggal', $today)->where('id_kehadiran', \App\Models\Kehadiran::IZIN)->count();
        $guruAlfa = PresensiGuru::whereDate('tanggal', $today)->where('id_kehadiran', \App\Models\Kehadiran::ALPHA)->count();

        return view('livewire.admin.dashboard', [
            'totalSiswa' => $totalSiswa,
            'totalGuru' => $totalGuru,
            'totalKelas' => $totalKelas,
            'totalPetugas' => $totalPetugas,
            'kelasOptions' => $kelasOptions,
            'siswaStats' => [
                'hadir' => $siswaHadir,
                'sakit' => $siswaSakit,
                'izin' => $siswaIzin,
                'alfa' => $siswaAlfa,
            ],
            'guruStats' => [
                'hadir' => $guruHadir,
                'sakit' => $guruSakit,
                'izin' => $guruIzin,
                'alfa' => $guruAlfa,
            ]
        ])->layout('layouts.admin', ['title' => 'Dashboard', 'context' => 'dashboard']);
    }
}
