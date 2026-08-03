<?= $this->extend('templates/layout_siswa_modern'); ?>
<?= $this->section('content'); ?>

<?php
// Get current day for schedule filtering
$days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$currentDay = $days[date('w')];
// For testing/mocking if today is Sunday, show Monday as default
if ($currentDay == 'Minggu') $currentDay = 'Senin';
?>

<!-- Splash Area -->
<section class="splash-area">
    <p class="splash-label">DASHBOARD UTAMA</p>
    <h1 class="splash-welcome">Selamat Datang, <?= $user ?></h1>
    <div class="splash-class">
        <span class="dot-point"></span>
        <span>Jilid: <?= $kelasInfo->kelas ?? '-' ?></span>
    </div>
    <div class="splash-class" style="margin-top: 4px;">
        <span class="dot-point" style="background-color: #0d9488;"></span>
        <span>Wali Jilid: <?= $kelasInfo->nama_wali_kelas ?? '-' ?></span>
    </div>
    <div class="splash-class" style="margin-top: 4px; opacity: 0.8; font-size: 0.85em;">
        <i class="material-icons" style="font-size: 14px; margin-right: 4px;">calendar_today</i>
        <span>Tahun Ajaran: <?= $generalSettings->school_year ?? '-' ?></span>
    </div>
</section>

<!-- Ringkasan Kehadiran -->
<section class="section-top">
    <div class="section-title-wrap">
        <h2 class="section-title">Ringkasan Kehadiran</h2>
        <form action="" method="get" id="filterFormWaktu">
            <select name="waktu" class="form-control-sm" style="border: none; background: transparent; font-weight: 700; color: #0d9488; font-size: 11px;" onchange="document.getElementById('filterFormWaktu').submit()">
                <option value="bulan" <?= $filterWaktu == 'bulan' ? 'selected' : '' ?>>Bulan Ini</option>
                <option value="minggu" <?= $filterWaktu == 'minggu' ? 'selected' : '' ?>>Minggu Ini</option>
            </select>
        </form>
    </div>

    <div class="attendance-grid">
        <div class="card-hadir-large">
            <span class="card-label">TOTAL HADIR</span>
            <span class="card-value-large"><?= $summary['hadir'] ?? 0 ?></span>
        </div>

        <div class="attendance-mini-grid">
            <div class="card-mini">
                <span class="card-label">SAKIT</span>
                <span class="card-value-mini"><?= $summary['sakit'] ?? 0 ?></span>
            </div>
            <div class="card-mini">
                <span class="card-label">IZIN</span>
                <span class="card-value-mini"><?= $summary['izin'] ?? 0 ?></span>
            </div>
        </div>

        <div class="card-alpha">
            <div>
                <span class="card-label">ALPHA</span>
                <span class="card-value-large"><?= $summary['alpha'] ?? 0 ?></span>
            </div>
            <div class="alpha-icon-wrap">
                <i class="material-icons">priority_high</i>
            </div>
        </div>
    </div>
</section>



<!-- Jadwal Hari Ini -->
<section class="section-top">
    <div class="section-title-wrap">
        <h2 class="section-title">Jadwal Hari Ini</h2>
        <span class="badge badge-light px-3 py-2" style="border-radius: 12px; font-weight: 800; font-size: 10px; color: #64748b; background: #e2e8f0;"><?= strtoupper($currentDay) ?></span>
    </div>

    <div class="schedule-list">
        <?php if (empty($jadwal[$currentDay])): ?>
            <p class="text-muted text-center py-3">Tidak ada jadwal hari ini.</p>
        <?php else: ?>
            <?php foreach ($jadwal[$currentDay] as $j): ?>
                <div class="schedule-card">
                    <div class="schedule-info">
                        <div class="schedule-time">
                            <i class="material-icons" style="font-size: 14px;">schedule</i>
                            <span><?= substr($j['jam_mulai'], 0, 5) ?> - <?= substr($j['jam_selesai'], 0, 5) ?></span>
                        </div>
                    </div>
                    <div class="schedule-icon">
                        <i class="material-icons">menu_book</i>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<!-- Riwayat Terbaru -->
<section class="section-top">
    <div class="section-title-wrap">
        <h2 class="section-title">Riwayat Terbaru</h2>
    </div>

    <div class="history-list">
        <?php if (empty($riwayat)): ?>
            <p class="text-center py-3 text-muted">Belum ada riwayat.</p>
        <?php else: ?>
            <?php 
            $count = 0;
            foreach ($riwayat as $r): 
                if ($count >= 3) break; // Only show 3 latest
            ?>
                <div class="history-item">
                    <div class="history-status-icon">
                        <i class="material-icons" style="font-size: 20px;">check_circle</i>
                    </div>
                    <div class="history-info">
                        <span class="history-status-txt"><?= $r['nama_kehadiran'] ?></span>
                        <span class="history-date"><?= date('l, d M Y', strtotime($r['tanggal'])) ?></span>
                    </div>
                    <div class="history-badge">VERIFIED</div>
                </div>
            <?php 
                $count++;
            endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<?= $this->endSection(); ?>
