<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\PresensiSiswa;
use App\Models\Tabungan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManualAttendance extends Component
{
    public $tanggal;
    public $filter_jk = ''; // '', 'Laki-laki', 'Perempuan'
    public $kehadiran = []; // array to store [id_siswa => id_kehadiran]
    public $jamScan = [];   // array to store [id_siswa => jam_masuk]

    // Tabungan Panel State
    public $showTabunganPanel = false;
    public $tabungan_siswa_id;
    public $tabungan_siswa_nama;
    public $tabungan_siswa_kelas;
    public $tabungan_siswa_inisial;
    public $tabungan_siswa_status_absen;
    public $tabungan_saldo_saat_ini = 0;
    public $tabungan_jenis = 'setor';
    public $tabungan_nominal;
    public $tabungan_keterangan;

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

        if ($user->is_superadmin == 1 || empty($user->id_guru)) {
            return Kelas::orderBy('tingkat')->get();
        }

        if ($user->id_guru) {
            $guru = Guru::with('kelasBinaan')->find($user->id_guru);
            if ($guru) {
                $waliKelas = Kelas::where('id_wali_kelas', $user->id_guru)->get();
                $binaanKelas = $guru->kelasBinaan;
                $merged = $waliKelas->merge($binaanKelas)->unique('id_kelas')->sortBy('tingkat');
                if ($merged->isNotEmpty()) {
                    return $merged;
                }
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
            $this->kehadiran[$siswa->id_siswa] = $presensi ? (string) $presensi->id_kehadiran : '';
            $this->jamScan[$siswa->id_siswa] = ($presensi && $presensi->jam_masuk && $presensi->id_kehadiran == 1) ? $presensi->jam_masuk : '-';
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

        $siswaList = Siswa::whereIn('id_kelas', $assignedIds)->get();
        $siswaMap = $siswaList->keyBy('id_siswa');

        $successCount = 0;

        DB::beginTransaction();
        try {
            foreach ($this->kehadiran as $idSiswa => $idKehadiran) {
                $siswa = $siswaMap->get($idSiswa) ?? Siswa::find($idSiswa);
                if (!$siswa) continue;

                if ($idKehadiran !== null && $idKehadiran !== '') {
                    $existing = PresensiSiswa::where('id_siswa', $idSiswa)
                        ->whereDate('tanggal', $this->tanggal)
                        ->first();

                    // Preserve existing scan time if present and status is Hadir (1), otherwise current time
                    $jamMasuk = ($idKehadiran == 1) 
                        ? ($existing && $existing->jam_masuk ? $existing->jam_masuk : Carbon::now()->toTimeString())
                        : null;

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
                } else {
                    // If reset to Belum Absen, delete record for this date
                    PresensiSiswa::where('id_siswa', $idSiswa)
                        ->whereDate('tanggal', $this->tanggal)
                        ->delete();
                }
            }

            DB::commit();
            $this->loadData();
            session()->flash('success', "Berhasil menyimpan data absensi untuk $successCount santri.");
            $this->dispatch('attendance-saved');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Save Attendance Error: ' . $e->getMessage(), ['exception' => $e]);
            session()->flash('error', 'Gagal menyimpan absensi: ' . $e->getMessage());
        }
    }

    public function openTabunganPanel($id_siswa)
    {
        $this->resetErrorBag();
        $siswa = Siswa::with('kelas')->find($id_siswa);
        if (!$siswa) return;

        $this->tabungan_siswa_id = $siswa->id_siswa;
        $this->tabungan_siswa_nama = $siswa->nama_siswa;
        $this->tabungan_siswa_kelas = $siswa->kelas ? $siswa->kelas->tingkat . ' ' . $siswa->kelas->index_kelas : '-';
        
        $cleanName = trim($siswa->nama_siswa);
        $words = explode(' ', $cleanName);
        $this->tabungan_siswa_inisial = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
        
        $statusAbsen = 'Belum Absen';
        if (isset($this->kehadiran[$id_siswa])) {
            $idKehadiran = $this->kehadiran[$id_siswa];
            if ($idKehadiran == '1') $statusAbsen = 'Hadir hari ini';
            elseif ($idKehadiran == '2') $statusAbsen = 'Sakit hari ini';
            elseif ($idKehadiran == '3') $statusAbsen = 'Izin hari ini';
            elseif ($idKehadiran == '4') $statusAbsen = 'Alfa hari ini';
        }
        $this->tabungan_siswa_status_absen = $statusAbsen;

        $this->tabungan_saldo_saat_ini = $siswa->saldo_tabungan ?? 0;
        $this->tabungan_jenis = 'setor';
        $this->tabungan_nominal = '';
        $this->tabungan_keterangan = '';
        
        $this->showTabunganPanel = true;
    }

    public function closeTabunganPanel()
    {
        $this->showTabunganPanel = false;
        $this->resetErrorBag();
    }

    public function setTabunganNominal($amount)
    {
        $current = (int) preg_replace('/[^0-9]/', '', (string)($this->tabungan_nominal ?: '0'));
        $this->tabungan_nominal = $current + (int)$amount;
    }

    public function simpanTabungan()
    {
        // Sanitize nominal to numeric
        $nominalClean = preg_replace('/[^0-9]/', '', (string)$this->tabungan_nominal);
        $this->tabungan_nominal = $nominalClean !== '' ? (int)$nominalClean : null;

        $this->validate([
            'tabungan_siswa_id' => 'required',
            'tabungan_jenis' => 'required|in:setor,tarik',
            'tabungan_nominal' => 'required|numeric|min:100',
        ], [
            'tabungan_nominal.required' => 'Nominal tabungan wajib diisi.',
            'tabungan_nominal.numeric' => 'Nominal tabungan harus berupa angka.',
            'tabungan_nominal.min' => 'Nominal tabungan minimal Rp 100.',
            'tabungan_jenis.required' => 'Pilih jenis transaksi (setor atau tarik).',
        ]);

        $siswa = Siswa::find($this->tabungan_siswa_id);
        if (!$siswa) {
            session()->flash('tabungan_error', 'Data santri tidak ditemukan.');
            return;
        }

        $nominal = (int) $this->tabungan_nominal;

        if ($this->tabungan_jenis === 'tarik' && ($siswa->saldo_tabungan ?? 0) < $nominal) {
            session()->flash('tabungan_error', 'Saldo tidak mencukupi untuk penarikan.');
            return;
        }

        DB::beginTransaction();
        try {
            Tabungan::create([
                'id_siswa' => $this->tabungan_siswa_id,
                'id_user' => Auth::id(),
                'tanggal' => $this->tanggal,
                'jenis_transaksi' => $this->tabungan_jenis,
                'nominal' => $nominal,
                'keterangan' => $this->tabungan_keterangan ?: '',
                'status_setoran' => 'belum',
            ]);

            if ($this->tabungan_jenis === 'setor') {
                $siswa->saldo_tabungan = ($siswa->saldo_tabungan ?? 0) + $nominal;
            } else {
                $siswa->saldo_tabungan = ($siswa->saldo_tabungan ?? 0) - $nominal;
            }
            $siswa->save();

            DB::commit();
            $this->closeTabunganPanel();
            $this->loadData(); // Reload to reflect changes
            session()->flash('success', 'Transaksi tabungan ' . ($this->tabungan_jenis == 'setor' ? 'setoran' : 'penarikan') . ' sebesar Rp ' . number_format($nominal, 0, ',', '.') . ' untuk ' . $siswa->nama_siswa . ' berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Simpan Tabungan Error: ' . $e->getMessage(), ['exception' => $e]);
            session()->flash('tabungan_error', 'Terjadi kesalahan saat menyimpan tabungan: ' . $e->getMessage());
        }
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
        ])->layout('layouts.admin', ['title' => 'Monitoring & Absensi Santri', 'nav_title' => 'Absensi Santri', 'context' => 'absen-manual']);
    }
}
