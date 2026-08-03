<?= $this->extend(isset($_layout) && $_layout === 'ajax' ? 'templates/ajax_layout' : 'templates/admin_page_layout') ?>
<?= $this->section('content') ?>



<div class="content">

    <div class="container-fluid">

        <?= view('admin/_messages') ?>



        <div class="row">

            <div class="col-lg-8 col-12">

                <div class="card">

                    <div class="card-header card-header-warning d-flex justify-content-between align-items-center">

                        <div>

                            <h4 class="card-title"><b>Data Mata Pelajaran</b></h4>

                            <p class="card-category">Daftar semua mata pelajaran</p>

                        </div>

                        <button type="button" class="btn btn-white btn-sm" data-toggle="modal" data-target="#modalTambah">

                            <i class="material-icons">add</i> Tambah Mapel

                        </button>

                    </div>

                    <div class="card-body">

                        <?php if (empty($mapel)): ?>

                        <div class="text-center py-5">

                            <i class="material-icons text-muted" style="font-size:48px">menu_book</i>

                            <p class="text-muted mt-2">Belum ada mata pelajaran. Klik <b>Tambah Mapel</b> untuk mulai.</p>

                        </div>

                        <?php else: ?>

                        <div class="table-responsive">

                            <table class="table table-hover">

                                <thead class="thead-light">

                                    <tr>

                                        <th style="width:50px" class="text-center">No</th>

                                        <th>Nama Mata Pelajaran</th>

                                        <th style="width:120px" class="text-center">Aksi</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php foreach ($mapel as $i => $m): ?>

                                    <tr>

                                        <td class="text-center"><?= $i + 1 ?></td>

                                        <td>

                                            <i class="material-icons text-warning" style="font-size:18px;vertical-align:middle">menu_book</i>

                                            <?= esc($m['nama_mapel']) ?>

                                        </td>

                                        <td class="text-center text-nowrap">

                                            <button class="btn btn-sm btn-info btn-edit"

                                                data-id="<?= $m['id_mapel'] ?>"

                                                data-nama="<?= esc($m['nama_mapel']) ?>"

                                                title="Edit">

                                                <i class="material-icons" style="font-size:16px">edit</i>

                                            </button>

                                            <a href="<?= base_url('admin/mapel/delete/' . $m['id_mapel']) ?>"

                                               class="btn btn-sm btn-danger btn-hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');" title="Hapus">

                                                <i class="material-icons" style="font-size:16px">delete</i>

                                            </a>

                                        </td>

                                    </tr>

                                    <?php endforeach; ?>

                                </tbody>

                            </table>

                        </div>

                        <?php endif; ?>

                    </div>

                    <div class="card-footer">

                        <div class="stats">

                            <i class="material-icons text-warning">menu_book</i>

                            Total: <b><?= count($mapel) ?></b> mata pelajaran

                        </div>

                    </div>

                </div>

            </div>



            <!-- Panel Tambah Cepat -->

            <div class="col-lg-4 col-12">

                <div class="card">

                    <div class="card-header card-header-warning">

                        <h4 class="card-title"><b>Tambah Cepat</b></h4>

                        <p class="card-category">Tambah satu mata pelajaran</p>

                    </div>

                    <div class="card-body">

                        <form method="POST" action="<?= base_url('admin/mapel/store') ?>">

                            <?= csrf_field() ?>

                            <div class="form-group">

                                <label>Nama Mata Pelajaran <span class="text-danger">*</span></label>

                                <input type="text" name="nama_mapel" class="form-control"

                                       placeholder="cth: Matematika, Bahasa Indonesia..." required autofocus>

                            </div>

                            <button type="submit" class="btn btn-warning btn-block">

                                <i class="material-icons">add</i> Tambah

                            </button>

                        </form>

                    </div>

                </div>



                <div class="card mt-2">

                    <div class="card-header">

                        <h5 class="card-title mb-0"><i class="material-icons text-info" style="vertical-align:middle">info</i> Info</h5>

                    </div>

                    <div class="card-body small text-muted">

                        <p>Mata pelajaran yang ditambahkan di sini akan tersedia sebagai pilihan saat membuat <b>Jadwal Pelajaran</b>.</p>

                        <p class="mb-0">Hapus hati-hati â€” jika mapel sudah digunakan di jadwal, data jadwal terkait tidak akan tampil mapel-nya.</p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>



<!-- Modal Tambah -->

<div class="modal fade" id="modalTambah" tabindex="-1">

    <div class="modal-dialog modal-sm">

        <div class="modal-content">

            <form method="POST" action="<?= base_url('admin/mapel/store') ?>">

                <?= csrf_field() ?>

                <div class="modal-header">

                    <h5 class="modal-title"><i class="material-icons">add_circle</i> Tambah Mata Pelajaran</h5>

                    <button type="button" class="close" data-dismiss="modal" onclick="$('#modalTambah').modal('hide');">&times;</button>

                </div>

                <div class="modal-body">

                    <div class="form-group mb-0">

                        <label>Nama Mapel <span class="text-danger">*</span></label>

                        <input type="text" name="nama_mapel" class="form-control"

                               placeholder="cth: Matematika" required>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$('#modalTambah').modal('hide');">Batal</button>

                    <button type="submit" class="btn btn-warning">Simpan</button>

                </div>

            </form>

        </div>

    </div>

</div>



<!-- Modal Edit -->

<div class="modal fade" id="modalEdit" tabindex="-1">

    <div class="modal-dialog modal-sm">

        <div class="modal-content">

            <form method="POST" action="<?= base_url('admin/mapel/update') ?>">

                <?= csrf_field() ?>

                <input type="hidden" name="id_mapel" id="editId">

                <div class="modal-header">

                    <h5 class="modal-title"><i class="material-icons">edit</i> Edit Mata Pelajaran</h5>

                    <button type="button" class="close" data-dismiss="modal" onclick="$('#modalEdit').modal('hide');">&times;</button>

                </div>

                <div class="modal-body">

                    <div class="form-group mb-0">

                        <label>Nama Mapel <span class="text-danger">*</span></label>

                        <input type="text" name="nama_mapel" id="editNama" class="form-control" required>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$('#modalEdit').modal('hide');">Batal</button>

                    <button type="submit" class="btn btn-warning">Update</button>

                </div>

            </form>

        </div>

    </div>

</div>



<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>

$(document).ready(function () {

    $('.btn-edit').on('click', function () {

        $('#editId').val($(this).data('id'));

        $('#editNama').val($(this).data('nama'));

        $('#modalEdit').modal('show');

    });

});

</script>

<?= $this->endSection() ?>
