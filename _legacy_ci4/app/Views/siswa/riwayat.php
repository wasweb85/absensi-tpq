<?= $this->extend('templates/layout_siswa_modern'); ?>
<?= $this->section('content'); ?>



<section class="section-content">
    <p class="splash-label">INFORMASI AKADEMIK</p>
    <h1 class="page-title">Riwayat<br>Kehadiran Saya</h1>
    <p class="page-subtitle">Menampilkan rekapitulasi kehadiran dan kedisiplinan Anda selama bulan ini.</p>

    <!-- Summary Stats -->
    <div class="summary-stats-row">
        <div class="stat-box">
            <span class="val val-hadir"><?= $summary['hadir'] ?></span>
            <span class="lab">Total Hadir</span>
        </div>
        <div class="stat-box">
            <span class="val val-sakit"><?= $summary['sakit'] ?></span>
            <span class="lab">Total Sakit</span>
        </div>
        <div class="stat-box">
            <span class="val val-izin"><?= $summary['izin'] ?></span>
            <span class="lab">Total Izin</span>
        </div>
        <div class="stat-box">
            <span class="val val-alpha"><?= $summary['alpha'] ?></span>
            <span class="lab">Total Alpha</span>
        </div>
    </div>

    <!-- Calendar Card -->
    <div class="modern-calendar-card">
        <div class="calendar-header">
            <div class="calendar-month">
                <i class="material-icons" style="color: #0c4a6e;">calendar_today</i>
                <span><?= $currentMonth ?></span>
            </div>
            <?php 
            $prevM = $m - 1; $prevY = $y;
            if ($prevM < 1) { $prevM = 12; $prevY--; }
            $nextM = $m + 1; $nextY = $y;
            if ($nextM > 12) { $nextM = 1; $nextY++; }
            ?>
            <div style="display: flex; gap: 15px;">
                <a href="<?= base_url("siswa/riwayat?m=$prevM&y=$prevY&status=$filterStatus") ?>" style="text-decoration: none;">
                    <i class="material-icons" style="color: #94a3b8; font-size: 20px; cursor: pointer;">chevron_left</i>
                </a>
                <a href="<?= base_url("siswa/riwayat?m=$nextM&y=$nextY&status=$filterStatus") ?>" style="text-decoration: none;">
                    <i class="material-icons" style="color: #94a3b8; font-size: 20px; cursor: pointer;">chevron_right</i>
                </a>
            </div>
        </div>

        <div class="calendar-grid">
            <div class="cal-weekday">Sen</div>
            <div class="cal-weekday">Sel</div>
            <div class="cal-weekday">Rab</div>
            <div class="cal-weekday">Kam</div>
            <div class="cal-weekday">Jum</div>
            <div class="cal-weekday">Sab</div>
            <div class="cal-weekday">Min</div>

            <?php 
            // Filler for start day
            for ($i = 1; $i < $startDay; $i++): ?>
                <div class="cal-day muted"></div>
            <?php endfor; ?>

            <?php for ($d = 1; $d <= $daysInMonth; $d++): 
                $status = $calendar[$d] ?? null;
                $class = '';
                if ($status == 1) $class = 'has-hadir';
                elseif ($status == 2 || $status == 3) $class = 'has-izin';
                elseif ($status == 4) $class = 'has-alpha';
                
                if ($d == (int)date('j')) $class .= ' today';
            ?>
                <div class="cal-day <?= $class ?>"><?= $d ?></div>
            <?php endfor; ?>
        </div>
    </div>

    <!-- Status Kehadiran Rate -->
    <div class="attendance-rate-card">
        <span class="card-label" style="color: #99f6e4; font-size: 10px;">STATUS KEHADIRAN</span>
        <div class="rate-val"><?= $attendanceRate ?>%</div>
        <span class="rate-label">Kehadiran aktual bulan ini</span>
    </div>

    <!-- Analisis Status -->
    <div class="analysis-card">
        <h3 style="font-size: 11px; font-weight: 800; color: #0c4a6e; text-transform: uppercase; margin-bottom: 20px;">Analisis Status</h3>
        
        <div class="analysis-item">
            <div class="analysis-info">
                <span>Hadir</span>
                <span><?= $stats['hadir'] ?>%</span>
            </div>
            <div class="progress-bar-wrap">
                <div class="progress-bar-fill fill-hadir" style="width: <?= $stats['hadir'] ?>%;"></div>
            </div>
        </div>

        <div class="analysis-item">
            <div class="analysis-info">
                <span>Izin & Sakit</span>
                <span><?= $stats['izin'] ?>%</span>
            </div>
            <div class="progress-bar-wrap">
                <div class="progress-bar-fill fill-izin" style="width: <?= $stats['izin'] ?>%;"></div>
            </div>
        </div>

        <div class="analysis-item">
            <div class="analysis-info">
                <span>Alpha</span>
                <span><?= $stats['alpha'] ?>%</span>
            </div>
            <div class="progress-bar-wrap">
                <div class="progress-bar-fill fill-alpha" style="width: <?= $stats['alpha'] ?>%;"></div>
            </div>
        </div>
    </div>

    <!-- Filter Status -->
    <div class="section-title-wrap" style="margin-top: 40px;">
        <h2 class="section-title" style="font-size: 24px;">Log Aktivitas<br>Terbaru</h2>
        <div class="filter-dropdown">
            <form action="" method="get" id="filterForm">
                <input type="hidden" name="m" value="<?= $m ?>">
                <input type="hidden" name="y" value="<?= $y ?>">
                <select name="status" class="form-control-sm" style="border: none; background: transparent; font-weight: 700; color: #0d9488;" onchange="document.getElementById('filterForm').submit()">
                    <option value="all">Semua Status</option>
                    <option value="1" <?= $filterStatus == '1' ? 'selected' : '' ?>>Hadir</option>
                    <option value="2" <?= $filterStatus == '2' ? 'selected' : '' ?>>Sakit</option>
                    <option value="3" <?= $filterStatus == '3' ? 'selected' : '' ?>>Izin</option>
                    <option value="4" <?= $filterStatus == '4' ? 'selected' : '' ?>>Alpha</option>
                </select>
            </form>
        </div>
    </div>

    <div class="log-list" style="margin-bottom: 100px;">
        <?php foreach ($riwayat as $r): 
            $icon = 'check';
            $iClass = 'icon-hadir';
            $bClass = 'badge-hadir';
            if ($r['id_kehadiran'] == 2 || $r['id_kehadiran'] == 3) {
                $icon = 'email';
                $iClass = 'icon-izin';
                $bClass = 'badge-izin';
            } elseif ($r['id_kehadiran'] == 4) {
                $icon = 'close';
                $iClass = 'icon-alpha';
                $bClass = 'badge-alpha';
            }
        ?>
            <div class="log-item-modern">
                <div class="log-icon-wrap <?= $iClass ?>">
                    <i class="material-icons"><?= $icon ?></i>
                </div>
                <div class="log-details">
                    <h4 class="log-title">Sesi Pembelajaran Mandiri</h4>
                    <div class="log-meta">
                        <span><i class="material-icons" style="font-size: 12px; vertical-align: middle;">schedule</i> <?= $r['jam_masuk'] ?? '--:--' ?></span>
                        <span><i class="material-icons" style="font-size: 12px; vertical-align: middle;">location_on</i> Area Kampus</span>
                    </div>
                </div>
                <div style="text-align: right;">
                    <span class="log-badge <?= $bClass ?>"><?= $r['nama_kehadiran'] ?></span>
                    <div style="font-size: 10px; color: #94a3b8; margin-top: 4px; font-weight: 700;"><?= date('d M Y', strtotime($r['tanggal'])) ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</section>

<?= $this->endSection(); ?>
