<?php

namespace App\Livewire\Admin;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use Livewire\Component;

class GenerateLaporanIndex extends Component
{
    public $tanggalMulai;
    public $tanggalAkhir;
    public $kelas = "";
    
    public $tanggalMulaiGuru;
    public $tanggalAkhirGuru;

    public function mount()
    {
        $this->tanggalMulai = date('Y-m-01');
        $this->tanggalAkhir = date('Y-m-t');
        
        $this->tanggalMulaiGuru = date('Y-m-01');
        $this->tanggalAkhirGuru = date('Y-m-t');
    }

    public function exportSiswa($type)
    {
        $this->validate([
            'tanggalMulai' => 'required|date',
            'tanggalAkhir' => 'required|date|after_or_equal:tanggalMulai',
        ]);

        return redirect()->route('admin.laporan.siswa', [
            'tanggal_mulai' => $this->tanggalMulai,
            'tanggal_akhir' => $this->tanggalAkhir,
            'kelas' => $this->kelas,
            'type' => $type
        ]);
    }

    public function exportGuru($type)
    {
        $this->validate([
            'tanggalMulaiGuru' => 'required|date',
            'tanggalAkhirGuru' => 'required|date|after_or_equal:tanggalMulaiGuru',
        ]);

        return redirect()->route('admin.laporan.guru', [
            'tanggal_mulai' => $this->tanggalMulaiGuru,
            'tanggal_akhir' => $this->tanggalAkhirGuru,
            'type' => $type
        ]);
    }

    public function render()
    {
        $kelasList = Kelas::withCount('siswa')->orderBy('tingkat')->get();
        // total_siswa is automatically available as siswa_count attribute
        foreach ($kelasList as $k) {
            $k->total_siswa = $k->siswa_count;
        }

        return view('livewire.admin.generate-laporan-index', [
            'kelasList' => $kelasList
        ])->layout('layouts.admin', ['title' => 'Generate Laporan', 'context' => 'laporan']);
    }
}

