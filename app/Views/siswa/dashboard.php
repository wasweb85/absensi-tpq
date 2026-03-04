<?= $this->extend('templates/index_siswa'); ?> 
<?= $this->section('content'); ?>

<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Halo, <?= $user; ?>!</h1>

    <div class="row">
         
         <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
               <div class="card-header card-header-success card-header-icon">
                  <div class="card-icon">
                     <i class="material-icons">check_circle</i>
                  </div>
                  <p class="card-category">Total Hadir</p>
                  <h3 class="card-title"><?= $summary['hadir'] ?? 0; ?></h3>
               </div>
               <div class="card-footer">
                  <div class="stats"><i class="material-icons">date_range</i> Bulan Ini</div>
               </div>
            </div>
         </div>

         <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
               <div class="card-header card-header-warning card-header-icon">
                  <div class="card-icon">
                     <i class="material-icons">local_hospital</i>
                  </div>
                  <p class="card-category">Total Sakit</p>
                  <h3 class="card-title"><?= $summary['sakit'] ?? 0; ?></h3>
               </div>
               <div class="card-footer">
                  <div class="stats"><i class="material-icons">date_range</i> Bulan Ini</div>
               </div>
            </div>
         </div>

         <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
               <div class="card-header card-header-info card-header-icon">
                  <div class="card-icon">
                     <i class="material-icons">email</i>
                  </div>
                  <p class="card-category">Total Izin</p>
                  <h3 class="card-title"><?= $summary['izin'] ?? 0; ?></h3>
               </div>
               <div class="card-footer">
                  <div class="stats"><i class="material-icons">date_range</i> Bulan Ini</div>
               </div>
            </div>
         </div>

         <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
               <div class="card-header card-header-danger card-header-icon">
                  <div class="card-icon">
                     <i class="material-icons">highlight_off</i>
                  </div>
                  <p class="card-category">Total Alpha</p>
                  <h3 class="card-title"><?= $summary['alpha'] ?? 0; ?></h3>
               </div>
               <div class="card-footer">
                  <div class="stats"><i class="material-icons">date_range</i> Bulan Ini</div>
               </div>
            </div>
         </div>

      </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Kehadiran Saya</h6>
        </div>
        <div class="card-body">
            
            <form action="" method="get" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <select name="waktu" class="form-control" onchange="this.form.submit()">
                            <option value="hari" <?= $filterWaktu == 'hari' ? 'selected' : '' ?>>Hari Ini</option>
                            <option value="minggu" <?= $filterWaktu == 'minggu' ? 'selected' : '' ?>>7 Hari Terakhir</option>
                            <option value="bulan" <?= $filterWaktu == 'bulan' ? 'selected' : '' ?>>Bulan Ini</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-control" onchange="this.form.submit()">
                            <option value="all">-- Semua Status --</option>
                            <option value="1" <?= $filterStatus == '1' ? 'selected' : '' ?>>Hadir</option>
                            <option value="2" <?= $filterStatus == '2' ? 'selected' : '' ?>>Sakit</option>
                            <option value="3" <?= $filterStatus == '3' ? 'selected' : '' ?>>Izin</option>
                            <option value="4" <?= $filterStatus == '4' ? 'selected' : '' ?>>Alpha</option>
                        </select>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tanggal Absen</th>
                            <th>Jam Masuk</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($riwayat)) : ?>
                            <tr><td colspan="4" class="text-center">Belum ada data absensi.</td></tr>
                        <?php else : ?>
                            <?php foreach ($riwayat as $row) : ?>
                            <tr>
                                <td><?= date('d F Y', strtotime($row['tanggal'])); ?></td>
                                <td><?= $row['jam_masuk'] ?? '-'; ?></td>
                                <td>
                                    <span class="badge badge-<?= $row['warna'] ?? 'secondary'; ?>">
                                        <?= $row['nama_kehadiran'] ?? 'Belum Absen'; ?>
                                    </span>
                                </td>
                                <td><?= $row['ket'] ?? '-'; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection(); ?>