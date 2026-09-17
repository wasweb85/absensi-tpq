<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Tabungan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LaporanTabunganIndex extends Component
{
    public $selectedMonth;
    public $selectedYear;

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

        $this->selectedMonth = (int) date('n');
        $this->selectedYear = (int) date('Y');
    }

    public function render()
    {
        $bulan = (int) ($this->selectedMonth ?: date('n'));
        $tahun = (int) ($this->selectedYear ?: date('Y'));

        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth()->toDateString();
        $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth()->toDateString();

        // 1. Saldo Awal (sebelum awal bulan terpilih)
        $totalSetorSebelum = (float) Tabungan::where('tanggal', '<', $startDate)->where('jenis_transaksi', 'setor')->sum('nominal');
        $totalTarikSebelum = (float) Tabungan::where('tanggal', '<', $startDate)->where('jenis_transaksi', 'tarik')->sum('nominal');
        $saldoAwal = $totalSetorSebelum - $totalTarikSebelum;

        // 2. Mutasi Bulan Ini
        $totalSetoranBulanIni = (float) Tabungan::whereBetween('tanggal', [$startDate, $endDate])->where('jenis_transaksi', 'setor')->sum('nominal');
        $totalPenarikanBulanIni = (float) Tabungan::whereBetween('tanggal', [$startDate, $endDate])->where('jenis_transaksi', 'tarik')->sum('nominal');
        $saldoAkhir = $saldoAwal + $totalSetoranBulanIni - $totalPenarikanBulanIni;

        // 3. Akuntabilitas Kas Fisik & Dana Mengendap
        $totalSetorSudah = (float) Tabungan::where('jenis_transaksi', 'setor')->where('status_setoran', 'sudah')->sum('nominal');
        $totalTarikSemua = (float) Tabungan::where('jenis_transaksi', 'tarik')->sum('nominal');
        $kasDiBendahara = max(0, $totalSetorSudah - $totalTarikSemua);
        $totalDanaMengendap = (float) Tabungan::where('jenis_transaksi', 'setor')->where('status_setoran', 'belum')->sum('nominal');

        // 4. Rekap Per Kelas
        $kelases = Kelas::with(['guru'])->orderBy('tingkat')->get();

        $laporanKelas = [];
        $totalTabunganKeseluruhan = 0;

        foreach ($kelases as $kelas) {
            $siswaIds = Siswa::where('id_kelas', $kelas->id_kelas)->pluck('id_siswa');
            $santriCount = Siswa::where('id_kelas', $kelas->id_kelas)->where('saldo_tabungan', '>', 0)->count();
            if ($santriCount === 0) {
                $santriCount = count($siswaIds);
            }
            
            // Setor & Tarik bulan ini
            $setorBulanIni = (float) Tabungan::whereIn('id_siswa', $siswaIds)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->where('jenis_transaksi', 'setor')
                ->sum('nominal');
            
            $tarikBulanIni = (float) Tabungan::whereIn('id_siswa', $siswaIds)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->where('jenis_transaksi', 'tarik')
                ->sum('nominal');

            // Saldo akumulatif sampai akhir bulan ini
            $totalSetorKelas = (float) Tabungan::whereIn('id_siswa', $siswaIds)->where('tanggal', '<=', $endDate)->where('jenis_transaksi', 'setor')->sum('nominal');
            $totalTarikKelas = (float) Tabungan::whereIn('id_siswa', $siswaIds)->where('tanggal', '<=', $endDate)->where('jenis_transaksi', 'tarik')->sum('nominal');
            $saldoKelas = $totalSetorKelas - $totalTarikKelas;
            
            // Hitung uang di tangan guru (belum disetor)
            $danaMengendap = (float) Tabungan::whereIn('id_siswa', $siswaIds)
                ->where('jenis_transaksi', 'setor')
                ->where('status_setoran', 'belum')
                ->sum('nominal');

            $laporanKelas[] = [
                'kelas' => $kelas,
                'guru' => $kelas->guru,
                'santri_count' => $santriCount,
                'setor_bulan_ini' => $setorBulanIni,
                'tarik_bulan_ini' => $tarikBulanIni,
                'saldo' => $saldoKelas,
                'dana_mengendap' => $danaMengendap
            ];

            $totalTabunganKeseluruhan += $saldoKelas;
        }

        // Get 10 most recent activities across all classes
        $aktivitasTerbaru = Tabungan::with(['siswa', 'siswa.kelas'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return view('livewire.admin.laporan-tabungan-index', [
            'laporanKelas' => collect($laporanKelas)->sortByDesc('saldo')->values(),
            'saldoAwal' => $saldoAwal,
            'totalSetoranBulanIni' => $totalSetoranBulanIni,
            'totalPenarikanBulanIni' => $totalPenarikanBulanIni,
            'saldoAkhir' => $saldoAkhir,
            'kasDiBendahara' => $kasDiBendahara,
            'totalDanaMengendap' => $totalDanaMengendap,
            'aktivitasTerbaru' => $aktivitasTerbaru,
            'monthNames' => $monthNames,
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
