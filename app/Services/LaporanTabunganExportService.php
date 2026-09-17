<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanTabunganExportService
{
    /**
     * Export Laporan Rekapitulasi Tabungan Santri ke format Excel (.xlsx) dengan A4 Landscape
     */
    public function exportTabungan(array $data): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap Tabungan Santri');

        $periodeLabel = $data['periodeLabel'] ?? 'Periode Laporan';
        $saldoAwal = (float) ($data['saldoAwal'] ?? 0);
        $totalSetoran = (float) ($data['totalSetoran'] ?? 0);
        $totalPenarikan = (float) ($data['totalPenarikan'] ?? 0);
        $saldoAkhir = (float) ($data['saldoAkhir'] ?? 0);
        $kasDiBendahara = (float) ($data['kasDiBendahara'] ?? 0);
        $danaMengendap = (float) ($data['danaMengendap'] ?? 0);
        $laporanKelas = $data['laporanKelas'] ?? [];
        $generalSettings = $data['generalSettings'] ?? null;
        $tanggalCetak = $data['tanggalCetak'] ?? date('d F Y');

        $schoolName = $generalSettings->school_name ?? 'TPQ Darul Huda';
        $schoolYear = $generalSettings->school_year ?? '2026/2027';

        // --- 1. PAGE SETUP (A4 LANDSCAPE & FIT TO WIDTH) ---
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

        // --- 2. LOGO TPQ DI KIRI KOP (AUTO-SCALED) ---
        $logoPath = null;
        if (!empty($generalSettings->logo) && file_exists(public_path('uploads/logo/' . $generalSettings->logo))) {
            $logoPath = public_path('uploads/logo/' . $generalSettings->logo);
        } elseif (file_exists(public_path('assets/img/logo-sekolah.jpg'))) {
            $logoPath = public_path('assets/img/logo-sekolah.jpg');
        }

        if ($logoPath && file_exists($logoPath)) {
            $drawing = new Drawing();
            $drawing->setName('Logo TPQ');
            $drawing->setDescription('Logo TPQ');
            $drawing->setPath($logoPath);
            $drawing->setCoordinates('B2');
            $drawing->setHeight(65);
            $drawing->setOffsetX(10);
            $drawing->setOffsetY(2);
            $drawing->setWorksheet($sheet);
        }

        // --- 3. KOP LAPORAN (CENTERED MELINTASI KOLOM A-H) ---
        $sheet->getRowDimension(1)->setRowHeight(8);

        $sheet->mergeCells('A2:H2');
        $sheet->setCellValue('A2', 'LAPORAN REKAPITULASI DANA TABUNGAN SANTRI');
        $sheet->getStyle('A2')->getFont()->setName('Arial')->setBold(true)->setSize(14)->setColor(new Color('0F172A'));
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(2)->setRowHeight(24);

        $sheet->mergeCells('A3:H3');
        $sheet->setCellValue('A3', strtoupper($schoolName));
        $sheet->getStyle('A3')->getFont()->setName('Arial')->setBold(true)->setSize(11.5)->setColor(new Color('1E3A8A'));
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(3)->setRowHeight(20);

        $sheet->mergeCells('A4:H4');
        $sheet->setCellValue('A4', 'PERIODE: ' . strtoupper($periodeLabel) . '  |  TAHUN PELAJARAN ' . strtoupper($schoolYear));
        $sheet->getStyle('A4')->getFont()->setName('Arial')->setBold(true)->setSize(10)->setColor(new Color('475569'));
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(4)->setRowHeight(18);

        $sheet->getRowDimension(5)->setRowHeight(12);

        // --- 4. BAGIAN I: RINGKASAN EKSEKUTIF (4 KARTU METRIK) ---
        $sheet->mergeCells('A6:H6');
        $sheet->setCellValue('A6', 'I. RINGKASAN EKSEKUTIF KAS TABUNGAN');
        $sheet->getStyle('A6')->getFont()->setName('Arial')->setBold(true)->setSize(10.5)->setColor(new Color('0F172A'));
        $sheet->getRowDimension(6)->setRowHeight(20);

        // Header 4 Kartu
        $sheet->mergeCells('A7:B7');
        $sheet->setCellValue('A7', 'SALDO AWAL BULAN');
        $sheet->mergeCells('C7:D7');
        $sheet->setCellValue('C7', 'TOTAL SETORAN (+)');
        $sheet->mergeCells('E7:F7');
        $sheet->setCellValue('E7', 'TOTAL PENARIKAN (-)');
        $sheet->mergeCells('G7:H7');
        $sheet->setCellValue('G7', 'SALDO AKHIR BULAN (=)');

        $sheet->getStyle('A7:H7')->getFont()->setName('Arial')->setBold(true)->setSize(9)->setColor(new Color('475569'));
        $sheet->getStyle('A7:H7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A7:H7')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF1F5F9');
        $sheet->getRowDimension(7)->setRowHeight(20);

        // Nilai 4 Kartu
        $sheet->mergeCells('A8:B8');
        $sheet->setCellValue('A8', $saldoAwal);
        $sheet->mergeCells('C8:D8');
        $sheet->setCellValue('C8', $totalSetoran);
        $sheet->mergeCells('E8:F8');
        $sheet->setCellValue('E8', $totalPenarikan);
        $sheet->mergeCells('G8:H8');
        $sheet->setCellValue('G8', $saldoAkhir);

        $sheet->getStyle('A8:H8')->getFont()->setName('Arial')->setBold(true)->setSize(11);
        $sheet->getStyle('A8:H8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A8:B8')->getFont()->setColor(new Color('1E293B'));
        $sheet->getStyle('C8:D8')->getFont()->setColor(new Color('15803D'));
        $sheet->getStyle('E8:F8')->getFont()->setColor(new Color('B91C1C'));
        $sheet->getStyle('G8:H8')->getFont()->setColor(new Color('1D4ED8'));

        $sheet->getStyle('A7:H8')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('CBD5E1'));
        $sheet->getRowDimension(8)->setRowHeight(24);

        $currencyFormat = '_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"_);_(@_)';
        $sheet->getStyle('A8')->getNumberFormat()->setFormatCode($currencyFormat);
        $sheet->getStyle('C8')->getNumberFormat()->setFormatCode($currencyFormat);
        $sheet->getStyle('E8')->getNumberFormat()->setFormatCode($currencyFormat);
        $sheet->getStyle('G8')->getNumberFormat()->setFormatCode($currencyFormat);

        $sheet->getRowDimension(9)->setRowHeight(10);

        // --- 5. BAGIAN II: POSISI FISIK DANA (AKUNTABILITAS KAS) ---
        $sheet->mergeCells('A10:H10');
        $sheet->setCellValue('A10', 'II. POSISI FISIK DANA (REKONSILIASI KAS TPQ)');
        $sheet->getStyle('A10')->getFont()->setName('Arial')->setBold(true)->setSize(10.5)->setColor(new Color('0F172A'));
        $sheet->getRowDimension(10)->setRowHeight(20);

        // Baris Kas Bendahara
        $sheet->mergeCells('A11:E11');
        $sheet->setCellValue('A11', '1. Dana Telah Disetor ke Kas Bendahara TPQ (Aman)');
        $sheet->getStyle('A11')->getFont()->setName('Arial')->setSize(9.5)->setBold(true)->setColor(new Color('166534'));
        $sheet->getStyle('A11')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->mergeCells('F11:H11');
        $sheet->setCellValue('F11', $kasDiBendahara);
        $sheet->getStyle('F11')->getFont()->setName('Arial')->setSize(10.5)->setBold(true)->setColor(new Color('166534'));
        $sheet->getStyle('F11')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('F11')->getNumberFormat()->setFormatCode($currencyFormat);
        $sheet->getStyle('A11:H11')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFDCFCE7');
        $sheet->getStyle('A11:H11')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('A7F3D0'));
        $sheet->getRowDimension(11)->setRowHeight(22);

        // Baris Dana Mengendap di Guru
        $sheet->mergeCells('A12:E12');
        $sheet->setCellValue('A12', '2. Dana Mengendap di Ustadz / Wali Kelas (Belum Disetor ke Bendahara)');
        $sheet->getStyle('A12')->getFont()->setName('Arial')->setSize(9.5)->setBold(true)->setColor(new Color('9A3412'));
        $sheet->getStyle('A12')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->mergeCells('F12:H12');
        $sheet->setCellValue('F12', $danaMengendap);
        $sheet->getStyle('F12')->getFont()->setName('Arial')->setSize(10.5)->setBold(true)->setColor(new Color('9A3412'));
        $sheet->getStyle('F12')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('F12')->getNumberFormat()->setFormatCode($currencyFormat);
        $sheet->getStyle('A12:H12')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEF3C7');
        $sheet->getStyle('A12:H12')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('FDE68A'));
        $sheet->getRowDimension(12)->setRowHeight(22);

        $sheet->getRowDimension(13)->setRowHeight(12);

        // --- 6. BAGIAN III: TABEL REKAPITULASI PER KELAS ---
        $sheet->mergeCells('A14:H14');
        $sheet->setCellValue('A14', 'III. REKAPITULASI DANA TABUNGAN PER KELAS');
        $sheet->getStyle('A14')->getFont()->setName('Arial')->setBold(true)->setSize(10.5)->setColor(new Color('0F172A'));
        $sheet->getRowDimension(14)->setRowHeight(20);

        // Header Kolom Tabel (Baris 15)
        $sheet->setCellValue('A15', 'NO');
        $sheet->setCellValue('B15', 'KELAS');
        $sheet->setCellValue('C15', 'WALI KELAS / USTADZ');
        $sheet->setCellValue('D15', 'SANTRI');
        $sheet->setCellValue('E15', 'SETORAN BULAN INI');
        $sheet->setCellValue('F15', 'PENARIKAN BULAN INI');
        $sheet->setCellValue('G15', 'SALDO AKHIR');
        $sheet->setCellValue('H15', 'STATUS SETORAN GURU');

        $sheet->getStyle('A15:H15')->getFont()->setName('Arial')->setBold(true)->setSize(9.5)->setColor(new Color('0F172A'));
        $sheet->getStyle('A15:H15')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A15:H15')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE2E8F0');
        $sheet->getRowDimension(15)->setRowHeight(24);

        // Loop Data Kelas (Baris 16 onwards)
        $startRow = 16;
        $currentRow = $startRow;
        $totalSantriSemua = 0;
        $totalSetoranSemua = 0;
        $totalPenarikanSemua = 0;
        $totalSaldoSemua = 0;

        foreach ($laporanKelas as $idx => $item) {
            $sheet->getRowDimension($currentRow)->setRowHeight(21);

            $sheet->setCellValue('A' . $currentRow, $idx + 1);
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

            $sheet->setCellValue('B' . $currentRow, $item['nama_kelas']);
            $sheet->getStyle('B' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('B' . $currentRow)->getFont()->setBold(true);

            $sheet->setCellValue('C' . $currentRow, $item['nama_guru']);
            $sheet->getStyle('C' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER);

            $santriCount = (int) ($item['santri_count'] ?? 0);
            $totalSantriSemua += $santriCount;
            $sheet->setCellValue('D' . $currentRow, $santriCount . ' santri');
            $sheet->getStyle('D' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

            $setorBulanIni = (float) ($item['setor_bulan_ini'] ?? 0);
            $totalSetoranSemua += $setorBulanIni;
            $sheet->setCellValue('E' . $currentRow, $setorBulanIni);
            $sheet->getStyle('E' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('E' . $currentRow)->getNumberFormat()->setFormatCode($currencyFormat);

            $tarikBulanIni = (float) ($item['tarik_bulan_ini'] ?? 0);
            $totalPenarikanSemua += $tarikBulanIni;
            $sheet->setCellValue('F' . $currentRow, $tarikBulanIni);
            $sheet->getStyle('F' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('F' . $currentRow)->getNumberFormat()->setFormatCode($currencyFormat);

            $saldoKelas = (float) ($item['saldo_akhir'] ?? 0);
            $totalSaldoSemua += $saldoKelas;
            $sheet->setCellValue('G' . $currentRow, $saldoKelas);
            $sheet->getStyle('G' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('G' . $currentRow)->getNumberFormat()->setFormatCode($currencyFormat);
            $sheet->getStyle('G' . $currentRow)->getFont()->setBold(true);

            // Status Setoran Guru
            $statusSetoran = $item['status_setoran'] ?? 'Lunas Disetor';
            $sheet->setCellValue('H' . $currentRow, $statusSetoran);
            $sheet->getStyle('H' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('H' . $currentRow)->getFont()->setSize(9);

            if (str_contains(strtolower($statusSetoran), 'mengendap') || str_contains(strtolower($statusSetoran), 'belum')) {
                $sheet->getStyle('H' . $currentRow)->getFont()->setColor(new Color('B91C1C'))->setBold(true);
                $sheet->getStyle('H' . $currentRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEE2E2');
            } else {
                $sheet->getStyle('H' . $currentRow)->getFont()->setColor(new Color('15803D'))->setBold(true);
            }

            // Alternating Row Fill
            if ($idx % 2 === 1) {
                $sheet->getStyle("A{$currentRow}:G{$currentRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF8FAFC');
            }

            $currentRow++;
        }

        $lastDataRow = $currentRow - 1;

        // Baris Total (Footer Tabel)
        $sheet->mergeCells("A{$currentRow}:C{$currentRow}");
        $sheet->setCellValue("A{$currentRow}", 'TOTAL KESELURUHAN');
        $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true);

        $sheet->setCellValue('D' . $currentRow, $totalSantriSemua . ' santri');
        $sheet->getStyle('D' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('D' . $currentRow)->getFont()->setBold(true);

        $sheet->setCellValue('E' . $currentRow, $totalSetoranSemua);
        $sheet->getStyle('E' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('E' . $currentRow)->getNumberFormat()->setFormatCode($currencyFormat);
        $sheet->getStyle('E' . $currentRow)->getFont()->setBold(true);

        $sheet->setCellValue('F' . $currentRow, $totalPenarikanSemua);
        $sheet->getStyle('F' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('F' . $currentRow)->getNumberFormat()->setFormatCode($currencyFormat);
        $sheet->getStyle('F' . $currentRow)->getFont()->setBold(true);

        $sheet->setCellValue('G' . $currentRow, $totalSaldoSemua);
        $sheet->getStyle('G' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('G' . $currentRow)->getNumberFormat()->setFormatCode($currencyFormat);
        $sheet->getStyle('G' . $currentRow)->getFont()->setBold(true);

        $sheet->setCellValue('H' . $currentRow, '');

        $sheet->getStyle("A{$currentRow}:H{$currentRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE2E8F0');
        $sheet->getRowDimension($currentRow)->setRowHeight(24);

        // Border Tabel
        $tableRange = "A15:H{$currentRow}";
        $sheet->getStyle($tableRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('CBD5E1'));
        $sheet->getStyle($tableRange)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('475569'));
        $sheet->getStyle("A15:H15")->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)->setColor(new Color('475569'));

        // Lebar Kolom
        $sheet->getColumnDimension('A')->setWidth(5.5);  // No
        $sheet->getColumnDimension('B')->setWidth(16);   // Kelas
        $sheet->getColumnDimension('C')->setWidth(26);   // Guru
        $sheet->getColumnDimension('D')->setWidth(14);   // Santri
        $sheet->getColumnDimension('E')->setWidth(21);   // Setor
        $sheet->getColumnDimension('F')->setWidth(21);   // Tarik
        $sheet->getColumnDimension('G')->setWidth(22);   // Saldo
        $sheet->getColumnDimension('H')->setWidth(26);   // Status

        // --- 7. LEMBAR PENGESAHAN (TANDA TANGAN KEPALA TPQ & BENDAHARA) ---
        $signStartRow = $currentRow + 3;

        // Kiri: Kepala TPQ
        $sheet->mergeCells("A{$signStartRow}:D{$signStartRow}");
        $sheet->setCellValue("A{$signStartRow}", 'Mengetahui / Menyetujui,');
        $sheet->getStyle("A{$signStartRow}")->getFont()->setName('Arial')->setSize(10)->setBold(true);
        $sheet->getStyle("A{$signStartRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells("A" . ($signStartRow + 1) . ":D" . ($signStartRow + 1));
        $sheet->setCellValue("A" . ($signStartRow + 1), 'Kepala TPQ');
        $sheet->getStyle("A" . ($signStartRow + 1))->getFont()->setName('Arial')->setSize(10)->setBold(true);
        $sheet->getStyle("A" . ($signStartRow + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells("A" . ($signStartRow + 5) . ":D" . ($signStartRow + 5));
        $namaKepala = $data['namaKepala'] ?? '( .................................................... )';
        $sheet->setCellValue("A" . ($signStartRow + 5), $namaKepala);
        $sheet->getStyle("A" . ($signStartRow + 5))->getFont()->setName('Arial')->setSize(10)->setBold(true);
        $sheet->getStyle("A" . ($signStartRow + 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $niupKepala = !empty($data['niupKepala']) ? 'NIUP: ' . $data['niupKepala'] : 'Kepala Sekolah / TPQ';
        $sheet->mergeCells("A" . ($signStartRow + 6) . ":D" . ($signStartRow + 6));
        $sheet->setCellValue("A" . ($signStartRow + 6), $niupKepala);
        $sheet->getStyle("A" . ($signStartRow + 6))->getFont()->setName('Arial')->setSize(9);
        $sheet->getStyle("A" . ($signStartRow + 6))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Kanan: Bendahara TPQ
        $sheet->mergeCells("E{$signStartRow}:H{$signStartRow}");
        $sheet->setCellValue("E{$signStartRow}", 'Dibuat pada: ' . $tanggalCetak);
        $sheet->getStyle("E{$signStartRow}")->getFont()->setName('Arial')->setSize(10);
        $sheet->getStyle("E{$signStartRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells("E" . ($signStartRow + 1) . ":H" . ($signStartRow + 1));
        $sheet->setCellValue("E" . ($signStartRow + 1), 'Bendahara TPQ');
        $sheet->getStyle("E" . ($signStartRow + 1))->getFont()->setName('Arial')->setSize(10)->setBold(true);
        $sheet->getStyle("E" . ($signStartRow + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells("E" . ($signStartRow + 5) . ":H" . ($signStartRow + 5));
        $namaBendahara = $data['namaBendahara'] ?? '( .................................................... )';
        $sheet->setCellValue("E" . ($signStartRow + 5), $namaBendahara);
        $sheet->getStyle("E" . ($signStartRow + 5))->getFont()->setName('Arial')->setSize(10)->setBold(true);
        $sheet->getStyle("E" . ($signStartRow + 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $niupBendahara = !empty($data['niupBendahara']) ? 'NIUP: ' . $data['niupBendahara'] : 'Bendahara TPQ';
        $sheet->mergeCells("E" . ($signStartRow + 6) . ":H" . ($signStartRow + 6));
        $sheet->setCellValue("E" . ($signStartRow + 6), $niupBendahara);
        $sheet->getStyle("E" . ($signStartRow + 6))->getFont()->setName('Arial')->setSize(9);
        $sheet->getStyle("E" . ($signStartRow + 6))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Stream Output
        $filename = 'Laporan_Rekapitulasi_Tabungan_Santri_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $periodeLabel) . '_' . date('Ymd_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
