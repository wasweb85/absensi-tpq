<?= $this->extend(isset($_layout) && $_layout === 'ajax' ? 'templates/ajax_layout' : 'templates/admin_page_layout') ?>
<?= $this->section('content') ?>



<div class="content">

    <div class="container-fluid">

        <?= view('admin/_messages') ?>



        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-header card-header-success d-flex justify-content-between align-items-center">

                        <div>

                            <h4 class="card-title"><b>Ketentuan Seragam</b></h4>

                            <p class="card-category">Aturan seragam siswa per hari</p>

                        </div>

                        <button type="button" class="btn btn-white btn-sm" data-toggle="modal" data-target="#modalTambah">

                            <i class="material-icons">add</i> Tambah Ketentuan

                        </button>

                    </div>

                    <div class="card-body">

                        <?php

                        $hariColors = [

                            'Senin'  => ['bg' => '#9c27b0', 'icon' => 'looks_one'],

                            'Selasa' => ['bg' => '#2196f3', 'icon' => 'looks_two'],

                            'Rabu'   => ['bg' => '#4caf50', 'icon' => 'looks_3'],

                            'Kamis'  => ['bg' => '#ff9800', 'icon' => 'looks_4'],

                            'Jumat'  => ['bg' => '#f44336', 'icon' => 'looks_5'],

                            'Sabtu'  => ['bg' => '#795548', 'icon' => 'looks_6'],

                        ];

                        ?>

                        <div class="row">

                            <?php foreach ($seragam as $hari => $s): ?>

                            <div class="col-lg-4 col-md-6 mb-4">

                                <div class="card h-100 shadow-sm" style="border-top: 4px solid <?= $hariColors[$hari]['bg'] ?>;">

                                    <div class="card-header d-flex align-items-center justify-content-between py-2" 

                                         style="background-color:<?= $hariColors[$hari]['bg'] ?>10;">

                                        <div class="d-flex align-items-center">

                                            <i class="material-icons mr-2" style="color:<?= $hariColors[$hari]['bg'] ?>">

                                                <?= $hariColors[$hari]['icon'] ?>

                                            </i>

                                            <strong style="color:<?= $hariColors[$hari]['bg'] ?>"><?= $hari ?></strong>

                                        </div>

                                        <?php if ($s): ?>

                                        <div>

                                            <button class="btn btn-sm btn-outline-info btn-edit"

                                                data-id="<?= $s['id_seragam'] ?>"

                                                data-hari="<?= $s['hari'] ?>"

                                                data-nama="<?= esc($s['nama_seragam']) ?>"

                                                data-deskripsi="<?= esc($s['deskripsi']) ?>"

                                                data-keterangan="<?= esc($s['keterangan']) ?>"

                                                title="Edit">

                                                <i class="material-icons" style="font-size:14px">edit</i>

                                            </button>

                                            <a href="<?= base_url('admin/seragam/delete/' . $s['id_seragam']) ?>"

                                               class="btn btn-sm btn-outline-danger btn-hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');" title="Hapus">

                                                <i class="material-icons" style="font-size:14px">delete</i>

                                            </a>

                                        </div>

                                        <?php else: ?>

                                        <button class="btn btn-sm btn-outline-secondary btn-tambah-hari"

                                                data-hari="<?= $hari ?>" title="Tambah seragam untuk hari ini">

                                            <i class="material-icons" style="font-size:14px">add</i>

                                        </button>

                                        <?php endif; ?>

                                    </div>

                                    <div class="card-body py-3">

                                        <?php if ($s): ?>

                                            <div class="d-flex align-items-center mb-2">

                                                <i class="material-icons mr-2 text-muted">checkroom</i>

                                                <strong><?= esc($s['nama_seragam']) ?></strong>

                                            </div>

                                            <?php if (!empty($s['deskripsi'])): ?>

                                            <p class="text-muted small mb-1"><?= nl2br(esc($s['deskripsi'])) ?></p>

                                            <?php endif; ?>

                                            <?php if (!empty($s['keterangan'])): ?>

                                            <div class="alert alert-light py-1 px-2 mb-0">

                                                <small><i class="material-icons" style="font-size:12px;vertical-align:middle">info</i>

                                                <?= esc($s['keterangan']) ?></small>

                                            </div>

                                            <?php endif; ?>

                                        <?php else: ?>

                                            <div class="text-center text-muted py-3">

                                                <i class="material-icons" style="font-size:36px;opacity:0.3">checkroom</i>

                                                <p class="small mb-0">Belum ada ketentuan seragam</p>

                                            </div>

                                        <?php endif; ?>

                                    </div>

                                </div>

                            </div>

                            <?php endforeach; ?>

                        </div>

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

            <form method="POST" action="<?= base_url('admin/seragam/store') ?>">

                <?= csrf_field() ?>

                <div class="modal-header">

                    <h5 class="modal-title"><i class="material-icons">add_circle</i> Tambah Ketentuan Seragam</h5>

                    <button type="button" class="close" data-dismiss="modal" onclick="$('#modalTambah').modal('hide');">&times;</button>

                </div>

                <div class="modal-body">

                    <div class="form-group">

                        <label>Hari <span class="text-danger">*</span></label>

                        <select name="hari" id="tambahHari" class="form-control" required>

                            <option value="">-- Pilih Hari --</option>

                            <?php foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h): ?>

                                <option value="<?= $h ?>"><?= $h ?></option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <div class="form-group">

                        <label>Nama Seragam <span class="text-danger">*</span></label>

                        <input type="text" name="nama_seragam" class="form-control" 

                               placeholder="cth: Putih Abu-abu, Batik, Pramuka..." required>

                    </div>

                    <div class="form-group">

                        <label>Deskripsi</label>

                        <textarea name="deskripsi" class="form-control" rows="3" 

                                  placeholder="cth: Baju putih, celana abu-abu, sepatu hitam..."></textarea>

                    </div>

                    <div class="form-group">

                        <label>Keterangan Tambahan</label>

                        <input type="text" name="keterangan" class="form-control" placeholder="Opsional">

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$('#modalTambah').modal('hide');">Batal</button>

                    <button type="submit" class="btn btn-success">Simpan</button>

                </div>

            </form>

        </div>

    </div>

</div>



<!-- Modal Edit -->

<div class="modal fade" id="modalEdit" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form method="POST" action="<?= base_url('admin/seragam/update') ?>">

                <?= csrf_field() ?>

                <input type="hidden" name="id_seragam" id="editId">

                <div class="modal-header">

                    <h5 class="modal-title"><i class="material-icons">edit</i> Edit Ketentuan Seragam</h5>

                    <button type="button" class="close" data-dismiss="modal" onclick="$('#modalEdit').modal('hide');">&times;</button>

                </div>

                <div class="modal-body">

                    <div class="form-group">

                        <label>Hari</label>

                        <input type="text" id="editHariDisplay" class="form-control" readonly>

                        <input type="hidden" name="hari" id="editHari">

                    </div>

                    <div class="form-group">

                        <label>Nama Seragam <span class="text-danger">*</span></label>

                        <input type="text" name="nama_seragam" id="editNama" class="form-control" required>

                    </div>

                    <div class="form-group">

                        <label>Deskripsi</label>

                        <textarea name="deskripsi" id="editDeskripsi" class="form-control" rows="3"></textarea>

                    </div>

                    <div class="form-group">

                        <label>Keterangan Tambahan</label>

                        <input type="text" name="keterangan" id="editKeterangan" class="form-control">

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$('#modalEdit').modal('hide');">Batal</button>

                    <button type="submit" class="btn btn-success">Update</button>

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

        const d = $(this).data();

        $('#editId').val(d.id);

        $('#editHari').val(d.hari);

        $('#editHariDisplay').val(d.hari);

        $('#editNama').val(d.nama);

        $('#editDeskripsi').val(d.deskripsi);

        $('#editKeterangan').val(d.keterangan);

        $('#modalEdit').modal('show');

    });



    // Tombol tambah quick dari card hari

    $('.btn-tambah-hari').on('click', function () {

        $('#tambahHari').val($(this).data('hari'));

        $('#modalTambah').modal('show');

    });

});

</script>

<?= $this->endSection() ?>
