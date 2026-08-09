<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Guru;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Livewire\WithPagination;

class PetugasIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perPage = 10;

    public $guruList;

    public $id_petugas;
    public $name; // This is used as username in auth
    public $email;
    public $password;
    public $is_superadmin = 0;
    public $id_guru = null;

    public $isEdit = false;
    public $showModal = false;

    public function mount()
    {
        $this->guruList = Guru::orderBy('nama_guru')->get();
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
        $petugasList = User::with('guru')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhereHas('guru', function ($q) {
                          $q->where('nama_guru', 'like', '%' . $this->search . '%');
                      });
            })
            ->orderBy('name')
            ->paginate($this->perPage);

        return view('livewire.admin.petugas-index', [
            'petugasList' => $petugasList
        ])->layout('layouts.admin', ['title' => 'Data Petugas', 'context' => 'petugas']);
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
            'name' => 'required|string|min:4|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'is_superadmin' => 'required|in:0,1,2,3',
            'id_guru' => 'nullable',
        ], [
            'name.unique' => 'Username/Nama ini sudah terdaftar.',
            'email.unique' => 'Email ini sudah terdaftar.'
        ]);

        $guruId = !empty($this->id_guru) ? (int) $this->id_guru : null;

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'is_superadmin' => (int) $this->is_superadmin,
            'id_guru' => $guruId,
        ]);

        $this->showModal = false;
        session()->flash('success', 'Tambah petugas berhasil');
    }

    public function edit($id)
    {
        $this->resetFields();
        $petugas = User::findOrFail($id);
        
        $this->id_petugas = $petugas->id;
        $this->name = $petugas->name;
        $this->email = $petugas->email;
        $this->is_superadmin = (int) $petugas->is_superadmin;
        $this->id_guru = $petugas->id_guru ? (string) $petugas->id_guru : '';

        $this->isEdit = true;
        $this->showModal = true;
    }

    public function update()
    {
        $this->validate([
            'name' => ['required', 'string', 'min:4', Rule::unique('users', 'name')->ignore($this->id_petugas)],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->id_petugas)],
            'password' => 'nullable|min:6',
            'is_superadmin' => 'required|in:0,1,2,3',
            'id_guru' => 'nullable',
        ], [
            'name.unique' => 'Username/Nama ini sudah terdaftar.',
            'email.unique' => 'Email ini sudah terdaftar.'
        ]);

        $petugas = User::findOrFail($this->id_petugas);
        $guruId = !empty($this->id_guru) ? (int) $this->id_guru : null;
        
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'is_superadmin' => (int) $this->is_superadmin,
            'id_guru' => $guruId,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        $petugas->update($data);

        $this->showModal = false;
        session()->flash('success', 'Edit petugas berhasil');
    }

    public function deleteId($id)
    {
        // prevent deleting oneself
        if (auth()->id() == $id) {
            session()->flash('error', 'Tidak dapat menghapus akun Anda sendiri.');
            return;
        }

        $this->id_petugas = $id;
        $this->dispatch('show-delete-modal');
    }

    public function delete()
    {
        if (auth()->id() == $this->id_petugas) {
            session()->flash('error', 'Tidak dapat menghapus akun Anda sendiri.');
            $this->dispatch('hide-delete-modal');
            return;
        }

        $petugas = User::findOrFail($this->id_petugas);
        $petugas->delete();

        session()->flash('success', 'Data berhasil dihapus');
        $this->dispatch('hide-delete-modal');
    }

    public function resetFields()
    {
        $this->id_petugas = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->is_superadmin = 0;
        $this->id_guru = '';
    }
}
