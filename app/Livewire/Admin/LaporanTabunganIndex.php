<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Kelas;
use App\Models\Tabungan;
use Illuminate\Support\Facades\Auth;

class LaporanTabunganIndex extends Component
{
    public $detailSiswa = [];
    public $namaKelasTerpilih = '';
    
    public $editAktivitasId = null;
    public $editAktivitasNominal = '';
    public $editAktivitasJenis = '';
    public $editAktivitasKeterangan = '';
    public $editAktivitasNominalLama = 0;
    public $editAktivitasJenisLama = '';

    public function mount()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->to('/login');
        }

        $isSuperadmin = (int) ($user->is_superadmin ?? 0) === 1;
        $canLaporan = $isSuperadmin || \App\Models\RolePermission::hasAccess($user, 'laporan_tabungan');

        if (!$canLaporan) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat Laporan Tabungan.');
        }
    }

    public function render()
    {
        // Get all classes with their total tabungan
        $kelases = Kelas::with(['guru'])->get();

        $laporanKelas = [];
        $totalTabunganKeseluruhan = 0;
        $totalDanaMengendap = 0;

        foreach ($kelases as $kelas) {
            $siswaIds = \App\Models\Siswa::where('id_kelas', $kelas->id_kelas)->pluck('id_siswa');
            
            // Hitung total saldo (setor - tarik) untuk kelas ini
            $totalSetor = Tabungan::whereIn('id_siswa', $siswaIds)->where('jenis_transaksi', 'setor')->sum('nominal');
            $totalTarik = Tabungan::whereIn('id_siswa', $siswaIds)->where('jenis_transaksi', 'tarik')->sum('nominal');
            $saldoKelas = $totalSetor - $totalTarik;
            
            // Hitung uang di tangan guru (belum disetor)
            $danaMengendap = Tabungan::whereIn('id_siswa', $siswaIds)
                ->where('jenis_transaksi', 'setor')
                ->where('status_setoran', 'belum')
                ->sum('nominal');

            $laporanKelas[] = [
                'kelas' => $kelas,
                'guru' => $kelas->guru,
                'saldo' => $saldoKelas,
                'dana_mengendap' => $danaMengendap
            ];

            $totalTabunganKeseluruhan += $saldoKelas;
            $totalDanaMengendap += $danaMengendap;
        }

        // Get 10 most recent activities across all classes
        $aktivitasTerbaru = Tabungan::with(['siswa', 'siswa.kelas'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('livewire.admin.laporan-tabungan-index', [
            'laporanKelas' => collect($laporanKelas)->sortByDesc('saldo')->values(),
            'totalTabunganKeseluruhan' => $totalTabunganKeseluruhan,
            'totalDanaMengendap' => $totalDanaMengendap,
            'aktivitasTerbaru' => $aktivitasTerbaru
        ])->layout('layouts.admin', ['title' => 'Laporan Tabungan', 'context' => 'laporan-tabungan']);
    }
    public function bukaDetailKelas($id_kelas)
    {
        $kelas = Kelas::find($id_kelas);
        if (!$kelas) return;

        $this->namaKelasTerpilih = $kelas->tingkat . ' ' . $kelas->index_kelas;
        
        $siswas = \App\Models\Siswa::where('id_kelas', $id_kelas)->orderBy('nama_siswa', 'asc')->get();
        $detail = [];

        foreach ($siswas as $siswa) {
            $totalSetor = Tabungan::where('id_siswa', $siswa->id_siswa)->where('jenis_transaksi', 'setor')->sum('nominal');
            $totalTarik = Tabungan::where('id_siswa', $siswa->id_siswa)->where('jenis_transaksi', 'tarik')->sum('nominal');
            $saldo = $totalSetor - $totalTarik;
            
            $danaMengendap = Tabungan::where('id_siswa', $siswa->id_siswa)
                ->where('jenis_transaksi', 'setor')
                ->where('status_setoran', 'belum')
                ->sum('nominal');
                
            $detail[] = [
                'nama' => $siswa->nama_siswa,
                'saldo' => $saldo,
                'dana_mengendap' => $danaMengendap
            ];
        }

        $this->detailSiswa = $detail;
        
        $this->dispatch('show-detail-modal');
    }

    public function editAktivitas($id_tabungan)
    {
        $tabungan = Tabungan::find($id_tabungan);
        if (!$tabungan) return;

        $this->editAktivitasId = $tabungan->id_tabungan;
        $this->editAktivitasJenis = $tabungan->jenis_transaksi;
        $this->editAktivitasNominal = $tabungan->nominal;
        $this->editAktivitasKeterangan = $tabungan->keterangan;
        
        $this->editAktivitasJenisLama = $tabungan->jenis_transaksi;
        $this->editAktivitasNominalLama = $tabungan->nominal;

        $this->dispatch('show-edit-aktivitas-modal');
    }

    public function updateAktivitas()
    {
        $this->validate([
            'editAktivitasJenis' => 'required|in:setor,tarik',
            'editAktivitasNominal' => 'required|numeric|min:1',
        ]);

        $tabungan = Tabungan::find($this->editAktivitasId);
        if (!$tabungan) return;

        $siswa = \App\Models\Siswa::find($tabungan->id_siswa);
        if (!$siswa) return;
        
        $nominal = (int) $this->editAktivitasNominal;

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Rollback old balance
            if ($this->editAktivitasJenisLama === 'setor') {
                $siswa->saldo_tabungan -= $this->editAktivitasNominalLama;
            } else {
                $siswa->saldo_tabungan += $this->editAktivitasNominalLama;
            }
            
            // Apply new balance
            if ($this->editAktivitasJenis === 'setor') {
                $siswa->saldo_tabungan += $nominal;
            } else {
                $siswa->saldo_tabungan -= $nominal;
            }
            
            $tabungan->update([
                'jenis_transaksi' => $this->editAktivitasJenis,
                'nominal' => $nominal,
                'keterangan' => $this->editAktivitasKeterangan,
            ]);

            $siswa->save();

            \Illuminate\Support\Facades\DB::commit();
            $this->dispatch('hide-edit-aktivitas-modal');
            session()->flash('success', 'Transaksi tabungan berhasil diperbarui.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat memperbarui tabungan.');
        }
    }

    public function hapusAktivitas($id_tabungan)
    {
        $tabungan = Tabungan::find($id_tabungan);
        if (!$tabungan) return;

        $siswa = \App\Models\Siswa::find($tabungan->id_siswa);
        if (!$siswa) return;

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Rollback saldo
            if ($tabungan->jenis_transaksi === 'setor') {
                $siswa->saldo_tabungan -= $tabungan->nominal;
            } else {
                $siswa->saldo_tabungan += $tabungan->nominal;
            }
            $siswa->save();
            
            $tabungan->delete();
            
            \Illuminate\Support\Facades\DB::commit();
            session()->flash('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat menghapus tabungan.');
        }
    }
}
