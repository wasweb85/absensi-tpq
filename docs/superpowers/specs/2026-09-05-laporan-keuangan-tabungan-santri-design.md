# Design Specification: Laporan Rekapitulasi Keuangan Tabungan Santri Bulanan

**Tanggal:** 05 September 2026  
**Status:** Disetujui (Approved by User)  
**Topik:** Format & Implementasi Laporan Bulanan Rekapitulasi Tabungan Santri untuk Pengesahan Kepala TPQ  

---

## 1. Pendahuluan & Latar Belakang

Sistem absensi & administrasi TPQ saat ini telah memiliki modul pencatatan transaksi tabungan santri (`tb_tabungan`) dan setoran guru ke bendahara (`tb_setoran_bendahara`). Namun, belum tersedia format pelaporan bulanan resmi yang terstruktur, rapi, dan siap ditandatangani oleh Kepala TPQ untuk akuntabilitas keuangan lembaga.

Spesifikasi ini menetapkan format **Rekapitulasi Eksekutif Per Kelas Bulanan** yang ringkas (1–2 halaman A4), mencakup ringkasan mutasi kas global, transparansi posisi fisik dana (di kas bendahara vs. mengendap di guru), rincian saldo per kelas, serta lembar pengesahan formal (tanda tangan Bendahara dan Kepala TPQ).

---

## 2. Struktur Data & Tata Letak Laporan

Laporan ini dirancang untuk dicetak pada kertas **A4** (baik via Print/PDF maupun Ekspor Excel `.xlsx`) dengan 5 komponen utama:

### 2.1. Kop Surat & Identitas Lembaga
- **Logo Lembaga TPQ**: Posisi di kiri atas dengan skala proporsional otomatis (maksimal tinggi 65–70px), diambil dari pengaturan umum (`uploads/logo/` atau `assets/img/logo-sekolah.jpg`).
- **Judul Laporan**: `LAPORAN REKAPITULASI DANA TABUNGAN SANTRI` (Centered, Bold, 14pt).
- **Nama Lembaga**: Nama TPQ dari pengaturan sistem, misal `TPQ DARUL HUDA` (Centered, Bold, 12pt).
- **Periode Laporan**: Bulan & Tahun berjalan (misal: *Periode: September 2026 / Tahun Pelajaran 2026/2027*).
- **Waktu Cetak**: Tanggal dan jam dokumen digenerate.

### 2.2. Ringkasan Eksekutif Kas Global (Kartu Mutasi)
Menampilkan 4 angka kunci posisi keuangan tabungan:
1. **Saldo Awal Bulan (Rp)**: Akumulasi seluruh saldo tabungan santri sebelum tanggal 1 bulan pelaporan:  
   $$\text{Saldo Awal} = \sum (\text{Setor Sebelum Bulan Ini}) - \sum (\text{Tarik Sebelum Bulan Ini})$$
2. **Total Setoran Masuk Bulan Ini (Rp)**: Total uang tabungan santri yang disetor selama bulan pelaporan:  
   $$\text{Setoran Bulan Ini} = \sum (\text{Setor dalam Bulan Ini})$$
3. **Total Penarikan Bulan Ini (Rp)**: Total penarikan tabungan santri selama bulan pelaporan:  
   $$\text{Penarikan Bulan Ini} = \sum (\text{Tarik dalam Bulan Ini})$$
4. **Saldo Akhir Bulan (Rp)**: Posisi saldo akhir seluruh santri pada akhir bulan pelaporan:  
   $$\text{Saldo Akhir} = \text{Saldo Awal} + \text{Setoran Bulan Ini} - \text{Penarikan Bulan Ini}$$

### 2.3. Rekonsiliasi & Transparansi Fisik Dana
Memberikan informasi kepada Kepala TPQ di mana fisik uang tabungan berada saat ini:
- **Kas di Bendahara**: Total dana tabungan yang telah diserahkan ustadz/ustadzah kepada Bendahara TPQ (`status_setoran = 'sudah'`).
- **Dana Mengendap di Guru**: Dana setoran santri yang masih dipegang guru kelas dan belum disetor ke bendahara (`status_setoran = 'belum'`).
- *Validasi Kontrol:* $\text{Kas di Bendahara} + \text{Dana Mengendap di Guru} = \text{Saldo Kas Tabungan}$.

### 2.4. Tabel Rekapitulasi Tabungan Per Kelas
Tabel teratur dengan garis batas (*border*) rapi:
| No | Kelas | Ustadz / Wali Kelas | Santri Menabung | Setoran Masuk (Rp) | Penarikan (Rp) | Saldo Akhir Kelas (Rp) | Status Setoran Guru ke Bendahara |
|:---|:------|:-------------------|:---------------:|-------------------:|---------------:|-----------------------:|:---------------------------------|
| 1  | Jilid 1 | Ustadz Ahmad | 18 santri | Rp 450.000 | Rp 50.000 | Rp 1.250.000 | Lunas Disetor |
| 2  | Jilid 2 | Ustadzah Fatimah | 15 santri | Rp 380.000 | Rp 0 | Rp 980.000 | Mengendap Rp 120.000 |
| **TOTAL** | | | **33 santri** | **Rp 830.000** | **Rp 50.000** | **Rp 2.230.000** | |

### 2.5. Lembar Pengesahan Formal
Di bawah tabel rekapitulasi, disematkan dua kolom tanda tangan formal:
- **Sisi Kiri**:
  - `Mengetahui / Menyetujui,`
  - `Kepala TPQ`
  - *(Tanda Tangan & Nama Terang Kepala TPQ)*
- **Sisi Kanan**:
  - `[Kota/Kecamatan], [Tanggal Akhir Bulan / Tanggal Cetak]`
  - `Dibuat oleh,`
  - `Bendahara TPQ`
  - *(Tanda Tangan & Nama Terang Bendahara)*

---

## 3. Arsitektur Teknis & Alur Implementasi

### 3.1. Halaman Manajemen & Filter (`LaporanTabunganIndex`)
- Tambahkan filter pemilihan **Bulan** (Januari–Desember) dan **Tahun** pada antarmuka `livewire/admin/laporan-tabungan-index.blade.php`.
- Tampilkan 2 tombol aksi ekspor:
  1. **Cetak / PDF** (`window.print()` dengan CSS A4 Landscape/Portrait print-ready).
  2. **Ekspor Excel (.xlsx)** (Menghasilkan file Excel asli melalui pustaka PhpSpreadsheet).

### 3.2. Layanan Ekspor Excel (`LaporanTabunganExportService`)
- Dibuat di `app/Services/LaporanTabunganExportService.php`.
- Menggunakan `\PhpOffice\PhpSpreadsheet\Spreadsheet`.
- Konfigurasi Halaman:
  - Kertas: A4 (`PageSetup::PAPERSIZE_A4`).
  - Orientasi: Landscape (`PageSetup::ORIENTATION_LANDSCAPE`) agar seluruh kolom termuat proporsional.
  - Fit to page: `FitToWidth = 1`, `FitToHeight = 0`.
  - Margins: 0.5 inci (12.7 mm).
- Logo disematkan via `\PhpOffice\PhpSpreadsheet\Worksheet\Drawing` di sel `B2` dengan tinggi 65px dan lebar auto-proporsional.
- Format angka mata uang rupiah (`_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"_);_(@_)`).
- Kolom tanda tangan di bagian bawah tabel.

### 3.3. Rute & Controller Pengendali
- Rute baru:
  - `GET /admin/laporan-tabungan/cetak` &rarr; Menampilkan tampilan cetak HTML/PDF.
  - `GET /admin/laporan-tabungan/excel` &rarr; Mengunduh file `.xlsx`.
- Dikendalikan oleh method pada controller pelaporan atau Livewire emit download.

---

## 4. Rencana Verifikasi & Pengujian
1. **Pengujian Nilai Matematis**:
   - Memastikan Saldo Awal + Total Setoran - Total Penarikan = Saldo Akhir.
   - Memastikan Kas di Bendahara + Dana Mengendap = Total Saldo.
2. **Pengujian Visual & Cetak A4**:
   - Membuka mode Print dialog browser (`Ctrl+P`) untuk memverifikasi dokumen pas di kertas A4 tanpa kolom terpotong.
3. **Pengujian File Excel**:
   - Membuka file `.xlsx` yang diunduh di Excel / spreadsheet viewer: memverifikasi logo tampil tanpa error, judul center, format angka rupiah terbaca rapi, dan Page Setup langsung A4 Landscape.

---
Dokumen spesifikasi ini menjadi acuan tunggal untuk penulisan rencana kerja dan eksekusi kode.
