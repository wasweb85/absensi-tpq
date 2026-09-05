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

        // Fallback jika dikirim parameter 'tanggal' (format YYYY-MM) atau jika kosong
        if (!$tanggalMulai && $request->has('tanggal')) {
            $parsed = Carbon::parse($request->query('tanggal'));
            $tanggalMulai = $parsed->copy()->startOfMonth()->toDateString();
            $tanggalAkhir = $parsed->copy()->endOfMonth()->toDateString();
        } elseif (!$tanggalMulai) {
            $tanggalMulai = Carbon::now()->startOfMonth()->toDateString();
            $tanggalAkhir = Carbon::now()->endOfMonth()->toDateString();
        }

        $kelas = Kelas::find($idKelas);
        if (!$kelas) {
            $kelas = Kelas::orderBy('tingkat')->first();
            $idKelas = $kelas ? $kelas->id_kelas : null;
        }

        $siswa = Siswa::where('id_kelas', $idKelas)->orderBy('nama_siswa')->get();

        if ($siswa->isEmpty()) {
            // Ambil kelas pertama yang memiliki santri jika kelas saat ini tidak ada siswanya
            $firstKelasWithSiswa = Siswa::select('id_kelas')->distinct()->first();
            if ($firstKelasWithSiswa) {
                $idKelas = $firstKelasWithSiswa->id_kelas;
                $kelas = Kelas::find($idKelas);
                $siswa = Siswa::where('id_kelas', $idKelas)->orderBy('nama_siswa')->get();
            }
        }

        if ($siswa->isEmpty()) {
            return redirect()->route('admin.laporan.index')->with('msg', 'Data siswa kosong!')->with('error', true);
        }

        $begin = Carbon::parse($tanggalMulai);
        $end = Carbon::parse($tanggalAkhir);

        $arrayTanggal = [];
        $dataAbsen = [];

        // Loop per hari dalam periode tersebut
        for ($date = $begin->copy(); $date->lte($end); $date->addDay()) {
            // Cek status hari libur dinamis dari Kalender TPQ & Hari Libur Mingguan
            $isLibur = AgendaKalender::isTanggalLibur($date);
            $keteranganLibur = AgendaKalender::getKeteranganLibur($date);
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
            $absenByTanggal['keterangan_libur'] = $keteranganLibur;

            $itemTanggal = (object) [
                'date' => $date->copy(),
                'is_libur' => $isLibur,
                'keterangan_libur' => $keteranganLibur
            ];

            $dataAbsen[] = $absenByTanggal;
            $arrayTanggal[] = $itemTanggal;
        }

        $laki = $siswa->where('jenis_kelamin', 'Laki-Laki')->count();

        // Agenda libur TPQ selama periode ini
        $agendaLiburBulanIni = AgendaKalender::where('is_libur', true)
            ->where(function ($q) use ($tanggalMulai, $tanggalAkhir) {
                $q->whereBetween('tanggal_mulai', [$tanggalMulai, $tanggalAkhir])
                  ->orWhere(function ($sub) use ($tanggalMulai, $tanggalAkhir) {
                      $sub->whereNotNull('tanggal_selesai')
                          ->where('tanggal_mulai', '<=', $tanggalAkhir)
                          ->where('tanggal_selesai', '>=', $tanggalMulai);
                  });
            })
            ->orderBy('tanggal_mulai')
            ->get();

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
            'grup' => "kelas " . ($kelas ? ($kelas->tingkat . ' ' . $kelas->index_kelas) : ''),
            'agendaLiburBulanIni' => $agendaLiburBulanIni,
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

        // Fallback jika dikirim parameter 'tanggal' (format YYYY-MM) atau jika kosong
        if (!$tanggalMulai && $request->has('tanggal')) {
            $parsed = Carbon::parse($request->query('tanggal'));
            $tanggalMulai = $parsed->copy()->startOfMonth()->toDateString();
            $tanggalAkhir = $parsed->copy()->endOfMonth()->toDateString();
        } elseif (!$tanggalMulai) {
            $tanggalMulai = Carbon::now()->startOfMonth()->toDateString();
            $tanggalAkhir = Carbon::now()->endOfMonth()->toDateString();
        }

        $guru = Guru::orderBy('nama_guru')->get();

        if ($guru->isEmpty()) {
            return redirect()->route('admin.laporan.index')->with('msg', 'Data guru kosong!')->with('error', true);
        }

        $begin = Carbon::parse($tanggalMulai);
        $end = Carbon::parse($tanggalAkhir);

        $arrayTanggal = [];
        $dataAbsen = [];

        // Loop per hari dalam periode tersebut
        for ($date = $begin->copy(); $date->lte($end); $date->addDay()) {
            $isLibur = AgendaKalender::isTanggalLibur($date);
            $keteranganLibur = AgendaKalender::getKeteranganLibur($date);
            $lewat = $date->isAfter(Carbon::today());

            // Left join with presensi_guru
            $absenByTanggal = Guru::select('tb_guru.*', 'p.id_presensi', 'p.tanggal', 'p.id_kehadiran')
                ->leftJoin(DB::raw("(SELECT * FROM tb_presensi_guru WHERE tanggal = '{$date->format('Y-m-d')}') as p"), 'tb_guru.id_guru', '=', 'p.id_guru')
                ->orderBy('tb_guru.nama_guru')
                ->get()
                ->toArray();
                
            $absenByTanggal['lewat'] = $lewat;
            $absenByTanggal['status_libur'] = $isLibur;
            $absenByTanggal['keterangan_libur'] = $keteranganLibur;

            $itemTanggal = (object) [
                'date' => $date->copy(),
                'is_libur' => $isLibur,
                'keterangan_libur' => $keteranganLibur
            ];

            $dataAbsen[] = $absenByTanggal;
            $arrayTanggal[] = $itemTanggal;
        }

        $laki = $guru->where('jenis_kelamin', 'Laki-Laki')->count();

        // Agenda libur TPQ selama periode ini
        $agendaLiburBulanIni = AgendaKalender::where('is_libur', true)
            ->where(function ($q) use ($tanggalMulai, $tanggalAkhir) {
                $q->whereBetween('tanggal_mulai', [$tanggalMulai, $tanggalAkhir])
                  ->orWhere(function ($sub) use ($tanggalMulai, $tanggalAkhir) {
                      $sub->whereNotNull('tanggal_selesai')
                          ->where('tanggal_mulai', '<=', $tanggalAkhir)
                          ->where('tanggal_selesai', '>=', $tanggalMulai);
                  });
            })
            ->orderBy('tanggal_mulai')
            ->get();

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
            'agendaLiburBulanIni' => $agendaLiburBulanIni,
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
