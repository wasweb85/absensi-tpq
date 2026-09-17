<?php

namespace App\Livewire\Admin;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\User;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\WithPagination;

class GuruIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perPage = 10;

    public $id_guru;
    public $niup;
    public $nama_guru;
    public $jk;
    public $alamat;
    public $no_hp;
    public $rfid;
    public $can_crud_siswa = false;
    public $selectedKelas = [];

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
        $guruList = Guru::with(['kelasBinaan', 'user'])
            ->when($this->search, function ($query) {
                $query->where('nama_guru', 'like', '%' . $this->search . '%')
                      ->orWhere('niup', 'like', '%' . $this->search . '%');
            })->paginate($this->perPage);

        $allKelas = Kelas::orderBy('tingkat')->get();

        return view('livewire.admin.guru-index', [
            'guruList' => $guruList,
            'allKelas' => $allKelas
        ])->layout('layouts.admin', ['title' => 'Data Guru', 'context' => 'guru']);
    }

    protected function checkKepsekReadOnly()
    {
        if (auth()->user() && (int) (auth()->user()->is_superadmin ?? 0) === 2) {
            abort(403, 'Akses dibatasi. Kepala TPQ hanya memiliki hak akses pemantauan (Read-Only).');
        }
    }

    public function create()
    {
        $this->checkKepsekReadOnly();
        $this->resetFields();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function store()
    {
        $this->checkKepsekReadOnly();
        $this->validate([
            'niup' => 'required|min:5|max:30|unique:tb_guru,niup',
            'nama_guru' => 'required|min:3',
            'jk' => 'required|in:Laki-laki,Perempuan',
            'no_hp' => 'required|min:5|max:20',
            'rfid' => 'nullable|unique:tb_guru,rfid_code',
            'can_crud_siswa' => 'boolean',
            'selectedKelas' => 'array'
        ], [
            'niup.unique' => 'NIUP ini telah terdaftar.',
            'rfid.unique' => 'RFID code sudah digunakan.'
        ]);

        $guru = Guru::create([
            'niup' => $this->niup,
            'nama_guru' => $this->nama_guru,
            'jenis_kelamin' => $this->jk,
            'alamat' => $this->alamat,
            'no_hp' => $this->no_hp,
            'rfid_code' => $this->rfid,
            'unique_code' => Str::random(16),
            'can_crud_siswa' => $this->can_crud_siswa ? 1 : 0,
        ]);

        // Sync kelas binaan (multi-kelas Putra/Putri)
        $guru->kelasBinaan()->sync($this->selectedKelas);

        // Auto create login User account for teacher
        $username = !empty($this->niup) ? $this->niup : 'guru_' . $guru->id_guru;
        $email = !empty($this->niup) ? $this->niup . '@tpq.local' : 'guru' . $guru->id_guru . '@tpq.local';

        User::updateOrCreate(
            ['id_guru' => $guru->id_guru],
            [
                'name' => $username,
                'email' => $email,
                'password' => Hash::make('12345678'),
                'is_superadmin' => 0
            ]
        );

        $this->showModal = false;
        session()->flash('success', 'Tambah data guru dan akun login berhasil (Password Default: 12345678)');
    }

    public function edit($id)
    {
        $this->checkKepsekReadOnly();
        $this->resetFields();
        $guru = Guru::with('kelasBinaan')->findOrFail($id);
        $this->id_guru = $guru->id_guru;
        $this->niup = $guru->niup;
        $this->nama_guru = $guru->nama_guru;
        $this->jk = $guru->jenis_kelamin;
        $this->alamat = $guru->alamat;
        $this->no_hp = $guru->no_hp;
        $this->rfid = $guru->rfid_code;
        $this->can_crud_siswa = (bool) $guru->can_crud_siswa;
        $binaanIds = $guru->kelasBinaan->pluck('id_kelas')->toArray();
        $waliIds = Kelas::where('id_wali_kelas', $guru->id_guru)->pluck('id_kelas')->toArray();
        $this->selectedKelas = array_values(array_unique(array_merge($binaanIds, $waliIds)));

        $this->isEdit = true;
        $this->showModal = true;
    }

    public function update()
    {
        $this->checkKepsekReadOnly();
        $this->validate([
            'niup' => ['required', 'min:5', 'max:30', Rule::unique('tb_guru', 'niup')->ignore($this->id_guru, 'id_guru')],
            'nama_guru' => 'required|min:3',
            'jk' => 'required|in:Laki-laki,Perempuan',
            'no_hp' => 'required|min:5|max:20',
            'rfid' => ['nullable', Rule::unique('tb_guru', 'rfid_code')->ignore($this->id_guru, 'id_guru')],
            'can_crud_siswa' => 'boolean',
            'selectedKelas' => 'array'
        ], [
            'niup.unique' => 'NIUP ini telah terdaftar.',
            'rfid.unique' => 'RFID code sudah digunakan.'
        ]);
        
        $guru = Guru::findOrFail($this->id_guru);
        $guru->update([
            'niup' => $this->niup,
            'nama_guru' => $this->nama_guru,
            'jenis_kelamin' => $this->jk,
            'alamat' => $this->alamat,
            'no_hp' => $this->no_hp,
            'rfid_code' => $this->rfid,
            'can_crud_siswa' => $this->can_crud_siswa ? 1 : 0,
        ]);

        // Sync kelas binaan (multi-kelas Putra/Putri)
        $guru->kelasBinaan()->sync($this->selectedKelas);

        // If a class had this teacher as wali kelas but is no longer in selectedKelas, remove it
        Kelas::where('id_wali_kelas', $guru->id_guru)
            ->whereNotIn('id_kelas', $this->selectedKelas)
            ->update(['id_wali_kelas' => null]);

        // Auto update username/email in User account
        $username = !empty($this->niup) ? $this->niup : 'guru_' . $guru->id_guru;
        $email = !empty($this->niup) ? $this->niup . '@tpq.local' : 'guru' . $guru->id_guru . '@tpq.local';

        $user = User::where('id_guru', $guru->id_guru)->first();
        if ($user) {
            $user->update([
                'name' => $username,
                'email' => $email,
            ]);
        } else {
            User::create([
                'name' => $username,
                'email' => $email,
                'password' => Hash::make('12345678'),
                'id_guru' => $guru->id_guru,
                'is_superadmin' => 0
            ]);
        }

        $this->showModal = false;
        session()->flash('success', 'Edit data guru dan akses kelas berhasil');
    }

    public function resetPassword($id)
    {
        $this->checkKepsekReadOnly();
        $guru = Guru::findOrFail($id);
        $user = User::where('id_guru', $guru->id_guru)->first();
        if ($user) {
            $user->update(['password' => Hash::make('12345678')]);
            session()->flash('success', 'Password akun ' . $guru->nama_guru . ' berhasil di-reset ke 12345678');
        } else {
            User::create([
                'name' => !empty($guru->niup) ? $guru->niup : 'guru_' . $guru->id_guru,
                'email' => !empty($guru->niup) ? $guru->niup . '@tpq.local' : 'guru' . $guru->id_guru . '@tpq.local',
                'password' => Hash::make('12345678'),
                'id_guru' => $guru->id_guru,
                'is_superadmin' => 0
            ]);
            session()->flash('success', 'Akun login baru dibuatkan dengan password: 12345678');
        }
    }

    public function deleteId($id)
    {
        $this->checkKepsekReadOnly();
        $this->id_guru = $id;
        $this->dispatch('show-delete-modal');
    }

    public function delete()
    {
        $this->checkKepsekReadOnly();
        $guru = Guru::findOrFail($this->id_guru);
        User::where('id_guru', $guru->id_guru)->delete();
        Kelas::where('id_wali_kelas', $guru->id_guru)->update(['id_wali_kelas' => null]);
        $guru->kelasBinaan()->detach();
        $guru->delete();

        session()->flash('success', 'Data guru dan akun terkait berhasil dihapus');
        $this->dispatch('hide-delete-modal');
    }

    public function resetFields()
    {
        $this->id_guru = null;
        $this->niup = '';
        $this->nama_guru = '';
        $this->jk = '';
        $this->alamat = '';
        $this->no_hp = '';
        $this->rfid = '';
        $this->can_crud_siswa = false;
        $this->selectedKelas = [];
    }
}
