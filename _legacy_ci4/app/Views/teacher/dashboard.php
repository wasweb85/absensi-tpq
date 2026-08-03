<?= $this->extend(isset($_layout) && $_layout === 'ajax' ? 'templates/ajax_layout' : 'templates/admin_page_layout') ?>
<?= $this->section('styles') ?>

<style>

    .chart-container {

        position: relative;

        height: 300px;

        width: 100%;

    }

</style>

<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="content">

    <div class="container-fluid">

        <!-- Jadwal Mengajar Guru (Visible to all Teachers) -->





        <?php if ($isWaliKelas): ?>

            <div class="row">

                <div class="col-md-6">

                    <div class="card card-stats">

                        <div class="card-header card-header-info card-header-icon">

                            <div class="card-icon">

                                <i class="material-icons">school</i>

                            </div>

                            <p class="card-category">Kelas Anda (Wali Jilid)</p>

                            <h3 class="card-title">

                                <?= $kelas['kelas']; ?>

                            </h3>

                        </div>

                        <div class="card-footer">

                            <div class="stats">

                                <i class="material-icons">person</i> Total Siswa: <?= $summary['total_siswa']; ?>

                                <span class="mx-2">|</span>

                                <i class="material-icons text-primary">qr_code</i> <a class="text-primary" href="<?= base_url('teacher/qr'); ?>">Download QR Code Siswa</a>

                            </div>

                        </div>

                    </div>

                </div>



                <div class="col-md-6">

                    <div class="card">

                        <div class="card-header card-header-primary">

                            <h4 class="card-title"><b>Statistik Kehadiran Kelas Hari Ini</b></h4>

                            <p class="card-category"><?= date('d F Y'); ?></p>

                        </div>

                        <div class="card-body">

                            <div class="row text-center flex-nowrap">

                                <div class="col-2">

                                    <h5 class="text-success text-nowrap"><b>Hadir</b></h5>

                                    <h4 class="text-nowrap"><?= $summary['hadir_hari_ini']; ?></h4>

                                </div>

                                <div class="col-2">

                                    <h5 class="text-warning text-nowrap"><b>Sakit</b></h5>

                                    <h4 class="text-nowrap"><?= $summary['sakit_hari_ini']; ?></h4>

                                </div>

                                <div class="col-2">

                                    <h5 class="text-info text-nowrap"><b>Izin</b></h5>

                                    <h4 class="text-nowrap"><?= $summary['izin_hari_ini']; ?></h4>

                                </div>

                                <div class="col-2">

                                    <h5 class="text-danger text-nowrap"><b>Alfa</b></h5>

                                    <h4 class="text-nowrap"><?= $summary['alfa_hari_ini']; ?></h4>

                                </div>

                                <div class="col-1">

                                    <div class="border-right mx-auto h-100" style="width: 0;"></div>

                                </div>

                                <div class="col-2 col-sm-3">

                                    <h5 class="text-primary text-nowrap"><b>Total</b></h5>

                                    <h4 class="text-nowrap"><?= $summary['total_siswa']; ?></h4>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                <div class="col-md-12">

                    <div class="card">

                        <div class="card-header card-header-primary">

                            <h4 class="card-title">Jadwal Pelajaran Kelas Hari Ini (<?= $hariIni ?>)</h4>

                        </div>

                        <div class="card-body table-responsive">

                            <?php if(empty($jadwalKelasHariIni)): ?>

                                <p class="text-center py-3">Tidak ada jadwal pelajaran untuk kelas Anda hari ini.</p>

                            <?php else: ?>

                                <table class="table table-hover">

                                    <thead class="text-primary">

                                        <th>Mata Pelajaran</th>

                                        <th>Waktu</th>

                                        <th>Guru Pengajar</th>

                                    </thead>

                                    <tbody>

                                        <?php foreach($jadwalKelasHariIni as $j): ?>

                                            <tr>

                                                <td><b><?= esc($j['nama_mapel']) ?></b></td>

                                                <td><i class="material-icons text-muted" style="font-size:14px; vertical-align:middle;">schedule</i> <?= substr($j['jam_mulai'],0,5) ?> - <?= substr($j['jam_selesai'],0,5) ?></td>

                                                <td><?= esc($j['nama_guru']) ?></td>

                                            </tr>

                                        <?php endforeach; ?>

                                    </tbody>

                                </table>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>



                <div class="col-md-12">

                    <div class="card">

                        <div class="card-header card-header-info">

                            <h4 class="card-title">Tingkat Kehadiran Kelas (7 Hari Terakhir)</h4>

                            <p class="card-category">Statistik kehadiran siswa per status</p>

                        </div>

                        <div class="card-body">

                            <div class="chart-container">

                                <canvas id="kehadiranSiswaKelas"></canvas>

                            </div>

                        </div>

                        <div class="card-footer">

                            <div class="stats">

                                <i class="material-icons text-info">assessment</i> <a class="text-info" href="<?= base_url('teacher/laporan'); ?>">Download Laporan</a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        <?php else: ?>

            <div class="row">

                <div class="col-md-12">

                    <div class="card">

                        <div class="card-body">

                            <h4 class="text-center">Anda login sebagai Guru Mata Pelajaran.</h4>

                            <p class="text-center">Halaman ini menampilkan jadwal mengajar Anda di berbagai kelas.</p>

                        </div>

                    </div>

                </div>

            </div>

        <?php endif; ?>

    </div>

</div>

<?= $this->endSection() ?>



<?= $this->section('scripts') ?>

<?php if ($isWaliKelas): ?>

    <!-- Chart.js -->

    <script src="<?= base_url('assets/js/plugins/chartjs/chart.umd.min.js') ?>"></script>

    <script>

        const chartLabels = <?= json_encode($dateRange) ?>;

        const chartColors = {
            hadir: { border: '#4caf50', bg: 'rgba(76, 175, 80, 0.2)' },
            sakit: { border: '#ff9800', bg: 'rgba(255, 152, 0, 1)' },
            izin: { border: '#00bcd4', bg: 'rgba(0, 188, 212, 1)' },
            alfa: { border: '#f44336', bg: 'rgba(244, 67, 54, 1)' }
        };

        function initTeacherCharts() {
            const ctx = document.getElementById('kehadiranSiswaKelas');
            if (ctx) {
                const data = {
                    hadir: <?= json_encode($grafikKehadiran['hadir']) ?>,
                    sakit: <?= json_encode($grafikKehadiran['sakit']) ?>,
                    izin: <?= json_encode($grafikKehadiran['izin']) ?>,
                    alfa: <?= json_encode($grafikKehadiran['alfa']) ?>
                };

                const totalData = data.hadir.map((h, i) => h + data.sakit[i] + data.izin[i] + data.alfa[i]);
                const percentageData = data.hadir.map((h, i) => totalData[i] > 0 ? Math.round((h / totalData[i]) * 100) : 0);

                const ctx2d = ctx.getContext('2d');
                const gradient = ctx2d.createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, 'rgba(76, 175, 80, 0.6)');
                gradient.addColorStop(1, 'rgba(76, 175, 80, 0.0)');

                const dataLabelsPlugin = {
                    id: 'dataLabels',
                    afterDatasetsDraw: function(chart) {
                        const ctx = chart.ctx;
                        const dataset = chart.data.datasets[0];
                        const meta = chart.getDatasetMeta(0);
                        if (!meta.hidden) {
                            meta.data.forEach((element, index) => {
                                ctx.fillStyle = '#333';
                                ctx.font = "bold 12px Arial";
                                const dataString = dataset.data[index] + '%';
                                ctx.textAlign = 'center';
                                ctx.textBaseline = 'middle';
                                const padding = 15;
                                ctx.fillText(dataString, element.x, element.y - padding);
                            });
                        }
                    }
                };

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: chartLabels,
                        datasets: [
                            {
                                label: 'Hadir',
                                data: percentageData,
                                borderColor: chartColors.hadir.border,
                                backgroundColor: gradient,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: chartColors.hadir.border,
                                pointRadius: 5,
                                pointHoverRadius: 7,
                                borderWidth: 3
                            },
                            {
                                label: 'Sakit',
                                data: percentageData,
                                borderColor: chartColors.sakit.border,
                                backgroundColor: chartColors.sakit.border,
                                pointRadius: 0,
                                pointHoverRadius: 0,
                                fill: false,
                                borderWidth: 0
                            },
                            {
                                label: 'Izin',
                                data: percentageData,
                                borderColor: chartColors.izin.border,
                                backgroundColor: chartColors.izin.border,
                                pointRadius: 0,
                                pointHoverRadius: 0,
                                fill: false,
                                borderWidth: 0
                            },
                            {
                                label: 'Alfa',
                                data: percentageData,
                                borderColor: chartColors.alfa.border,
                                backgroundColor: chartColors.alfa.border,
                                pointRadius: 0,
                                pointHoverRadius: 0,
                                fill: false,
                                borderWidth: 0
                            }
                        ]
                    },
                    plugins: [dataLabelsPlugin],
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20
                                }
                            },
                            tooltip: {
                                enabled: true,
                                backgroundColor: 'rgba(33, 33, 33, 0.9)',
                                titleFont: { size: 14 },
                                bodyFont: { size: 14 },
                                padding: 12,
                                cornerRadius: 8,
                                displayColors: true,
                                boxWidth: 12,
                                boxHeight: 12,
                                callbacks: {
                                    title: function() {
                                        return ''; 
                                    },
                                    label: function (context) {
                                        const i = context.dataIndex;
                                        if (context.datasetIndex === 0) return `Hadir: ${data.hadir[i]} orang`;
                                        if (context.datasetIndex === 1) return `Sakit: ${data.sakit[i]} orang`;
                                        if (context.datasetIndex === 2) return `Izin: ${data.izin[i]} orang`;
                                        if (context.datasetIndex === 3) return `Alfa: ${data.alfa[i]} orang`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                min: 0,
                                max: 100,
                                ticks: {
                                    stepSize: 20,
                                    callback: function (value) {
                                        return value + '%';
                                    }
                                },
                                grid: { color: 'rgba(0, 0, 0, 0.05)' }
                            },
                            x: {
                                grid: { display: false }
                            }
                        }
                    }
                });
            }
        }

        $(document).ready(function () {
            initTeacherCharts();
        });
    </script>

<?php endif; ?>

<?= $this->endSection() ?>
