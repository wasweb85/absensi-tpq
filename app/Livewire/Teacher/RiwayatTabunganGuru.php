<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tabungan;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RiwayatTabunganGuru extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';

    // Filters
    public $tanggal_awal;
    public $tanggal_akhir;
    public $filter_santri_id = '';
    public $filter_jenis = '';

    // Edit State
    public $editId = null;
    public $editNominal = '';
    public $editJenis = '';
    public $editKeterangan = '';
    public $editNominalLama = 0;
    public $editJenisLama = '';

    public function mount()
    {
        if (!\App\Models\RolePermission::hasAccess(Auth::user(), 'monitoring')) {
            abort(403, 'Anda tidak memiliki hak akses.');
        }

        $this->tanggal_awal = Carbon::now()->startOfMonth()->toDateString();
        $this->tanggal_akhir = Carbon::today()->toDateString();
    }

    private function getAssignedSiswaIds()
    {
        $user = Auth::user();
        if (!$user) return collect();

        $kelasIds = [];
        if ($user->is_superadmin == 1) {
            $kelasIds = Kelas::pluck('id_kelas')->toArray();
        } elseif ($user->id_guru) {
            $guru = Guru::with('kelasBinaan')->find($user->id_guru);
            if ($guru) {
                $waliKelas = Kelas::where('id_wali_kelas', $user->id_guru)->pluck('id_kelas')->toArray();
                $binaanKelas = $guru->kelasBinaan->pluck('id_kelas')->toArray();
                $kelasIds = array_unique(array_merge($waliKelas, $binaanKelas));
            }
        }

        return Siswa::whereIn('id_kelas', $kelasIds)->pluck('id_siswa');
    }

    public function getDaftarSantri()
    {
        $siswaIds = $this->getAssignedSiswaIds();
        return Siswa::whereIn('id_siswa', $siswaIds)->orderBy('nama_siswa')->get();
    }

    public function editTabungan($id_tabungan)
    {
        $tabungan = Tabungan::find($id_tabungan);
        if (!$tabungan || $tabungan->status_setoran === 'sudah') return;

        // Ensure this tabungan belongs to an assigned student
        $siswaIds = $this->getAssignedSiswaIds()->toArray();
        if (!in_array($tabungan->id_siswa, $siswaIds)) return;

        $this->editId = $tabungan->id_tabungan;
        $this->editJenis = $tabungan->jenis_transaksi;
        $this->editNominal = $tabungan->nominal;
        $this->editKeterangan = $tabungan->keterangan;
        
        $this->editJenisLama = $tabungan->jenis_transaksi;
        $this->editNominalLama = $tabungan->nominal;

        $this->dispatch('show-edit-tabungan-modal');
    }

    public function updateTabungan()
    {
        $this->validate([
            'editJenis' => 'required|in:setor,tarik',
            'editNominal' => 'required|numeric|min:1',
        ]);

        $tabungan = Tabungan::find($this->editId);
        if (!$tabungan || $tabungan->status_setoran === 'sudah') return;

        $siswa = Siswa::find($tabungan->id_siswa);
        if (!$siswa) return;
        
        $nominal = (int) $this->editNominal;

        DB::beginTransaction();
        try {
            // Rollback old balance
            if ($this->editJenisLama === 'setor') {
                $siswa->saldo_tabungan -= $this->editNominalLama;
            } else {
                $siswa->saldo_tabungan += $this->editNominalLama;
            }
            
            // Apply new balance
            if ($this->editJenis === 'setor') {
                $siswa->saldo_tabungan += $nominal;
            } else {
                $siswa->saldo_tabungan -= $nominal;
            }
            
            $tabungan->update([
                'jenis_transaksi' => $this->editJenis,
                'nominal' => $nominal,
                'keterangan' => $this->editKeterangan,
            ]);

            $siswa->save();

            DB::commit();
            $this->dispatch('hide-edit-tabungan-modal');
            session()->flash('success', 'Transaksi tabungan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat memperbarui tabungan.');
        }
    }

    public function hapusTabungan($id_tabungan)
    {
        $tabungan = Tabungan::find($id_tabungan);
        if (!$tabungan || $tabungan->status_setoran === 'sudah') return;

        $siswaIds = $this->getAssignedSiswaIds()->toArray();
        if (!in_array($tabungan->id_siswa, $siswaIds)) return;

        $siswa = Siswa::find($tabungan->id_siswa);
        if (!$siswa) return;

        DB::beginTransaction();
        try {
            // Rollback saldo
            if ($tabungan->jenis_transaksi === 'setor') {
                $siswa->saldo_tabungan -= $tabungan->nominal;
            } else {
                $siswa->saldo_tabungan += $tabungan->nominal;
            }
            $siswa->save();
            
            $tabungan->delete();
            
            DB::commit();
            session()->flash('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat menghapus tabungan.');
        }
    }

    public function render()
    {
        $siswaIds = $this->getAssignedSiswaIds();

        $query = Tabungan::with(['siswa', 'siswa.kelas'])
            ->whereIn('id_siswa', $siswaIds);

        if ($this->tanggal_awal) {
            $query->whereDate('tanggal', '>=', $this->tanggal_awal);
        }
        
        if ($this->tanggal_akhir) {
            $query->whereDate('tanggal', '<=', $this->tanggal_akhir);
        }

        if ($this->filter_santri_id) {
            $query->where('id_siswa', $this->filter_santri_id);
        }

        if ($this->filter_jenis) {
            $query->where('jenis_transaksi', $this->filter_jenis);
        }

        $riwayat = $query->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc')->paginate(15);

        return view('livewire.teacher.riwayat-tabungan-guru', [
            'riwayat' => $riwayat,
            'daftarSantri' => $this->getDaftarSantri(),
        ])->layout('layouts.admin', ['title' => 'Riwayat Tabungan', 'context' => 'riwayat-tabungan']);
    }
}
