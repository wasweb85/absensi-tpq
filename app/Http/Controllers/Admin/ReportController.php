<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgendaKalender;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\PresensiGuru;
use App\Models\PresensiSiswa;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function generateLaporanSiswa(Request $request)
    {
        $idKelas = $request->query('kelas');
        $type = $request->query('type');
        $tanggalMulai = $request->query('tanggal_mulai');
        $tanggalAkhir = $request->query('tanggal_akhir');

        $siswa = Siswa::where('id_kelas', $idKelas)->orderBy('nama_siswa')->get();

        if ($siswa->isEmpty()) {
            return redirect()->route('admin.laporan.index')->with('msg', 'Data siswa kosong!')->with('error', true);
        }

        $kelas = Kelas::find($idKelas);

        $begin = Carbon::parse($tanggalMulai);
        $end = Carbon::parse($tanggalAkhir);

        $arrayTanggal = [];
        $dataAbsen = [];

        // Loop per hari dalam bulan tersebut
        for ($date = $begin->copy(); $date->lte($end); $date->addDay()) {
            // Cek status hari libur dinamis dari Kalender TPQ & Hari Libur Mingguan
            $isLibur = AgendaKalender::isTanggalLibur($date);
            $lewat = $date->isAfter(Carbon::today());

            // Left join with presensi_siswa for this specific date
            $absenByTanggal = Siswa::select('tb_siswa.*', 'p.id_presensi', 'p.tanggal', 'p.id_kehadiran')
                ->where('tb_siswa.id_kelas', $idKelas)
                ->leftJoin(DB::raw("(SELECT * FROM tb_presensi_siswa WHERE tanggal = '{$date->format('Y-m-d')}') as p"), 'tb_siswa.id_siswa', '=', 'p.id_siswa')
                ->orderBy('tb_siswa.nama_siswa')
                ->get()
                ->toArray();
                
            $absenByTanggal['lewat'] = $lewat;
            $absenByTanggal['status_libur'] = $isLibur;

            $dataAbsen[] = $absenByTanggal;
            $arrayTanggal[] = $date->copy();
        }

        $laki = $siswa->where('jenis_kelamin', 'Laki-Laki')->count();

        // General settings (for now mock or use config if available, wait, we don't have GeneralSettings model yet. Let's make a basic object)
        // I will implement GeneralSettings model shortly. For now, use DB::table('general_settings')->first() or fallback
        $generalSettings = DB::table('general_settings')->first() ?? (object) [
            'school_name' => 'TPQ Darul Huda',
            'school_year' => '2026/2027',
        ];

        $data = [
            'tanggal' => $arrayTanggal,
            'bulan' => $begin->translatedFormat('d M Y') . ' - ' . $end->translatedFormat('d M Y'),
            'listAbsen' => $dataAbsen,
            'listSiswa' => $siswa,
            'rekapSiswa' => [
                'laki' => $laki,
                'perempuan' => $siswa->count() - $laki
            ],
            'kelas' => $kelas,
            'grup' => "kelas " . $kelas->tingkat . ' ' . $kelas->index_kelas,
            'generalSettings' => $generalSettings
        ];

        if ($type == 'doc') {
            return response(view('admin.report.laporan-siswa', $data))
                ->header('Content-Type', 'application/vnd.ms-word')
                ->header('Content-Disposition', 'attachment;Filename=Laporan_absen_siswa.doc');
        } elseif ($type == 'xls') {
            return response(view('admin.report.laporan-siswa', $data))
                ->header('Content-Type', 'application/vnd.ms-excel')
                ->header('Content-Disposition', 'attachment;Filename=Laporan_absen_siswa.xls');
        } else {
            // PDF -> Print HTML
            return view('admin.report.topdf', ['content' => view('admin.report.laporan-siswa', $data)->render()]);
        }
    }

    public function generateLaporanGuru(Request $request)
    {
        $type = $request->query('type');
        $tanggalMulai = $request->query('tanggal_mulai');
        $tanggalAkhir = $request->query('tanggal_akhir');

        $guru = Guru::orderBy('nama_guru')->get();

        if ($guru->isEmpty()) {
            return redirect()->route('admin.laporan.index')->with('msg', 'Data guru kosong!')->with('error', true);
        }

        $begin = Carbon::parse($tanggalMulai);
        $end = Carbon::parse($tanggalAkhir);

        $arrayTanggal = [];
        $dataAbsen = [];

        // Loop per hari dalam bulan tersebut
        for ($date = $begin->copy(); $date->lte($end); $date->addDay()) {
            $isLibur = AgendaKalender::isTanggalLibur($date);
            $lewat = $date->isAfter(Carbon::today());

            // Left join with presensi_guru
            $absenByTanggal = Guru::select('tb_guru.*', 'p.id_presensi', 'p.tanggal', 'p.id_kehadiran')
                ->leftJoin(DB::raw("(SELECT * FROM tb_presensi_guru WHERE tanggal = '{$date->format('Y-m-d')}') as p"), 'tb_guru.id_guru', '=', 'p.id_guru')
                ->orderBy('tb_guru.nama_guru')
                ->get()
                ->toArray();
                
            $absenByTanggal['lewat'] = $lewat;
            $absenByTanggal['status_libur'] = $isLibur;

            $dataAbsen[] = $absenByTanggal;
            $arrayTanggal[] = $date->copy();
        }

        $laki = $guru->where('jenis_kelamin', 'Laki-Laki')->count();

        $generalSettings = DB::table('general_settings')->first() ?? (object) [
            'school_name' => 'TPQ Darul Huda',
            'school_year' => '2026/2027',
        ];

        $data = [
            'tanggal' => $arrayTanggal,
            'bulan' => $begin->translatedFormat('d M Y') . ' - ' . $end->translatedFormat('d M Y'),
            'listAbsen' => $dataAbsen,
            'listGuru' => $guru,
            'jumlahGuru' => [
                'laki' => $laki,
                'perempuan' => $guru->count() - $laki
            ],
            'grup' => "guru",
            'generalSettings' => $generalSettings
        ];

        if ($type == 'doc') {
            return response(view('admin.report.laporan-guru', $data))
                ->header('Content-Type', 'application/vnd.ms-word')
                ->header('Content-Disposition', 'attachment;Filename=Laporan_absen_guru.doc');
        } elseif ($type == 'xls') {
            return response(view('admin.report.laporan-guru', $data))
                ->header('Content-Type', 'application/vnd.ms-excel')
                ->header('Content-Disposition', 'attachment;Filename=Laporan_absen_guru.xls');
        } else {
            // PDF -> Print HTML
            return view('admin.report.topdf', ['content' => view('admin.report.laporan-guru', $data)->render()]);
        }
    }
}
