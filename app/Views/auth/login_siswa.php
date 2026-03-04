<?= $this->extend('templates/starting_page_layout'); ?>

<?= $this->section('content'); ?>
<div class="main-panel">
   <div class="content pt-5 pt-md-2 px-0 px-sm-1 px-md-2">
      <div class="container-fluid px-0 px-md-2">
         <div class="row">
            <div class="col-xxl-5 col-lg-7 col-md-8 col-sm-10 m-auto">
               <div class="card">
                  <div class="card-header card-header-success mb-3">
                     <h4 class="card-title text-center">Login Siswa</h4>
                     <p class="card-category text-center">Masukkan data diri Anda</p>
                  </div>

                  <div class="card-body mx-4 my-3">
                     
                     <?php if(session()->getFlashdata('error')):?>
                        <div class="alert alert-danger">
                           <?= session()->getFlashdata('error') ?>
                        </div>
                     <?php endif;?>

                     <form action="<?= base_url('auth-siswa') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="form-group">
                           <label class="bmd-label-floating">Nama Lengkap</label>
                           <input type="text" class="form-control" name="nama" required autofocus>
                        </div>

                        <div class="form-group mt-3">
                           <label class="bmd-label-floating">NIS</label>
                           <input type="number" name="nis" class="form-control" required>
                        </div>

                        <br>
                        <button type="submit" class="btn btn-success btn-block">Masuk</button>
                     </form>
                     
                     <div class="text-center mt-3">
                        <a href="<?= base_url('login'); ?>" class="text-muted small">Login sebagai Guru/Admin?</a>
                     </div>

                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection(); ?>