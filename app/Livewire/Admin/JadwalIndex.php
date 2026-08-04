<?php

namespace App\Livewire\Admin;

use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Guru;
use Livewire\Component;

class JadwalIndex extends Component
{
    public $jadwalList;
    public $kelasList;
    public $mapelList;
    public $guruList;

    public $filter_kelas = '';

    public $id_jadwal;
    public $id_kelas;
    public $id_mapel;
    public $id_guru;
    public $hari;
    public $keterangan;

    public $isEdit = false;
    public $showModal = false;

    public function mount()
    {
        $this->kelasList = Kelas::orderBy('tingkat')->get();
        $this->mapelList = Mapel::orderBy('nama_mapel')->get();
        $this->guruList = Guru::orderBy('nama_guru')->get();
    }

    public function render()
    {
        $query = JadwalPelajaran::with(['kelas', 'mapel', 'guru']);
        
        if ($this->filter_kelas) {
            $query->where('id_kelas', $this->filter_kelas);
        }

        $jadwalRaw = $query->orderByRaw('FIELD(hari, "Senin","Selasa","Rabu","Kamis","Jumat","Sabtu")')
                           ->orderBy('id_jadwal', 'asc')
                           ->get();

        $hariUrut = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $this->jadwalList = array_fill_keys($hariUrut, []);
        
        foreach ($jadwalRaw as $j) {
            $this->jadwalList[$j->hari][] = $j;
        }

        return view('livewire.admin.jadwal-index')->layout('layouts.admin', ['title' => 'Jadwal Pelajaran', 'context' => 'jadwal-pelajaran']);
    }

    public function create()
    {
        $this->resetFields();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function store()
    {
        $this->validate([
            'id_kelas' => 'required|exists:tb_kelas,id_kelas',
            'id_mapel' => 'required|exists:tb_mapel,id_mapel',
            'id_guru' => 'required|exists:tb_guru,id_guru',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'keterangan' => 'nullable|string'
        ]);

        JadwalPelajaran::create([
            'id_kelas' => $this->id_kelas,
            'id_mapel' => $this->id_mapel,
            'id_guru' => $this->id_guru,
            'hari' => $this->hari,
            'keterangan' => $this->keterangan,
        ]);

        $this->showModal = false;
        session()->flash('success', 'Tambah jadwal berhasil');
    }

    public function edit($id = null)
    {
        if (!$id) return;
        $this->resetFields();
        $jadwal = JadwalPelajaran::findOrFail($id);
        
        $this->id_jadwal = $jadwal->id_jadwal;
        $this->id_kelas = $jadwal->id_kelas;
        $this->id_mapel = $jadwal->id_mapel;
        $this->id_guru = $jadwal->id_guru;
        $this->hari = $jadwal->hari;
        $this->keterangan = $jadwal->keterangan;

        $this->isEdit = true;
        $this->showModal = true;
    }

    public function update()
    {
        $this->validate([
            'id_kelas' => 'required|exists:tb_kelas,id_kelas',
            'id_mapel' => 'required|exists:tb_mapel,id_mapel',
            'id_guru' => 'required|exists:tb_guru,id_guru',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'keterangan' => 'nullable|string'
        ]);

        $jadwal = JadwalPelajaran::findOrFail($this->id_jadwal);
        $jadwal->update([
            'id_kelas' => $this->id_kelas,
            'id_mapel' => $this->id_mapel,
            'id_guru' => $this->id_guru,
            'hari' => $this->hari,
            'keterangan' => $this->keterangan,
        ]);

        $this->showModal = false;
        session()->flash('success', 'Edit jadwal berhasil');
    }

    public function deleteId($id = null)
    {
        if (!$id) return;
        $this->id_jadwal = $id;
        $this->dispatch('show-delete-modal');
    }

    public function delete()
    {
        $jadwal = JadwalPelajaran::findOrFail($this->id_jadwal);
        $jadwal->delete();

        session()->flash('success', 'Data berhasil dihapus');
        $this->dispatch('hide-delete-modal');
    }

    public function resetFields()
    {
        $this->id_jadwal = null;
        $this->id_kelas = '';
        $this->id_mapel = '';
        $this->id_guru = '';
        $this->hari = '';
        $this->keterangan = '';
    }
}
