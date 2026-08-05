<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\User;
use App\Models\PresensiSiswa;
use App\Models\PresensiGuru;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->check() && !empty(auth()->user()->id_guru) && auth()->user()->is_superadmin != 1) {
            return redirect()->route('teacher.dashboard');
        }

        $today = Carbon::today()->toDateString();

        $jumlahKehadiranSiswa = [
            'hadir' => PresensiSiswa::where('tanggal', $today)->where('id_kehadiran', '1')->count(),
            'sakit' => PresensiSiswa::where('tanggal', $today)->where('id_kehadiran', '2')->count(),
            'izin' => PresensiSiswa::where('tanggal', $today)->where('id_kehadiran', '3')->count(),
            'alfa' => PresensiSiswa::where('tanggal', $today)->where('id_kehadiran', '4')->count(),
        ];

        $jumlahKehadiranGuru = [
            'hadir' => PresensiGuru::where('tanggal', $today)->where('id_kehadiran', '1')->count(),
            'sakit' => PresensiGuru::where('tanggal', $today)->where('id_kehadiran', '2')->count(),
            'izin' => PresensiGuru::where('tanggal', $today)->where('id_kehadiran', '3')->count(),
            'alfa' => PresensiGuru::where('tanggal', $today)->where('id_kehadiran', '4')->count(),
        ];

        // 7-day trends for Siswa and Guru
        $chartLabels = [];
        $chartSiswa = ['hadir' => [], 'sakit' => [], 'izin' => [], 'alfa' => []];
        $chartGuru = ['hadir' => [], 'sakit' => [], 'izin' => [], 'alfa' => []];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateStr = $date->toDateString();
            $chartLabels[] = $date->locale('id')->isoFormat('D MMM');

            // Siswa counts
            $chartSiswa['hadir'][] = PresensiSiswa::where('tanggal', $dateStr)->where('id_kehadiran', '1')->count();
            $chartSiswa['sakit'][] = PresensiSiswa::where('tanggal', $dateStr)->where('id_kehadiran', '2')->count();
            $chartSiswa['izin'][]  = PresensiSiswa::where('tanggal', $dateStr)->where('id_kehadiran', '3')->count();
            $chartSiswa['alfa'][]  = PresensiSiswa::where('tanggal', $dateStr)->where('id_kehadiran', '4')->count();

            // Guru counts
            $chartGuru['hadir'][] = PresensiGuru::where('tanggal', $dateStr)->where('id_kehadiran', '1')->count();
            $chartGuru['sakit'][] = PresensiGuru::where('tanggal', $dateStr)->where('id_kehadiran', '2')->count();
            $chartGuru['izin'][]  = PresensiGuru::where('tanggal', $dateStr)->where('id_kehadiran', '3')->count();
            $chartGuru['alfa'][]  = PresensiGuru::where('tanggal', $dateStr)->where('id_kehadiran', '4')->count();
        }

        $kelases = Kelas::withCount('siswa')->get();
        $totalSiswa = Siswa::count();
        $totalGuru = Guru::count();
        $totalKelas = $kelases->count();
        $totalPetugas = User::count();

        return view('dashboard', [
            'totalSiswa' => $totalSiswa,
            'totalGuru' => $totalGuru,
            'totalKelas' => $totalKelas,
            'totalPetugas' => $totalPetugas,
            'kelases' => $kelases,
            'jumlahKehadiranSiswa' => $jumlahKehadiranSiswa,
            'jumlahKehadiranGuru' => $jumlahKehadiranGuru,
            'chartLabels' => $chartLabels,
            'chartSiswa' => $chartSiswa,
            'chartGuru' => $chartGuru,
        ]);
    }

    public function filterData(Request $request)
    {
        $idKelas = $request->input('id_kelas');
        $today = Carbon::today()->toDateString();

        $query = PresensiSiswa::where('tanggal', $today);
        if ($idKelas) {
            $query->where('id_kelas', $idKelas);
        }

        $jumlahKehadiranSiswa = [
            'hadir' => (clone $query)->where('id_kehadiran', '1')->count(),
            'sakit' => (clone $query)->where('id_kehadiran', '2')->count(),
            'izin' => (clone $query)->where('id_kehadiran', '3')->count(),
            'alfa' => (clone $query)->where('id_kehadiran', '4')->count(),
        ];

        $jumlahSiswaQuery = Siswa::query();
        if ($idKelas) {
            $jumlahSiswaQuery->where('id_kelas', $idKelas);
        }
        $jumlahSiswa = $jumlahSiswaQuery->count();

        $chartSiswa = ['hadir' => [], 'sakit' => [], 'izin' => [], 'alfa' => []];
        for ($i = 6; $i >= 0; $i--) {
            $dateStr = Carbon::today()->subDays($i)->toDateString();
            $dq = PresensiSiswa::where('tanggal', $dateStr);
            if ($idKelas) {
                $dq->where('id_kelas', $idKelas);
            }
            $chartSiswa['hadir'][] = (clone $dq)->where('id_kehadiran', '1')->count();
            $chartSiswa['sakit'][] = (clone $dq)->where('id_kehadiran', '2')->count();
            $chartSiswa['izin'][]  = (clone $dq)->where('id_kehadiran', '3')->count();
            $chartSiswa['alfa'][]  = (clone $dq)->where('id_kehadiran', '4')->count();
        }

        $data = [
            'hadir' => $jumlahKehadiranSiswa['hadir'],
            'sakit' => $jumlahKehadiranSiswa['sakit'],
            'izin' => $jumlahKehadiranSiswa['izin'],
            'alfa' => $jumlahKehadiranSiswa['alfa'],
            'totalSiswa' => $jumlahSiswa,
        ];

        return response()->json([
            'result' => 1,
            'htmlContent' => view('admin._dashboard_siswa_stats', $data)->render(),
            'chartData' => $chartSiswa,
            'totalSiswa' => $jumlahSiswa
        ]);
    }
}
