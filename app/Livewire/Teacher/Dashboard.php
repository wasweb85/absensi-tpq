<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\PresensiSiswa;
use App\Models\JadwalPelajaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public function render()
    {
        $user = Auth::user();
        $todayDate = Carbon::today()->toDateString();
        
        $hariIndo = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
        $hariIni = $hariIndo[date('l')];

        // Ensure user is teacher (has id_guru)
        if (empty($user->id_guru)) {
            return view('livewire.teacher.dashboard', ['isTeacher' => false])->layout('layouts.admin', ['title' => 'Dashboard', 'context' => 'dashboard']);
        }

        $guru = Guru::with('kelasBinaan')->find($user->id_guru);
        $waliKelasList = Kelas::where('id_wali_kelas', $user->id_guru)->get();
        $binaanList = $guru ? $guru->kelasBinaan : collect();
        $assignedClasses = $waliKelasList->merge($binaanList)->unique('id_kelas')->sortBy('tingkat');

        $isWaliKelas = $assignedClasses->count() > 0;
        $summary = [
            'total_siswa' => 0,
            'hadir_hari_ini' => 0,
            'sakit_hari_ini' => 0,
            'izin_hari_ini' => 0,
            'alfa_hari_ini' => 0
        ];
        $jadwalKelasHariIni = collect();

        if ($isWaliKelas) {
            $assignedKelasIds = $assignedClasses->pluck('id_kelas')->toArray();
            
            $totalSiswa = Siswa::whereIn('id_kelas', $assignedKelasIds)->count();
            
            $presensiQuery = PresensiSiswa::whereDate('tanggal', $todayDate)
                ->whereHas('siswa', function($q) use ($assignedKelasIds) {
                    $q->whereIn('id_kelas', $assignedKelasIds);
                });

            $hadir = (clone $presensiQuery)->where('id_kehadiran', \App\Models\Kehadiran::HADIR)->count();
            $sakit = (clone $presensiQuery)->where('id_kehadiran', \App\Models\Kehadiran::SAKIT)->count();
            $izin = (clone $presensiQuery)->where('id_kehadiran', \App\Models\Kehadiran::IZIN)->count();
            $alfa = (clone $presensiQuery)->where('id_kehadiran', \App\Models\Kehadiran::ALPHA)->count();

            $summary = [
                'total_siswa' => $totalSiswa,
                'hadir_hari_ini' => $hadir,
                'sakit_hari_ini' => $sakit,
                'izin_hari_ini' => $izin,
                'alfa_hari_ini' => $alfa
            ];

            $jadwalKelasHariIni = JadwalPelajaran::with(['mapel', 'guru', 'kelas'])
                ->whereIn('id_kelas', $assignedKelasIds)
                ->where('hari', $hariIni)
                ->orderBy('id_jadwal', 'asc')
                ->get();
        }

        return view('livewire.teacher.dashboard', [
            'isTeacher' => true,
            'isWaliKelas' => $isWaliKelas,
            'assignedClasses' => $assignedClasses,
            'hariIni' => $hariIni,
            'summary' => $summary,
            'jadwalKelasHariIni' => $jadwalKelasHariIni,
            'dateNow' => Carbon::now()->translatedFormat('d F Y')
        ])->layout('layouts.admin', ['title' => 'Dashboard Wali Kelas', 'context' => 'dashboard']);
    }
}
