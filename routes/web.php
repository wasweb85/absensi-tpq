<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect('/login');
});

Route::get('/scan', \App\Livewire\ScanIndex::class);

// Siswa Portal Routes (Custom Session Auth)
Route::get('/login-siswa', [\App\Http\Controllers\Siswa\AuthController::class, 'index'])->name('siswa.login');
Route::post('/login-siswa', [\App\Http\Controllers\Siswa\AuthController::class, 'login']);
Route::get('/siswa/logout', [\App\Http\Controllers\Siswa\AuthController::class, 'logout'])->name('siswa.logout');

Route::middleware(['is_siswa'])->prefix('siswa')->group(function () {
    Route::get('/dashboard', \App\Livewire\Siswa\DashboardIndex::class)->name('siswa.dashboard');
    Route::get('/jadwal', \App\Livewire\Siswa\JadwalIndex::class)->name('siswa.jadwal');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::post('admin/dashboard/filter-data', [\App\Http\Controllers\Admin\DashboardController::class, 'filterData'])->name('admin.dashboard.filter-data');

    Route::get('/manual-attendance', App\Livewire\Teacher\ManualAttendance::class)->name('manual.attendance')->middleware('is_guru');

    // Admin Routes
    Route::prefix('admin')->group(function () {
        Route::get('siswa', \App\Livewire\Admin\SiswaIndex::class);
        Route::get('guru', \App\Livewire\Admin\GuruIndex::class);
        Route::get('kelas', \App\Livewire\Admin\KelasIndex::class);
        Route::get('mapel', \App\Livewire\Admin\MapelIndex::class);
        Route::get('jadwal-pelajaran', \App\Livewire\Admin\JadwalIndex::class);
        Route::get('seragam', \App\Livewire\Admin\SeragamIndex::class);
        Route::get('absen-guru', \App\Livewire\Admin\AbsenGuruIndex::class);
        Route::get('absen-siswa', \App\Livewire\Admin\AbsenSiswaIndex::class);
        Route::get('petugas', \App\Livewire\Admin\PetugasIndex::class);
        Route::get('generate', function () { return redirect('/admin/qr'); });
        Route::get('laporan', \App\Livewire\Admin\GenerateLaporanIndex::class)->name('admin.laporan.index');
        Route::get('laporan/siswa', [\App\Http\Controllers\Admin\ReportController::class, 'generateLaporanSiswa'])->name('admin.laporan.siswa');
        Route::get('laporan/guru', [\App\Http\Controllers\Admin\ReportController::class, 'generateLaporanGuru'])->name('admin.laporan.guru');
        Route::get('qr', \App\Livewire\Admin\GenerateQrIndex::class);
        Route::get('qr/siswa', [\App\Http\Controllers\Admin\QrController::class, 'downloadSiswa'])->name('admin.qr.siswa');
        Route::get('qr/guru', [\App\Http\Controllers\Admin\QrController::class, 'downloadGuru'])->name('admin.qr.guru');
        Route::get('backup', [\App\Http\Controllers\Admin\BackupController::class, 'index']);
        Route::get('backup/db/backup', [\App\Http\Controllers\Admin\BackupController::class, 'dbBackup'])->name('admin.backup.db');
        Route::post('backup/db/restore', [\App\Http\Controllers\Admin\BackupController::class, 'dbRestore'])->name('admin.backup.db.restore');
        Route::get('backup/photos/backup', [\App\Http\Controllers\Admin\BackupController::class, 'photosBackup'])->name('admin.backup.photos');
        Route::post('backup/photos/restore', [\App\Http\Controllers\Admin\BackupController::class, 'photosRestore'])->name('admin.backup.photos.restore');
        Route::get('general-settings', \App\Livewire\Admin\GeneralSettingsIndex::class);
    });

    // Teacher Routes
    Route::prefix('teacher')->group(function () {
        Route::get('dashboard', \App\Livewire\Teacher\Dashboard::class)->name('teacher.dashboard');
        Route::get('laporan', \App\Livewire\Teacher\GenerateLaporanIndex::class)->name('teacher.laporan');
        Route::get('siswa', \App\Livewire\Teacher\SiswaIndex::class)->name('teacher.siswa');
        Route::get('jadwal', \App\Livewire\Teacher\JadwalIndex::class)->name('teacher.jadwal');
        Route::get('qr', \App\Livewire\Teacher\GenerateQrIndex::class)->name('teacher.qr');
    });
});
