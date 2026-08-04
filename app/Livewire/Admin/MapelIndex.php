<?php

namespace App\Livewire\Admin;

use App\Models\Mapel;
use Livewire\Component;
use Illuminate\Validation\Rule;

use Livewire\WithPagination;

class MapelIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perPage = 10;

    public $id_mapel;
    public $nama_mapel;

    public $isEdit = false;
    public $showModal = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        $mapelList = Mapel::when($this->search, function ($query) {
            $query->where('nama_mapel', 'like', '%' . $this->search . '%');
        })->orderBy('nama_mapel')->paginate($this->perPage);

        return view('livewire.admin.mapel-index', [
            'mapelList' => $mapelList
        ])->layout('layouts.admin', ['title' => 'Mata Pelajaran', 'context' => 'mapel']);
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
