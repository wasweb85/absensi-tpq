<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use ZipArchive;

class QrController extends Controller
{
    public function downloadSiswa(Request $request)
    {
        $idKelas = $request->query('kelas');
        $kelas = Kelas::find($idKelas);
        
        if (!$kelas) {
            return back()->with('msg', 'Kelas tidak ditemukan')->with('error', true);
        }

        $siswa = Siswa::where('id_kelas', $idKelas)->get();

        if ($siswa->isEmpty()) {
            return back()->with('msg', 'Data siswa kosong')->with('error', true);
        }

        $zip = new ZipArchive();
        $zipFileName = 'QR_Siswa_' . str_replace(' ', '_', $kelas->tingkat . '_' . $kelas->index_kelas) . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0777, true);
        }

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($siswa as $s) {
                // Generate QR
                $result = (new Builder(
                    writer: new PngWriter(),
                    writerOptions: [],
                    data: (string) ($s->unique_code ?: $s->rfid_code ?: $s->nis ?: 'Siswa-'.$s->id_siswa),
                    encoding: new Encoding('UTF-8'),
                    errorCorrectionLevel: ErrorCorrectionLevel::High,
                    size: 300,
                    margin: 10,
                    roundBlockSizeMode: RoundBlockSizeMode::Margin,
                    labelText: $s->nama_siswa,
                    labelFont: new OpenSans(12),
                    labelAlignment: LabelAlignment::Center
                ))->build();

                $zip->addFromString($s->nama_siswa . '.png', $result->getString());
            }
            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function downloadGuru(Request $request)
    {
        $guru = Guru::all();

        if ($guru->isEmpty()) {
            return back()->with('msg', 'Data guru kosong')->with('error', true);
        }

        $zip = new ZipArchive();
        $zipFileName = 'QR_Semua_Guru.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0777, true);
        }

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($guru as $g) {
                // Generate QR
                $result = (new Builder(
                    writer: new PngWriter(),
                    writerOptions: [],
                    data: (string) ($g->unique_code ?: $g->rfid_code ?: $g->niup ?: 'Guru-'.$g->id_guru),
                    encoding: new Encoding('UTF-8'),
                    errorCorrectionLevel: ErrorCorrectionLevel::High,
                    size: 300,
                    margin: 10,
                    roundBlockSizeMode: RoundBlockSizeMode::Margin,
                    labelText: $g->nama_guru,
                    labelFont: new OpenSans(12),
                    labelAlignment: LabelAlignment::Center
                ))->build();

                $zip->addFromString($g->nama_guru . '.png', $result->getString());
            }
            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
