<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\PresensiSiswa;
use App\Models\Siswa;
use App\Models\Kelas;
use Carbon\Carbon;

class AbsenSiswaIndex extends Component
{
    public $filter_tanggal;
    public $filter_kelas;
    
    public $kelasList;
    public $siswaList;
    public $kehadiran = []; // array to store [id_siswa => id_kehadiran]

    public function mount()
    {
        if (auth()->check() && auth()->user()->is_superadmin == 1) {
            session()->flash('error', 'Akses ditolak. Superadmin tidak memiliki akses untuk menginput absensi.');
            return redirect()->to('/dashboard');
        }

        $this->filter_tanggal = Carbon::today()->toDateString();
        $this->kelasList = Kelas::orderBy('tingkat')->get();
        $this->siswaList = collect();
    }

    public function updatedFilterTanggal()
    {
        $this->loadData();
    }

    public function updatedFilterKelas()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->kehadiran = [];
        if (empty($this->filter_kelas)) {
            $this->siswaList = collect();
            return;
        }

        $this->siswaList = Siswa::where('id_kelas', $this->filter_kelas)->orderBy('nama_siswa')->get();
        $siswaIds = $this->siswaList->pluck('id_siswa');
        
        $presensiHariIni = PresensiSiswa::whereIn('id_siswa', $siswaIds)
            ->whereDate('tanggal', $this->filter_tanggal)
            ->get()
            ->keyBy('id_siswa');

        foreach ($this->siswaList as $siswa) {
            $presensi = $presensiHariIni->get($siswa->id_siswa);
            $this->kehadiran[$siswa->id_siswa] = $presensi ? (string) $presensi->id_kehadiran : null;
        }
    }

    public function saveAttendance()
    {
        if (empty($this->filter_kelas)) {
            session()->flash('error', 'Kelas tidak dipilih.');
            return;
        }

        $successCount = 0;

        foreach ($this->kehadiran as $idSiswa => $idKehadiran) {
            if ($idKehadiran !== null && $idKehadiran !== '') {
                PresensiSiswa::updateOrCreate(
                    [
                        'id_siswa' => $idSiswa,
                        'tanggal' => $this->filter_tanggal
                    ],
                    [
                        'id_kelas' => $this->filter_kelas,
                        'id_kehadiran' => $idKehadiran,
                        'jam_masuk' => Carbon::now()->toTimeString(),
                        'keterangan' => ''
                    ]
                );
                $successCount++;
            }
        }

        session()->flash('success', "Berhasil menyimpan data absensi untuk $successCount siswa.");
    }

    public function render()
    {
        return view('livewire.admin.absen-siswa-index')->layout('layouts.admin', ['title' => 'Absensi Siswa', 'context' => 'absen-siswa']);
    }
}
