<?= $this->extend(isset($_layout) && $_layout === 'ajax' ? 'templates/ajax_layout' : 'templates/admin_page_layout') ?>
<?= $this->section('styles') ?>

<style>

    .schedule-header {

        background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);

        color: white;

        padding: 20px;

        border-radius: 8px 8px 0 0;

        margin-bottom: 0;

    }

    .schedule-card {

        border-radius: 8px;

        overflow: hidden;

        border: none;

        box-shadow: 0 4px 20px rgba(0,0,0,0.08);

    }

    .schedule-table {

        margin-bottom: 0;

    }

    .schedule-table thead th {

        background-color: #f8f9fa;

        color: #555;

        font-weight: 600;

        text-transform: uppercase;

        font-size: 0.85rem;

        letter-spacing: 0.5px;

        border-top: none;

        text-align: center;

        padding: 15px;

    }

    .schedule-table td {

        vertical-align: middle !important;

        text-align: center;

        padding: 12px;

        border-color: #eee;

        height: 80px;

    }

    .time-col {

        background-color: #fdfdfd;

        font-weight: 500;

        color: #666;

        width: 120px;

    }

    .slot-item {

        border-radius: 6px;

        padding: 10px;

        height: 100%;

        display: flex;

        flex-direction: column;

        justify-content: center;

        transition: all 0.3s ease;

        box-shadow: 0 2px 5px rgba(0,0,0,0.05);

    }

    .slot-item:hover {

        transform: translateY(-2px);

        box-shadow: 0 4px 10px rgba(0,0,0,0.1);

    }

    .mapel-name {

        font-weight: 700;

        font-size: 0.95rem;

        margin-bottom: 4px;

        display: block;

    }

    .guru-name {

        font-size: 0.75rem;

        color: rgba(0,0,0,0.6);

        display: block;

    }

    

    /* Preset Colors for Subjects */

    .color-0 { background-color: #e3f2fd; border-left: 4px solid #2196f3; }

    .color-1 { background-color: #f1f8e9; border-left: 4px solid #8bc34a; }

    .color-2 { background-color: #fff3e0; border-left: 4px solid #ff9800; }

    .color-3 { background-color: #fce4ec; border-left: 4px solid #e91e63; }

    .color-4 { background-color: #f3e5f5; border-left: 4px solid #9c27b0; }

    .color-5 { background-color: #e8f5e9; border-left: 4px solid #4caf50; }

    .color-6 { background-color: #ede7f6; border-left: 4px solid #673ab7; }

    .color-7 { background-color: #e0f2f1; border-left: 4px solid #009688; }

    .color-8 { background-color: #fffde7; border-left: 4px solid #fbc02d; }

</style>

<?= $this->endSection() ?>



<?= $this->section('content') ?>

<div class="content">

    <div class="container-fluid">

        <div class="row">

            <div class="col-md-12">

                <!-- Class Selector Overlay -->

                <div class="card mb-4" style="border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">

                    <div class="card-body py-3">

                        <form method="GET" class="row align-items-center">

                            <div class="col-md-4">

                                <div class="form-group mb-0">

                                    <label class="mb-1 text-muted small font-weight-bold">PILIH KELAS UNTUK MELIHAT JADWAL</label>

                                    <select name="id_kelas" class="form-control selectpicker" data-style="btn btn-outline-primary" onchange="this.form.submit()">

                                        <?php foreach ($allKelas as $k): ?>

                                            <option value="<?= $k['id_kelas'] ?>" <?= $idKelas == $k['id_kelas'] ? 'selected' : '' ?>>

                                                <?= esc($k['kelas']) ?>

                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                </div>

                            </div>

                            <div class="col-md-8 text-md-right mt-3 mt-md-0">

                                <h4 class="mb-0 font-weight-bold text-primary">

                                    Jadwal Kelas: <?= $selectedKelas ? esc($selectedKelas['kelas']) : '-' ?>

                                </h4>

                            </div>

                        </form>

                    </div>

                </div>



                <div class="card schedule-card">

                    <div class="schedule-header d-flex justify-content-between align-items-center">

                        <h4 class="m-0 font-weight-bold"><i class="material-icons mr-2">event_note</i> Jadwal Pelajaran Mingguan</h4>

                        <div class="small opacity-7">Tahun Ajaran <?= date('Y') ?>/<?= date('Y', strtotime('+1 year')) ?></div>

                    </div>

                    <div class="card-body p-0 table-responsive">

                        <?php if (empty($slots)): ?>

                            <div class="text-center py-5">

                                <i class="material-icons text-muted" style="font-size:64px">event_busy</i>

                                <h5 class="text-muted mt-3">Belum ada data jadwal untuk kelas ini.</h5>

                            </div>

                        <?php else: ?>

                            <table class="table schedule-table table-bordered">

                                <thead>

                                    <tr>

                                        <th class="time-col">WAKTU</th>

                                        <?php foreach ($hariList as $hari): ?>

                                            <th><?= $hari ?></th>

                                        <?php endforeach; ?>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php foreach ($slots as $slot): ?>

                                        <tr>

                                            <td class="time-col">

                                                <span class="badge badge-light p-2"><?= $slot ?></span>

                                            </td>

                                            <?php foreach ($hariList as $hari): ?>

                                                <td>

                                                    <?php if (isset($grid[$slot][$hari])): 

                                                        $j = $grid[$slot][$hari];

                                                        $colorIdx = (abs(crc32($j['nama_mapel']))) % 9;

                                                    ?>

                                                        <div class="slot-item color-<?= $colorIdx ?>">

                                                            <span class="mapel-name"><?= esc($j['nama_mapel']) ?></span>

                                                            <span class="guru-name"><?= esc($j['nama_guru']) ?></span>

                                                        </div>

                                                    <?php endif; ?>

                                                </td>

                                            <?php endforeach; ?>

                                        </tr>

                                    <?php endforeach; ?>

                                </tbody>

                            </table>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>
