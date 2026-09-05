<?php

namespace App\Livewire\Teacher;

use App\Models\Kelas;
use App\Models\Siswa;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class GenerateLaporanIndex extends Component
{
    public $tanggalSiswa;
    public $kelas = "";
    
    public $isWaliKelas = false;
    public $kelasList = [];

    public function mount()
    {
        $this->tanggalSiswa = date('Y-m');
        
        $user = Auth::user();
        if ($user && $user->id_guru) {
            $myKelas = Kelas::where('id_wali_kelas', $user->id_guru)->first();
            if ($myKelas) {
                $this->isWaliKelas = true;
                $this->kelas = $myKelas->id_kelas;
                $this->kelasList = collect([$myKelas]);
            }
        }
    }

    public function exportSiswa($type)
    {
        $this->validate([
            'tanggalSiswa' => 'required',
            'kelas' => 'required'
        ]);

        return redirect()->route('admin.laporan.siswa', [
            'tanggal' => $this->tanggalSiswa,
            'kelas' => $this->kelas,
            'type' => $type
        ]);
    }

    public function render()
    {
        return view('livewire.teacher.generate-laporan-index')->layout('layouts.admin', ['title' => 'Laporan Kelas', 'nav_title' => 'Laporan', 'context' => 'laporan-kelas']);
    }
}
