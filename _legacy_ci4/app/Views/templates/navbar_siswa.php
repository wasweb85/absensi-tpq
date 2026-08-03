<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top">
   <div class="container-fluid">
      <div class="navbar-wrapper" style="display:flex;align-items:center;">
         <button class="sb-mobile-btn" id="sidebarMobileToggle" type="button" aria-label="Buka Sidebar">
            <i class="material-icons">menu</i>
         </button>
      </div>
      
      <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
         <span class="sr-only">Toggle navigation</span>
         <span class="navbar-toggler-icon icon-bar"></span>
         <span class="navbar-toggler-icon icon-bar"></span>
         <span class="navbar-toggler-icon icon-bar"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end">
         <ul class="navbar-nav">
            
            <li class="nav-item dropdown">
               <a class="nav-link" href="javascript:;" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <div class="d-inline-flex align-items-center">
                     <?php if(isset($kelasInfo)): ?>
                        <!-- Desktop & Mobile Info -->
                        <div class="text-right mr-3">
                           <div class="d-flex align-items-center">
                              <div class="mr-3 text-right">
                                 <small class="text-muted d-block font-weight-bold" style="font-size: 8px; line-height: 1; text-transform: uppercase;">Kelas</small>
                                 <span class="text-info font-weight-bold" style="font-size: 12px;"><?= $kelasInfo->kelas ?? '-' ?></span>
                              </div>
                              <div class="text-right">
                                 <small class="text-muted d-block font-weight-bold" style="font-size: 8px; line-height: 1; text-transform: uppercase;">Wali Kelas</small>
                                 <span class="text-primary font-weight-bold" style="font-size: 12px;"><?= $kelasInfo->nama_wali_kelas ?? '-' ?></span>
                              </div>
                           </div>
                        </div>
                     <?php endif; ?>
                     <i class="material-icons">person</i>
                     <p class="d-lg-none d-md-block">
                        Akun
                     </p>
                     
                     <span class="font-weight-bold d-none d-sm-inline" style="margin-left:5px;">
                        <?= session()->get('nama_siswa'); ?>
                     </span>
                  </div>
               </a>
               
               <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                  
                  <div class="dropdown-item">
                     <small class="text-muted">NIS Anda:</small><br>
                     <b><?= session()->get('nis'); ?></b>
                  </div>

                  <div class="dropdown-divider"></div>
                  
                  <a class="dropdown-item text-danger" href="<?= base_url('siswa/logout'); ?>">
                     <i class="material-icons text-danger" style="font-size: 18px; vertical-align: middle;">logout</i> 
                     Log Out
                  </a>
               </div>
            </li>

         </ul>
      </div>
   </div>
</nav>