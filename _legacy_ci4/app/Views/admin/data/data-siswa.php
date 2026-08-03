<?= $this->extend(isset($_layout) && $_layout === 'ajax' ? 'templates/ajax_layout' : 'templates/admin_page_layout') ?>
<?= $this->section('content') ?>

<div class="content">

   <div class="container-fluid">

      <div class="row">

         <div class="col-lg-12 col-md-12">

            <?php if (session()->getFlashdata('msg')): ?>

               <div class="pb-2 px-3">

                  <div class="alert alert-<?= session()->getFlashdata('error') == true ? 'danger' : 'success' ?> ">

                     <button type="button" class="close" data-dismiss="alert" aria-label="Close">

                        <i class="material-icons">close</i>

                     </button>

                     <?= session()->getFlashdata('msg') ?>

                  </div>

               </div>

            <?php endif; ?>

            <a class="btn btn-primary ml-3 pl-3 py-3" href="<?= base_url('admin/siswa/create'); ?>">

               <i class="material-icons mr-2">add</i> Tambah data siswa

            </a>

            <a class="btn btn-primary ml-3 pl-3 py-3" href="<?= base_url('admin/siswa/bulk'); ?>">

               <i class="material-icons mr-2">add</i> Import CSV

            </a>

            <button class="btn btn-danger ml-3 pl-3 py-3 btn-table-delete" onclick="deleteSelectedSiswa('Data yang sudah dihapus tidak bisa kembalikan');"><i

                  class="material-icons mr-2">delete_forever</i>Bulk Delete</button>

            <div class="card">

               <div class="card-header card-header-primary">

                  <h4 class="card-title"><b>Daftar Siswa</b></h4>

                  <p class="card-category">Angkatan <?= $generalSettings->school_year; ?></p>

               </div>

               <div class="card-body">

                  <div class="row mb-3 pt-2">

                     <div class="col-md-4">
                        <div class="form-group mb-0">
                           <label class="bmd-label-floating text-primary" style="font-size: 14px; position: initial; margin-bottom: 5px;">Jilid</label>
                           <select name="kelas" id="filterKelasSiswa" class="custom-select">
                              <option value="">-- Semua Jilid --</option>
                              <?php foreach ($jilid as $value): ?>
                                 <option value="<?= $value['jilid']; ?>"><?= $value['jilid']; ?></option>
                              <?php endforeach; ?>
                           </select>
                        </div>
                     </div>

                     <div class="col-md-4">
                        <div class="form-group mb-0">
                           <label class="bmd-label-floating text-primary" style="font-size: 14px; position: initial; margin-bottom: 5px;">Indeks Kelas</label>
                           <select name="index" id="filterIndexSiswa" class="custom-select">
                              <option value="">-- Semua Indeks --</option>
                              <?php foreach ($index_kelas as $value): ?>
                                 <option value="<?= $value['index_kelas']; ?>"><?= $value['index_kelas']; ?></option>
                              <?php endforeach; ?>
                           </select>
                        </div>
                     </div>

                     <div class="col-md-4">
                        <div class="form-group mb-0">
                           <label class="bmd-label-floating text-primary" style="font-size: 14px; position: initial; margin-bottom: 5px;">Cari Siswa (Nama/NIS)</label>
                           <input type="text" id="filterSearchSiswa" class="form-control" placeholder="Ketik nama atau NIS...">
                        </div>
                     </div>

                  </div>

                  <div id="dataSiswa">

                     <p class="text-center mt-3">Daftar siswa muncul disini</p>

                  </div>

               </div>

            </div>

         </div>

      </div>

   </div>

   <?= $this->endSection() ?>



   <?= $this->section('scripts') ?>

   <script>

      var jilid = null;
      var index = null;
      var search = null;

      getDataSiswa(jilid, index, search);

      $('#filterKelasSiswa').on('change', function () {
         jilid = $(this).val() || null;
         trig();
      });

      $('#filterIndexSiswa').on('change', function () {
         index = $(this).val() || null;
         trig();
      });

      $('#filterSearchSiswa').on('keyup', function () {
         search = $(this).val() || null;
         trig();
      });

      function trig() {
         getDataSiswa(jilid, index, search);
      }

      function getDataSiswa(_jilid = null, _index = null, _search = null) {
         jQuery.ajax({
            url: "<?= base_url('/admin/siswa'); ?>",
            type: 'post',
            data: {
               'kelas': _jilid,
               'index': _index,
               'search': _search
            },
            success: function (response, status, xhr) {
               // console.log(status);
               $('#dataSiswa').html(response);



               $('html, body').animate({

                  scrollTop: $("#dataSiswa").offset().top

               }, 500);

            },

            error: function (xhr, status, thrown) {

               console.log(thrown);

               $('#dataSiswa').html(thrown);

            }

         });

      }





   </script>

   <?= $this->endSection() ?>
