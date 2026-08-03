<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\PresensiSiswa;
use App\Models\PresensiGuru;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
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

        return view('dashboard', [
            'siswa' => Siswa::all(),
            'guru' => Guru::all(),
            'kelas' => Kelas::all(),
            'jumlahKehadiranSiswa' => $jumlahKehadiranSiswa,
            'jumlahKehadiranGuru' => $jumlahKehadiranGuru,
            'totalSiswa' => Siswa::count(),
            'grafikKehadiranSiswa' => collect(), // Dummy data for chart if any
            'grafikKehadiranGuru' => collect(), // Dummy data for chart if any
        ]);
    }

    public function filterData(\Illuminate\Http\Request $request)
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

        $jumlahSiswa = Siswa::query();
        if ($idKelas) {
            $jumlahSiswa->where('id_kelas', $idKelas);
        }
        $jumlahSiswa = $jumlahSiswa->count();

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
            'chartData' => [],
            'totalSiswa' => $jumlahSiswa
        ]);
    }
}
