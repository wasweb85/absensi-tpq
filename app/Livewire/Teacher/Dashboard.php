<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use App\Models\Kelas;
use App\Models\Siswa;
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
        
        // Translate day to Indonesian
        $hariIndo = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
        $hariIni = $hariIndo[date('l')];

        // Ensure user is teacher (has id_guru)
        if (empty($user->id_guru)) {
            // Ideally we'd handle authorization before this, but for now just pass empty data
            return view('livewire.teacher.dashboard', ['isTeacher' => false])->layout('layouts.admin', ['title' => 'Dashboard', 'context' => 'dashboard']);
        }

        // Get class where teacher is Wali Kelas
        $kelas = Kelas::where('id_wali_kelas', $user->id_guru)->first();
        $isWaliKelas = !empty($kelas);

        $summary = [];
        $jadwalKelasHariIni = collect();

        if ($isWaliKelas) {
            $totalSiswa = Siswa::where('id_kelas', $kelas->id_kelas)->count();
            
            $presensiQuery = PresensiSiswa::whereDate('tanggal', $todayDate)
                ->whereHas('siswa', function($q) use ($kelas) {
                    $q->where('id_kelas', $kelas->id_kelas);
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

            $jadwalKelasHariIni = JadwalPelajaran::with(['mapel', 'guru'])
                ->where('id_kelas', $kelas->id_kelas)
                ->where('hari', $hariIni)
                ->orderBy('jam_mulai', 'asc')
                ->get();
        }

        $seragam = \App\Models\Seragam::where('hari', $hariIni)->get();

        return view('livewire.teacher.dashboard', [
            'isTeacher' => true,
            'isWaliKelas' => $isWaliKelas,
            'hariIni' => $hariIni,
            'kelas' => $kelas,
            'summary' => $summary,
            'jadwalKelasHariIni' => $jadwalKelasHariIni,
            'seragam' => $seragam,
            'dateNow' => Carbon::now()->translatedFormat('d F Y')
        ])->layout('layouts.admin', ['title' => 'Dashboard Wali Kelas', 'context' => 'dashboard']);
    }
}
