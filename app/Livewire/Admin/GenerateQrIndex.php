<?php

namespace App\Livewire\Admin;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use Livewire\Component;

class GenerateQrIndex extends Component
{
    public $kelas;

    public function downloadSiswa()
    {
        $this->validate([
            'kelas' => 'required'
        ]);

        return redirect()->route('admin.qr.siswa', ['kelas' => $this->kelas]);
    }

    public function downloadGuru()
    {
        return redirect()->route('admin.qr.guru');
    }

    public function render()
    {
        $kelasList = Kelas::orderBy('tingkat')->get();
        // Calculate total siswa per class
        foreach ($kelasList as $k) {
            $k->total_siswa = Siswa::where('id_kelas', $k->id_kelas)->count();
        }

        $totalSiswa = Siswa::count();
        $totalGuru = Guru::count();

        return view('livewire.admin.generate-qr-index', [
            'kelasList' => $kelasList,
            'totalSiswa' => $totalSiswa,
            'totalGuru' => $totalGuru
        ])->layout('layouts.admin', ['title' => 'Generate QR Code', 'context' => 'qr']);
    }
}
