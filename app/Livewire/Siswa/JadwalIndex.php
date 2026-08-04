<?php

namespace App\Livewire\Siswa;

use Livewire\Component;
use App\Models\JadwalPelajaran;
use Illuminate\Support\Facades\Auth;

class JadwalIndex extends Component
{
    public function render()
    {
        $siswa = Auth::guard('siswa')->user();
        $siswa->load('kelas.guru');
        
        $idKelas = $siswa->id_kelas;
        
        $jadwalMingguan = JadwalPelajaran::with(['mapel', 'guru'])
            ->where('id_kelas', $idKelas)
            ->orderByRaw('FIELD(hari, "Senin","Selasa","Rabu","Kamis","Jumat","Sabtu")')
            ->orderBy('id_jadwal', 'asc')
            ->get()
            ->groupBy('hari')
            ->toArray();

        // Ensure all days exist in the array
        $hariUrut = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $jadwalMingguan = array_merge(array_fill_keys($hariUrut, []), $jadwalMingguan);

        $kelasInfo = null;
        if ($siswa->kelas) {
            $kelasInfo = (object) [
                'kelas' => $siswa->kelas->tingkat . ' ' . $siswa->kelas->index_kelas,
            ];
        }
        
        $settings = \App\Models\GeneralSetting::first();

        return view('livewire.siswa.jadwal-index', [
            'jadwalMingguan' => $jadwalMingguan,
            'kelasInfo' => $kelasInfo,
            'tahun_ajaran' => $settings->school_year ?? '-',
            'semester' => $settings->semester ?? '-'
        ])->layout('layouts.siswa', ['title' => 'Jadwal Pelajaran', 'context' => 'jadwal']);
    }
}
