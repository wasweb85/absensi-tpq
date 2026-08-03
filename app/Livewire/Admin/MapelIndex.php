<?php

namespace App\Livewire\Admin;

use App\Models\Mapel;
use Livewire\Component;
use Illuminate\Validation\Rule;

class MapelIndex extends Component
{
    public $mapelList;

    public $id_mapel;
    public $nama_mapel;

    public $isEdit = false;
    public $showModal = false;

    public function render()
    {
        $this->mapelList = Mapel::orderBy('nama_mapel')->get();

        return view('livewire.admin.mapel-index')->layout('layouts.admin', ['title' => 'Mata Pelajaran', 'context' => 'mapel']);
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
            'nama_mapel' => 'required|min:3|max:100|unique:tb_mapel,nama_mapel',
        ]);

        Mapel::create([
            'nama_mapel' => $this->nama_mapel,
        ]);

        $this->showModal = false;
        session()->flash('success', 'Tambah data berhasil');
    }

    public function edit($id)
    {
        $this->resetFields();
        $mapel = Mapel::findOrFail($id);
        $this->id_mapel = $mapel->id_mapel;
        $this->nama_mapel = $mapel->nama_mapel;

        $this->isEdit = true;
        $this->showModal = true;
    }

    public function update()
    {
        $this->validate([
            'nama_mapel' => ['required', 'min:3', 'max:100', Rule::unique('tb_mapel', 'nama_mapel')->ignore($this->id_mapel, 'id_mapel')],
        ]);

        $mapel = Mapel::findOrFail($this->id_mapel);
        $mapel->update([
            'nama_mapel' => $this->nama_mapel,
        ]);

        $this->showModal = false;
        session()->flash('success', 'Edit data berhasil');
    }

    public function deleteId($id)
    {
        $this->id_mapel = $id;
        $this->dispatch('show-delete-modal');
    }

    public function delete()
    {
        $mapel = Mapel::findOrFail($this->id_mapel);
        $mapel->delete();

        session()->flash('success', 'Data berhasil dihapus');
        $this->dispatch('hide-delete-modal');
    }

    public function resetFields()
    {
        $this->id_mapel = null;
        $this->nama_mapel = '';
    }
}
