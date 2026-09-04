<?php

namespace App\Livewire\Kepsek;

use Livewire\Component;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\PresensiSiswa;
use App\Models\PresensiGuru;
use App\Models\Tabungan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public function mount()
    {
        $user = Auth::user();
        if (!$user || !in_array((int) ($user->is_superadmin ?? 0), [1, 2])) {
            return redirect()->to('/dashboard');
        }
    }

    public function refreshData()
    {
        // Triggers re-render for realtime updates
        session()->flash('info', 'Data berhasil diperbarui.');
    }

    public function render()
    {
        $today = Carbon::today()->toDateString();
        $formattedDate = Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y');

        // 1. STATISTIK PRESENSI SANTRI
        $totalSiswa = Siswa::count();
        $siswaHadir = PresensiSiswa::where('tanggal', $today)->where('id_kehadiran', 1)->count();
        $siswaSakit = PresensiSiswa::where('tanggal', $today)->where('id_kehadiran', 2)->count();
        $siswaIzin = PresensiSiswa::where('tanggal', $today)->where('id_kehadiran', 3)->count();
        $siswaAlfa = PresensiSiswa::where('tanggal', $today)->where('id_kehadiran', 4)->count();
        $persenSiswaHadir = $totalSiswa > 0 ? round(($siswaHadir / $totalSiswa) * 100, 1) : 0;

        // 2. STATISTIK PRESENSI USTADZAH & INVAL
        $totalGuru = Guru::count();
        $presensiGuruHariIni = PresensiGuru::with(['guru.kelas', 'kehadiran'])->where('tanggal', $today)->get();
        $guruHadir = $presensiGuruHariIni->where('id_kehadiran', 1)->count();
        $guruInval = $presensiGuruHariIni->where('id_kehadiran', '!=', 1);

        $recordedGuruIds = $presensiGuruHariIni->pluck('id_guru')->filter()->toArray();
        $guruBelumPresensi = Guru::with('kelas')->whereNotIn('id_guru', $recordedGuruIds)->get();

        // 3. KEUANGAN TABUNGAN
        $totalSetor = Tabungan::where('jenis_transaksi', 'setor')->sum('nominal');
        $totalTarik = Tabungan::where('jenis_transaksi', 'tarik')->sum('nominal');
        $totalKasTabungan = $totalSetor - $totalTarik;
        $totalBelumDisetor = Tabungan::where('jenis_transaksi', 'setor')
            ->where('status_setoran', 'belum')
            ->sum('nominal');

        // 4. MATRIKS KELAS REALTIME
        $presensiPerKelas = PresensiSiswa::where('tanggal', $today)
            ->selectRaw('id_kelas, id_kehadiran, count(*) as total')
            ->groupBy('id_kelas', 'id_kehadiran')
            ->get()
            ->groupBy('id_kelas');

        $kelasList = Kelas::with('guru')->withCount('siswa')->orderBy('tingkat')->orderBy('index_kelas')->get();
        $matriksKelas = $kelasList->map(function ($k) use ($presensiPerKelas) {
            $rekap = $presensiPerKelas->get($k->id_kelas);
            $h = $rekap ? $rekap->where('id_kehadiran', 1)->sum('total') : 0;
            $s = $rekap ? $rekap->where('id_kehadiran', 2)->sum('total') : 0;
            $i = $rekap ? $rekap->where('id_kehadiran', 3)->sum('total') : 0;
            $a = $rekap ? $rekap->where('id_kehadiran', 4)->sum('total') : 0;
            $totalDiabsen = $h + $s + $i + $a;
            $totalSiswaKelas = $k->siswa_count;
            $persen = $totalSiswaKelas > 0 ? round(($h / $totalSiswaKelas) * 100, 1) : 0;

            if ($totalDiabsen === 0) {
                $status = 'belum';
            } elseif ($totalDiabsen >= $totalSiswaKelas && $totalSiswaKelas > 0) {
                $status = 'lengkap';
            } else {
                $status = 'sebagian';
            }

            return [
                'id_kelas' => $k->id_kelas,
                'nama_kelas' => $k->tingkat . ' ' . $k->index_kelas,
                'wali_kelas' => $k->guru ? $k->guru->nama_guru : 'Belum Ditugaskan',
                'wali_kelas_hp' => $k->guru ? $k->guru->no_hp : null,
                'total_siswa' => $totalSiswaKelas,
                'hadir' => $h,
                'sakit' => $s,
                'izin' => $i,
                'alfa' => $a,
                'persen' => $persen,
                'status' => $status,
            ];
        });

        // Rekap status kelas
        $totalKelas = $kelasList->count();
        $kelasSelesai = $matriksKelas->where('status', 'lengkap')->count();

        return view('livewire.kepsek.dashboard', [
            'today' => $today,
            'formattedDate' => $formattedDate,
            'totalSiswa' => $totalSiswa,
            'siswaHadir' => $siswaHadir,
            'siswaSakit' => $siswaSakit,
            'siswaIzin' => $siswaIzin,
            'siswaAlfa' => $siswaAlfa,
            'persenSiswaHadir' => $persenSiswaHadir,
            'totalGuru' => $totalGuru,
            'guruHadir' => $guruHadir,
            'guruInval' => $guruInval,
            'guruBelumPresensi' => $guruBelumPresensi,
            'totalKasTabungan' => $totalKasTabungan,
            'totalBelumDisetor' => $totalBelumDisetor,
            'matriksKelas' => $matriksKelas,
            'totalKelas' => $totalKelas,
            'kelasSelesai' => $kelasSelesai,
        ])->layout('layouts.admin', [
            'title' => 'Executive Dashboard TPQ',
            'context' => 'dashboard'
        ]);
    }
}
