<?php

namespace App\Livewire\Admin;

use App\Models\Siswa;
use App\Models\Kelas;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

class SiswaIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $filter_kelas = '';
    public $perPage = 10;
    public $kelasList;

    public $id_siswa;
    public $nis;
    public $nama_siswa;
    public $id_kelas;
    public $jenis_kelamin;
    public $tanggal_lahir;
    public $nama_ayah;
    public $nama_ibu;
    public $no_hp;

    public $isEdit = false;
    public $showModal = false;

    public $showDetailModal = false;
    public $detailStudent = null;
    
    public $showQrModal = false;
    public $qrDataUrl = '';
    public $qrStudent = null;

    public function mount()
    {
        $this->kelasList = Kelas::orderBy('tingkat')->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterKelas()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Siswa::with('kelas');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nama_siswa', 'like', '%' . $this->search . '%')
                  ->orWhere('nis', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filter_kelas) {
            $query->where('id_kelas', $this->filter_kelas);
        }

        $siswaList = $query->orderBy('nama_siswa')->paginate($this->perPage);

        return view('livewire.admin.siswa-index', [
            'siswaList' => $siswaList
        ])->layout('layouts.admin', ['title' => 'Data Siswa', 'context' => 'siswa']);
    }

    public function create()
    {
        $this->resetFields();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function showDetail($id)
    {
        $this->detailStudent = Siswa::with('kelas')->find($id);
        if ($this->detailStudent) {
            $this->showDetailModal = true;
        }
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->detailStudent = null;
    }

    public function store()
    {
        $this->validate([
            'nis' => 'required|numeric|max_digits:35|unique:tb_siswa,nis',
            'nama_siswa' => 'required|min:3|max:255',
            'id_kelas' => 'required|exists:tb_kelas,id_kelas',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'nullable|date',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'no_hp' => 'nullable|min:5|max:30',
        ], [
            'nis.required' => 'NIS/NISN wajib diisi.',
            'nis.numeric' => 'NIS/NISN harus berupa angka.',
            'nis.max_digits' => 'NIS/NISN maksimal 35 digit.',
            'nis.unique' => 'NIS/NISN ini sudah terdaftar.',
            'nama_siswa.required' => 'Nama lengkap wajib diisi.',
            'nama_siswa.min' => 'Nama lengkap minimal 3 karakter.',
            'id_kelas.required' => 'Kelas wajib dipilih.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'no_hp.max' => 'Nomor HP maksimal 30 karakter.',
        ]);

        Siswa::create([
            'nis' => $this->nis,
            'nama_siswa' => $this->nama_siswa,
            'id_kelas' => $this->id_kelas,
            'jenis_kelamin' => $this->jenis_kelamin,
            'tanggal_lahir' => $this->tanggal_lahir ?: null,
            'nama_ayah' => $this->nama_ayah ?: null,
            'nama_ibu' => $this->nama_ibu ?: null,
            'no_hp' => $this->no_hp ?: null,
            'unique_code' => Str::random(16),
        ]);

        $this->showModal = false;
        session()->flash('success', 'Tambah data berhasil');
    }

    public function edit($id)
    {
        $this->resetFields();
        $siswa = Siswa::findOrFail($id);
        $this->id_siswa = $siswa->id_siswa;
        $this->nis = $siswa->nis;
        $this->nama_siswa = $siswa->nama_siswa;
        $this->id_kelas = $siswa->id_kelas;
        $this->jenis_kelamin = $siswa->jenis_kelamin;
        $this->tanggal_lahir = $siswa->tanggal_lahir;
        $this->nama_ayah = $siswa->nama_ayah;
        $this->nama_ibu = $siswa->nama_ibu;
        $this->no_hp = $siswa->no_hp;

        $this->isEdit = true;
        $this->showModal = true;
    }

    public function update()
    {
        $this->validate([
            'nis' => ['required', 'numeric', 'max_digits:35', Rule::unique('tb_siswa', 'nis')->ignore($this->id_siswa, 'id_siswa')],
            'nama_siswa' => 'required|min:3|max:255',
            'id_kelas' => 'required|exists:tb_kelas,id_kelas',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'nullable|date',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'no_hp' => 'nullable|min:5|max:30',
        ], [
            'nis.required' => 'NIS/NISN wajib diisi.',
            'nis.numeric' => 'NIS/NISN harus berupa angka.',
            'nis.max_digits' => 'NIS/NISN maksimal 35 digit.',
            'nis.unique' => 'NIS/NISN ini sudah terdaftar.',
            'nama_siswa.required' => 'Nama lengkap wajib diisi.',
            'nama_siswa.min' => 'Nama lengkap minimal 3 karakter.',
            'id_kelas.required' => 'Kelas wajib dipilih.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'no_hp.max' => 'Nomor HP maksimal 30 karakter.',
        ]);

        $siswa = Siswa::findOrFail($this->id_siswa);
        $siswa->update([
            'nis' => $this->nis,
            'nama_siswa' => $this->nama_siswa,
            'id_kelas' => $this->id_kelas,
            'jenis_kelamin' => $this->jenis_kelamin,
            'tanggal_lahir' => $this->tanggal_lahir ?: null,
            'nama_ayah' => $this->nama_ayah ?: null,
            'nama_ibu' => $this->nama_ibu ?: null,
            'no_hp' => $this->no_hp ?: null,
        ]);

        $this->showModal = false;
        session()->flash('success', 'Edit data berhasil');
    }

    public function deleteId($id)
    {
        $this->id_siswa = $id;
        $this->dispatch('show-delete-modal');
    }

    public function delete()
    {
        if ($this->id_siswa) {
            Siswa::find($this->id_siswa)->delete();
            session()->flash('success', 'Data siswa berhasil dihapus.');
            $this->dispatch('hide-delete-modal');
        }
    }

    public function generateQR($id)
    {
        $siswa = Siswa::with('kelas')->find($id);
        if ($siswa) {
            $this->qrStudent = $siswa;
            
            $result = (new Builder(
                writer: new PngWriter(),
                writerOptions: [],
                data: (string) ($siswa->qr_code ?: $siswa->nis ?: 'Siswa-'.$siswa->id_siswa),
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::High,
                size: 250,
                margin: 10,
                roundBlockSizeMode: RoundBlockSizeMode::Margin
            ))->build();

            $this->qrDataUrl = $result->getDataUri();
            $this->showQrModal = true;
        }
    }

    public function closeQrModal()
    {
        $this->showQrModal = false;
        $this->qrDataUrl = '';
        $this->qrStudent = null;
    }

    public function resetFields()
    {
        $this->id_siswa = null;
        $this->nis = '';
        $this->nama_siswa = '';
        $this->id_kelas = '';
        $this->jenis_kelamin = '';
        $this->tanggal_lahir = '';
        $this->nama_ayah = '';
        $this->nama_ibu = '';
        $this->no_hp = '';
    }
}
