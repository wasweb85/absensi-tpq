<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\PresensiSiswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ManualAttendance extends Component
{
    public $tanggal;
    public $id_kelas;
    public $kehadiran = []; // array to store [id_siswa => id_kehadiran]

    public function mount()
    {
        $this->tanggal = Carbon::today()->toDateString();
        $this->initClasses();
    }

    private function initClasses()
    {
        $allKelas = $this->getAssignedClasses();
        if ($allKelas->count() > 0 && empty($this->id_kelas)) {
            $this->id_kelas = $allKelas->first()->id_kelas;
        }
        $this->loadData();
    }

    private function getAssignedClasses()
    {
        $user = Auth::user();
        if (!$user) {
            return collect();
        }

        if ($user->is_superadmin == 1) {
            return Kelas::orderBy('tingkat')->get();
        }

        if ($user->id_guru) {
            $guru = Guru::with('kelasBinaan')->find($user->id_guru);
            if ($guru) {
                $waliKelas = Kelas::where('id_wali_kelas', $user->id_guru)->get();
                $binaanKelas = $guru->kelasBinaan;
                return $waliKelas->merge($binaanKelas)->unique('id_kelas')->sortBy('tingkat');
            }
        }

        return collect();
    }

    public function updatedTanggal()
    {
        $this->loadData();
    }

    public function updatedIdKelas()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->kehadiran = [];
        if (empty($this->id_kelas)) {
            return;
        }

        $siswaList = Siswa::where('id_kelas', $this->id_kelas)->orderBy('nama_siswa')->get();
        $siswaIds = $siswaList->pluck('id_siswa');
        
        $presensiHariIni = PresensiSiswa::whereIn('id_siswa', $siswaIds)
            ->whereDate('tanggal', $this->tanggal)
            ->get()
            ->keyBy('id_siswa');

        foreach ($siswaList as $siswa) {
            $presensi = $presensiHariIni->get($siswa->id_siswa);
            $this->kehadiran[$siswa->id_siswa] = $presensi ? (string) $presensi->id_kehadiran : null;
        }
    }

    public function saveAttendance()
    {
        if (empty($this->id_kelas)) {
            session()->flash('error', 'Kelas tidak dipilih.');
            return;
        }

        $successCount = 0;

        foreach ($this->kehadiran as $idSiswa => $idKehadiran) {
            if ($idKehadiran !== null && $idKehadiran !== '') {
                PresensiSiswa::updateOrCreate(
                    [
                        'id_siswa' => $idSiswa,
                        'tanggal' => $this->tanggal
                    ],
                    [
                        'id_kelas' => $this->id_kelas,
                        'id_kehadiran' => $idKehadiran,
                        'jam_masuk' => Carbon::now()->toTimeString(),
                        'keterangan' => ''
                    ]
                );
                $successCount++;
            }
        }

        session()->flash('success', "Berhasil menyimpan data absensi untuk $successCount santri.");
    }

    public function render()
    {
        $allKelas = $this->getAssignedClasses();
        $siswaList = empty($this->id_kelas) ? collect() : Siswa::where('id_kelas', $this->id_kelas)->orderBy('nama_siswa')->get();

        return view('livewire.teacher.manual-attendance', [
            'allKelas' => $allKelas,
            'siswaList' => $siswaList
        ])->layout('layouts.admin', ['title' => 'Monitoring & Absensi Santri', 'context' => 'absen-manual']);
    }
}
