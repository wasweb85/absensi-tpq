# Spesifikasi Desain: Fitur Kalender & Agenda Terpadu (Kanvas Kaca Modern)

## Ringkasan Proyek
Fitur **Kalender & Agenda** menyediakan sistem pengelolaan agenda kegiatan akademik, hari libur pondok/nasional, serta pemantauan jadwal belajar secara terpadu untuk lingkungan TPQ (Taman Pendidikan Al-Qur'an). Antarmuka mengusung konsep visual **Kanvas Kaca Modern (*Glassmorphism*)** yang diadaptasi langsung dari referensi portal universitas berbasis pesantren (SSO UNUJA).

Fitur ini mendukung:
- **Tampilan Dwi-Mode**: Mode **Bulanan** (grid kalender interaktif dengan tanggal Masehi & Hijriah berdampingan) dan Mode **Tahunan** (ringkasan 12 bulan mini).
- **Pengaturan Hari Libur Mingguan & Khusus**: Konfigurasi dinamis hari libur rutin (Jumat / Ahad) dan hari libur kegiatan TPQ.
- **Integrasi Presensi & Laporan**: Status hari libur otomatis diakui oleh mesin presensi dan rekap laporan bulanan tanpa alpa keliru.
- **Sentralisasi Hak Akses**: Pengelolaan penuh (CRUD) oleh Admin/Petugas, sedangkan Guru dan Santri/Wali Santri memiliki akses melihat (*view-only*) di portal masing-masing.

---

## 1. Arsitektur & Teknologi

* **Framework**: Laravel 13.x
* **Komponen Dinamis**: Livewire 3.6+
* **Interaktivitas Antarmuka**: Alpine.js
* **Desain & Styling**: Tailwind CSS (Glassmorphism: `backdrop-blur-md`, subtle translucent borders, gradients)
* **Penanggalan**:
  - Masehi: `Carbon\Carbon`
  - Hijriah: Konverter Algoritma Kalender Islam / `IntlDateFormatter` (menampilkan tanggal dan nama bulan ringkas, misal `18 Rob`)

---

## 2. Struktur Data & Database

### 2.1. Tabel Baru: `tb_agenda_kalender`
Tabel ini mencatat seluruh agenda, kegiatan akademik, dan hari libur resmi TPQ.

| Kolom | Tipe Data | Nullable | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | bigIncrements | Tidak | Primary Key |
| `judul` | varchar(255) | Tidak | Judul kegiatan/agenda (contoh: *Imtihan Semester Ganjil*, *Libur Awal Ramadhan*) |
| `deskripsi` | text | Ya | Rincian informasi agenda |
| `tanggal_mulai` | date | Tidak | Tanggal awal kegiatan (`YYYY-MM-DD`) |
| `tanggal_selesai` | date | Ya | Tanggal akhir jika kegiatan lebih dari 1 hari |
| `kategori` | enum/varchar(50)| Tidak | Kategori kegiatan: `'umum'`, `'akademik'`, `'penting'`, `'tugas'` |
| `warna` | varchar(30) | Ya | Kode hex atau kelas warna tema badge (`emerald`, `indigo`, `purple`, `amber`, `rose`) |
| `is_libur` | boolean | Tidak | Default `false`. Jika `true`, rentang tanggal ini diakui sebagai **Hari Libur TPQ** |
| `created_by` | unsignedBigInteger | Ya | Foreign key ke `users.id` penginput data |
| `created_at` | timestamp | Ya | Tanggal pembuatan |
| `updated_at` | timestamp | Ya | Tanggal perubahan |

**Indeks:**
- `index(['tanggal_mulai', 'tanggal_selesai'])`
- `index('kategori')`
- `index('is_libur')`

### 2.2. Perubahan Tabel `general_settings`
Menambahkan pengaturan hari libur rutin mingguan TPQ.
- `hari_libur_mingguan`: varchar(20), default `'jumat'` (pilihan: `'jumat'`, `'ahad'`, dsb.).

---

## 3. Komponen Livewire & Logika Backend

### 3.1. File Komponen: `App\Livewire\Admin\KalenderAgendaIndex`
Komponen Livewire tunggal yang cerdas dan efisien untuk menyajikan kalender:

#### Properti Reaktif:
* `public $viewMode = 'bulanan';` // Pilihan: 'bulanan' | 'tahunan'
* `public $selectedYear;` // Tahun aktif (default: tahun berjalan, misal 2026)
* `public $selectedMonth;` // Bulan aktif (default: bulan berjalan, 1-12)
* `public $selectedCategory = 'semua';` // 'semua' | 'umum' | 'akademik' | 'penting' | 'tugas'
* `public $searchQuery = '';` // Pencarian teks judul/keterangan
* `public $showLiburPekan = true;` // Sakelar toggle "JUMAT & LIBUR OFF/ON"
* `public $isManageable = false;` // Menentukan apakah user memiliki wewenang CRUD

#### Form Input (Modal Admin):
* `public $agendaId = null;`
* `public $judul, $deskripsi, $tanggal_mulai, $tanggal_selesai, $kategori = 'umum', $is_libur = false, $warna = '#10b981';`
* `public $isModalOpen = false;`

#### Metode Utama:
1. `mount($mode = null)`: Inisialisasi bulan, tahun, serta memeriksa hak akses pengguna saat ini.
2. `changeViewMode($mode)`: Beralih mode tampilan antara `'bulanan'` dan `'tahunan'`.
3. `prevMonth()`, `nextMonth()`, `prevYear()`, `nextYear()`: Navigasi waktu.
4. `jumpToMonth($month)`: Berpindah dari kartu tahunan langsung ke bulan yang dipilih.
5. `saveAgenda()`: Validasi dan simpan agenda baru atau update data yang ada.
6. `deleteAgenda($id)`: Menghapus data agenda.
7. `openCreateModal($date = null)`: Membuka modal tambah data, otomatis terisi tanggal jika user mengklik kotak tanggal pada kalender.

### 3.2. Layanan Penanggalan Hijriah (`App\Helpers\HijriHelper`)
Menyediakan konversi tanggal Masehi ke Hijriah secara akurat:
- Input: Objek `Carbon` (contoh: `2026-09-04`)
- Output: Format ringkas `18 Rob` (18 Rabi'ul Awwal)
- Mendukung penyesuaian koreksi hilal jika dibutuhkan.

### 3.3. Perhitungan Statistik Bulan Ini (Live Stats)
Dihitung secara reaktif untuk bulan aktif:
1. **Hari Kerja**: Jumlah hari aktif santri (total hari pada bulan berjalan dikurangi hari libur mingguan dan tanggal dengan `is_libur = true`).
2. **Hari Libur**: Total hari libur mingguan (contoh: 4 hari Jumat) ditambah seluruh tanggal berstatus `is_libur = true`.
3. **Agenda**: Total kegiatan terjadwal non-libur yang ada pada bulan tersebut.

---

## 4. Desain Antarmuka Pengguna (*Glassmorphism Canvas UI*)

### 4.1. Header Banner Ungu Kaca (Hero Section)
* Gradien ungu bercahaya (*vibrant purple gradient* `from-indigo-600 via-purple-600 to-fuchsia-600`) dengan sudut lengkung lembut `rounded-3xl` dan bayangan halus.
* Ikon kalender transparan kaca (*frosted glass*), judul **Kalender & Agenda**, subjudul terarah, dan tombol kapsul lonjong `[ BULANAN | TAHUNAN ]` di pojok kanan atas.

### 4.2. Bilah Filter & Pencarian (Search & Filter Bar)
* Kartu melayang transparan (*glass card*) berisi:
  - Input pencarian cepat di sebelah kiri dengan ikon *search*.
  - Lencana filter kategori berbentuk pil:
    - **Semua** (latar gelap solid)
    - **• Umum** (titik biru)
    - **• Akademik** (titik hijau zamrud)
    - **• Penting** (titik ungu)
    - **• Tugas** (titik oranye)
  - Tombol **+ Tambah Agenda** (khusus Admin/Petugas).

### 4.3. Area Kalender Utama (Mode Bulanan & Tahunan)
* **Periode Akademik Aktif (Top Card):**
  - Menampilkan lencana hijau kegiatan utama yang sedang aktif (contoh: *Ospektren Madarma*, *Perkuliahan/Pembelajaran Semester Ganjil*).
  - Tombol toggle: `JUMAT & LIBUR OFF / ON`.
* **Grid Kalender Bulanan:**
  - 7 Kolom hari: `MIN`, `SEN`, `SEL`, `RAB`, `KAM`, `JUM LIBUR`, `SAB`.
  - Kotak tanggal berbingkai kaca halus:
    - Angka Masehi tebal di sudut kiri atas. Tanggal hari ini diberi penanda lingkaran ungu terang.
    - Angka Hijriah ringkas di sudut kanan atas (misal: `18 Rob`).
    - Kolom Jumat otomatis memiliki latar merah muda lembut (*soft rose*) dengan lencana *Libur Jumat*.
    - Pil agenda warna-warni yang dapat diklik untuk melihat informasi lengkap.
* **Mode Kalender Tahunan:**
  - 12 Kartu mini bulan (`Januari` s/d `Desember`).
  - Mengklik judul bulan mini langsung membawa pengguna ke tampilan bulan tersebut.
  - Tanggal libur mingguan dan kegiatan disorot dengan titik/aksen warna.

### 4.4. Panel Kanan: Statistik & Timeline
* **Statistik Bulan Ini:**
  - Dilengkapi lencana hijau berkedip `● LIVE` dan subjudul *"Ringkasan otomatis"*.
  - 3 Kotak metrik: **Hari Kerja** (biru), **Hari Libur** (merah), **Agenda** (ungu).
* **Agenda & Libur Timeline:**
  - Daftar kronologis agenda bulan berjalan.
  - Menampilkan tanggal & hari di sebelah kiri (misal: `4 JUM`), nama kegiatan dan badge kategori di sebelah kanan.
  - Tombol aksi cepat Edit dan Hapus bagi Admin.

### 4.5. Responsivitas Layar
* **Desktop (≥ 1024px):** Grid 2 kolom seimbang (Kalender 70%, Statistik & Timeline 30%).
* **Tablet (768px – 1023px):** Kalender lebar penuh di atas, 3 kartu statistik tersusun horizontal di bawah, diikuti timeline.
* **Mobile (< 768px):** 
  - Kategori filter dapat digeser horizontal (*horizontal scroll*).
  - Kotak kalender responsif proporsional. Mengetuk tanggal memunculkan *modal/bottom-sheet* rincian agenda.
  - Kartu tahunan mengalir menjadi 1 atau 2 kolom yang nyaman dibaca.

---

## 5. Integrasi Sistem & Hak Akses

### 5.1. Pemetaan Rute (*Web Routes*)
* **Admin / Petugas:**
  - Rute: `GET /admin/kalender-agenda`
  - Komponen: `App\Livewire\Admin\KalenderAgendaIndex`
  - Akses: Penuh (Lihat, Tambah, Edit, Hapus).
* **Guru / Wali Kelas:**
  - Rute: `GET /teacher/kalender-agenda`
  - Akses: *View-Only* (Tanpa tombol modifikasi).
* **Santri / Wali Santri:**
  - Rute: `GET /siswa/kalender`
  - Akses: *View-Only* di portal santri mobile-friendly.

### 5.2. Penempatan Menu Navigasi
* **Sidebar Admin:** Menu "Kalender & Agenda" dengan ikon `calendar_month`.
* **Sidebar Guru:** Menu "Kalender & Agenda" dengan ikon `calendar_month`.
* **Bottom Nav Santri:** Menambahkan item "Kalender" di samping "Beranda" dan "Jadwal".

### 5.3. Integrasi Logika Hari Libur dengan Mesin Presensi
* Helper terpusat `AgendaKalender::isTanggalLibur($date)`:
  - Mengembalikan `true` jika hari tersebut adalah hari libur mingguan (contoh: Jumat) ATAU bertepatan dengan agenda `is_libur = true`.
* **Laporan Presensi (`ReportController.php`):**
  - Menggantikan kode hari libur yang sebelumnya *hardcoded* (`Tue` & `Fri`) dengan pengecekan dinamis `AgendaKalender::isTanggalLibur($date)`.
  - Hari libur tidak dihitung sebagai Alpa santri/guru dalam rekap kehadiran.
* **Mesin Scan QR (`ScanIndex.php`):**
  - Opsional memberikan pemberitahuan informatif jika kegiatan presensi dilakukan pada hari libur resmi TPQ.

---

## 6. Verifikasi & Pengujian

1. **Pengujian Fungsionalitas Kalender:**
   - Navigasi bulan dan tahun berfungsi presisi (perpindahan tahun kabisat, jumlah hari 28-31 hari).
   - Penomoran tanggal Hijriah tampil sinkron di samping tanggal Masehi.
   - Pergantian mode *Bulanan* ke *Tahunan* dan sebaliknya berlangsung mulus dan reaktif.
2. **Pengujian CRUD & Filter Agenda:**
   - Menambah agenda 1 hari dan multi-hari berhasil tersimpan dan tampil di tanggal terkait.
   - Filter kategori (*Umum, Akademik, Penting, Tugas*) memfilter agenda secara akurat.
   - Pencarian judul agenda menyaring data secara instan.
3. **Pengujian Hak Akses:**
   - Admin & Staf Petugas dapat menambah, mengubah, dan menghapus agenda.
   - Guru dan Santri hanya dapat melihat tanpa opsi modifikasi.
4. **Pengujian Integrasi Hari Libur:**
   - Tanggal yang ditandai sebagai libur otomatis diakui pada kalkulasi statistik dan laporan presensi.
5. **Pengujian Responsivitas:**
   - Uji tampilan pada resolusi desktop, tablet, dan smartphone (tanpa overflow atau layout pecah).
