<?php

namespace App\Livewire\Admin;

use App\Models\Seragam;
use Livewire\Component;

class SeragamIndex extends Component
{
    public $seragamList;

    public $id_seragam;
    public $hari;
    public $nama_seragam;
    public $deskripsi;
    public $keterangan;

    public $isEdit = false;
    public $showModal = false;

    public function render()
    {
        $this->seragamList = Seragam::orderByRaw('FIELD(hari, "Senin","Selasa","Rabu","Kamis","Jumat","Sabtu")')->get();

        return view('livewire.admin.seragam-index')->layout('layouts.admin', ['title' => 'Data Seragam', 'context' => 'seragam']);
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
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu|unique:tb_seragam,hari',
            'nama_seragam' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ], [
            'hari.unique' => 'Jadwal seragam untuk hari ini sudah ada.'
        ]);

        Seragam::create([
            'hari' => $this->hari,
            'nama_seragam' => $this->nama_seragam,
            'deskripsi' => $this->deskripsi,
            'keterangan' => $this->keterangan,
        ]);

        $this->showModal = false;
        session()->flash('success', 'Tambah data berhasil');
    }

    public function edit($id)
    {
        $this->resetFields();
        $seragam = Seragam::findOrFail($id);
        
        $this->id_seragam = $seragam->id_seragam;
        $this->hari = $seragam->hari;
        $this->nama_seragam = $seragam->nama_seragam;
        $this->deskripsi = $seragam->deskripsi;
        $this->keterangan = $seragam->keterangan;

        $this->isEdit = true;
        $this->showModal = true;
    }

    public function update()
    {
        $this->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu|unique:tb_seragam,hari,' . $this->id_seragam . ',id_seragam',
            'nama_seragam' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ], [
            'hari.unique' => 'Jadwal seragam untuk hari ini sudah ada.'
        ]);

        $seragam = Seragam::findOrFail($this->id_seragam);
        $seragam->update([
            'hari' => $this->hari,
            'nama_seragam' => $this->nama_seragam,
            'deskripsi' => $this->deskripsi,
            'keterangan' => $this->keterangan,
        ]);

        $this->showModal = false;
        session()->flash('success', 'Edit data berhasil');
    }

    public function deleteId($id)
    {
        $this->id_seragam = $id;
        $this->dispatch('show-delete-modal');
    }

    public function delete()
    {
        $seragam = Seragam::findOrFail($this->id_seragam);
        $seragam->delete();

        session()->flash('success', 'Data berhasil dihapus');
        $this->dispatch('hide-delete-modal');
    }

    public function resetFields()
    {
        $this->id_seragam = null;
        $this->hari = '';
        $this->nama_seragam = '';
        $this->deskripsi = '';
        $this->keterangan = '';
    }
}
