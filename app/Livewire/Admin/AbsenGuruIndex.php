<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\PresensiGuru;
use App\Models\Guru;
use Carbon\Carbon;

class AbsenGuruIndex extends Component
{
    public $filter_tanggal;
    
    public $guruList;
    public $kehadiran = []; // array to store [id_guru => id_kehadiran]

    public function mount()
    {
        $user = auth()->user();
        if (!\App\Models\RolePermission::hasAccess($user, 'absen_guru')) {
            session()->flash('error', 'Anda tidak memiliki hak akses ke fitur Input Absensi Guru.');
            return redirect()->to($user && !empty($user->id_guru) ? '/teacher/dashboard' : '/dashboard');
        }

        $this->filter_tanggal = Carbon::today()->toDateString();
        $this->guruList = Guru::orderBy('nama_guru')->get();
        $this->loadData();
    }

    public function updatedFilterTanggal()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->kehadiran = [];
        $guruIds = $this->guruList->pluck('id_guru');
        
        $presensiHariIni = PresensiGuru::whereIn('id_guru', $guruIds)
            ->whereDate('tanggal', $this->filter_tanggal)
            ->get()
            ->keyBy('id_guru');

        foreach ($this->guruList as $guru) {
            $presensi = $presensiHariIni->get($guru->id_guru);
            $this->kehadiran[$guru->id_guru] = $presensi ? (string) $presensi->id_kehadiran : null;
        }
    }

    public function saveAttendance()
    {
        $successCount = 0;

        foreach ($this->kehadiran as $idGuru => $idKehadiran) {
            if ($idKehadiran !== null && $idKehadiran !== '') {
                PresensiGuru::updateOrCreate(
                    [
                        'id_guru' => $idGuru,
                        'tanggal' => $this->filter_tanggal
                    ],
                    [
                        'id_kehadiran' => $idKehadiran,
                        'jam_masuk' => Carbon::now()->toTimeString(),
                        'keterangan' => ''
                    ]
                );
                $successCount++;
            }
        }

        session()->flash('success', "Berhasil menyimpan data absensi untuk $successCount guru.");
    }

    public function render()
    {
        return view('livewire.admin.absen-guru-index')->layout('layouts.admin', ['title' => 'Absensi Guru', 'context' => 'absen-guru']);
    }
}
