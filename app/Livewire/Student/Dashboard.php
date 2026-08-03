<?php

namespace App\Livewire\Student;

use Livewire\Component;
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

        if (empty($user->id_siswa)) {
            return view('livewire.student.dashboard', ['isStudent' => false])->layout('layouts.admin', ['title' => 'Dashboard Siswa']);
        }

        $siswa = Siswa::with('kelas')->find($user->id_siswa);
        
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Get Attendance Summary for Current Month
        $presensiBulanIni = PresensiSiswa::where('id_siswa', $user->id_siswa)
            ->whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->get();

        $summary = [
            'hadir' => $presensiBulanIni->where('id_kehadiran', 1)->count(),
            'sakit' => $presensiBulanIni->where('id_kehadiran', 2)->count(),
            'izin' => $presensiBulanIni->where('id_kehadiran', 3)->count(),
            'alfa' => $presensiBulanIni->where('id_kehadiran', 4)->count(),
        ];

        // Get recent attendance logs (limit to 5 for dashboard)
        $riwayat = PresensiSiswa::where('id_siswa', $user->id_siswa)
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        // Weekly schedule for the student's class
        $rawJadwal = JadwalPelajaran::with(['mapel', 'guru'])
            ->where('id_kelas', $siswa->id_kelas)
            ->orderBy('jam_mulai', 'asc')
            ->get();

        $jadwalMingguan = [
            'Senin' => [], 'Selasa' => [], 'Rabu' => [], 'Kamis' => [], 'Jumat' => [], 'Sabtu' => []
        ];

        foreach ($rawJadwal as $j) {
            if (array_key_exists($j->hari, $jadwalMingguan)) {
                $jadwalMingguan[$j->hari][] = $j;
            }
        }

        return view('livewire.student.dashboard', [
            'isStudent' => true,
            'siswa' => $siswa,
            'summary' => $summary,
            'riwayat' => $riwayat,
            'jadwalMingguan' => $jadwalMingguan,
            'bulanTahun' => Carbon::now()->translatedFormat('F Y')
        ])->layout('layouts.admin', ['title' => 'Dashboard Siswa']);
    }
}
