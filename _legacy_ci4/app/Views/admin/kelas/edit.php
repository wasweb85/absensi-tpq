<?= $this->extend(isset($_layout) && $_layout === 'ajax' ? 'templates/ajax_layout' : 'templates/admin_page_layout') ?>
<?= $this->section('content') ?>

<div class="content">

  <div class="container-fluid">

    <div class="row">

      <div class="col-lg-12 col-md-12">

        <div class="card">

          <div class="card-header card-header-primary">

            <h4 class="card-title"><b>Form Edit Jilid</b></h4>

          </div>

          <div class="card-body mx-5 my-3">



            <form action="<?= base_url('admin/kelas/editKelasPost'); ?>" method="post">

              <?= csrf_field() ?>

              <input type="hidden" name="id" value="<?= esc($kelas->id_kelas); ?>">

              <input type="hidden" name="back_url" value="<?= currentFullURL(); ?>">



              <div class="row">

                <div class="col-md-6">

                  <div class="form-group mt-4">

                    <label for="jilid">Jilid</label>

                    <input type="text" id="jilid" class="form-control <?= invalidFeedback('jilid') ? 'is-invalid' : ''; ?>" name="jilid" placeholder="'X', 'XI', 'XII'"

                      value="<?= old('jilid') ?? $kelas->jilid ?? '' ?>" required>

                    <div class="invalid-feedback">

                      <?= invalidFeedback('jilid'); ?>

                    </div>

                  </div>

                </div>

                <div class="col-md-6">

                  <div class="form-group mt-4">

                    <label for="index_kelas">Index / Nama Ruang (Opsional)</label>

                    <input type="text" id="index_kelas" class="form-control <?= invalidFeedback('index_kelas') ? 'is-invalid' : ''; ?>" name="index_kelas" placeholder="'A', 'B'"

                      value="<?= old('index_kelas') ?? $kelas->index_kelas ?? '' ?>">

                    <div class="invalid-feedback">

                      <?= invalidFeedback('index_kelas'); ?>

                    </div>

                  </div>

                </div>

              </div>

              <div class="row">



                <div class="col-md-6">

                  <label for="kategori">Kategori (Pemisahan Gender)</label>

                  <select class="custom-select <?= invalidFeedback('kategori') ? 'is-invalid' : ''; ?>" id="kategori" name="kategori" required>

                    <option value="Campur" <?= (old('kategori') ?? $kelas->kategori) == 'Campur' ? 'selected' : ''; ?>>Campur (Putra-Putri Gabung)</option>

                    <option value="Putra" <?= (old('kategori') ?? $kelas->kategori) == 'Putra' ? 'selected' : ''; ?>>Putra Saja</option>

                    <option value="Putri" <?= (old('kategori') ?? $kelas->kategori) == 'Putri' ? 'selected' : ''; ?>>Putri Saja</option>

                  </select>

                  <div class="invalid-feedback">

                    <?= invalidFeedback('kategori'); ?>

                  </div>

                </div>

                <div class="col-md-6">

                  <label for="id_wali_kelas">Wali Kelas</label>

                  <select class="custom-select <?= invalidFeedback('id_wali_kelas') ? 'is-invalid' : ''; ?>" id="id_wali_kelas" name="id_wali_kelas">

                    <option value="">--Pilih Wali Kelas--</option>

                    <?php foreach ($guru as $value): ?>

                      <option value="<?= $value['id_guru']; ?>" <?= $kelas->id_wali_kelas == $value['id_guru'] ? 'selected' : ''; ?>>

                        <?= $value['nama_guru']; ?>

                      </option>

                    <?php endforeach; ?>

                  </select>

                  <div class="invalid-feedback">

                    <?= invalidFeedback('id_wali_kelas'); ?>

                  </div>

                </div>

              </div>



              <button type="submit" class="btn btn-primary mt-4">Simpan</button>

            </form>

          </div>



          <hr>

        </div>

      </div>

    </div>

  </div>

</div>

<?= $this->endSection() ?>
