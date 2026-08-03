<?php

namespace App\Livewire\Teacher;

use App\Models\Kelas;
use App\Models\Siswa;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class GenerateQrIndex extends Component
{
    public $kelas;
    public $kelasList = [];
    public $totalSiswa = 0;

    public function mount()
    {
        $user = Auth::user();
        if ($user && $user->id_guru) {
            $myKelas = Kelas::where('id_wali_kelas', $user->id_guru)->first();
            if ($myKelas) {
                $this->kelas = $myKelas->id_kelas;
                $this->kelasList = collect([$myKelas]);
                $this->totalSiswa = Siswa::where('id_kelas', $this->kelas)->count();
            }
        }
    }

    public function downloadSiswa()
    {
        $this->validate([
            'kelas' => 'required'
        ]);

        return redirect()->route('admin.qr.siswa', ['kelas' => $this->kelas]);
    }

    public function render()
    {
        return view('livewire.teacher.generate-qr-index')->layout('layouts.admin', ['title' => 'Generate QR Code Siswa', 'context' => 'qr']);
    }
}
