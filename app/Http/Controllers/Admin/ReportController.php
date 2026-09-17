<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgendaKalender;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\PresensiGuru;
use App\Models\PresensiSiswa;
use App\Models\Siswa;
use App\Models\Tabungan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\LaporanPresensiExportService;
use App\Services\LaporanTabunganExportService;

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
        } elseif ($type == 'xls' || $type == 'xlsx') {
            return (new LaporanPresensiExportService())->exportSiswa($data);
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
        } elseif ($type == 'xls' || $type == 'xlsx') {
            return (new LaporanPresensiExportService())->exportGuru($data);
        } else {
            // PDF -> Print HTML
            return view('admin.report.topdf', ['content' => view('admin.report.laporan-guru', $data)->render()]);
        }
    }

    public function generateLaporanTabungan(Request $request)
    {
        $type = $request->query('type', 'pdf');
        $bulan = (int) ($request->query('bulan') ?: Carbon::now()->month);
        $tahun = (int) ($request->query('tahun') ?: Carbon::now()->year);

        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth()->toDateString();
        $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth()->toDateString();

        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $namaBulan = $monthNames[$bulan] ?? 'Bulan';
        $periodeLabel = $namaBulan . ' ' . $tahun;

        // 1. Saldo Awal (sebelum awal bulan terpilih)
        $totalSetorSebelum = Tabungan::where('tanggal', '<', $startDate)->where('jenis_transaksi', 'setor')->sum('nominal');
        $totalTarikSebelum = Tabungan::where('tanggal', '<', $startDate)->where('jenis_transaksi', 'tarik')->sum('nominal');
        $saldoAwal = (float) ($totalSetorSebelum - $totalTarikSebelum);

        // 2. Mutasi Bulan Ini
        $totalSetoran = (float) Tabungan::whereBetween('tanggal', [$startDate, $endDate])->where('jenis_transaksi', 'setor')->sum('nominal');
        $totalPenarikan = (float) Tabungan::whereBetween('tanggal', [$startDate, $endDate])->where('jenis_transaksi', 'tarik')->sum('nominal');
        $saldoAkhir = $saldoAwal + $totalSetoran - $totalPenarikan;

        // 3. Posisi Fisik Dana (Akuntabilitas Kas)
        $totalSetorSudah = (float) Tabungan::where('jenis_transaksi', 'setor')->where('status_setoran', 'sudah')->sum('nominal');
        $totalTarikSemua = (float) Tabungan::where('jenis_transaksi', 'tarik')->sum('nominal');
        $kasDiBendahara = max(0, $totalSetorSudah - $totalTarikSemua);
        $danaMengendap = (float) Tabungan::where('jenis_transaksi', 'setor')->where('status_setoran', 'belum')->sum('nominal');

        // 4. Rekap Per Kelas
        $kelases = Kelas::with(['guru'])->orderBy('tingkat')->get();
        $laporanKelas = [];
        foreach ($kelases as $k) {
            $siswaIds = Siswa::where('id_kelas', $k->id_kelas)->pluck('id_siswa');
            $santriCount = Siswa::where('id_kelas', $k->id_kelas)->where('saldo_tabungan', '>', 0)->count();
            if ($santriCount === 0) {
                $santriCount = count($siswaIds);
            }

            $setorBulanIni = (float) Tabungan::whereIn('id_siswa', $siswaIds)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->where('jenis_transaksi', 'setor')
                ->sum('nominal');

            $tarikBulanIni = (float) Tabungan::whereIn('id_siswa', $siswaIds)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->where('jenis_transaksi', 'tarik')
                ->sum('nominal');

            $setorAll = (float) Tabungan::whereIn('id_siswa', $siswaIds)->where('tanggal', '<=', $endDate)->where('jenis_transaksi', 'setor')->sum('nominal');
            $tarikAll = (float) Tabungan::whereIn('id_siswa', $siswaIds)->where('tanggal', '<=', $endDate)->where('jenis_transaksi', 'tarik')->sum('nominal');
            $saldoKelas = $setorAll - $tarikAll;

            $danaMengendapKelas = (float) Tabungan::whereIn('id_siswa', $siswaIds)
                ->where('jenis_transaksi', 'setor')
                ->where('status_setoran', 'belum')
                ->sum('nominal');

            $statusSetoran = $danaMengendapKelas > 0 
                ? 'Mengendap Rp ' . number_format($danaMengendapKelas, 0, ',', '.') 
                : 'Lunas Disetor';

            $laporanKelas[] = [
                'nama_kelas' => $k->tingkat . ' ' . $k->index_kelas,
                'nama_guru' => $k->guru->nama_guru ?? '-',
                'santri_count' => $santriCount,
                'setor_bulan_ini' => $setorBulanIni,
                'tarik_bulan_ini' => $tarikBulanIni,
                'saldo_akhir' => $saldoKelas,
                'status_setoran' => $statusSetoran,
                'dana_mengendap' => $danaMengendapKelas,
            ];
        }

        $generalSettings = DB::table('general_settings')->first() ?? (object) [
            'school_name' => 'TPQ Darul Huda',
            'school_year' => '2026/2027',
        ];

        // Nama penandatangan:
        // Ambil Kepala Sekolah / Kepala TPQ dari Data Petugas dengan Role Kepala Sekolah (is_superadmin = 2)
        $userKepala = User::with('guru')->where('is_superadmin', 2)->first();
        if (!$userKepala) {
            // Fallback jika belum diset role Kepala Sekolah, cari akun dengan username 'kepala' atau superadmin (1)
            $userKepala = User::with('guru')->where('name', 'like', '%kepala%')->first()
                ?? User::with('guru')->where('is_superadmin', 1)->first();
        }

        $namaKepala = $userKepala?->guru?->nama_guru ?? $userKepala?->name ?? 'Kepala TPQ';
        $niupKepala = $userKepala?->guru?->niup ?? null;

        // Bendahara TPQ: ambil dari user yang sedang login
        $userBendahara = auth()->user();
        if ($userBendahara && !$userBendahara->relationLoaded('guru')) {
            $userBendahara->load('guru');
        }
        $namaBendahara = $userBendahara?->guru?->nama_guru ?? $userBendahara?->name ?? 'Bendahara TPQ';
        $niupBendahara = $userBendahara?->guru?->niup ?? null;

        $tanggalCetak = Carbon::now()->translatedFormat('d F Y');

        $data = [
            'bulan' => $namaBulan,
            'tahun' => $tahun,
            'periodeLabel' => $periodeLabel,
            'saldoAwal' => $saldoAwal,
            'totalSetoran' => $totalSetoran,
            'totalPenarikan' => $totalPenarikan,
            'saldoAkhir' => $saldoAkhir,
            'kasDiBendahara' => $kasDiBendahara,
            'danaMengendap' => $danaMengendap,
            'laporanKelas' => $laporanKelas,
            'generalSettings' => $generalSettings,
            'namaKepala' => $namaKepala,
            'niupKepala' => $niupKepala,
            'namaBendahara' => $namaBendahara,
            'niupBendahara' => $niupBendahara,
            'tanggalCetak' => $tanggalCetak,
        ];

        if ($type === 'xls' || $type === 'xlsx') {
            return (new LaporanTabunganExportService())->exportTabungan($data);
        }

        return view('admin.report.laporan-tabungan-pdf', $data);
    }
}
