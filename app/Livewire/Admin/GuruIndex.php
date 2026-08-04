<?php

namespace App\Livewire\Admin;

use App\Models\Guru;
use Livewire\Component;
use Illuminate\Validation\Rule;

use Livewire\WithPagination;

class GuruIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perPage = 10;

    public $id_guru;
    public $nuptk;
    public $nama_guru; // maps to nama in CI4
    public $jk; // jenis kelamin
    public $alamat;
    public $no_hp;
    public $rfid;
    public $can_crud_siswa = false;

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
        $guruList = Guru::when($this->search, function ($query) {
            $query->where('nama_guru', 'like', '%' . $this->search . '%')
                  ->orWhere('nuptk', 'like', '%' . $this->search . '%');
        })->paginate($this->perPage);

        return view('livewire.admin.guru-index', [
            'guruList' => $guruList
        ])->layout('layouts.admin', ['title' => 'Data Guru', 'context' => 'guru']);
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
            'nuptk' => 'required|min:16|max:20|unique:tb_guru,nuptk',
            'nama_guru' => 'required|min:3',
            'jk' => 'required|in:Laki-laki,Perempuan',
            'no_hp' => 'required|min:5|max:20',
            'rfid' => 'nullable|unique:tb_guru,rfid_code',
            'can_crud_siswa' => 'boolean'
        ], [
            'nuptk.unique' => 'NUPTK ini telah terdaftar.',
            'rfid.unique' => 'RFID code sudah digunakan.'
        ]);

        Guru::create([
            'nuptk' => $this->nuptk,
            'nama_guru' => $this->nama_guru,
            'jenis_kelamin' => $this->jk,
            'alamat' => $this->alamat,
            'no_hp' => $this->no_hp,
            'rfid_code' => $this->rfid,
            'unique_code' => \Illuminate\Support\Str::random(16),
            'can_crud_siswa' => $this->can_crud_siswa ? 1 : 0,
        ]);

        $this->showModal = false;
        session()->flash('success', 'Tambah data berhasil');
    }

    public function edit($id)
    {
        $this->resetFields();
        $guru = Guru::findOrFail($id);
        $this->id_guru = $guru->id_guru;
        $this->nuptk = $guru->nuptk;
        $this->nama_guru = $guru->nama_guru;
        $this->jk = $guru->jenis_kelamin;
        $this->alamat = $guru->alamat;
        $this->no_hp = $guru->no_hp;
        $this->rfid = $guru->rfid_code;
        $this->can_crud_siswa = (bool) $guru->can_crud_siswa;

        $this->isEdit = true;
        $this->showModal = true;
    }

    public function update()
    {
        $this->validate([
            'nuptk' => ['required', 'min:16', 'max:20', Rule::unique('tb_guru', 'nuptk')->ignore($this->id_guru, 'id_guru')],
            'nama_guru' => 'required|min:3',
            'jk' => 'required|in:Laki-laki,Perempuan',
            'no_hp' => 'required|min:5|max:20',
            'rfid' => ['nullable', Rule::unique('tb_guru', 'rfid_code')->ignore($this->id_guru, 'id_guru')],
            'can_crud_siswa' => 'boolean'
        ], [
            'nuptk.unique' => 'NUPTK ini telah terdaftar.',
            'rfid.unique' => 'RFID code sudah digunakan.'
        ]);
        
        $guru = Guru::findOrFail($this->id_guru);
        $guru->update([
            'nuptk' => $this->nuptk,
            'nama_guru' => $this->nama_guru,
            'jenis_kelamin' => $this->jk,
            'alamat' => $this->alamat,
            'no_hp' => $this->no_hp,
            'rfid_code' => $this->rfid,
            'can_crud_siswa' => $this->can_crud_siswa ? 1 : 0,
        ]);

        $this->showModal = false;
        session()->flash('success', 'Edit data berhasil');
    }

    public function deleteId($id)
    {
        $this->id_guru = $id;
        $this->dispatch('show-delete-modal');
    }

    public function delete()
    {
        $guru = Guru::findOrFail($this->id_guru);
        $guru->delete();

        session()->flash('success', 'Data berhasil dihapus');
        $this->dispatch('hide-delete-modal');
    }

    public function resetFields()
    {
        $this->id_guru = null;
        $this->nuptk = '';
        $this->nama_guru = '';
        $this->jk = '';
        $this->alamat = '';
        $this->no_hp = '';
        $this->rfid = '';
        $this->can_crud_siswa = false;
    }
}
