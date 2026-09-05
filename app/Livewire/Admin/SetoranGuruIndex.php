<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Guru;
use App\Models\Tabungan;
use App\Models\SetoranBendahara;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SetoranGuruIndex extends Component
{
    public function mount()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->to('/login');
        }

        $isSuperadmin = (int) ($user->is_superadmin ?? 0) === 1;
        $canSetoran = $isSuperadmin || \App\Models\RolePermission::hasAccess($user, 'setoran_bendahara');

        if (!$canSetoran) {
            abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
        }
    }

    public function terimaSetoran($id_guru)
    {
        $guru = Guru::find($id_guru);
        if (!$guru) return;

        // Find unsettled tabungan records for this guru (where id_user = guru's user id, or we just rely on id_siswa's wali_kelas but id_user is safer).
        // Let's use id_user since when the teacher inputs, they are logged in. Wait, we don't have id_user in tb_guru directly.
        // But the tabungan entries have `id_siswa` belonging to the guru's assigned classes.
        
        // Find assigned classes for this guru
        $waliKelasIds = \App\Models\Kelas::where('id_wali_kelas', $id_guru)->pluck('id_kelas')->toArray();
        $binaanIds = DB::table('guru_kelas')->where('id_guru', $id_guru)->pluck('id_kelas')->toArray();
        $assignedKelasIds = array_unique(array_merge($waliKelasIds, $binaanIds));
        
        if (empty($assignedKelasIds)) {
            session()->flash('error', 'Guru tidak memiliki kelas binaan.');
            return;
        }

        $siswaIds = \App\Models\Siswa::whereIn('id_kelas', $assignedKelasIds)->pluck('id_siswa')->toArray();

        $unsettledSetor = Tabungan::whereIn('id_siswa', $siswaIds)
            ->where('status_setoran', 'belum')
            ->where('jenis_transaksi', 'setor')
            ->sum('nominal');

        $unsettledTarik = Tabungan::whereIn('id_siswa', $siswaIds)
            ->where('status_setoran', 'belum')
            ->where('jenis_transaksi', 'tarik')
            ->sum('nominal');

        $totalUnsettled = $unsettledSetor - $unsettledTarik;

        if ($totalUnsettled <= 0) {
            session()->flash('error', 'Tidak ada dana yang perlu disetorkan.');
            return;
        }

        DB::beginTransaction();
        try {
            $setoran = SetoranBendahara::create([
                'id_guru' => $id_guru,
                'id_bendahara' => Auth::id(),
                'tanggal' => Carbon::now(),
                'nominal' => $totalUnsettled,
                'keterangan' => 'Penarikan setoran oleh bendahara',
            ]);

            Tabungan::whereIn('id_siswa', $siswaIds)
                ->where('status_setoran', 'belum')
                ->update([
                    'status_setoran' => 'sudah',
                    'id_setoran' => $setoran->id_setoran,
                ]);

            DB::commit();
            session()->flash('success', 'Setoran sebesar Rp ' . number_format($totalUnsettled, 0, ',', '.') . ' berhasil diterima.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        // Get all Gurus with their unsettled amount
        $gurus = Guru::orderBy('nama_guru')->get();
        $setoranList = [];

        foreach ($gurus as $guru) {
            $waliKelasIds = \App\Models\Kelas::where('id_wali_kelas', $guru->id_guru)->pluck('id_kelas')->toArray();
            $binaanIds = DB::table('guru_kelas')->where('id_guru', $guru->id_guru)->pluck('id_kelas')->toArray();
            $assignedKelasIds = array_unique(array_merge($waliKelasIds, $binaanIds));
            
            if (empty($assignedKelasIds)) {
                continue;
            }

            $siswaIds = \App\Models\Siswa::whereIn('id_kelas', $assignedKelasIds)->pluck('id_siswa')->toArray();

            $unsettledSetor = Tabungan::whereIn('id_siswa', $siswaIds)
                ->where('status_setoran', 'belum')
                ->where('jenis_transaksi', 'setor')
                ->sum('nominal');
                
            $unsettledTarik = Tabungan::whereIn('id_siswa', $siswaIds)
                ->where('status_setoran', 'belum')
                ->where('jenis_transaksi', 'tarik')
                ->sum('nominal');

            $totalUnsettled = $unsettledSetor - $unsettledTarik;

            if ($totalUnsettled > 0) {
                $guru->unsettled_amount = $totalUnsettled;
                $setoranList[] = $guru;
            }
        }

        // Fetch recent setoran history
        $riwayatSetoran = SetoranBendahara::with(['guru', 'bendahara'])->orderBy('tanggal', 'desc')->take(10)->get();

        return view('livewire.admin.setoran-guru-index', [
            'setoranList' => collect($setoranList),
            'riwayatSetoran' => $riwayatSetoran
        ])->layout('layouts.admin', ['title' => 'Rekap Setoran Guru', 'context' => 'setoran-guru']);
    }
}
