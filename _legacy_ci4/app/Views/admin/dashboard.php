<?= $this->extend(isset($_layout) && $_layout === 'ajax' ? 'templates/ajax_layout' : 'templates/admin_page_layout') ?>
<?= $this->section('styles') ?>

<style>

    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }

    /* Modern Stat Cards */
    .modern-stat-card {
        border: none !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
        margin-top: 15px !important;
        transition: transform 0.2s ease;
    }

    .modern-stat-card:hover {
        transform: translateY(-2px);
    }

    .modern-stat-card .card-body {
        padding: 20px !important;
    }

    .stat-flex {
        display: flex;
        align-items: center;
        width: 100%;
    }

    .stat-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        flex-shrink: 0;
        box-shadow: 0 4px 20px 0 rgba(0,0,0,.14), 0 7px 10px -5px rgba(0,188,212,.4);
    }

    .stat-icon-box i {
        font-size: 24px;
        color: #fff;
    }

    .stat-info {
        flex-grow: 1;
        overflow: hidden;
    }

    .stat-category {
        color: #999;
        font-size: 14px;
        margin-bottom: 2px;
        text-transform: capitalize;
    }

    .stat-title {
        color: #3C4858;
        margin: 0;
        font-weight: 700;
        font-size: 20px;
    }

    .card-primary .stat-icon-box { background: linear-gradient(60deg, #ab47bc, #8e24aa); box-shadow: 0 4px 20px 0 rgba(0,0,0,.14), 0 7px 10px -5px rgba(156, 39, 176, .4); }
    .card-success .stat-icon-box { background: linear-gradient(60deg, #66bb6a, #43a047); box-shadow: 0 4px 20px 0 rgba(0,0,0,.14), 0 7px 10px -5px rgba(76, 175, 80, .4); }
    .card-info .stat-icon-box { background: linear-gradient(60deg, #26c6da, #00acc1); box-shadow: 0 4px 20px 0 rgba(0,0,0,.14), 0 7px 10px -5px rgba(0, 188, 212, .4); }
    .card-danger .stat-icon-box { background: linear-gradient(60deg, #ef5350, #e53935); box-shadow: 0 4px 20px 0 rgba(0,0,0,.14), 0 7px 10px -5px rgba(244, 67, 54, .4); }

    .modern-stat-card .card-footer {
        padding: 10px 20px !important;
        border-top: 1px solid #f0f0f0 !important;
        background: transparent !important;
    }

</style>

<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="content">

    <div class="container-fluid">

        <!-- REKAP JUMLAH DATA -->

        <div class="row d-none d-sm-flex">
            <div class="col-lg-3 col-md-6">
                <div class="card modern-stat-card card-primary">
                    <div class="card-body">
                        <div class="stat-flex">
                            <div class="stat-icon-box">
                                <a href="<?= base_url('admin/siswa'); ?>">
                                    <i class="material-icons">person</i>
                                </a>
                            </div>
                            <div class="stat-info">
                                <p class="stat-category">Jumlah siswa</p>
                                <h3 class="stat-title"><?= count($siswa); ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons text-primary" style="font-size: 14px; vertical-align: middle;">check</i>
                            Terdaftar
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card modern-stat-card card-success">
                    <div class="card-body">
                        <div class="stat-flex">
                            <div class="stat-icon-box">
                                <a href="<?= base_url('admin/guru'); ?>">
                                    <i class="material-icons">person_4</i>
                                </a>
                            </div>
                            <div class="stat-info">
                                <p class="stat-category">Jumlah guru</p>
                                <h3 class="stat-title"><?= count($guru); ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons text-success" style="font-size: 14px; vertical-align: middle;">check</i>
                            Terdaftar
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card modern-stat-card card-info">
                    <div class="card-body">
                        <div class="stat-flex">
                            <div class="stat-icon-box">
                                <a href="<?= base_url('admin/kelas'); ?>">
                                    <i class="material-icons">grade</i>
                                </a>
                            </div>
                            <div class="stat-info">
                                <p class="stat-category">Jumlah Kelas</p>
                                <h3 class="stat-title text-nowrap"><?= count($kelas); ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons" style="font-size: 14px; vertical-align: middle;">home</i>
                            <?= $generalSettings->school_name; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card modern-stat-card card-danger">
                    <div class="card-body">
                        <div class="stat-flex">
                            <div class="stat-icon-box">
                                <a href="<?= base_url('admin/petugas'); ?>">
                                    <i class="material-icons">settings</i>
                                </a>
                            </div>
                            <div class="stat-info">
                                <p class="stat-category">Jumlah petugas</p>
                                <h3 class="stat-title"><?= count($petugas); ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons" style="font-size: 14px; vertical-align: middle;">person</i>
                            Administrator
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row d-sm-none">
            <div class="col-6 mb-2 px-2">
                <div class="card modern-stat-card card-primary m-0 shadow-sm" style="min-height: auto;">
                    <div class="card-body p-2">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon-box m-0 mr-2" style="width: 32px; height: 32px; border-radius: 8px;">
                                <a href="<?= base_url('admin/siswa'); ?>">
                                    <i class="material-icons" style="font-size: 16px;">person</i>
                                </a>
                            </div>
                            <div class="stat-info">
                                <p class="stat-category m-0" style="font-size: 10px; line-height: 1.1;">Siswa</p>
                                <h4 class="stat-title m-0" style="font-size: 14px;"><?= count($siswa); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 mb-2 px-2">
                <div class="card modern-stat-card card-success m-0 shadow-sm" style="min-height: auto;">
                    <div class="card-body p-2">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon-box m-0 mr-2" style="width: 32px; height: 32px; border-radius: 8px;">
                                <a href="<?= base_url('admin/guru'); ?>">
                                    <i class="material-icons" style="font-size: 16px;">person_4</i>
                                </a>
                            </div>
                            <div class="stat-info">
                                <p class="stat-category m-0" style="font-size: 10px; line-height: 1.1;">Guru</p>
                                <h4 class="stat-title m-0" style="font-size: 14px;"><?= count($guru); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 mb-2 px-2">
                <div class="card modern-stat-card card-info m-0 shadow-sm" style="min-height: auto;">
                    <div class="card-body p-2">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon-box m-0 mr-2" style="width: 32px; height: 32px; border-radius: 8px;">
                                <a href="<?= base_url('admin/kelas'); ?>">
                                    <i class="material-icons" style="font-size: 14px;">grade</i>
                                </a>
                            </div>
                            <div class="stat-info">
                                <p class="stat-category m-0" style="font-size: 10px; line-height: 1.1;">Kelas</p>
                                <h4 class="stat-title m-0" style="font-size: 13px;"><?= count($kelas); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 mb-2 px-2">
                <div class="card modern-stat-card card-danger m-0 shadow-sm" style="min-height: auto;">
                    <div class="card-body p-2">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon-box m-0 mr-2" style="width: 32px; height: 32px; border-radius: 8px;">
                                <a href="<?= base_url('admin/petugas'); ?>">
                                    <i class="material-icons" style="font-size: 16px;">settings</i>
                                </a>
                            </div>
                            <div class="stat-info">
                                <p class="stat-category m-0" style="font-size: 10px; line-height: 1.1;">Petugas</p>
                                <h4 class="stat-title m-0" style="font-size: 14px;"><?= count($petugas); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="row">

            <!-- STATS SISWA HARI INI -->

            <div class="col-lg-6">

                <div class="card">

                    <div class="card-header card-header-primary">

                        <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap: 12px;">

                            <div>

                                <h4 class="card-title"><b id="titleSiswaStats">Absensi Siswa Hari Ini</b></h4>

                                <p class="card-category"><?= $dateNow; ?></p>

                            </div>

                            <!-- FILTER KELAS -->

                            <div class="text-right">

                                <div class="d-flex align-items-center justify-content-end">

                                    <div id="filterLoader" style="display: none;">

                                        <div class="spinner-border spinner-border-sm text-primary" role="status">

                                            <span class="sr-only">Loading...</span>

                                        </div>

                                    </div>

                                    <div>

                                        <select name="id_kelas" id="filterKelas" class="custom-select">

                                            <option value="">-- Semua Kelas (<?= count($siswa) ?> siswa) --

                                            </option>

                                            <?php foreach ($kelas as $k): ?>

                                                <option value="<?= $k['id_kelas'] ?>" data-kelas="<?= $k['kelas'] ?>">

                                                    <?= $k['kelas'] ?> (

                                                    <?= $k['jumlah_siswa'] ?? 0 ?> siswa)

                                                </option>

                                            <?php endforeach; ?>

                                        </select>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="card-body" id="siswaStatsContainer">

                        <?= view('admin/_dashboard_siswa_stats', [

                            'hadir' => $jumlahKehadiranSiswa['hadir'],

                            'sakit' => $jumlahKehadiranSiswa['sakit'],

                            'izin' => $jumlahKehadiranSiswa['izin'],

                            'alfa' => $jumlahKehadiranSiswa['alfa'],

                            'totalSiswa' => $totalSiswa

                        ]) ?>

                    </div>

                </div>

            </div>

            <!-- STATS GURU HARI INI -->

            <div class="col-lg-6">

                <div class="card">

                    <div class="card-header card-header-success">

                        <h4 class="card-title"><b>Absensi Guru Hari Ini</b></h4>

                        <p class="card-category"><?= $dateNow; ?></p>

                    </div>

                    <div class="card-body">

                        <div class="row text-center flex-nowrap">

                            <div class="col-2">

                                <h5 class="text-success text-nowrap"><b>Hadir</b></h5>

                                <h4 class="text-nowrap"><?= $jumlahKehadiranGuru['hadir']; ?></h4>

                            </div>

                            <div class="col-2">

                                <h5 class="text-warning text-nowrap"><b>Sakit</b></h5>

                                <h4 class="text-nowrap"><?= $jumlahKehadiranGuru['sakit']; ?></h4>

                            </div>

                            <div class="col-2">

                                <h5 class="text-info text-nowrap"><b>Izin</b></h5>

                                <h4 class="text-nowrap"><?= $jumlahKehadiranGuru['izin']; ?></h4>

                            </div>

                            <div class="col-2">

                                <h5 class="text-danger text-nowrap"><b>Alfa</b></h5>

                                <h4 class="text-nowrap"><?= $jumlahKehadiranGuru['alfa']; ?></h4>

                            </div>

                            <div class="col-1">

                                <div class="border-right mx-auto h-100" style="width: 0;"></div>

                            </div>

                            <div class="col-2 col-sm-3">

                                <h5 class="text-primary text-nowrap"><b>Total</b></h5>

                                <h4 class="text-nowrap"><?= $totalGuru; ?></h4>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>



        <div class="row">

            <!-- CHART SISWA -->

            <div class="col-lg-6">

                <div class="card">

                    <div class="card-header card-header-primary">

                        <h4 class="card-title" id="titleSiswaChart">Tingkat Kehadiran Siswa</h4>

                        <p class="card-category">Statistik kehadiran 7 hari terakhir | <?= $dateNow; ?></p>

                    </div>

                    <div class="card-body">

                        <div class="chart-container">

                            <canvas id="kehadiranSiswa"></canvas>

                        </div>

                    </div>

                    <div class="card-footer">

                        <div class="stats">

                            <i class="material-icons text-primary">checklist</i> <a class="text-primary" href="<?= base_url('admin/absen-siswa'); ?>">Lihat data</a>

                        </div>

                    </div>

                </div>

            </div>

            <!-- CHART GURU -->

            <div class="col-lg-6">

                <div class="card">

                    <div class="card-header card-header-success">

                        <h4 class="card-title">Tingkat Kehadiran Guru</h4>

                        <p class="card-category">Statistik kehadiran 7 hari terakhir | <?= $dateNow; ?></p>

                    </div>

                    <div class="card-body">

                        <div class="chart-container">

                            <canvas id="kehadiranGuru"></canvas>

                        </div>

                    </div>

                    <div class="card-footer">

                        <div class="stats">

                            <i class="material-icons text-success">checklist</i> <a class="text-success" href="<?= base_url('admin/absen-guru'); ?>">Lihat data</a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>



<?= $this->section('scripts') ?>

<!-- Chart.js CDN -->

<script src="<?= base_url('assets/js/plugins/chartjs/chart.umd.min.js') ?>"></script>

<script>

    let kehadiranSiswaChart;

    let kehadiranGuruChart;



    const chartLabels = <?= json_encode($dateRange) ?>;



    const chartColors = {

        hadir: { border: '#4caf50', bg: 'rgba(76, 175, 80, 1)' },

        sakit: { border: '#ff9800', bg: 'rgba(255, 152, 0, 1)' },

        izin: { border: '#00bcd4', bg: 'rgba(0, 188, 212, 1)' },

        alfa: { border: '#f44336', bg: 'rgba(244, 67, 54, 1)' }

    };



    function createChartConfig(data) {

        return {

            type: 'bar',

            data: {

                labels: chartLabels,

                datasets: [

                    {

                        label: 'Hadir',

                        data: data.hadir,

                        borderColor: chartColors.hadir.border,

                        backgroundColor: chartColors.hadir.bg,

                        tension: 0.3,

                        fill: false,

                        pointRadius: 4,

                        pointHoverRadius: 6

                    },

                    {

                        label: 'Sakit',

                        data: data.sakit,

                        borderColor: chartColors.sakit.border,

                        backgroundColor: chartColors.sakit.bg,

                        tension: 0.3,

                        fill: false,

                        pointRadius: 4,

                        pointHoverRadius: 6

                    },

                    {

                        label: 'Izin',

                        data: data.izin,

                        borderColor: chartColors.izin.border,

                        backgroundColor: chartColors.izin.bg,

                        tension: 0.3,

                        fill: false,

                        pointRadius: 4,

                        pointHoverRadius: 6

                    },

                    {

                        label: 'Alfa',

                        data: data.alfa,

                        borderColor: chartColors.alfa.border,

                        backgroundColor: chartColors.alfa.bg,

                        tension: 0.3,

                        fill: false,

                        pointRadius: 4,

                        pointHoverRadius: 6

                    }

                ]

            },

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

                        backgroundColor: 'rgba(0, 0, 0, 0.8)',

                        titleFont: { size: 14 },

                        bodyFont: { size: 13 },

                        padding: 12,

                        cornerRadius: 8,

                        callbacks: {

                            label: function (context) {

                                return context.dataset.label + ': ' + context.parsed.y + ' orang';

                            }

                        }

                    }

                },

                scales: {

                    y: {

                        stacked: false,

                        beginAtZero: true,

                        ticks: {

                            stepSize: 1,

                            callback: function (value) {

                                if (Number.isInteger(value)) return value;

                            }

                        },

                        grid: { color: 'rgba(0, 0, 0, 0.05)' }

                    },

                    x: {

                        stacked: false,

                        grid: { display: false }

                    }

                }

            }

        };

    }



    function updateSiswaChart(newData) {

        if (kehadiranSiswaChart) {

            kehadiranSiswaChart.data.datasets[0].data = newData.hadir;

            kehadiranSiswaChart.data.datasets[1].data = newData.sakit;

            kehadiranSiswaChart.data.datasets[2].data = newData.izin;

            kehadiranSiswaChart.data.datasets[3].data = newData.alfa;

            kehadiranSiswaChart.update('active');

        }

    }



    function initDashboardPageCharts() {

        const siswaCtx = document.getElementById('kehadiranSiswa');

        if (siswaCtx) {

            const dataSiswa = {

                hadir: <?= json_encode($grafikKehadiranSiswa['hadir']) ?>,

                sakit: <?= json_encode($grafikKehadiranSiswa['sakit']) ?>,

                izin: <?= json_encode($grafikKehadiranSiswa['izin']) ?>,

                alfa: <?= json_encode($grafikKehadiranSiswa['alfa']) ?>

            };

            kehadiranSiswaChart = new Chart(siswaCtx, createChartConfig(dataSiswa));

        }



        const guruCtx = document.getElementById('kehadiranGuru');

        if (guruCtx) {

            const dataGuru = {

                hadir: <?= json_encode($grafikKehadiranGuru['hadir']) ?>,

                sakit: <?= json_encode($grafikKehadiranGuru['sakit']) ?>,

                izin: <?= json_encode($grafikKehadiranGuru['izin']) ?>,

                alfa: <?= json_encode($grafikKehadiranGuru['alfa']) ?>

            };

            kehadiranGuruChart = new Chart(guruCtx, createChartConfig(dataGuru));

        }

    }



    $(document).ready(function () {

        initDashboardPageCharts();



        $('#filterKelas').on('change', function () {

            const idKelas = $(this).val();

            const loader = $('#filterLoader');



            loader.show();



            $.ajax({

                url: '<?= base_url('admin/dashboard/filter-data') ?>',

                type: 'POST',

                data: setAjaxData({ id_kelas: idKelas }),

                success: function (response) {

                    const obj = JSON.parse(response);

                    if (obj.result == 1) {

                        $('#siswaStatsContainer').html(obj.htmlContent);

                        updateSiswaChart(obj.chartData);



                        // Update Titles

                        // const className = $('#filterKelas option:selected').attr('data-kelas');

                        // if (idKelas == "") {

                        //     $('#titleSiswaStats').text("Absensi Siswa Hari Ini");

                        //     $('#titleSiswaChart').text("Tingkat Kehadiran Siswa");

                        // } else {

                        //     $('#titleSiswaStats').text("Absensi Siswa " + className + " Hari Ini");

                        //     $('#titleSiswaChart').text("Tingkat Kehadiran Siswa " + className);

                        // }

                    }

                },

                error: function (xhr, status, thrown) {

                    console.error(thrown);

                },

                complete: function () {

                    loader.hide();

                }

            });

        });

    });

</script>

<?= $this->endSection() ?>
