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
    public $no_hp;
    public $rfid_code;

    public $isEdit = false;
    public $showModal = false;
    
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

    public function store()
    {
        $this->validate([
            'nis' => 'required|numeric|max_digits:35|unique:tb_siswa,nis',
            'nama_siswa' => 'required|min:3|max:255',
            'id_kelas' => 'required|exists:tb_kelas,id_kelas',
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
            'id_kelas.required' => 'Kelas wajib dipilih.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'no_hp.max' => 'Nomor HP maksimal 30 karakter.',
            'rfid_code.unique' => 'Kode RFID ini sudah digunakan.',
        ]);

        Siswa::create([
            'nis' => $this->nis,
            'nama_siswa' => $this->nama_siswa,
            'id_kelas' => $this->id_kelas,
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
        $this->resetFields();
        $siswa = Siswa::findOrFail($id);
        $this->id_siswa = $siswa->id_siswa;
        $this->nis = $siswa->nis;
        $this->nama_siswa = $siswa->nama_siswa;
        $this->id_kelas = $siswa->id_kelas;
        $this->jenis_kelamin = $siswa->jenis_kelamin;
        $this->no_hp = $siswa->no_hp;
        $this->rfid_code = $siswa->rfid_code;

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
            'no_hp' => 'nullable|min:5|max:30',
            'rfid_code' => ['nullable', 'max:100', Rule::unique('tb_siswa', 'rfid_code')->ignore($this->id_siswa, 'id_siswa')],
        ], [
            'nis.required' => 'NIS wajib diisi.',
            'nis.numeric' => 'NIS harus berupa angka.',
            'nis.max_digits' => 'NIS maksimal 35 digit.',
            'nis.unique' => 'NIS ini sudah terdaftar.',
            'nama_siswa.required' => 'Nama siswa wajib diisi.',
            'nama_siswa.min' => 'Nama siswa minimal 3 karakter.',
            'id_kelas.required' => 'Kelas wajib dipilih.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'no_hp.max' => 'Nomor HP maksimal 30 karakter.',
            'rfid_code.unique' => 'Kode RFID ini sudah digunakan.',
        ]);

        $siswa = Siswa::findOrFail($this->id_siswa);
        $siswa->update([
            'nis' => $this->nis,
            'nama_siswa' => $this->nama_siswa,
            'id_kelas' => $this->id_kelas,
            'jenis_kelamin' => $this->jenis_kelamin,
            'no_hp' => $this->no_hp,
            'rfid_code' => $this->rfid_code,
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
        $this->no_hp = '';
        $this->rfid_code = '';
    }
}
