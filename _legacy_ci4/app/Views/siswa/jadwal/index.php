<?= $this->extend('templates/layout_siswa_modern'); ?>
<?= $this->section('content'); ?>



<section class="section-content">
    
    <div class="header-tags">
        <span class="h-tag h-tag-primary">ID: <?= $nis ?></span>
        <span class="h-tag h-tag-secondary">JILID <?= $kelasInfo->kelas ?></span>
    </div>

    <h1 class="page-title">Jadwal Pelajaran &<br>Ketentuan Seragam</h1>
    <p class="page-subtitle">Tahun Ajaran <?= $tahun_ajaran ?> • Semester <?= $semester ?></p>

    <div class="schedule-grid-modern">
        <?php foreach ($hariList as $hari): ?>
            <?php 
            // Simple uniform matching logic (customizable)
            $s = $seragam[$hari] ?? null;
            $nama_seragam = $s ? $s['nama_seragam'] : 'Batik';
            $is_batik = stripos($nama_seragam, 'Batik') !== false;
            ?>
            <div class="day-section">
                <div class="day-header">
                    <h2 class="day-name"><?= strtoupper($hari) ?></h2>
                    <div class="uniform-badge <?= $is_batik ? 'uniform-badge-active' : '' ?>">
                        <i class="material-icons" style="font-size: 14px;">checkroom</i>
                        <span>Seragam: <?= $nama_seragam ?></span>
                    </div>
                </div>

                <div class="schedule-card-modern">
                    <?php 
                    $found = false;
                    foreach ($slots as $slot): 
                        if (isset($grid[$slot][$hari])): 
                            $j = $grid[$slot][$hari];
                            $found = true;
                            // Pick icon based on subject
                            $icon = 'book';
                            if (stripos($j['nama_mapel'], 'Matematika') !== false) $icon = 'calculate';
                    ?>
                        <div class="modern-subject-item">
                            <div class="subject-icon-circle">
                                <i class="material-icons"><?= $icon ?></i>
                            </div>
                            <div class="subject-details">
                                <h4><?= $j['nama_mapel'] ?></h4>
                                <div class="subject-time">
                                    <i class="material-icons" style="font-size: 14px;">schedule</i>
                                    <span><?= $slot ?></span>
                                </div>
                            </div>
                        </div>
                    <?php 
                        endif;
                    endforeach; 
                    if (!$found): ?>
                        <p class="text-muted small pl-2 py-2">Tidak ada pelajaran.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?= $this->endSection(); ?>
