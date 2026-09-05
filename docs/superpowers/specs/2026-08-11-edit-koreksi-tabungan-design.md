# Fitur Edit dan Koreksi Transaksi Tabungan Santri

## Deskripsi Singkat
Menambahkan fitur Edit & Hapus transaksi tabungan santri agar kesalahan input nominal/keterangan oleh Guru dapat dikoreksi, sekaligus menjaga konsistensi saldo santri (`tb_siswa.saldo_tabungan`).

## Hak Akses & Aturan Keamanan
- **Guru (Wali Kelas)**: Hanya dapat mengedit atau menghapus transaksi tabungan milik santrinya yang **belum disetorkan** (`status_setoran == 'belum'`).
- **Bendahara & Superadmin**: Memiliki hak akses penuh untuk mengedit atau menghapus transaksi tabungan baik yang `belum` maupun `sudah` disetorkan.

## Logika Penyesuaian Saldo (Data Integrity)
1. **Edit Transaksi**:
   - Hitung selisih nominal lama dan nominal baru.
   - Perbarui `tb_siswa.saldo_tabungan`:
     - Jika jenis `setor`: `saldo_baru = saldo_lama - nominal_lama + nominal_baru`.
     - Jika jenis `tarik`: `saldo_baru = saldo_lama + nominal_lama - nominal_baru`.
   - Update data transaksi di `tb_tabungan`.
2. **Hapus Transaksi**:
   - Kembalikan `tb_siswa.saldo_tabungan`:
     - Jika jenis `setor`: `saldo_baru = saldo_lama - nominal`.
     - Jika jenis `tarik`: `saldo_baru = saldo_lama + nominal`.
   - Hapus record dari `tb_tabungan`.

## Rincian Perubahan UI & Komponen

### 1. Menu Baru Guru: "Riwayat Tabungan" (`RiwayatTabunganGuru.php` & `riwayat-tabungan-guru.blade.php`)
- **Lokasi**: Menu baru di Sidebar khusus Guru, diletakkan di bawah menu "Monitoring & Absensi".
- **Tampilan Utama**: Halaman penuh berisi tabel riwayat transaksi (Setor & Tarik) khusus untuk santri-santri yang diajarkan oleh guru tersebut.
- **Fitur Filter**:
  - Filter Rentang Tanggal (Tanggal Awal - Akhir).
  - Filter Santri (Pilih Santri).
  - Filter Jenis Transaksi (Setor/Tarik).
- **Aksi Koreksi**:
  - Tabel menampilkan kolom Aksi dengan tombol **Edit** dan **Hapus**.
  - Tombol disembunyikan jika transaksi sudah disetor (`status_setoran == 'sudah'`).
- **Implementasi**: 
  - Menggunakan Modal Bootstrap untuk form edit nominal dan catatan.
  - Memindahkan metode `editTabungan()`, `updateTabungan()`, dan `hapusTabungan()` dari `ManualAttendance` ke komponen baru ini.

### 2. Panel Tabungan Guru (`ManualAttendance.php` & `manual-attendance.blade.php`)
- Hapus riwayat 5 transaksi terakhir beserta aksi Edit/Hapus dari slide-over panel ini, agar form input lebih bersih dan fokus pada pengisian transaksi baru saja.

### 2. Menu Laporan Tabungan Admin (`LaporanTabunganIndex.php` & `laporan-tabungan-index.blade.php`)
- Pada tabel **Riwayat Transaksi Terbaru**, tambahkan kolom **Aksi** dengan tombol **Edit** dan **Hapus**.
- Menambahkan Modal Edit Transaksi di `laporan-tabungan-index.blade.php`.
- Menambahkan method `editTabungan($id)`, `updateTabungan()`, dan `hapusTabungan($id)` di `LaporanTabunganIndex.php`.

## Review Check
- [x] Placeholder/ambiguity: Tidak ada. Pemindahan fitur koreksi ke halaman khusus memperjelas fungsi (separation of concerns).
- [x] Internal Consistency: Logika kalkulasi saldo tetap sama dan dipusatkan, UI lebih bersih.
- [x] Scope: Terfokus pada pembuatan menu baru `RiwayatTabunganGuru` dan pembersihan `ManualAttendance`.
