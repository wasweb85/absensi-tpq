<?php

namespace App\Livewire\Teacher;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Guru;
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
        if (!\App\Models\RolePermission::hasAccess($user, 'generate_qr')) {
            session()->flash('error', 'Anda tidak memiliki hak akses ke fitur Generate QR Code.');
            return redirect()->to('/teacher/dashboard');
        }

        if ($user && $user->id_guru) {
            $guru = Guru::with('kelasBinaan')->find($user->id_guru);
            if ($guru) {
                $waliKelas = Kelas::where('id_wali_kelas', $user->id_guru)->get();
                $binaanKelas = $guru->kelasBinaan;
                $this->kelasList = $waliKelas->merge($binaanKelas)->unique('id_kelas')->sortBy('tingkat');
                if ($this->kelasList->count() > 0) {
                    $this->kelas = $this->kelasList->first()->id_kelas;
                    $this->updatedKelas();
                }
            }
        }
    }

    public function updatedKelas()
    {
        if ($this->kelas) {
            $this->totalSiswa = Siswa::where('id_kelas', $this->kelas)->count();
        } else {
            $this->totalSiswa = 0;
        }
    }

    public function downloadSiswa()
    {
        $this->validate([
            'kelas' => 'required'
        ], [
            'kelas.required' => 'Silakan pilih kelas terlebih dahulu.'
        ]);

        return redirect()->route('admin.qr.siswa', ['kelas' => $this->kelas]);
    }

    public function render()
    {
        return view('livewire.teacher.generate-qr-index')->layout('layouts.admin', ['title' => 'Download Kartu QR Code Siswa', 'context' => 'qr']);
    }
}
