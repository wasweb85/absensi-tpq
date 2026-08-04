<?php

namespace App\Livewire\Admin;
use App\Models\Kelas;
use App\Models\Guru;
use Livewire\Component;

use Livewire\WithPagination;

class KelasIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perPage = 10;

    public $guruList;
    public $tingkat;
    public $index_kelas;
    public $id_wali_kelas;
    public $id_kelas;

    public $isEdit = false;
    public $showModal = false;

    protected $rules = [
        'tingkat' => 'required|string|max:50',
        'id_wali_kelas' => 'nullable|integer',
    ];

    public function mount()
    {
        $this->guruList = Guru::all();
    }

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
        $kelasList = Kelas::with(['guru'])
            ->when($this->search, function ($query) {
                $query->where('tingkat', 'like', '%' . $this->search . '%')
                      ->orWhereHas('guru', function ($q) {
                          $q->where('nama_guru', 'like', '%' . $this->search . '%');
                      });
            })
            ->paginate($this->perPage);

        return view('livewire.admin.kelas-index', [
            'kelasList' => $kelasList
        ])->layout('layouts.admin', ['title' => 'Manajemen Kelas', 'context' => 'kelas']);
    }

    public function create()
    {
        $this->resetFields();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function store()
    {
        $this->validate();

        Kelas::create([
            'tingkat' => $this->tingkat,
            'index_kelas' => $this->index_kelas ?? '',
            'id_wali_kelas' => $this->id_wali_kelas,
        ]);

        $this->showModal = false;
        session()->flash('success', 'Data berhasil ditambah');
    }

    public function edit($id)
    {
        $this->resetFields();
        $kelas = Kelas::findOrFail($id);
        $this->id_kelas = $kelas->id_kelas;
        $this->tingkat = $kelas->tingkat ?? '';
        $this->index_kelas = $kelas->index_kelas ?? '';
        $this->id_wali_kelas = $kelas->id_wali_kelas;

        $this->isEdit = true;
        $this->showModal = true;
    }

    public function update()
    {
        $this->validate();
        
        $kelas = Kelas::findOrFail($this->id_kelas);
        
        $kelas->update([
            'tingkat' => $this->tingkat,
            'index_kelas' => $this->index_kelas ?? '',
            'id_wali_kelas' => $this->id_wali_kelas,
        ]);

        $this->showModal = false;
        session()->flash('success', 'Data berhasil diubah');
    }

    public function deleteId($id)
    {
        $this->id_kelas = $id;
        $this->dispatch('show-delete-modal');
    }

    public function delete()
    {
        $kelas = Kelas::findOrFail($this->id_kelas);
        // check if has students
        if ($kelas->siswa()->count() > 0) {
            session()->flash('error', 'Kelas Masih Memiliki Siswa Aktif');
            return;
        }

        $kelas->delete();
        session()->flash('success', 'Data berhasil dihapus');
        $this->dispatch('hide-delete-modal');
    }

    public function resetFields()
    {
        $this->id_kelas = null;
        $this->tingkat = '';
        $this->index_kelas = '';
        $this->id_wali_kelas = null;
    }
}
