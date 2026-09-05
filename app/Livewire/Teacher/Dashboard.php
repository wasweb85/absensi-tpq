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

            $totalSaldo = Siswa::whereIn('id_kelas', $assignedKelasIds)->sum('saldo_tabungan');
            
            $siswaIds = Siswa::whereIn('id_kelas', $assignedKelasIds)->pluck('id_siswa')->toArray();

            $unsettledSetor = \App\Models\Tabungan::whereIn('id_siswa', $siswaIds)
                ->where('status_setoran', 'belum')
                ->where('jenis_transaksi', 'setor')
                ->sum('nominal');

            $unsettledTarik = \App\Models\Tabungan::whereIn('id_siswa', $siswaIds)
                ->where('status_setoran', 'belum')
                ->where('jenis_transaksi', 'tarik')
                ->sum('nominal');

            $uangDiTangan = $unsettledSetor - $unsettledTarik;

            $aktifitasTabungan = \App\Models\Tabungan::with('siswa')
                ->whereIn('id_siswa', $siswaIds)
                ->orderBy('created_at', 'desc')
                ->take(4)
                ->get();
                
            $aktifitasSetoran = \App\Models\SetoranBendahara::where('id_guru', $user->id_guru)
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();
                
            $aktifitasGabung = collect();
            foreach ($aktifitasTabungan as $t) {
                $t->activity_type = 'tabungan';
                $aktifitasGabung->push($t);
            }
            foreach ($aktifitasSetoran as $s) {
                $s->activity_type = 'setoran';
                $s->created_at = $s->tanggal;
                $aktifitasGabung->push($s);
            }
            
            $aktifitasGabung = $aktifitasGabung->sortByDesc('created_at')->take(4);
        }

        return view('livewire.teacher.dashboard', [
            'isTeacher' => true,
            'isWaliKelas' => $isWaliKelas,
            'assignedClasses' => $assignedClasses,
            'hariIni' => $hariIni,
            'summary' => $summary,
            'jadwalKelasHariIni' => $jadwalKelasHariIni,
            'totalSaldo' => $totalSaldo ?? 0,
            'uangDiTangan' => $uangDiTangan ?? 0,
            'aktifitasGabung' => $aktifitasGabung ?? collect(),
            'dateNow' => Carbon::now()->translatedFormat('d F Y')
        ])->layout('layouts.admin', ['title' => 'Dashboard Wali Kelas', 'context' => 'dashboard']);
    }
}
