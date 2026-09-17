<?php

namespace App\Services;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanPresensiExportService
{
    /**
     * Export Laporan Presensi Siswa ke format Excel (.xlsx) dengan tata letak profesional & A4 Landscape
     */
    public function exportSiswa(array $data): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Presensi Siswa');

        $tanggal = $data['tanggal'] ?? [];
        $bulan = $data['bulan'] ?? '';
        $listAbsen = $data['listAbsen'] ?? [];
        $listSiswa = $data['listSiswa'] ?? [];
        $rekapSiswa = $data['rekapSiswa'] ?? [];
        $kelas = $data['kelas'] ?? null;
        $generalSettings = $data['generalSettings'] ?? null;

        $schoolName = $generalSettings->school_name ?? 'TPQ Darul Huda';
        $schoolYear = $generalSettings->school_year ?? '2026/2027';
        $namaKelas = $kelas ? ($kelas->tingkat . ' ' . $kelas->index_kelas) : 'Semua Kelas';

        $totalTanggal = count($tanggal);
        // Kolom A = No (1), B = Nama (2)
        // Kolom C s/d (2 + totalTanggal) = Tanggal
        // 4 Kolom berikutnya = H, S, I, A
        $totalCols = 2 + $totalTanggal + 4;
        $lastColLetter = Coordinate::stringFromColumnIndex($totalCols);
        $dateStartCol = 3; // C
        $dateEndCol = 2 + $totalTanggal;
        $dateEndColLetter = Coordinate::stringFromColumnIndex($dateEndCol);

        $colH = Coordinate::stringFromColumnIndex($dateEndCol + 1);
        $colS = Coordinate::stringFromColumnIndex($dateEndCol + 2);
        $colI = Coordinate::stringFromColumnIndex($dateEndCol + 3);
        $colA = Coordinate::stringFromColumnIndex($dateEndCol + 4);

        // --- 1. SETUP HALAMAN A4 LANDSCAPE & MARGIN ---
        $pageSetup = $sheet->getPageSetup();
        $pageSetup->setPaperSize(PageSetup::PAPERSIZE_A4);
        $pageSetup->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $pageSetup->setFitToPage(true);
        $pageSetup->setFitToWidth(1);
        $pageSetup->setFitToHeight(0); // Biarkan paginate vertikal jika baris siswa banyak

        $sheet->getPageMargins()->setTop(0.5);
        $sheet->getPageMargins()->setBottom(0.5);
        $sheet->getPageMargins()->setLeft(0.4);
        $sheet->getPageMargins()->setRight(0.4);

        $sheet->setShowGridLines(true);
        $sheet->setPrintGridLines(true);

        // --- 2. LOGO TPQ DI SEBELAH KIRI JUDUL (AUTO-SCALED) ---
        $logoPath = null;
        if (!empty($generalSettings->logo) && file_exists(public_path('uploads/logo/' . $generalSettings->logo))) {
            $logoPath = public_path('uploads/logo/' . $generalSettings->logo);
        } elseif (file_exists(public_path('assets/img/logo-sekolah.jpg'))) {
            $logoPath = public_path('assets/img/logo-sekolah.jpg');
        }

        if ($logoPath && file_exists($logoPath)) {
            $drawing = new Drawing();
            $drawing->setName('Logo TPQ');
            $drawing->setDescription('Logo Lembaga TPQ');
            $drawing->setPath($logoPath);
            $drawing->setCoordinates('B2');
            $drawing->setHeight(65); // Tinggi 65px, lebar menyesuaikan proporsional
            $drawing->setOffsetX(8);
            $drawing->setOffsetY(4);
            $drawing->setWorksheet($sheet);
        }

        // --- 3. JUDUL LAPORAN (CENTERED TERHADAP LEBAR TABEL) ---
        $sheet->getRowDimension(1)->setRowHeight(8);

        // Baris 2: Judul Utama
        $sheet->mergeCells("A2:{$lastColLetter}2");
        $sheet->setCellValue('A2', 'DAFTAR HADIR SANTRI / SISWA');
        $sheet->getStyle('A2')->getFont()->setName('Arial')->setBold(true)->setSize(14)->setColor(new Color('0F172A'));
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(2)->setRowHeight(24);

        // Baris 3: Nama TPQ / Lembaga
        $sheet->mergeCells("A3:{$lastColLetter}3");
        $sheet->setCellValue('A3', strtoupper($schoolName));
        $sheet->getStyle('A3')->getFont()->setName('Arial')->setBold(true)->setSize(11.5)->setColor(new Color('1E3A8A'));
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(3)->setRowHeight(20);

        // Baris 4: Tahun Pelajaran
        $sheet->mergeCells("A4:{$lastColLetter}4");
        $sheet->setCellValue('A4', 'TAHUN PELAJARAN ' . strtoupper($schoolYear));
        $sheet->getStyle('A4')->getFont()->setName('Arial')->setBold(true)->setSize(10)->setColor(new Color('475569'));
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(4)->setRowHeight(18);

        $sheet->getRowDimension(5)->setRowHeight(10);

        // --- 4. BARIS INFORMASI PERIODE & KELAS (BARIS 6) ---
        $sheet->setCellValue('A6', '');
        $sheet->setCellValue('B6', 'Bulan / Periode : ' . $bulan);
        $sheet->getStyle('B6')->getFont()->setName('Arial')->setBold(true)->setSize(10)->setColor(new Color('1E293B'));
        $sheet->getStyle('B6')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->setCellValue($colH . '6', 'Kelas : ' . $namaKelas);
        $sheet->getStyle($colH . '6')->getFont()->setName('Arial')->setBold(true)->setSize(10)->setColor(new Color('1E293B'));
        $sheet->getStyle($colH . '6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->mergeCells("{$colH}6:{$lastColLetter}6");
        $sheet->getRowDimension(6)->setRowHeight(22);

        // --- 5. HEADER TABEL (BARIS 7, 8, 9) ---
        // A7:A9 -> NO
        $sheet->mergeCells('A7:A9');
        $sheet->setCellValue('A7', 'NO');
        $sheet->getStyle('A7')->getFont()->setName('Arial')->setBold(true)->setSize(9.5)->setColor(new Color('0F172A'));
        $sheet->getStyle('A7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

        // B7:B9 -> NAMA SISWA
        $sheet->mergeCells('B7:B9');
        $sheet->setCellValue('B7', 'NAMA SANTRI');
        $sheet->getStyle('B7')->getFont()->setName('Arial')->setBold(true)->setSize(9.5)->setColor(new Color('0F172A'));
        $sheet->getStyle('B7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

        // C7:{dateEnd}7 -> HARI / TANGGAL
        $sheet->mergeCells("C7:{$dateEndColLetter}7");
        $sheet->setCellValue('C7', 'HARI / TANGGAL');
        $sheet->getStyle('C7')->getFont()->setName('Arial')->setBold(true)->setSize(9.5)->setColor(new Color('0F172A'));
        $sheet->getStyle('C7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

        // {colH}7:{lastColLetter}7 -> TOTAL KEHADIRAN
        $sheet->mergeCells("{$colH}7:{$lastColLetter}7");
        $sheet->setCellValue($colH . '7', 'REKAP');
        $sheet->getStyle($colH . '7')->getFont()->setName('Arial')->setBold(true)->setSize(9.5)->setColor(new Color('0F172A'));
        $sheet->getStyle($colH . '7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

        // Baris 8 & 9 untuk Kolom Tanggal
        $sheet->getRowDimension(7)->setRowHeight(19);
        $sheet->getRowDimension(8)->setRowHeight(19);
        $sheet->getRowDimension(9)->setRowHeight(20);

        // Subheader Rekap (H, S, I, A) merge baris 8 dan 9
        $sheet->mergeCells("{$colH}8:{$colH}9");
        $sheet->setCellValue($colH . '8', 'H');
        $sheet->getStyle($colH . '8')->getFont()->setName('Arial')->setBold(true)->setSize(9)->setColor(new Color('15803D'));
        $sheet->getStyle($colH . '8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("{$colH}8:{$colH}9")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFDCFCE7');

        $sheet->mergeCells("{$colS}8:{$colS}9");
        $sheet->setCellValue($colS . '8', 'S');
        $sheet->getStyle($colS . '8')->getFont()->setName('Arial')->setBold(true)->setSize(9)->setColor(new Color('854D0E'));
        $sheet->getStyle($colS . '8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("{$colS}8:{$colS}9")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEF9C3');

        $sheet->mergeCells("{$colI}8:{$colI}9");
        $sheet->setCellValue($colI . '8', 'I');
        $sheet->getStyle($colI . '8')->getFont()->setName('Arial')->setBold(true)->setSize(9)->setColor(new Color('854D0E'));
        $sheet->getStyle($colI . '8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("{$colI}8:{$colI}9")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEF9C3');

        $sheet->mergeCells("{$colA}8:{$colA}9");
        $sheet->setCellValue($colA . '8', 'A');
        $sheet->getStyle($colA . '8')->getFont()->setName('Arial')->setBold(true)->setSize(9)->setColor(new Color('991B1B'));
        $sheet->getStyle($colA . '8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("{$colA}8:{$colA}9")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEE2E2');

        // Render Kolom Tanggal (Nama Hari di Row 8, Nomor Tanggal di Row 9)
        foreach ($tanggal as $idx => $tglItem) {
            $colIndex = 3 + $idx;
            $colLetter = Coordinate::stringFromColumnIndex($colIndex);
            $cDate = $tglItem->date ?? $tglItem;
            $isLibur = !empty($tglItem->is_libur);

            // Nama Hari (Row 8)
            $dayNameShort = substr($cDate->translatedFormat('D'), 0, 3);
            $sheet->setCellValue($colLetter . '8', $dayNameShort);
            $sheet->getStyle($colLetter . '8')->getFont()->setName('Arial')->setBold(true)->setSize(7.5);
            $sheet->getStyle($colLetter . '8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

            // Nomor Tanggal (Row 9)
            $sheet->setCellValue($colLetter . '9', $cDate->format('d'));
            $sheet->getStyle($colLetter . '9')->getFont()->setName('Arial')->setBold(true)->setSize(8.5);
            $sheet->getStyle($colLetter . '9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

            // Styling jika hari libur
            if ($isLibur) {
                $sheet->getStyle($colLetter . '8:' . $colLetter . '9')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEE2E2');
                $sheet->getStyle($colLetter . '8:' . $colLetter . '9')->getFont()->setColor(new Color('B91C1C'));
            } else {
                $sheet->getStyle($colLetter . '8:' . $colLetter . '9')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF8FAFC');
                $sheet->getStyle($colLetter . '8:' . $colLetter . '9')->getFont()->setColor(new Color('334155'));
            }
        }

        // Background default untuk Header Row 7
        $sheet->getStyle("A7:{$lastColLetter}7")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE2E8F0');

        // --- 6. ISI DATA SANTRI (BARIS 10 ONWARDS) ---
        $currentRow = 10;
        foreach ($listSiswa as $i => $siswa) {
            $sheet->getRowDimension($currentRow)->setRowHeight(20);

            // A: No
            $sheet->setCellValue('A' . $currentRow, $i + 1);
            $sheet->getStyle('A' . $currentRow)->getFont()->setName('Arial')->setSize(9);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

            // B: Nama Siswa
            $sheet->setCellValue('B' . $currentRow, ' ' . $siswa->nama_siswa);
            $sheet->getStyle('B' . $currentRow)->getFont()->setName('Arial')->setSize(9);
            $sheet->getStyle('B' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER);

            // Perhitungan Kehadiran Siswa
            $jumlahHadir = 0;
            $jumlahSakit = 0;
            $jumlahIzin = 0;
            $jumlahAlpa = 0;

            foreach ($listAbsen as $tglIdx => $absenTgl) {
                $colIndex = 3 + $tglIdx;
                $colLetter = Coordinate::stringFromColumnIndex($colIndex);

                $cellCoord = $colLetter . $currentRow;
                $sheet->getStyle($cellCoord)->getFont()->setName('Arial')->setSize(8.5)->setBold(true);
                $sheet->getStyle($cellCoord)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

                if (!empty($absenTgl['status_libur'])) {
                    $sheet->setCellValue($cellCoord, 'L');
                    $sheet->getStyle($cellCoord)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFE4E6');
                    $sheet->getStyle($cellCoord)->getFont()->setColor(new Color('BE123C'));
                } elseif (!empty($absenTgl['lewat'])) {
                    $sheet->setCellValue($cellCoord, '');
                    $sheet->getStyle($cellCoord)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFBFDFF');
                } else {
                    $kehadiran = $absenTgl[$i]['id_kehadiran'] ?? null;
                    if ($kehadiran == 1) {
                        $jumlahHadir++;
                        $sheet->setCellValue($cellCoord, 'H');
                        $sheet->getStyle($cellCoord)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFDCFCE7');
                        $sheet->getStyle($cellCoord)->getFont()->setColor(new Color('15803D'));
                    } elseif ($kehadiran == 2) {
                        $jumlahSakit++;
                        $sheet->setCellValue($cellCoord, 'S');
                        $sheet->getStyle($cellCoord)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEF9C3');
                        $sheet->getStyle($cellCoord)->getFont()->setColor(new Color('A16207'));
                    } elseif ($kehadiran == 3) {
                        $jumlahIzin++;
                        $sheet->setCellValue($cellCoord, 'I');
                        $sheet->getStyle($cellCoord)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEF9C3');
                        $sheet->getStyle($cellCoord)->getFont()->setColor(new Color('A16207'));
                    } else {
                        // Alpa (id_kehadiran == 4 atau tidak absen sebelum hari ini)
                        $jumlahAlpa++;
                        $sheet->setCellValue($cellCoord, 'A');
                        $sheet->getStyle($cellCoord)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEE2E2');
                        $sheet->getStyle($cellCoord)->getFont()->setColor(new Color('B91C1C'));
                    }
                }
            }

            // Rekap Kolom H, S, I, A per siswa
            $sheet->setCellValue($colH . $currentRow, $jumlahHadir ?: '-');
            $sheet->getStyle($colH . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle($colH . $currentRow)->getFont()->setName('Arial')->setSize(9)->setBold(true);

            $sheet->setCellValue($colS . $currentRow, $jumlahSakit ?: '-');
            $sheet->getStyle($colS . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle($colS . $currentRow)->getFont()->setName('Arial')->setSize(9);

            $sheet->setCellValue($colI . $currentRow, $jumlahIzin ?: '-');
            $sheet->getStyle($colI . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle($colI . $currentRow)->getFont()->setName('Arial')->setSize(9);

            $sheet->setCellValue($colA . $currentRow, $jumlahAlpa ?: '-');
            $sheet->getStyle($colA . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle($colA . $currentRow)->getFont()->setName('Arial')->setSize(9)->setBold($jumlahAlpa > 0);
            if ($jumlahAlpa > 0) {
                $sheet->getStyle($colA . $currentRow)->getFont()->setColor(new Color('B91C1C'));
            }

            // Alternating Row Background
            if ($i % 2 === 1) {
                $sheet->getStyle("A{$currentRow}:B{$currentRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF8FAFC');
                $sheet->getStyle("{$colH}{$currentRow}:{$lastColLetter}{$currentRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF8FAFC');
            }

            $currentRow++;
        }

        $lastDataRow = $currentRow - 1;

        // --- 7. BORDER TABEL LENGKAP ---
        $tableRange = "A7:{$lastColLetter}{$lastDataRow}";
        $sheet->getStyle($tableRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('CBD5E1'));
        $sheet->getStyle($tableRange)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('475569'));
        $sheet->getStyle("A7:{$lastColLetter}9")->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('475569'));

        // --- 8. ATUR LEBAR KOLOM RAPI & PROPORSIONAL ---
        $sheet->getColumnDimension('A')->setWidth(5.5); // No
        $sheet->getColumnDimension('B')->setWidth(26);  // Nama Santri

        for ($c = 3; $c <= $dateEndCol; $c++) {
            $colLetter = Coordinate::stringFromColumnIndex($c);
            $sheet->getColumnDimension($colLetter)->setWidth(4.2); // Kolom tanggal
        }

        $sheet->getColumnDimension($colH)->setWidth(5.5);
        $sheet->getColumnDimension($colS)->setWidth(5.5);
        $sheet->getColumnDimension($colI)->setWidth(5.5);
        $sheet->getColumnDimension($colA)->setWidth(5.5);

        // --- 9. REKAPITULASI & KETERANGAN (DI HAPUS AGENDA LIBUR SESUAI REQUEST) ---
        $rekapStartRow = $lastDataRow + 2;

        // Blok Rekap Santri
        $sheet->setCellValue("B{$rekapStartRow}", 'REKAPITULASI SANTRI:');
        $sheet->getStyle("B{$rekapStartRow}")->getFont()->setName('Arial')->setBold(true)->setSize(9.5)->setColor(new Color('0F172A'));

        $sheet->setCellValue('B' . ($rekapStartRow + 1), 'Total Santri');
        $sheet->setCellValue('C' . ($rekapStartRow + 1), ': ' . count($listSiswa) . ' santri');
        $sheet->getStyle('B' . ($rekapStartRow + 1) . ':C' . ($rekapStartRow + 1))->getFont()->setName('Arial')->setSize(9);

        $sheet->setCellValue('B' . ($rekapStartRow + 2), 'Laki-laki');
        $sheet->setCellValue('C' . ($rekapStartRow + 2), ': ' . ($rekapSiswa['laki'] ?? 0) . ' santri');
        $sheet->getStyle('B' . ($rekapStartRow + 2) . ':C' . ($rekapStartRow + 2))->getFont()->setName('Arial')->setSize(9);

        $sheet->setCellValue('B' . ($rekapStartRow + 3), 'Perempuan');
        $sheet->setCellValue('C' . ($rekapStartRow + 3), ': ' . ($rekapSiswa['perempuan'] ?? 0) . ' santri');
        $sheet->getStyle('B' . ($rekapStartRow + 3) . ':C' . ($rekapStartRow + 3))->getFont()->setName('Arial')->setSize(9);

        // Blok Keterangan Kode Kehadiran
        $ketColLabel = Coordinate::stringFromColumnIndex(min($dateStartCol + 3, $dateEndCol));
        $ketColDesc = Coordinate::stringFromColumnIndex(min($dateStartCol + 4, $dateEndCol));

        $sheet->setCellValue("{$ketColLabel}{$rekapStartRow}", 'KETERANGAN KODE:');
        $sheet->getStyle("{$ketColLabel}{$rekapStartRow}")->getFont()->setName('Arial')->setBold(true)->setSize(9.5)->setColor(new Color('0F172A'));

        $keteranganList = [
            ['H', ': Hadir', 'FFDCFCE7', '15803D'],
            ['S', ': Sakit', 'FFFEF9C3', '854D0E'],
            ['I', ': Izin', 'FFFEF9C3', '854D0E'],
            ['A', ': Alpa (Tanpa Keterangan)', 'FFFEE2E2', '991B1B'],
            ['L', ': Libur (Kalender TPQ & Libur Pekan)', 'FFFFE4E6', 'BE123C'],
        ];

        foreach ($keteranganList as $kIdx => $kItem) {
            $r = $rekapStartRow + 1 + $kIdx;
            $sheet->setCellValue($ketColLabel . $r, $kItem[0]);
            $sheet->getStyle($ketColLabel . $r)->getFont()->setName('Arial')->setBold(true)->setSize(8.5)->setColor(new Color($kItem[3]));
            $sheet->getStyle($ketColLabel . $r)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle($ketColLabel . $r)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($kItem[2]);
            $sheet->getStyle($ketColLabel . $r)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('CBD5E1'));

            $sheet->setCellValue($ketColDesc . $r, $kItem[1]);
            $sheet->getStyle($ketColDesc . $r)->getFont()->setName('Arial')->setSize(9)->setColor(new Color('334155'));
        }

        // Row repeat at top for print (rows 7 to 9)
        $pageSetup->setRowsToRepeatAtTopByStartAndEnd(7, 9);

        // --- 10. GENERATE DAN STREAM RESPONSE EXCEL ---
        $filename = 'Laporan_Presensi_Siswa_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $namaKelas) . '_' . date('Ymd_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Export Laporan Presensi Guru ke format Excel (.xlsx) dengan tata letak profesional & A4 Landscape
     */
    public function exportGuru(array $data): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Presensi Asatidz');

        $tanggal = $data['tanggal'] ?? [];
        $bulan = $data['bulan'] ?? '';
        $listAbsen = $data['listAbsen'] ?? [];
        $listGuru = $data['listGuru'] ?? [];
        $jumlahGuru = $data['jumlahGuru'] ?? [];
        $generalSettings = $data['generalSettings'] ?? null;

        $schoolName = $generalSettings->school_name ?? 'TPQ Darul Huda';
        $schoolYear = $generalSettings->school_year ?? '2026/2027';

        $totalTanggal = count($tanggal);
        $totalCols = 2 + $totalTanggal + 4;
        $lastColLetter = Coordinate::stringFromColumnIndex($totalCols);
        $dateStartCol = 3;
        $dateEndCol = 2 + $totalTanggal;
        $dateEndColLetter = Coordinate::stringFromColumnIndex($dateEndCol);

        $colH = Coordinate::stringFromColumnIndex($dateEndCol + 1);
        $colS = Coordinate::stringFromColumnIndex($dateEndCol + 2);
        $colI = Coordinate::stringFromColumnIndex($dateEndCol + 3);
        $colA = Coordinate::stringFromColumnIndex($dateEndCol + 4);

        // --- Page Setup A4 Landscape ---
        $pageSetup = $sheet->getPageSetup();
        $pageSetup->setPaperSize(PageSetup::PAPERSIZE_A4);
        $pageSetup->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $pageSetup->setFitToPage(true);
        $pageSetup->setFitToWidth(1);
        $pageSetup->setFitToHeight(0);

        $sheet->getPageMargins()->setTop(0.5);
        $sheet->getPageMargins()->setBottom(0.5);
        $sheet->getPageMargins()->setLeft(0.4);
        $sheet->getPageMargins()->setRight(0.4);

        $sheet->setShowGridLines(true);
        $sheet->setPrintGridLines(true);

        // Logo TPQ
        $logoPath = null;
        if (!empty($generalSettings->logo) && file_exists(public_path('uploads/logo/' . $generalSettings->logo))) {
            $logoPath = public_path('uploads/logo/' . $generalSettings->logo);
        } elseif (file_exists(public_path('assets/img/logo-sekolah.jpg'))) {
            $logoPath = public_path('assets/img/logo-sekolah.jpg');
        }

        if ($logoPath && file_exists($logoPath)) {
            $drawing = new Drawing();
            $drawing->setName('Logo TPQ');
            $drawing->setDescription('Logo Lembaga TPQ');
            $drawing->setPath($logoPath);
            $drawing->setCoordinates('B2');
            $drawing->setHeight(65);
            $drawing->setOffsetX(8);
            $drawing->setOffsetY(4);
            $drawing->setWorksheet($sheet);
        }

        // Header Title
        $sheet->getRowDimension(1)->setRowHeight(8);

        $sheet->mergeCells("A2:{$lastColLetter}2");
        $sheet->setCellValue('A2', 'DAFTAR HADIR USTADZ & USTADZAH');
        $sheet->getStyle('A2')->getFont()->setName('Arial')->setBold(true)->setSize(14)->setColor(new Color('0F172A'));
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(2)->setRowHeight(24);

        $sheet->mergeCells("A3:{$lastColLetter}3");
        $sheet->setCellValue('A3', strtoupper($schoolName));
        $sheet->getStyle('A3')->getFont()->setName('Arial')->setBold(true)->setSize(11.5)->setColor(new Color('1E3A8A'));
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(3)->setRowHeight(20);

        $sheet->mergeCells("A4:{$lastColLetter}4");
        $sheet->setCellValue('A4', 'TAHUN PELAJARAN ' . strtoupper($schoolYear));
        $sheet->getStyle('A4')->getFont()->setName('Arial')->setBold(true)->setSize(10)->setColor(new Color('475569'));
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(4)->setRowHeight(18);

        $sheet->getRowDimension(5)->setRowHeight(10);

        // Info Periode
        $sheet->setCellValue('B6', 'Bulan / Periode : ' . $bulan);
        $sheet->getStyle('B6')->getFont()->setName('Arial')->setBold(true)->setSize(10)->setColor(new Color('1E293B'));
        $sheet->getStyle('B6')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->setCellValue($colH . '6', 'Unit : TPQ');
        $sheet->getStyle($colH . '6')->getFont()->setName('Arial')->setBold(true)->setSize(10)->setColor(new Color('1E293B'));
        $sheet->getStyle($colH . '6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->mergeCells("{$colH}6:{$lastColLetter}6");
        $sheet->getRowDimension(6)->setRowHeight(22);

        // Table Header
        $sheet->mergeCells('A7:A9');
        $sheet->setCellValue('A7', 'NO');
        $sheet->getStyle('A7')->getFont()->setName('Arial')->setBold(true)->setSize(9.5)->setColor(new Color('0F172A'));
        $sheet->getStyle('A7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->mergeCells('B7:B9');
        $sheet->setCellValue('B7', 'NAMA ASATIDZ');
        $sheet->getStyle('B7')->getFont()->setName('Arial')->setBold(true)->setSize(9.5)->setColor(new Color('0F172A'));
        $sheet->getStyle('B7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->mergeCells("C7:{$dateEndColLetter}7");
        $sheet->setCellValue('C7', 'HARI / TANGGAL');
        $sheet->getStyle('C7')->getFont()->setName('Arial')->setBold(true)->setSize(9.5)->setColor(new Color('0F172A'));
        $sheet->getStyle('C7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->mergeCells("{$colH}7:{$lastColLetter}7");
        $sheet->setCellValue($colH . '7', 'REKAP');
        $sheet->getStyle($colH . '7')->getFont()->setName('Arial')->setBold(true)->setSize(9.5)->setColor(new Color('0F172A'));
        $sheet->getStyle($colH . '7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getRowDimension(7)->setRowHeight(19);
        $sheet->getRowDimension(8)->setRowHeight(19);
        $sheet->getRowDimension(9)->setRowHeight(20);

        $sheet->mergeCells("{$colH}8:{$colH}9");
        $sheet->setCellValue($colH . '8', 'H');
        $sheet->getStyle($colH . '8')->getFont()->setName('Arial')->setBold(true)->setSize(9)->setColor(new Color('15803D'));
        $sheet->getStyle($colH . '8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("{$colH}8:{$colH}9")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFDCFCE7');

        $sheet->mergeCells("{$colS}8:{$colS}9");
        $sheet->setCellValue($colS . '8', 'S');
        $sheet->getStyle($colS . '8')->getFont()->setName('Arial')->setBold(true)->setSize(9)->setColor(new Color('854D0E'));
        $sheet->getStyle($colS . '8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("{$colS}8:{$colS}9")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEF9C3');

        $sheet->mergeCells("{$colI}8:{$colI}9");
        $sheet->setCellValue($colI . '8', 'I');
        $sheet->getStyle($colI . '8')->getFont()->setName('Arial')->setBold(true)->setSize(9)->setColor(new Color('854D0E'));
        $sheet->getStyle($colI . '8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("{$colI}8:{$colI}9")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEF9C3');

        $sheet->mergeCells("{$colA}8:{$colA}9");
        $sheet->setCellValue($colA . '8', 'A');
        $sheet->getStyle($colA . '8')->getFont()->setName('Arial')->setBold(true)->setSize(9)->setColor(new Color('991B1B'));
        $sheet->getStyle($colA . '8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("{$colA}8:{$colA}9")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEE2E2');

        foreach ($tanggal as $idx => $tglItem) {
            $colIndex = 3 + $idx;
            $colLetter = Coordinate::stringFromColumnIndex($colIndex);
            $cDate = $tglItem->date ?? $tglItem;
            $isLibur = !empty($tglItem->is_libur);

            $dayNameShort = substr($cDate->translatedFormat('D'), 0, 3);
            $sheet->setCellValue($colLetter . '8', $dayNameShort);
            $sheet->getStyle($colLetter . '8')->getFont()->setName('Arial')->setBold(true)->setSize(7.5);
            $sheet->getStyle($colLetter . '8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

            $sheet->setCellValue($colLetter . '9', $cDate->format('d'));
            $sheet->getStyle($colLetter . '9')->getFont()->setName('Arial')->setBold(true)->setSize(8.5);
            $sheet->getStyle($colLetter . '9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

            if ($isLibur) {
                $sheet->getStyle($colLetter . '8:' . $colLetter . '9')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEE2E2');
                $sheet->getStyle($colLetter . '8:' . $colLetter . '9')->getFont()->setColor(new Color('B91C1C'));
            } else {
                $sheet->getStyle($colLetter . '8:' . $colLetter . '9')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF8FAFC');
                $sheet->getStyle($colLetter . '8:' . $colLetter . '9')->getFont()->setColor(new Color('334155'));
            }
        }

        $sheet->getStyle("A7:{$lastColLetter}7")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE2E8F0');

        // Rows Asatidz
        $currentRow = 10;
        foreach ($listGuru as $i => $guru) {
            $sheet->getRowDimension($currentRow)->setRowHeight(20);

            $sheet->setCellValue('A' . $currentRow, $i + 1);
            $sheet->getStyle('A' . $currentRow)->getFont()->setName('Arial')->setSize(9);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

            $sheet->setCellValue('B' . $currentRow, ' ' . $guru->nama_guru);
            $sheet->getStyle('B' . $currentRow)->getFont()->setName('Arial')->setSize(9);
            $sheet->getStyle('B' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER);

            $jumlahHadir = 0;
            $jumlahSakit = 0;
            $jumlahIzin = 0;
            $jumlahAlpa = 0;

            foreach ($listAbsen as $tglIdx => $absenTgl) {
                $colIndex = 3 + $tglIdx;
                $colLetter = Coordinate::stringFromColumnIndex($colIndex);
                $cellCoord = $colLetter . $currentRow;

                $sheet->getStyle($cellCoord)->getFont()->setName('Arial')->setSize(8.5)->setBold(true);
                $sheet->getStyle($cellCoord)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

                if (!empty($absenTgl['status_libur'])) {
                    $sheet->setCellValue($cellCoord, 'L');
                    $sheet->getStyle($cellCoord)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFE4E6');
                    $sheet->getStyle($cellCoord)->getFont()->setColor(new Color('BE123C'));
                } elseif (!empty($absenTgl['lewat'])) {
                    $sheet->setCellValue($cellCoord, '');
                    $sheet->getStyle($cellCoord)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFBFDFF');
                } else {
                    $kehadiran = $absenTgl[$i]['id_kehadiran'] ?? null;
                    if ($kehadiran == 1) {
                        $jumlahHadir++;
                        $sheet->setCellValue($cellCoord, 'H');
                        $sheet->getStyle($cellCoord)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFDCFCE7');
                        $sheet->getStyle($cellCoord)->getFont()->setColor(new Color('15803D'));
                    } elseif ($kehadiran == 2) {
                        $jumlahSakit++;
                        $sheet->setCellValue($cellCoord, 'S');
                        $sheet->getStyle($cellCoord)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEF9C3');
                        $sheet->getStyle($cellCoord)->getFont()->setColor(new Color('A16207'));
                    } elseif ($kehadiran == 3) {
                        $jumlahIzin++;
                        $sheet->setCellValue($cellCoord, 'I');
                        $sheet->getStyle($cellCoord)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEF9C3');
                        $sheet->getStyle($cellCoord)->getFont()->setColor(new Color('A16207'));
                    } else {
                        $jumlahAlpa++;
                        $sheet->setCellValue($cellCoord, 'A');
                        $sheet->getStyle($cellCoord)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEE2E2');
                        $sheet->getStyle($cellCoord)->getFont()->setColor(new Color('B91C1C'));
                    }
                }
            }

            $sheet->setCellValue($colH . $currentRow, $jumlahHadir ?: '-');
            $sheet->getStyle($colH . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle($colH . $currentRow)->getFont()->setName('Arial')->setSize(9)->setBold(true);

            $sheet->setCellValue($colS . $currentRow, $jumlahSakit ?: '-');
            $sheet->getStyle($colS . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle($colS . $currentRow)->getFont()->setName('Arial')->setSize(9);

            $sheet->setCellValue($colI . $currentRow, $jumlahIzin ?: '-');
            $sheet->getStyle($colI . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle($colI . $currentRow)->getFont()->setName('Arial')->setSize(9);

            $sheet->setCellValue($colA . $currentRow, $jumlahAlpa ?: '-');
            $sheet->getStyle($colA . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle($colA . $currentRow)->getFont()->setName('Arial')->setSize(9)->setBold($jumlahAlpa > 0);
            if ($jumlahAlpa > 0) {
                $sheet->getStyle($colA . $currentRow)->getFont()->setColor(new Color('B91C1C'));
            }

            if ($i % 2 === 1) {
                $sheet->getStyle("A{$currentRow}:B{$currentRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF8FAFC');
                $sheet->getStyle("{$colH}{$currentRow}:{$lastColLetter}{$currentRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF8FAFC');
            }

            $currentRow++;
        }

        $lastDataRow = $currentRow - 1;

        // Border
        $tableRange = "A7:{$lastColLetter}{$lastDataRow}";
        $sheet->getStyle($tableRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('CBD5E1'));
        $sheet->getStyle($tableRange)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('475569'));
        $sheet->getStyle("A7:{$lastColLetter}9")->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('475569'));

        // Column Widths
        $sheet->getColumnDimension('A')->setWidth(5.5);
        $sheet->getColumnDimension('B')->setWidth(26);

        for ($c = 3; $c <= $dateEndCol; $c++) {
            $colLetter = Coordinate::stringFromColumnIndex($c);
            $sheet->getColumnDimension($colLetter)->setWidth(4.2);
        }

        $sheet->getColumnDimension($colH)->setWidth(5.5);
        $sheet->getColumnDimension($colS)->setWidth(5.5);
        $sheet->getColumnDimension($colI)->setWidth(5.5);
        $sheet->getColumnDimension($colA)->setWidth(5.5);

        // Rekapitulasi Ustadz
        $rekapStartRow = $lastDataRow + 2;

        $sheet->setCellValue("B{$rekapStartRow}", 'REKAPITULASI ASATIDZ:');
        $sheet->getStyle("B{$rekapStartRow}")->getFont()->setName('Arial')->setBold(true)->setSize(9.5)->setColor(new Color('0F172A'));

        $sheet->setCellValue('B' . ($rekapStartRow + 1), 'Total Asatidz');
        $sheet->setCellValue('C' . ($rekapStartRow + 1), ': ' . count($listGuru) . ' asatidz');
        $sheet->getStyle('B' . ($rekapStartRow + 1) . ':C' . ($rekapStartRow + 1))->getFont()->setName('Arial')->setSize(9);

        $sheet->setCellValue('B' . ($rekapStartRow + 2), 'Laki-laki');
        $sheet->setCellValue('C' . ($rekapStartRow + 2), ': ' . ($jumlahGuru['laki'] ?? 0) . ' asatidz');
        $sheet->getStyle('B' . ($rekapStartRow + 2) . ':C' . ($rekapStartRow + 2))->getFont()->setName('Arial')->setSize(9);

        $sheet->setCellValue('B' . ($rekapStartRow + 3), 'Perempuan');
        $sheet->setCellValue('C' . ($rekapStartRow + 3), ': ' . ($jumlahGuru['perempuan'] ?? 0) . ' asatidz');
        $sheet->getStyle('B' . ($rekapStartRow + 3) . ':C' . ($rekapStartRow + 3))->getFont()->setName('Arial')->setSize(9);

        // Keterangan Kode
        $ketColLabel = Coordinate::stringFromColumnIndex(min($dateStartCol + 3, $dateEndCol));
        $ketColDesc = Coordinate::stringFromColumnIndex(min($dateStartCol + 4, $dateEndCol));

        $sheet->setCellValue("{$ketColLabel}{$rekapStartRow}", 'KETERANGAN KODE:');
        $sheet->getStyle("{$ketColLabel}{$rekapStartRow}")->getFont()->setName('Arial')->setBold(true)->setSize(9.5)->setColor(new Color('0F172A'));

        $keteranganList = [
            ['H', ': Hadir', 'FFDCFCE7', '15803D'],
            ['S', ': Sakit', 'FFFEF9C3', '854D0E'],
            ['I', ': Izin', 'FFFEF9C3', '854D0E'],
            ['A', ': Alpa (Tanpa Keterangan)', 'FFFEE2E2', '991B1B'],
            ['L', ': Libur (Kalender TPQ & Libur Pekan)', 'FFFFE4E6', 'BE123C'],
        ];

        foreach ($keteranganList as $kIdx => $kItem) {
            $r = $rekapStartRow + 1 + $kIdx;
            $sheet->setCellValue($ketColLabel . $r, $kItem[0]);
            $sheet->getStyle($ketColLabel . $r)->getFont()->setName('Arial')->setBold(true)->setSize(8.5)->setColor(new Color($kItem[3]));
            $sheet->getStyle($ketColLabel . $r)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle($ketColLabel . $r)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($kItem[2]);
            $sheet->getStyle($ketColLabel . $r)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('CBD5E1'));

            $sheet->setCellValue($ketColDesc . $r, $kItem[1]);
            $sheet->getStyle($ketColDesc . $r)->getFont()->setName('Arial')->setSize(9)->setColor(new Color('334155'));
        }

        $pageSetup->setRowsToRepeatAtTopByStartAndEnd(7, 9);

        $filename = 'Laporan_Presensi_Asatidz_' . date('Ymd_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
