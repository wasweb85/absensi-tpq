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
    public $filter_jk = ''; // '', 'Laki-laki', 'Perempuan'
    public $kehadiran = []; // array to store [id_siswa => id_kehadiran]
    public $jamScan = [];   // array to store [id_siswa => jam_masuk]

    public function mount()
    {
        if (!\App\Models\RolePermission::hasAccess(Auth::user(), 'monitoring')) {
            session()->flash('error', 'Anda tidak memiliki hak akses ke fitur Monitoring Absensi.');
            return redirect()->to('/teacher/dashboard');
        }
        $this->tanggal = Carbon::today()->toDateString();
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

    public function updatedFilterJk()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->kehadiran = [];
        $this->jamScan = [];

        $assignedClasses = $this->getAssignedClasses();
        $assignedIds = $assignedClasses->pluck('id_kelas')->toArray();

        if (empty($assignedIds)) {
            return;
        }

        $query = Siswa::whereIn('id_kelas', $assignedIds);

        if (!empty($this->filter_jk)) {
            $query->where('jenis_kelamin', $this->filter_jk);
        }

        $siswaList = $query->orderBy('nama_siswa')->get();
        $siswaIds = $siswaList->pluck('id_siswa');
        
        $presensiHariIni = PresensiSiswa::whereIn('id_siswa', $siswaIds)
            ->whereDate('tanggal', $this->tanggal)
            ->get()
            ->keyBy('id_siswa');

        foreach ($siswaList as $siswa) {
            $presensi = $presensiHariIni->get($siswa->id_siswa);
            $this->kehadiran[$siswa->id_siswa] = $presensi ? (string) $presensi->id_kehadiran : null;
            $this->jamScan[$siswa->id_siswa] = ($presensi && $presensi->jam_masuk) ? $presensi->jam_masuk : '-';
        }
    }

    public function saveAttendance()
    {
        $assignedClasses = $this->getAssignedClasses();
        $assignedIds = $assignedClasses->pluck('id_kelas')->toArray();

        if (empty($assignedIds)) {
            session()->flash('error', 'Anda belum memiliki kelas binaan.');
            return;
        }

        $siswaMap = Siswa::whereIn('id_kelas', $assignedIds)->get()->keyBy('id_siswa');

        $successCount = 0;

        foreach ($this->kehadiran as $idSiswa => $idKehadiran) {
            if ($idKehadiran !== null && $idKehadiran !== '') {
                $siswa = $siswaMap->get($idSiswa);
                if (!$siswa) continue;

                $existing = PresensiSiswa::where('id_siswa', $idSiswa)
                    ->whereDate('tanggal', $this->tanggal)
                    ->first();

                // Preserve existing scan time if present, otherwise set current time
                $jamMasuk = $existing && $existing->jam_masuk ? $existing->jam_masuk : Carbon::now()->toTimeString();

                PresensiSiswa::updateOrCreate(
                    [
                        'id_siswa' => $idSiswa,
                        'tanggal' => $this->tanggal
                    ],
                    [
                        'id_kelas' => $siswa->id_kelas,
                        'id_kehadiran' => $idKehadiran,
                        'jam_masuk' => $jamMasuk,
                        'keterangan' => ''
                    ]
                );
                $successCount++;
            }
        }

        $this->loadData();
        session()->flash('success', "Berhasil menyimpan data absensi untuk $successCount santri.");
    }

    public function render()
    {
        $allKelas = $this->getAssignedClasses();
        $assignedIds = $allKelas->pluck('id_kelas')->toArray();
        
        if (!empty($assignedIds)) {
            $query = Siswa::with('kelas')->whereIn('id_kelas', $assignedIds);
            if (!empty($this->filter_jk)) {
                $query->where('jenis_kelamin', $this->filter_jk);
            }
            $siswaList = $query->orderBy('nama_siswa')->get();
        } else {
            $siswaList = collect();
        }

        return view('livewire.teacher.manual-attendance', [
            'allKelas' => $allKelas,
            'siswaList' => $siswaList
        ])->layout('layouts.admin', ['title' => 'Monitoring & Absensi Santri', 'context' => 'absen-manual']);
    }
}
