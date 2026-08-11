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

### 1. Panel Tabungan Guru (`ManualAttendance.php` & `manual-attendance.blade.php`)
- Tampilkan sub-panel "Riwayat Transaksi Santri Ini" (5 transaksi terakhir).
- Setiap transaksi menampilkan tanggal, jenis (setor/tarik), nominal, keterangan, dan tombol **Edit** & **Hapus**.
- Tombol Edit/Hapus disembunyikan jika `status_setoran == 'sudah'`.
- Menambahkan method `editTabungan($id)`, `updateTabungan()`, dan `hapusTabungan($id)`.

### 2. Menu Laporan Tabungan Admin (`LaporanTabunganIndex.php` & `laporan-tabungan-index.blade.php`)
- Pada tabel **Riwayat Transaksi Terbaru**, tambahkan kolom **Aksi** dengan tombol **Edit** dan **Hapus**.
- Menambahkan Modal Edit Transaksi di `laporan-tabungan-index.blade.php`.
- Menambahkan method `editTabungan($id)`, `updateTabungan()`, dan `hapusTabungan($id)` di `LaporanTabunganIndex.php`.

## Review Check
- [x] Placeholder/ambiguity: Tidak ada. Logika adjustment saldo dan batasan role dijelaskan secara rinci.
- [x] Internal Consistency: Sinkron antara backend `ManualAttendance` & `LaporanTabunganIndex`.
- [x] Scope: Sangat jelas dan fokus pada pengelolaan transaksi tabungan.
