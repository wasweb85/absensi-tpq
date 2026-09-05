<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use ZipArchive;

class QrController extends Controller
{
    public function downloadSiswa(Request $request)
    {
        $idKelas = $request->query('kelas');
        
        if ($idKelas && $idKelas !== 'all') {
            $kelas = Kelas::find($idKelas);
            if (!$kelas) {
                return back()->with('msg', 'Kelas tidak ditemukan')->with('error', true);
            }
            $siswa = Siswa::where('id_kelas', $idKelas)->orderBy('nama_siswa')->get();
            $zipFileName = 'Kartu_Santri_' . str_replace(' ', '_', $kelas->tingkat . '_' . $kelas->index_kelas) . '.zip';
        } else {
            $siswa = Siswa::orderBy('nama_siswa')->get();
            $zipFileName = 'Kartu_Semua_Santri.zip';
        }

        if ($siswa->isEmpty()) {
            return back()->with('msg', 'Data santri kosong')->with('error', true);
        }

        $zip = new ZipArchive();
        $zipPath = storage_path('app/temp/' . $zipFileName);

        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0777, true);
        }

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($siswa as $s) {
                $qrCodeData = (string) ($s->unique_code ?: $s->rfid_code ?: $s->nis ?: 'Siswa-'.$s->id_siswa);
                $cardPngData = $this->generateCardPng($s->nama_siswa, $s->nis, $qrCodeData, false);
                $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $s->nama_siswa);
                $zip->addFromString('Kartu_' . $cleanName . '_' . $s->nis . '.png', $cardPngData);
            }
            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function viewSingleSiswa($id)
    {
        $siswa = Siswa::findOrFail($id);
        $qrCodeData = (string) ($siswa->unique_code ?: $siswa->rfid_code ?: $siswa->nis ?: 'Siswa-'.$siswa->id_siswa);
        $cardPngData = $this->generateCardPng($siswa->nama_siswa, $siswa->nis, $qrCodeData, false);
        
        return response($cardPngData)->header('Content-Type', 'image/png');
    }

    public function downloadSingleSiswa($id)
    {
        $siswa = Siswa::findOrFail($id);
        $qrCodeData = (string) ($siswa->unique_code ?: $siswa->rfid_code ?: $siswa->nis ?: 'Siswa-'.$siswa->id_siswa);
        $cardPngData = $this->generateCardPng($siswa->nama_siswa, $siswa->nis, $qrCodeData, false);
        $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $siswa->nama_siswa);
        
        return response()->streamDownload(function () use ($cardPngData) {
            echo $cardPngData;
        }, 'Kartu_' . $cleanName . '_' . $siswa->nis . '.png');
    }

    public function downloadGuru(Request $request)
    {
        $guru = Guru::orderBy('nama_guru')->get();

        if ($guru->isEmpty()) {
            return back()->with('msg', 'Data guru kosong')->with('error', true);
        }

        $zip = new ZipArchive();
        $zipFileName = 'Kartu_Semua_Guru.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0777, true);
        }

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($guru as $g) {
                $qrCodeData = (string) ($g->unique_code ?: $g->rfid_code ?: $g->niup ?: 'Guru-'.$g->id_guru);
                $cardPngData = $this->generateCardPng($g->nama_guru, $g->niup, $qrCodeData, true);
                $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $g->nama_guru);
                $zip->addFromString('Kartu_Guru_' . $cleanName . '_' . $g->niup . '.png', $cardPngData);
            }
            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    private function generateCardPng($nama, $nis, $qrCodeData, $isGuru = false)
    {
        // High-res 720x1060 (2x scale of 360x530)
        $w = 720;
        $h = 1060;
        $img = imagecreatetruecolor($w, $h);

        // Enable anti-aliasing
        imagealphablending($img, true);

        // Colors
        $white = imagecolorallocate($img, 255, 255, 255);
        $slateDark = imagecolorallocate($img, 15, 23, 42); // #0f172a
        $lightBg = imagecolorallocate($img, 248, 250, 252); // #f8fafc

        $settings = \App\Models\GeneralSetting::first();
        $schoolName = strtoupper($settings->school_name ?? 'TPQ ABSENSI');

        if ($isGuru) {
            $primary = imagecolorallocate($img, 22, 163, 74); // #16a34a (emerald)
            $primaryLight = imagecolorallocate($img, 187, 247, 208); // #bbf7d0
            $primaryText = imagecolorallocate($img, 22, 163, 74);
            $headerTitle = "KARTU ABSENSI PENGAJAR";
            $subTitle = $schoolName;
        } else {
            $primary = imagecolorallocate($img, 2, 132, 199); // #0284c7 (sky blue)
            $primaryLight = imagecolorallocate($img, 186, 230, 253); // #bae6fd
            $primaryText = imagecolorallocate($img, 2, 132, 199);
            $headerTitle = "KARTU ABSENSI SANTRI";
            $subTitle = $schoolName;
        }

        // Fill white background
        imagefill($img, 0, 0, $white);

        // Background Curves matching CSS clip-path: ellipse(120% 100% at 50% 0%)
        // Light curve behind
        imagefilledellipse($img, 360, 220, 1000, 450, $primaryLight);

        // Main header curve
        imagefilledellipse($img, 360, 200, 1000, 420, $primary);
        imagefilledrectangle($img, 0, 0, 720, 200, $primary);

        // School Logo Badge (White Circle)
        $logoPath = null;
        if (!empty($settings->logo) && file_exists(public_path('uploads/logo/' . $settings->logo))) {
            $logoPath = public_path('uploads/logo/' . $settings->logo);
        } elseif (file_exists(public_path('assets/img/logo-sekolah.jpg'))) {
            $logoPath = public_path('assets/img/logo-sekolah.jpg');
        }

        if ($logoPath && file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoSrc = @imagecreatefromstring($logoData);
            if ($logoSrc) {
                $logoW = imagesx($logoSrc);
                $logoH = imagesy($logoSrc);

                // Circle background
                imagefilledellipse($img, 360, 85, 106, 106, $white);

                // Copy Logo
                imagecopyresampled($img, $logoSrc, 315, 40, 0, 0, 90, 90, $logoW, $logoH);
                imagedestroy($logoSrc);
            }
        }

        // Font files
        $fontBold = 'C:/Windows/Fonts/arialbd.ttf';
        $fontRegular = 'C:/Windows/Fonts/arial.ttf';

        // Header Title
        if (file_exists($fontBold)) {
            $bbox = imagettfbbox(18, 0, $fontBold, $headerTitle);
            $textW = $bbox[2] - $bbox[0];
            imagettftext($img, 18, 0, (int)((720 - $textW) / 2), 175, $white, $fontBold, $headerTitle);

            // Subtitle
            $bbox = imagettfbbox(13, 0, $fontBold, $subTitle);
            $textW = $bbox[2] - $bbox[0];
            imagettftext($img, 13, 0, (int)((720 - $textW) / 2), 212, $primaryLight, $fontBold, $subTitle);
        } else {
            imagestring($img, 5, 230, 155, $headerTitle, $white);
            imagestring($img, 4, 250, 190, $subTitle, $primaryLight);
        }

        // Center QR Card Container Box
        imagefilledrectangle($img, 140, 290, 580, 730, $lightBg);
        imagerectangle($img, 140, 290, 580, 730, $primaryLight);

        // Generate QR Code PNG
        $qrResult = (new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            data: $qrCodeData,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 400,
            margin: 5,
            roundBlockSizeMode: RoundBlockSizeMode::Margin
        ))->build();

        $qrImg = imagecreatefromstring($qrResult->getString());
        if ($qrImg) {
            imagecopyresampled($img, $qrImg, 160, 310, 0, 0, 400, 400, imagesx($qrImg), imagesy($qrImg));
            imagedestroy($qrImg);
        }

        // Name
        if (file_exists($fontBold)) {
            $bbox = imagettfbbox(24, 0, $fontBold, $nama);
            $textW = $bbox[2] - $bbox[0];
            imagettftext($img, 24, 0, (int)((720 - $textW) / 2), 840, $slateDark, $fontBold, $nama);

            // NIS / NIUP
            $nisLabel = ($isGuru ? "NIUP : " : "NIS : ") . $nis;
            $bbox = imagettfbbox(18, 0, $fontBold, $nisLabel);
            $textW = $bbox[2] - $bbox[0];
            imagettftext($img, 18, 0, (int)((720 - $textW) / 2), 900, $primaryText, $fontBold, $nisLabel);
        } else {
            imagestring($img, 5, 260, 830, $nama, $slateDark);
            imagestring($img, 4, 270, 880, ($isGuru ? "NIUP: " : "NIS: ") . $nis, $primaryText);
        }

        // Bottom Thick Accent Bar
        imagefilledrectangle($img, 0, 1028, 720, 1060, $primary);

        ob_start();
        imagepng($img);
        $pngData = ob_get_clean();
        imagedestroy($img);

        return $pngData;
    }
}
