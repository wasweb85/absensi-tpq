<?php

namespace App\Livewire\Admin;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\PresensiSiswa;
use App\Models\PresensiGuru;
use Livewire\Component;
use Livewire\WithPagination;

class GenerateLaporanIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $tanggalMulai;
    public $tanggalAkhir;
    public $kelas = "";
    
    public $tanggalMulaiGuru;
    public $tanggalAkhirGuru;

    public function mount()
    {
        $this->tanggalMulai = date('Y-m-01');
        $this->tanggalAkhir = date('Y-m-t');
        
        $this->tanggalMulaiGuru = date('Y-m-01');
        $this->tanggalAkhirGuru = date('Y-m-t');
    }

    public function updatingTanggalMulai()
    {
        $this->resetPage('siswaPage');
    }

    public function updatingTanggalAkhir()
    {
        $this->resetPage('siswaPage');
    }

    public function updatingKelas()
    {
        $this->resetPage('siswaPage');
    }

    public function updatingTanggalMulaiGuru()
    {
        $this->resetPage('guruPage');
    }

    public function updatingTanggalAkhirGuru()
    {
        $this->resetPage('guruPage');
    }

    public function exportSiswa($type)
    {
        $this->validate([
            'tanggalMulai' => 'required|date',
            'tanggalAkhir' => 'required|date|after_or_equal:tanggalMulai',
        ]);

        return redirect()->route('admin.laporan.siswa', [
            'tanggal_mulai' => $this->tanggalMulai,
            'tanggal_akhir' => $this->tanggalAkhir,
            'kelas' => $this->kelas,
            'type' => $type
        ]);
    }

    public function exportGuru($type)
    {
        $this->validate([
            'tanggalMulaiGuru' => 'required|date',
            'tanggalAkhirGuru' => 'required|date|after_or_equal:tanggalMulaiGuru',
        ]);

        return redirect()->route('admin.laporan.guru', [
            'tanggal_mulai' => $this->tanggalMulaiGuru,
            'tanggal_akhir' => $this->tanggalAkhirGuru,
            'type' => $type
        ]);
    }

    public function render()
    {
        $kelasList = Kelas::withCount('siswa')->orderBy('tingkat')->get();
        // total_siswa is automatically available as siswa_count attribute
        foreach ($kelasList as $k) {
            $k->total_siswa = $k->siswa_count;
        }

        // Query data presensi siswa
        $siswaQuery = PresensiSiswa::with(['siswa', 'kelas', 'kehadiran'])
            ->whereBetween('tanggal', [$this->tanggalMulai, $this->tanggalAkhir]);

        if ($this->kelas !== "") {
            $siswaQuery->where('id_kelas', $this->kelas);
        }

        $presensiSiswa = $siswaQuery->orderBy('tanggal', 'desc')
            ->orderBy('id_siswa', 'asc')
            ->paginate(10, ['*'], 'siswaPage');

        // Query data presensi guru
        $presensiGuru = PresensiGuru::with(['guru', 'kehadiran'])
            ->whereBetween('tanggal', [$this->tanggalMulaiGuru, $this->tanggalAkhirGuru])
            ->orderBy('tanggal', 'desc')
            ->orderBy('id_guru', 'asc')
            ->paginate(10, ['*'], 'guruPage');

        return view('livewire.admin.generate-laporan-index', [
            'kelasList' => $kelasList,
            'presensiSiswa' => $presensiSiswa,
            'presensiGuru' => $presensiGuru,
        ])->layout('layouts.admin', ['title' => 'Generate Laporan', 'nav_title' => 'Laporan', 'context' => 'laporan']);
    }
}

