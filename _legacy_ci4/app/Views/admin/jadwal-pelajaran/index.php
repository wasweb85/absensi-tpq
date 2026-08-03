<?= $this->extend(isset($_layout) && $_layout === 'ajax' ? 'templates/ajax_layout' : 'templates/admin_page_layout') ?>
<?= $this->section('content') ?>



<div class="content">

    <div class="container-fluid">

        <?= view('admin/_messages') ?>



        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-header card-header-primary d-flex justify-content-between align-items-center">

                        <div>

                            <h4 class="card-title"><b>Jadwal Pelajaran</b></h4>

                            <p class="card-category">Jadwal mata pelajaran per kelas</p>

                        </div>

                        <button type="button" class="btn btn-white btn-sm" data-toggle="modal" data-target="#modalTambah">

                            <i class="material-icons">add</i> Tambah Jadwal

                        </button>

                    </div>

                    <div class="card-body">



                        <!-- Filter Kelas -->

                        <div class="row mb-3">

                            <div class="col-md-4">

                                <div class="form-group">

                                    <label>Filter Kelas</label>

                                    <select id="filterKelas" class="form-control form-control-sm">

                                        <option value="">-- Semua Kelas --</option>

                                        <?php foreach ($kelas as $k): ?>

                                            <option value="<?= $k['id_kelas'] ?>"><?= esc($k['kelas']) ?></option>

                                        <?php endforeach; ?>

                                    </select>

                                </div>

                            </div>

                        </div>



                        <?php

                        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

                        $badgeColors = [

                            'Senin'  => 'primary', 'Selasa' => 'info',

                            'Rabu'   => 'success', 'Kamis'  => 'warning',

                            'Jumat'  => 'danger',  'Sabtu'  => 'secondary',

                        ];

                        ?>



                        <?php foreach ($hariList as $hari): ?>

                            <?php if (!empty($perHari[$hari])): ?>

                            <div class="hari-section" data-hari="<?= $hari ?>">

                                <h5 class="mt-3">

                                    <span class="badge badge-<?= $badgeColors[$hari] ?> px-3 py-2">

                                        <i class="material-icons" style="font-size:16px;vertical-align:middle;">calendar_today</i>

                                        <?= $hari ?>

                                    </span>

                                </h5>

                                <div class="table-responsive">

                                    <table class="table table-hover table-sm">

                                        <thead class="thead-light">

                                            <tr>

                                                <th>Jam</th>

                                                <th>Mata Pelajaran</th>

                                                <th>Guru</th>

                                                <th>Kelas</th>

                                                <th>Keterangan</th>

                                                <th class="text-center">Aksi</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            <?php foreach ($perHari[$hari] as $j): ?>

                                            <tr class="row-jadwal" data-kelas="<?= $j['id_kelas'] ?>">

                                                <td class="text-nowrap">

                                                    <i class="material-icons text-muted" style="font-size:14px;vertical-align:middle;">schedule</i>

                                                    <?= substr($j['jam_mulai'], 0, 5) ?> â€“ <?= substr($j['jam_selesai'], 0, 5) ?>

                                                </td>

                                                <td><?= esc($j['nama_mapel'] ?? '-') ?></td>

                                                <td><?= esc($j['nama_guru'] ?? '-') ?></td>

                                                <td><?= esc($j['jilid'] . ($j['kategori'] != 'Campur' ? ' ' . $j['kategori'] : '') . ($j['index_kelas'] ? ' ' . $j['index_kelas'] : '')) ?></td>

                                                <td><?= esc($j['keterangan'] ?? '-') ?></td>

                                                <td class="text-center text-nowrap">

                                                    <button class="btn btn-sm btn-info btn-edit"

                                                        data-id="<?= $j['id_jadwal_pelajaran'] ?>"

                                                        data-kelas="<?= $j['id_kelas'] ?>"

                                                        data-mapel="<?= $j['id_mapel'] ?>"

                                                        data-guru="<?= $j['id_guru'] ?>"

                                                        data-hari="<?= $j['hari'] ?>"

                                                        data-jam_mulai="<?= $j['jam_mulai'] ?>"

                                                        data-jam_selesai="<?= $j['jam_selesai'] ?>"

                                                        data-keterangan="<?= esc($j['keterangan']) ?>"

                                                        title="Edit">

                                                        <i class="material-icons" style="font-size:16px">edit</i>

                                                    </button>

                                                    <a href="<?= base_url('admin/jadwal-pelajaran/delete/' . $j['id_jadwal_pelajaran']) ?>"

                                                       class="btn btn-sm btn-danger btn-hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');" title="Hapus">

                                                        <i class="material-icons" style="font-size:16px">delete</i>

                                                    </a>

                                                </td>

                                            </tr>

                                            <?php endforeach; ?>

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                            <?php endif; ?>

                        <?php endforeach; ?>



                        <?php $kosong = true; foreach ($perHari as $rows) { if (!empty($rows)) { $kosong = false; break; } } ?>

                        <?php if ($kosong): ?>

                        <div class="text-center py-5">

                            <i class="material-icons text-muted" style="font-size:48px">event_note</i>

                            <p class="text-muted mt-2">Belum ada jadwal pelajaran. Klik tombol <b>Tambah Jadwal</b> untuk mulai.</p>

                        </div>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>



<!-- Modal Tambah -->

<div class="modal fade" id="modalTambah" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form method="POST" action="<?= base_url('admin/jadwal-pelajaran/store') ?>">

                <?= csrf_field() ?>

                <div class="modal-header">

                    <h5 class="modal-title"><i class="material-icons">add_circle</i> Tambah Jadwal Pelajaran</h5>

                    <button type="button" class="close" data-dismiss="modal" onclick="$('#modalTambah').modal('hide');">&times;</button>

                </div>

                <div class="modal-body">

                    <div class="form-group">

                        <label>Kelas <span class="text-danger">*</span></label>

                        <select name="id_kelas" class="form-control" required>

                            <option value="">-- Pilih Kelas --</option>

                            <?php foreach ($kelas as $k): ?>

                                <option value="<?= $k['id_kelas'] ?>"><?= esc($k['kelas']) ?></option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <div class="form-group">

                        <label>Mata Pelajaran <span class="text-danger">*</span></label>

                        <select name="id_mapel" class="form-control" required>

                            <option value="">-- Pilih Mapel --</option>

                            <?php foreach ($mapel as $m): ?>

                                <option value="<?= $m['id_mapel'] ?>"><?= esc($m['nama_mapel']) ?></option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <div class="form-group">

                        <label>Guru <span class="text-danger">*</span></label>

                        <select name="id_guru" class="form-control" required>

                            <option value="">-- Pilih Guru --</option>

                            <?php foreach ($guru as $g): ?>

                                <option value="<?= $g['id_guru'] ?>"><?= esc($g['nama_guru']) ?></option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <div class="row">

                        <div class="col-md-4">

                            <div class="form-group">

                                <label>Hari <span class="text-danger">*</span></label>

                                <select name="hari" class="form-control" required>

                                    <option value="">-- Pilih --</option>

                                    <?php foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h): ?>

                                        <option value="<?= $h ?>"><?= $h ?></option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="form-group">

                                <label>Jam Mulai <span class="text-danger">*</span></label>

                                <input type="time" name="jam_mulai" class="form-control" required>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="form-group">

                                <label>Jam Selesai <span class="text-danger">*</span></label>

                                <input type="time" name="jam_selesai" class="form-control" required>

                            </div>

                        </div>

                    </div>

                    <div class="form-group">

                        <label>Keterangan</label>

                        <input type="text" name="keterangan" class="form-control" placeholder="Opsional">

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$('#modalTambah').modal('hide');">Batal</button>

                    <button type="submit" class="btn btn-primary">Simpan</button>

                </div>

            </form>

        </div>

    </div>

</div>



<!-- Modal Edit -->

<div class="modal fade" id="modalEdit" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form method="POST" action="<?= base_url('admin/jadwal-pelajaran/update') ?>">

                <?= csrf_field() ?>

                <input type="hidden" name="id_jadwal_pelajaran" id="editId">

                <div class="modal-header">

                    <h5 class="modal-title"><i class="material-icons">edit</i> Edit Jadwal Pelajaran</h5>

                    <button type="button" class="close" data-dismiss="modal" onclick="$('#modalEdit').modal('hide');">&times;</button>

                </div>

                <div class="modal-body">

                    <div class="form-group">

                        <label>Kelas <span class="text-danger">*</span></label>

                        <select name="id_kelas" id="editKelas" class="form-control" required>

                            <option value="">-- Pilih Kelas --</option>

                            <?php foreach ($kelas as $k): ?>

                                <option value="<?= $k['id_kelas'] ?>"><?= esc($k['kelas']) ?></option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <div class="form-group">

                        <label>Mata Pelajaran <span class="text-danger">*</span></label>

                        <select name="id_mapel" id="editMapel" class="form-control" required>

                            <option value="">-- Pilih Mapel --</option>

                            <?php foreach ($mapel as $m): ?>

                                <option value="<?= $m['id_mapel'] ?>"><?= esc($m['nama_mapel']) ?></option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <div class="form-group">

                        <label>Guru <span class="text-danger">*</span></label>

                        <select name="id_guru" id="editGuru" class="form-control" required>

                            <option value="">-- Pilih Guru --</option>

                            <?php foreach ($guru as $g): ?>

                                <option value="<?= $g['id_guru'] ?>"><?= esc($g['nama_guru']) ?></option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <div class="row">

                        <div class="col-md-4">

                            <div class="form-group">

                                <label>Hari <span class="text-danger">*</span></label>

                                <select name="hari" id="editHari" class="form-control" required>

                                    <?php foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h): ?>

                                        <option value="<?= $h ?>"><?= $h ?></option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="form-group">

                                <label>Jam Mulai <span class="text-danger">*</span></label>

                                <input type="time" name="jam_mulai" id="editJamMulai" class="form-control" required>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="form-group">

                                <label>Jam Selesai <span class="text-danger">*</span></label>

                                <input type="time" name="jam_selesai" id="editJamSelesai" class="form-control" required>

                            </div>

                        </div>

                    </div>

                    <div class="form-group">

                        <label>Keterangan</label>

                        <input type="text" name="keterangan" id="editKeterangan" class="form-control">

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$('#modalEdit').modal('hide');">Batal</button>

                    <button type="submit" class="btn btn-primary">Update</button>

                </div>

            </form>

        </div>

    </div>

</div>



<?= $this->endSection() ?>



<?= $this->section('scripts') ?>

<script>

$(document).ready(function () {

    // Tombol Edit

    $('.btn-edit').on('click', function () {

        const d = $(this).data();

        $('#editId').val(d.id);

        $('#editKelas').val(d.kelas);

        $('#editMapel').val(d.mapel);

        $('#editGuru').val(d.guru);

        $('#editHari').val(d.hari);

        $('#editJamMulai').val(d.jam_mulai);

        $('#editJamSelesai').val(d.jam_selesai);

        $('#editKeterangan').val(d.keterangan);

        $('#modalEdit').modal('show');

    });



    // Filter kelas

    $('#filterKelas').on('change', function () {

        const val = $(this).val();

        if (!val) {

            $('.hari-section').show();

            $('.row-jadwal').show();

        } else {

            $('.row-jadwal').each(function () {

                const visible = $(this).data('kelas') == val;

                $(this).toggle(visible);

            });

            // Sembunyikan hari section yang tidak punya row

            $('.hari-section').each(function () {

                const hasVisible = $(this).find('.row-jadwal:visible').length > 0;

                $(this).toggle(hasVisible);

            });

        }

    });

});

</script>

<?= $this->endSection() ?>
