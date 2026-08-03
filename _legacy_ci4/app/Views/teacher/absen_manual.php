<?= $this->extend(isset($_layout) && $_layout === 'ajax' ? 'templates/ajax_layout' : 'templates/admin_page_layout') ?>
<?= $this->section('styles') ?>

<style>

    .table-absen thead {

        background-color: #1a1a2e;

        color: #ffffff;

    }

    .table-absen thead th {

        text-align: center;

        vertical-align: middle;

        font-weight: 600;

        padding: 14px 10px;

        border: none;

    }

    .table-absen tbody td {

        vertical-align: middle;

        padding: 12px 10px;

        border-bottom: 1px solid #e0e0e0;

    }

    .table-absen tbody tr:hover {

        background-color: #f5f5f5;

    }

    .table-absen .radio-col {

        text-align: center;

    }

    .table-absen input[type="radio"] {

        transform: scale(1.3);

        cursor: pointer;

    }

    .table-absen .nama-siswa {

        font-weight: 500;

        padding-left: 20px;

    }

</style>

<?= $this->endSection() ?>



<?= $this->section('content') ?>

<div class="content">

    <div class="container-fluid">

        <div class="card">

            <div class="card-header card-header-primary">

                <h4 class="card-title"><b>Absensi Manual</b></h4>

                <p class="card-category">

                    <?= $selectedKelas ? 'Kelas ' . esc($selectedKelas['kelas']) : 'Pilih kelas terlebih dahulu'; ?>

                </p>

            </div>

            <div class="card-body">

                <!-- Filter row -->

                <div class="row align-items-end mb-4">

                    <div class="col-md-4">

                        <label for="filterKelas"><b>Kelas</b></label>

                        <select class="form-control" id="filterKelas" onchange="filterKelas()">

                            <option value="">-- Pilih Kelas --</option>

                            <?php foreach ($allKelas as $k): ?>

                                <option value="<?= $k['id_kelas']; ?>"

                                    <?= $idKelas == $k['id_kelas'] ? 'selected' : ''; ?>>

                                    <?= esc($k['kelas']); ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <div class="col-md-3">

                        <label for="tanggal"><b>Tanggal</b></label>

                        <input class="form-control" type="date" name="tanggal" id="tanggal"

                            value="<?= esc($date); ?>" onchange="filterKelas()">

                    </div>

                    <?php if (!empty($siswa)): ?>

                        <div class="col-md-2 d-flex align-items-end">

                            <span class="badge badge-info p-2">Total Siswa: <?= count($siswa); ?></span>

                        </div>

                    <?php endif; ?>

                </div>



                <?php if (!empty($idKelas) && empty($siswa)): ?>

                    <div class="text-center p-5">

                        <h4 class="text-danger">Tidak ada siswa di kelas ini.</h4>

                    </div>

                <?php elseif (empty($idKelas)): ?>

                    <div class="text-center p-5 text-muted">

                        <i class="material-icons" style="font-size:48px;">class</i>

                        <h5 class="mt-3">Pilih kelas untuk menampilkan daftar siswa.</h5>

                    </div>

                <?php else: ?>

                    <div class="table-responsive">

                        <table class="table table-absen" id="tabelAbsensi">

                            <thead>

                                <tr>

                                    <th style="width: 40%; text-align: left; padding-left: 20px;">Nama</th>

                                    <th style="width: 15%;">H</th>

                                    <th style="width: 15%;">S</th>

                                    <th style="width: 15%;">I</th>

                                    <th style="width: 15%;">A</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php foreach ($siswa as $s): ?>

                                    <?php

                                    $sid      = $s['id_siswa'];

                                    $existing = $existingAttendance[$sid] ?? 1; // default Hadir

                                    ?>

                                    <tr>

                                        <td class="nama-siswa"><?= esc($s['nama_siswa']); ?></td>

                                        <td class="radio-col">

                                            <input type="radio" name="kehadiran[<?= $sid; ?>]"

                                                value="1" <?= $existing == 1 ? 'checked' : ''; ?>>

                                        </td>

                                        <td class="radio-col">

                                            <input type="radio" name="kehadiran[<?= $sid; ?>]"

                                                value="2" <?= $existing == 2 ? 'checked' : ''; ?>>

                                        </td>

                                        <td class="radio-col">

                                            <input type="radio" name="kehadiran[<?= $sid; ?>]"

                                                value="3" <?= $existing == 3 ? 'checked' : ''; ?>>

                                        </td>

                                        <td class="radio-col">

                                            <input type="radio" name="kehadiran[<?= $sid; ?>]"

                                                value="4" <?= $existing == 4 ? 'checked' : ''; ?>>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>



                    <div class="text-right mt-3">

                        <button type="button" class="btn btn-success btn-lg" id="btnSimpan" onclick="simpanAbsensi()">

                            <i class="material-icons mr-1">save</i> Simpan Absensi

                        </button>

                    </div>

                <?php endif; ?>

            </div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>



<?= $this->section('scripts') ?>

<script>

    function filterKelas() {

        var idKelas = $('#filterKelas').val();

        var tanggal = $('#tanggal').val();

        var url = "<?= base_url('teacher/absen-manual'); ?>";

        var params = [];

        if (idKelas) params.push('id_kelas=' + idKelas);

        if (tanggal) params.push('tanggal=' + tanggal);

        window.location.href = url + (params.length ? '?' + params.join('&') : '');

    }



    function simpanAbsensi() {

        var idKelas = $('#filterKelas').val();

        if (!idKelas) {

            alert('Pilih kelas terlebih dahulu.');

            return;

        }



        var btn = $('#btnSimpan');

        btn.prop('disabled', true).html('<i class="material-icons mr-1">hourglass_empty</i> Menyimpan...');



        var kehadiranData = $('#tabelAbsensi input[type=radio]:checked').serialize();

        kehadiranData += '&tanggal=' + encodeURIComponent($('#tanggal').val());

        kehadiranData += '&id_kelas=' + encodeURIComponent(idKelas);



        $.ajax({

            url: "<?= base_url('teacher/absen-manual/save'); ?>",

            type: 'POST',

            data: kehadiranData,

            dataType: 'json',

            success: function(response) {

                if (response.status) {

                    alert(response.message);

                } else {

                    alert('Gagal: ' + response.message);

                }

            },

            error: function(xhr, status, thrown) {

                alert('Terjadi kesalahan: ' + thrown);

            },

            complete: function() {

                btn.prop('disabled', false).html('<i class="material-icons mr-1">save</i> Simpan Absensi');

            }

        });

    }

</script>

<?= $this->endSection() ?>
