<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;

class JadwalIndex extends Component
{
    public $jadwalList = [];
    public $kelasList;
    public $filter_kelas = '';

    public function mount()
    {
        $this->kelasList = Kelas::orderBy('tingkat')->get();
        $user = Auth::user();
        
        if ($user && $user->id_guru) {
            $myKelas = Kelas::where('id_wali_kelas', $user->id_guru)->first();
            if ($myKelas) {
                $this->filter_kelas = $myKelas->id_kelas;
            } else if ($this->kelasList->isNotEmpty()) {
                $this->filter_kelas = $this->kelasList->first()->id_kelas;
            }
        }
    }

    public function render()
    {
        $query = JadwalPelajaran::with(['kelas', 'mapel', 'guru']);
        
        if ($this->filter_kelas) {
            $query->where('id_kelas', $this->filter_kelas);
        }

        $jadwalRaw = $query->orderByRaw('FIELD(hari, "Senin","Selasa","Rabu","Kamis","Jumat","Sabtu")')
                           ->orderBy('jam_mulai', 'asc')
                           ->get();

        $hariUrut = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        
        $jadwalGrouped = $jadwalRaw->groupBy('hari');
        
        $this->jadwalList = [];
        foreach ($hariUrut as $hari) {
            $this->jadwalList[$hari] = $jadwalGrouped->get($hari, collect())->all();
        }

        return view('livewire.teacher.jadwal-index')->layout('layouts.admin', ['title' => 'Jadwal Pelajaran Mingguan', 'context' => 'jadwal-pelajaran']);
    }
}
