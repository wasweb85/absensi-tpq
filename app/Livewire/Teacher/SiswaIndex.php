<?php

namespace App\Livewire\Teacher;

use App\Models\Siswa;
use App\Models\Kelas;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SiswaIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    
    public $id_siswa;
    public $nis;
    public $nama_siswa;
    public $jenis_kelamin;
    public $no_hp;
    public $rfid_code;

    public $isEdit = false;
    public $showModal = false;

    // We fetch the current logged-in teacher and their class
    public $guru;
    public $kelas_id;
    public $can_crud = false;

    public function mount()
    {
        $user = Auth::user();
        if ($user->id_guru) {
            // Find the teacher details
            $this->guru = \App\Models\Guru::find($user->id_guru);
            if ($this->guru) {
                $this->can_crud = (bool) $this->guru->can_crud_siswa;
            }
            
            // Find the class assigned to this teacher
            $kelas = Kelas::where('id_wali_kelas', $user->id_guru)->first();
            if ($kelas) {
                $this->kelas_id = $kelas->id_kelas;
            }
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Siswa::with('kelas');

        // Only show students in this teacher's class
        if ($this->kelas_id) {
            $query->where('id_kelas', $this->kelas_id);
        } else {
            // If the teacher has no class assigned, show nothing
            $query->where('id_kelas', -1);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nama_siswa', 'like', '%' . $this->search . '%')
                  ->orWhere('nis', 'like', '%' . $this->search . '%');
            });
        }

        $siswaList = $query->orderBy('nama_siswa')->paginate(20);

        return view('livewire.teacher.siswa-index', [
            'siswaList' => $siswaList
        ])->layout('layouts.admin', ['title' => 'Data Siswa Kelas', 'context' => 'siswa-kelas']);
    }

    public function create()
    {
        if (!$this->can_crud) return;
        
        $this->resetFields();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function store()
    {
        if (!$this->can_crud || !$this->kelas_id) return;

        $this->validate([
            'nis' => 'required|numeric|max_digits:35|unique:tb_siswa,nis',
            'nama_siswa' => 'required|min:3|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'no_hp' => 'nullable|min:5|max:30',
            'rfid_code' => 'nullable|max:100|unique:tb_siswa,rfid_code',
        ], [
            'nis.required' => 'NIS wajib diisi.',
            'nis.numeric' => 'NIS harus berupa angka.',
            'nis.max_digits' => 'NIS maksimal 35 digit.',
            'nis.unique' => 'NIS ini sudah terdaftar.',
            'nama_siswa.required' => 'Nama siswa wajib diisi.',
            'nama_siswa.min' => 'Nama siswa minimal 3 karakter.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'no_hp.max' => 'Nomor HP maksimal 30 karakter.',
            'rfid_code.unique' => 'Kode RFID ini sudah digunakan.',
        ]);

        Siswa::create([
            'nis' => $this->nis,
            'nama_siswa' => $this->nama_siswa,
            'id_kelas' => $this->kelas_id,
            'jenis_kelamin' => $this->jenis_kelamin,
            'no_hp' => $this->no_hp,
            'rfid_code' => $this->rfid_code,
            'unique_code' => Str::random(16),
        ]);

        $this->showModal = false;
        session()->flash('success', 'Tambah data berhasil');
    }

    public function edit($id)
    {
        if (!$this->can_crud) return;

        $this->resetFields();
        $siswa = Siswa::where('id_siswa', $id)->where('id_kelas', $this->kelas_id)->firstOrFail();
        
        $this->id_siswa = $siswa->id_siswa;
        $this->nis = $siswa->nis;
        $this->nama_siswa = $siswa->nama_siswa;
        $this->jenis_kelamin = $siswa->jenis_kelamin;
        $this->no_hp = $siswa->no_hp;
        $this->rfid_code = $siswa->rfid_code;

        $this->isEdit = true;
        $this->showModal = true;
    }

    public function update()
    {
        if (!$this->can_crud || !$this->kelas_id) return;

        $this->validate([
            'nis' => ['required', 'numeric', 'max_digits:35', Rule::unique('tb_siswa', 'nis')->ignore($this->id_siswa, 'id_siswa')],
            'nama_siswa' => 'required|min:3|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'no_hp' => 'nullable|min:5|max:30',
            'rfid_code' => ['nullable', 'max:100', Rule::unique('tb_siswa', 'rfid_code')->ignore($this->id_siswa, 'id_siswa')],
        ], [
            'nis.required' => 'NIS wajib diisi.',
            'nis.numeric' => 'NIS harus berupa angka.',
            'nis.max_digits' => 'NIS maksimal 35 digit.',
            'nis.unique' => 'NIS ini sudah terdaftar.',
            'nama_siswa.required' => 'Nama siswa wajib diisi.',
            'nama_siswa.min' => 'Nama siswa minimal 3 karakter.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'no_hp.max' => 'Nomor HP maksimal 30 karakter.',
            'rfid_code.unique' => 'Kode RFID ini sudah digunakan.',
        ]);

        $siswa = Siswa::where('id_siswa', $this->id_siswa)->where('id_kelas', $this->kelas_id)->firstOrFail();
        $siswa->update([
            'nis' => $this->nis,
            'nama_siswa' => $this->nama_siswa,
            'jenis_kelamin' => $this->jenis_kelamin,
            'no_hp' => $this->no_hp,
            'rfid_code' => $this->rfid_code,
        ]);

        $this->showModal = false;
        session()->flash('success', 'Edit data berhasil');
    }

    public function deleteId($id)
    {
        if (!$this->can_crud) return;

        $this->id_siswa = $id;
        $this->dispatch('show-delete-modal');
    }

    public function delete()
    {
        if (!$this->can_crud || !$this->kelas_id) return;

        $siswa = Siswa::where('id_siswa', $this->id_siswa)->where('id_kelas', $this->kelas_id)->firstOrFail();
        $siswa->delete();

        session()->flash('success', 'Data berhasil dihapus');
        $this->dispatch('hide-delete-modal');
    }

    public function resetFields()
    {
        $this->id_siswa = null;
        $this->nis = '';
        $this->nama_siswa = '';
        $this->jenis_kelamin = '';
        $this->no_hp = '';
        $this->rfid_code = '';
    }
}
