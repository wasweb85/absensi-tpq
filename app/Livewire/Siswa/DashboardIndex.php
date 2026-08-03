<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use App\Models\PresensiSiswa;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\GeneralSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardIndex extends Component
{
    public $filterWaktu = 'bulan';

    public function mount()
    {
        $this->filterWaktu = request()->query('waktu', 'bulan');
    }

    public function updateFilter()
    {
        // This will be called when select changes
        // Livewire updates property automatically, so just re-render
    }

    public function render()
    {
        $siswa = Auth::guard('siswa')->user();
        $siswa->load('kelas.guru');
        $siswaId = $siswa->id_siswa;
        
        $kelasInfo = null;
        if ($siswa->kelas) {
            $kelasInfo = (object) [
                'kelas' => $siswa->kelas->tingkat . ' ' . $siswa->kelas->index_kelas,
                'nama_wali_kelas' => $siswa->kelas->guru->nama_guru ?? 'Belum Ditentukan'
            ];
        }

        $settings = GeneralSetting::first();

        // Base Query for riwayat
        $query = PresensiSiswa::query()
            ->where('id_siswa', $siswaId)
            ->with(['kehadiran'])
            ->orderBy('tanggal', 'desc');

        if ($this->filterWaktu == 'bulan') {
            $query->whereMonth('tanggal', now()->month)
                  ->whereYear('tanggal', now()->year);
        } elseif ($this->filterWaktu == 'minggu') {
            $startOfWeek = now()->startOfWeek()->toDateString();
            $endOfWeek = now()->endOfWeek()->toDateString();
            $query->whereBetween('tanggal', [$startOfWeek, $endOfWeek]);
        }

        $allRiwayat = $query->get();

        // Calculate summary based on Kehadiran model constants
        $summary = [
            'hadir' => $allRiwayat->where('id_kehadiran', \App\Models\Kehadiran::HADIR)->count(),
            'sakit' => $allRiwayat->where('id_kehadiran', \App\Models\Kehadiran::SAKIT)->count(),
            'izin' => $allRiwayat->where('id_kehadiran', \App\Models\Kehadiran::IZIN)->count(),
            'alpha' => $allRiwayat->where('id_kehadiran', \App\Models\Kehadiran::ALPHA)->count(),
        ];

        return view('livewire.siswa.dashboard-index', [
            'user' => Auth::guard('siswa')->user()->nama_siswa,
            'kelasInfo' => $kelasInfo,
            'generalSettings' => $settings,
            'summary' => $summary,
            'riwayat' => $allRiwayat
        ])->layout('layouts.siswa', ['title' => 'Dashboard Siswa', 'context' => 'dashboard']);
    }
}
