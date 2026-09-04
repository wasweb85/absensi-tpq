# Desain Executive Portal & Dashboard Khusus Kepala TPQ (Kepsek)

## 1. Deskripsi Singkat & Filosofi Role
Peran **Kepala TPQ (Kepala Sekolah)** berfungsi sebagai **Supervisor & Pengambil Kebijakan (Executive View)**, bukan operator input data harian. Kepsek membutuhkan informasi yang ringkas, komprehensif, dan langsung dapat ditindaklanjuti dalam satu pandangan (Executive Grid), yang meliputi:
- Kedisiplinan & kehadiran Ustadzah hari ini (termasuk deteksi kebutuhan guru pengganti / inval).
- Progres dan tingkat kehadiran Santri realtime per kelas.
- Pengawasan transparansi keuangan (total kas tabungan santri & status dana di guru).
- Akses cepat untuk mengesahkan dan mencetak laporan resmi bertandatangan Kepala TPQ.
- Proteksi **Read-Only** pada seluruh data master (Data Santri, Data Guru) untuk mencegah modifikasi data yang tidak disengaja.

---

## 2. Hak Akses, Routing, & Proteksi Read-Only

### 2.1 Identifikasi Role & Routing
- **Role Identity:** Dideteksi melalui `is_superadmin === 2` atau role `'kepsek'` pada sistem hak akses.
- **Routing Dashboard:**
  - Route `/dashboard` memeriksa peran pengguna:
    - Jika `is_superadmin === 2` (Kepsek), sistem secara otomatis mengarahkan ke dashboard eksekutif khusus (misal: view terpisah `resources/views/kepsek/dashboard.blade.php` atau komponen Livewire `App\Livewire\Kepsek\Dashboard`).
    - Jika Super Admin (`1`) atau Staf (`3`), tetap memuat dashboard admin teknis.
    - Jika Guru (`0` dengan `id_guru`), tetap diarahkan ke `/teacher/dashboard`.

### 2.2 Navigasi Sidebar Khusus Kepsek
- **Header Badge:** Menampilkan label peran `"Kepala TPQ"` atau `"Kepsek"`.
- **Daftar Menu:**
  1. `Executive Dashboard` (`/dashboard` - Aktif).
  2. `Data Santri` (`/admin/siswa` - Mode View-Only).
  3. `Data Guru` (`/admin/guru` - Mode View-Only).
  4. `Pusat Laporan`:
     - Rekap Presensi Siswa (`/admin/laporan/siswa`).
     - Rekap Presensi Guru (`/admin/laporan/guru`).
     - Laporan Tabungan Global (`/admin/laporan-tabungan`).

### 2.3 Mekanisme Proteksi Read-Only (Master Data)
- **Tampilan UI (Blade / Livewire):**
  - Pada halaman Data Santri (`SiswaIndex`) dan Data Guru (`GuruIndex`), tombol aksi seperti **Tambah Data**, **Edit**, **Hapus**, **Import Excel**, dan **Reset Password** disembunyikan jika `is_superadmin == 2`.
  - Tabel master data tetap menyediakan fitur pencarian, filter kelas, pagination, dan tombol "Detail / Lihat" santri/guru.
- **Backend Gate:**
  - Pada metode Livewire mutasi data (`save`, `store`, `update`, `delete`, `resetPassword`), ditambahkan validasi peran:
    ```php
    if (auth()->user() && (int) auth()->user()->is_superadmin === 2) {
        abort(403, 'Akses dibatasi. Kepala Sekolah hanya memiliki wewenang pemantauan.');
    }
    ```

---

## 3. Komponen Tampilan & Layout Dashboard Eksekutif

Tampilan dashboard berada di dalam layout standar aplikasi (sidebar kiri dan navbar atas tetap utuh), dengan konten utama berformat **Executive Grid**:

```
+-----------------------------------------------------------------------------------+
|  Header: Selamat Datang, [Nama Kepala TPQ]  |  [Hari, Tanggal Hijriyah & Masehi]  |
+-----------------------------------------------------------------------------------+
| [KPI 1: % Santri Hadir] [KPI 2: Ustadzah Hadir] [KPI 3: Kas Tabungan] [KPI 4: Dana di Guru] |
+-------------------------------------------------------+---------------------------+
| TABEL MATRIKS KELAS HARI INI (Kolom Utama ~70%)       | WIDGET USTADZAH (~30%)    |
| - Nama Kelas & Wali Kelas                             | Kesiapan Ustadzah & Inval |
| - Total Santri & Detail (Hadir/Sakit/Izin/Alfa)       | - List Guru Sakit / Izin  |
| - Persentase Kehadiran & Status (Lengkap / Belum)     | - Keterangan Alasan       |
|                                                       | - Indikator Butuh Inval   |
+-------------------------------------------------------+---------------------------+
| PANEL LAPORAN CEPAT (1-Klik Cetak):                                              |
| [ Rekap Presensi Santri ]  [ Rekap Presensi Ustadzah ]  [ Laporan Tabungan ]      |
+-----------------------------------------------------------------------------------+
```

### 3.1 Executive KPI Cards (4 Kartu)
1. **Kehadiran Santri Hari Ini:**
   - Menampilkan persentase kehadiran (misal: `95.4%`).
   - Subteks: Total hadir dibanding total santri aktif (misal: `190 / 200 Santri`).
2. **Kehadiran Ustadzah Hari Ini:**
   - Menampilkan rasio hadir (misal: `11 / 12 Hadir`).
   - Badge status: Hijau jika semua hadir, oranye/merah jika ada yang berhalangan.
3. **Kas Tabungan TPQ:**
   - Total nominal saldo bersih tabungan santri (Total Setoran − Total Tarikan).
4. **Dana di Ustadzah:**
   - Total nominal tabungan yang belum disetor oleh guru ke bendahara (`status_setoran == 'belum'`).

### 3.2 Tabel Matriks Status Kelas Realtime
- **Kolom Tabel:**
  1. *Kelas* (Nama kelas & jenjang/jilid).
  2. *Wali Kelas* (Nama ustadzah pengampu).
  3. *Total Santri*.
  4. *Rincian Kehadiran* (Hadir, Sakit, Izin, Alfa).
  5. *Persentase Kehadiran* (`Hadir / Total * 100%`).
  6. *Status Input Absensi:*
     - **Lengkap (Badge Hijau):** Jika seluruh santri di kelas tersebut sudah diabsen.
     - **Sebagian (Badge Kuning):** Jika baru sebagian santri yang diabsen.
     - **Belum Diabsen (Badge Merah/Abu-abu):** Jika belum ada input absensi sama sekali hari ini.

### 3.3 Widget Kesiapan Ustadzah & Inval Alert
- Menyorot ustadzah yang status presensinya bukan Hadir (`id_kehadiran != 1`) pada hari ini.
- Menampilkan:
  - Nama Ustadzah.
  - Status (Sakit / Izin / Cuti / Alfa).
  - Alasan / Keterangan izin.
  - Kelas yang diampu (sebagai pengingat kebutuhan guru pengganti / inval).
- Jika semua guru hadir: Tampil pesan *"Seluruh Ustadzah hadir lengkap hari ini"*.

### 3.4 Panel Pintasan Cetak Laporan
- 3 Tombol aksi cepat:
  1. **Cetak Rekap Presensi Santri Bulan Berjalan** (PDF format siap tanda tangan Kepala TPQ).
  2. **Cetak Rekap Presensi Ustadzah Bulan Berjalan**.
  3. **Cetak Laporan Keuangan & Tabungan Santri**.

---

## 4. Alur Data (Data Flow) & Logika Query

1. **Agregat Kehadiran Santri:**
   - `PresensiSiswa::where('tanggal', $today)` dikelompokkan berdasarkan `id_kehadiran`.
2. **Matriks Kelas:**
   - `Kelas::with(['guru', 'siswa'])->withCount('siswa')->get()`.
   - Mengambil data presensi santri hari ini per kelas secara efisien via relationship.
3. **Presensi Guru & Inval:**
   - `PresensiGuru::with('guru')->where('tanggal', $today)->where('id_kehadiran', '!=', 1)->get()`.
4. **Tabungan:**
   - `Tabungan::where('jenis_transaksi', 'setor')->sum('nominal') - Tabungan::where('jenis_transaksi', 'tarik')->sum('nominal')`.
   - `Tabungan::where('jenis_transaksi', 'setor')->where('status_setoran', 'belum')->sum('nominal')`.
5. **Optimasi:**
   - Eager loading untuk mencegah masalah query *N+1*.
   - Menyediakan tombol manual "Segarkan Data" serta polling berkala 60 detik (`wire:poll.60s`) agar data realtime.

---

## 5. Rencana Pengujian & Validasi

1. **Verifikasi Role & Pengalihan:**
   - Login dengan user Kepsek (`is_superadmin = 2`) -> Harus membuka antarmuka Executive Dashboard.
   - Login dengan Super Admin (`1`) -> Harus membuka antarmuka Dashboard Admin teknis.
   - Login dengan Guru (`0` dengan `id_guru`) -> Harus membuka Dashboard Guru.
2. **Verifikasi Matriks Kelas & Inval:**
   - Ubah status presensi guru -> Cek pembaruan pada Widget Ustadzah (nama, alasan, kelas).
   - Simulasikan absensi santri di salah satu kelas -> Pastikan badge status kelas berubah (Belum Diabsen -> Sebagian -> Lengkap).
3. **Verifikasi Proteksi Read-Only:**
   - Buka `/admin/siswa` dan `/admin/guru` sebagai Kepsek: tombol Tambah, Edit, Hapus, dan Reset Password tidak muncul.
   - Simulasi direct invocation method write -> Server menolak dengan kode HTTP 403.
4. **Verifikasi Cetak Laporan:**
   - Klik tombol pintasan laporan -> Dokumen preview/cetak PDF siap tanda tangan terbuka dengan data yang akurat.

---

## 6. Spec Self-Review Checklist
- [x] **Placeholder Scan:** Tidak ada kata TBD, TODO, atau instruksi yang menggantung.
- [x] **Internal Consistency:** Struktur role `is_superadmin = 2`, routing `/dashboard`, dan menu sidebar selaras dengan arsitektur saat ini.
- [x] **Scope Check:** Terfokus pada pengalaman eksekutif Kepsek (dashboard khusus + proteksi read-only master data).
- [x] **Ambiguity Check:** Perilaku read-only di level tampilan dan proteksi backend telah didefinisikan secara tegas.
