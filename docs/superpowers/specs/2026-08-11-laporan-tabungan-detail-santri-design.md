# Laporan Tabungan Detail Santri

## Deskripsi Singkat
Menambahkan fitur untuk melihat detail saldo tabungan per anak (santri) pada menu Laporan Tabungan (diakses oleh peran yang memiliki permission `laporan_tabungan`, seperti Superadmin dan Bendahara).

## Pendekatan Desain
Fitur ini menggunakan pendekatan **Tombol "Lihat Detail" dengan Modal** agar tampilan tabel utama rincian per kelas tetap rapi dan ringkas.

### Sisi Backend (Livewire Component: `LaporanTabunganIndex.php`)
1. **State / Properties**:
   - `$detailSiswa`: Array atau Collection untuk menampung daftar santri dari kelas yang dipilih.
   - `$namaKelasTerpilih`: String untuk menampung nama kelas (tingkat + index) yang sedang dilihat detailnya.
   - `$isModalOpen`: Boolean (opsional, jika menggunakan Alpine.js atau default bootstrap) untuk mendandakan status modal.
2. **Method**:
   - `bukaDetailKelas($id_kelas)`: 
     - Menerima argumen `$id_kelas`.
     - Mengambil data kelas tersebut.
     - Mengambil data semua `Siswa` yang memiliki `$id_kelas` tersebut.
     - Melakukan kalkulasi saldo per anak: `saldo = total setor - total tarik`.
     - Melakukan kalkulasi `dana_mengendap` per anak: setoran dengan `status_setoran == 'belum'`.
     - Menyimpan hasil ke `$detailSiswa` dan menset kelas ke `$namaKelasTerpilih`.
     - (Opsional) Meng-emit event `show-detail-modal` untuk memicu modal terbuka via JavaScript.

### Sisi Frontend (Blade View: `laporan-tabungan-index.blade.php`)
1. **Tabel Rincian Saldo per Kelas**:
   - Menambah `<th>Aksi</th>` di header.
   - Menambah `<td><button wire:click="bukaDetailKelas({{ $row['kelas']->id_kelas }})" class="btn btn-sm btn-info">Lihat Detail</button></td>` di tiap baris.
2. **Modal (Bootstrap)**:
   - Menambahkan struktur Modal di luar main content.
   - Kondisional atau di-trigger menggunakan event browser via Livewire (misal: `@script` atau event listener).
   - Di dalam modal, ada tabel yang me-loop `$detailSiswa` dan menampilkan kolom: **Nama Santri**, **Dana Mengendap**, **Total Saldo**.
   - Jika `$detailSiswa` kosong, tampilkan *state empty*.

## Batasan (Scope)
- Tidak ada perubahan relasi database. Kalkulasi bergantung pada `tb_tabungan` yang dihubungkan dengan `tb_siswa` menggunakan `id_siswa`.
- Hanya berfungsi membaca (read-only) untuk keperluan pelaporan.

## Review Check
- [x] Placeholder/ambiguity: Tidak ada. Semua sudah ditentukan spesifik terkait perhitungan dan UI.
- [x] Internal Consistency: Sisi blade dan komponen sinkron dan sesuai dengan model data (`Siswa`, `Tabungan`).
- [x] Scope: Cukup spesifik, dapat dikerjakan dalam satu tahapan implementasi.
