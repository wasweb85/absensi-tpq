<?php
/**
 * Modern Sidebar â€” Admin / Wali Kelas
 * Groups: Dashboard | Absensi | Data Master | Laporan & QR | Sistem
 */
$context = $ctx ?? 'dashboard';

// Helper: active class
function sbActive(string $ctx, string|array $match): string {
   if (is_array($match)) return in_array($ctx, $match) ? 'active' : '';
   return $ctx === $match ? 'active' : '';
}
// Helper: group open
function sbOpen(string $ctx, array $contexts): string {
   return in_array($ctx, $contexts) ? 'open' : '';
}
?>
<aside id="appSidebar" class="app-sidebar" data-context="<?= $context ?>">

   <!-- â”€â”€ Header â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ -->
   <div class="sb-header">
      <div class="sb-brand-icon">
         <i class="material-icons">school</i>
      </div>
      <div class="sb-brand-text">
         <div class="sb-role"><?= is_wali_kelas() ? 'Wali Kelas' : 'Operator' ?></div>
         <div class="sb-school"><?= esc($generalSettings->school_name ?? 'Sekolah') ?></div>
      </div>
      <button class="sb-toggle" id="sidebarToggle" title="Toggle Sidebar">
         <i class="material-icons">menu_open</i>
      </button>
   </div>

   <!-- â”€â”€ Search â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ -->
   <div class="sb-search-wrap">
      <div class="sb-search-inner">
         <i class="material-icons">search</i>
         <input type="text" id="sidebarSearch" placeholder="Cari menu" autocomplete="off">
      </div>
   </div>

   <!-- â”€â”€ Nav â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ -->
   <nav class="sb-nav">

      <?php if (is_wali_kelas()): ?>
      <!-- ======= WALI KELAS MENU ======= -->

      <!-- Dashboard -->
      <a data-ajax-nav class="sb-item <?= sbActive($context, 'dashboard') ?>" href="<?= base_url('teacher/dashboard') ?>" data-tooltip="Dashboard">
         <i class="material-icons">dashboard</i>
         <span class="sb-item-label">Dashboard Wali Kelas</span>
      </a>

      <!-- Kelas group -->
      <?php $kGrp = sbOpen($context, ['laporan-kelas','attendance','absen-manual']); ?>
      <div class="sb-section-label">Kelas</div>
      <div class="sb-group">
         <div class="sb-group-header <?= $kGrp ?>" data-tooltip="Kelas">
            <i class="material-icons">class</i>
            <span class="sb-group-title">Manajemen Kelas</span>
            <i class="material-icons sb-chevron">expand_more</i>
         </div>
         <div class="sb-children <?= $kGrp ?>">
            <a data-ajax-nav class="sb-child-item <?= sbActive($context, 'laporan-kelas') ?>" href="<?= base_url('teacher/laporan') ?>">
               <i class="material-icons">print</i>
               <span class="sb-child-label">Laporan Kelas</span>
            </a>
            <a data-ajax-nav class="sb-child-item <?= sbActive($context, 'attendance') ?>" href="<?= base_url('teacher/attendance') ?>">
               <i class="material-icons">event_note</i>
               <span class="sb-child-label">Manajemen Kehadiran</span>
            </a>
            <a data-ajax-nav class="sb-child-item <?= sbActive($context, 'absen-manual') ?>" href="<?= base_url('teacher/absen-manual') ?>">
               <i class="material-icons">edit_note</i>
               <span class="sb-child-label">Absensi Manual</span>
            </a>
         </div>
      </div>

      <!-- Jadwal group -->
      <?php $jGrp = sbOpen($context, ['jadwal-pelajaran']); ?>
      <div class="sb-section-label">Jadwal</div>
      <a data-ajax-nav class="sb-item <?= sbActive($context, 'jadwal-pelajaran') ?>" href="<?= base_url('teacher/jadwal') ?>" data-tooltip="Jadwal Pelajaran">
         <i class="material-icons">menu_book</i>
         <span class="sb-item-label">Jadwal Pelajaran</span>
      </a>

      <!-- Siswa -->
      <div class="sb-section-label">Siswa</div>
      <a data-ajax-nav class="sb-item <?= sbActive($context, 'qr') ?>" href="<?= base_url('teacher/qr') ?>" data-tooltip="QR Code Siswa">
         <i class="material-icons">qr_code</i>
         <span class="sb-item-label">QR Code Siswa</span>
      </a>

      <?php else: ?>
      <!-- ======= ADMIN MENU ======= -->

      <!-- Dashboard -->
      <a data-ajax-nav class="sb-item <?= sbActive($context, 'dashboard') ?>" href="<?= base_url('admin/dashboard') ?>" data-tooltip="Dashboard">
         <i class="material-icons">dashboard</i>
         <span class="sb-item-label">Dashboard</span>
      </a>

      <!-- Absensi group -->
      <?php $aGrp = sbOpen($context, ['absen-siswa','absen-guru']); ?>
      <div class="sb-section-label">Absensi</div>
      <div class="sb-group">
         <div class="sb-group-header <?= $aGrp ?>" data-tooltip="Absensi">
            <i class="material-icons">checklist</i>
            <span class="sb-group-title">Absensi</span>
            <i class="material-icons sb-chevron">expand_more</i>
         </div>
         <div class="sb-children <?= $aGrp ?>">
            <a data-ajax-nav class="sb-child-item <?= sbActive($context, 'absen-siswa') ?>" href="<?= base_url('admin/absen-siswa') ?>">
               <i class="material-icons">person</i>
               <span class="sb-child-label">Absensi Siswa</span>
            </a>
            <a data-ajax-nav class="sb-child-item <?= sbActive($context, 'absen-guru') ?>" href="<?= base_url('admin/absen-guru') ?>">
               <i class="material-icons">person_4</i>
               <span class="sb-child-label">Absensi Guru</span>
            </a>
         </div>
      </div>

      <!-- Data Master group -->
      <?php $mGrp = sbOpen($context, ['siswa','guru','kelas','jadwal-pelajaran','mapel','seragam','petugas']); ?>
      <div class="sb-section-label">Data Master</div>
      <div class="sb-group">
         <div class="sb-group-header <?= $mGrp ?>" data-tooltip="Data Master">
            <i class="material-icons">storage</i>
            <span class="sb-group-title">Data Master</span>
            <i class="material-icons sb-chevron">expand_more</i>
         </div>
         <div class="sb-children <?= $mGrp ?>">
            <?php if (is_superadmin()): ?>
            <a data-ajax-nav class="sb-child-item <?= sbActive($context, 'siswa') ?>" href="<?= base_url('admin/siswa') ?>">
               <i class="material-icons">person</i>
               <span class="sb-child-label">Data Siswa</span>
            </a>
            <a data-ajax-nav class="sb-child-item <?= sbActive($context, 'guru') ?>" href="<?= base_url('admin/guru') ?>">
               <i class="material-icons">person_4</i>
               <span class="sb-child-label">Data Guru</span>
            </a>
            <a data-ajax-nav class="sb-child-item <?= sbActive($context, 'kelas') ?>" href="<?= base_url('admin/kelas') ?>">
               <i class="material-icons">school</i>
               <span class="sb-child-label">Kelas &amp; Jurusan</span>
            </a>
            <?php endif; ?>
            <a data-ajax-nav class="sb-child-item <?= sbActive($context, 'mapel') ?>" href="<?= base_url('admin/mapel') ?>">
               <i class="material-icons">class</i>
               <span class="sb-child-label">Mata Pelajaran</span>
            </a>
            <a data-ajax-nav class="sb-child-item <?= sbActive($context, 'jadwal-pelajaran') ?>" href="<?= base_url('admin/jadwal-pelajaran') ?>">
               <i class="material-icons">menu_book</i>
               <span class="sb-child-label">Jadwal Pelajaran</span>
            </a>
            <a data-ajax-nav class="sb-child-item <?= sbActive($context, 'seragam') ?>" href="<?= base_url('admin/seragam') ?>">
               <i class="material-icons">checkroom</i>
               <span class="sb-child-label">Ketentuan Seragam</span>
            </a>
            <?php if (is_superadmin()): ?>
            <a data-ajax-nav class="sb-child-item <?= sbActive($context, 'petugas') ?>" href="<?= base_url('admin/petugas') ?>">
               <i class="material-icons">computer</i>
               <span class="sb-child-label">Data Petugas</span>
            </a>
            <?php endif; ?>
         </div>
      </div>

      <!-- Laporan & QR group -->
      <?php $lGrp = sbOpen($context, ['qr','laporan']); ?>
      <div class="sb-section-label">Laporan &amp; QR</div>
      <div class="sb-group">
         <div class="sb-group-header <?= $lGrp ?>" data-tooltip="Laporan &amp; QR">
            <i class="material-icons">assessment</i>
            <span class="sb-group-title">Laporan &amp; QR</span>
            <i class="material-icons sb-chevron">expand_more</i>
         </div>
         <div class="sb-children <?= $lGrp ?>">
            <?php if (can_generate_qr()): ?>
            <a data-ajax-nav class="sb-child-item <?= sbActive($context, 'qr') ?>" href="<?= base_url('admin/generate') ?>">
               <i class="material-icons">qr_code</i>
               <span class="sb-child-label">Generate QR Code</span>
            </a>
            <?php endif; ?>
            <?php if (can_view_report()): ?>
            <a data-ajax-nav class="sb-child-item <?= sbActive($context, 'laporan') ?>" href="<?= base_url('admin/laporan') ?>">
               <i class="material-icons">print</i>
               <span class="sb-child-label">Generate Laporan</span>
            </a>
            <?php endif; ?>
         </div>
      </div>

      <!-- Sistem group -->
      <?php if (is_superadmin() || is_kepsek()): ?>
      <?php $sGrp = sbOpen($context, ['backup','general_settings']); ?>
      <div class="sb-section-label">Sistem</div>
      <div class="sb-group">
         <div class="sb-group-header <?= $sGrp ?>" data-tooltip="Sistem">
            <i class="material-icons">settings</i>
            <span class="sb-group-title">Sistem</span>
            <i class="material-icons sb-chevron">expand_more</i>
         </div>
         <div class="sb-children <?= $sGrp ?>">
            <?php if (is_superadmin()): ?>
            <a data-ajax-nav class="sb-child-item <?= sbActive($context, 'backup') ?>" href="<?= base_url('admin/backup') ?>">
               <i class="material-icons">backup</i>
               <span class="sb-child-label">Backup &amp; Restore</span>
            </a>
            <?php endif; ?>
            <a data-ajax-nav class="sb-child-item <?= sbActive($context, 'general_settings') ?>" href="<?= base_url('admin/general-settings') ?>">
               <i class="material-icons">tune</i>
               <span class="sb-child-label">Pengaturan</span>
            </a>
         </div>
      </div>
      <?php endif; ?>

      <?php endif; /* end admin/wali kelas */ ?>

   </nav><!-- /.sb-nav -->

   <!-- ————————————————————————————— -->
   <div class="sb-footer">
      <div class="sb-user" data-tooltip="<?= esc(user()->toArray()['email'] ?? '') ?>">
         <div class="sb-avatar"><i class="material-icons">account_circle</i></div>
         <div class="sb-user-info">
            <div class="sb-uname"><?= esc(user()->toArray()['username'] ?? 'User') ?></div>
            <div class="sb-urole"><?= esc(user_role()->label() ?? '') ?></div>
         </div>
         <a href="<?= base_url('/logout') ?>" class="sb-user-action" title="Logout" data-tooltip="Logout">
            <i class="material-icons">exit_to_app</i>
         </a>
      </div>
   </div>
</aside>

<script>
document.addEventListener('DOMContentLoaded', function () {
   'use strict';
   const sidebar        = document.getElementById('appSidebar');
   const wrapper        = document.querySelector('.app-wrapper');
   const toggleBtn      = document.getElementById('sidebarToggle');
   const mobileBtn      = document.getElementById('sidebarMobileToggle');
   const overlay        = document.getElementById('sidebarOverlay');
   const searchInp      = document.getElementById('sidebarSearch');
   const STORAGE_KEY    = 'appSidebarCollapsed';
   const MOBILE_BP      = 991; // px

   function isMobile() { return window.innerWidth <= MOBILE_BP; }

   /* â”€â”€ Desktop: collapse/expand â”€â”€ */
   function applyCollapse(collapsed) {
      sidebar.classList.toggle('collapsed', collapsed);
      if (wrapper) wrapper.classList.toggle('sb-collapsed', collapsed);
      try { localStorage.setItem(STORAGE_KEY, collapsed ? '1' : '0'); } catch(e){}
   }

   // Restore desktop collapse state from storage
   try {
      if (!isMobile() && localStorage.getItem(STORAGE_KEY) === '1') applyCollapse(true);
   } catch(e){}

   /* â”€â”€ Mobile: open/close sidebar â”€â”€ */
   function openMobileSidebar() {
      sidebar.classList.add('mobile-open');
      if (overlay) overlay.classList.add('active');
      document.body.style.overflow = 'hidden'; // prevent scroll behind overlay
   }

   function closeMobileSidebar() {
      sidebar.classList.remove('mobile-open');
      if (overlay) overlay.classList.remove('active');
      document.body.style.overflow = '';
   }

   /* â”€â”€ Toggle button inside sidebar header â”€â”€ */
   if (toggleBtn) {
      toggleBtn.addEventListener('click', function () {
         if (isMobile()) {
            // On mobile, this button closes the sidebar
            closeMobileSidebar();
         } else {
            applyCollapse(!sidebar.classList.contains('collapsed'));
         }
      });
   }

   /* â”€â”€ Mobile hamburger in navbar â”€â”€ */
   if (mobileBtn) {
      mobileBtn.addEventListener('click', function () {
         if (sidebar.classList.contains('mobile-open')) {
            closeMobileSidebar();
         } else {
            openMobileSidebar();
         }
      });
   }

   /* â”€â”€ Overlay click closes sidebar â”€â”€ */
   if (overlay) {
      overlay.addEventListener('click', closeMobileSidebar);
   }

   /* â”€â”€ Close sidebar on nav link click (mobile) â”€â”€ */
   sidebar.querySelectorAll('.sb-item, .sb-child-item').forEach(function (link) {
      link.addEventListener('click', function () {
         if (isMobile()) closeMobileSidebar();
      });
   });

   /* â”€â”€ Handle resize: clean up mobile state when going to desktop â”€â”€ */
   window.addEventListener('resize', function () {
      if (!isMobile()) {
         closeMobileSidebar(); // clean mobile classes
         // Restore desktop collapse state
         try {
            applyCollapse(localStorage.getItem(STORAGE_KEY) === '1');
         } catch(e){}
      } else {
         // On mobile, remove collapsed state so sidebar shows full-width when opened
         sidebar.classList.remove('collapsed');
         if (wrapper) wrapper.classList.remove('sb-collapsed');
      }
   });

   /* â”€â”€ Accordion â”€â”€ */
   document.querySelectorAll('.sb-group-header').forEach(function (header) {
      header.addEventListener('click', function () {
         // Block accordion only on desktop icon-only collapsed mode
         if (!isMobile() && sidebar.classList.contains('collapsed')) return;
         const children = header.nextElementSibling;
         const isOpen   = header.classList.contains('open');
         // Close all other groups
         header.closest('.sb-nav').querySelectorAll('.sb-group-header.open').forEach(function (h) {
            if (h !== header) {
               h.classList.remove('open');
               if (h.nextElementSibling) h.nextElementSibling.classList.remove('open');
            }
         });
         header.classList.toggle('open', !isOpen);
         if (children) children.classList.toggle('open', !isOpen);
      });
   });

   /* â”€â”€ Search filter â”€â”€ */
   if (searchInp) {
      searchInp.addEventListener('input', function () {
         const q = this.value.trim().toLowerCase();

         document.querySelectorAll('.sb-item, .sb-child-item').forEach(function (el) {
            const label = el.querySelector('.sb-item-label, .sb-child-label');
            const text  = (label ? label.textContent : el.textContent).toLowerCase();
            el.classList.toggle('sb-hidden', q !== '' && !text.includes(q));
         });

         document.querySelectorAll('.sb-group').forEach(function (grp) {
            const header   = grp.querySelector('.sb-group-header');
            const children = grp.querySelector('.sb-children');
            if (!header || !children) return;
            const hasVisible = [...children.querySelectorAll('.sb-child-item')]
               .some(function (c) { return !c.classList.contains('sb-hidden'); });
            if (q === '') {
               grp.classList.remove('sb-hidden');
            } else {
               grp.classList.toggle('sb-hidden', !hasVisible);
               if (hasVisible) { header.classList.add('open'); children.classList.add('open'); }
            }
         });

         document.querySelectorAll('.sb-section-label').forEach(function (lbl) {
            lbl.classList.toggle('sb-hidden', q !== '');
         });
      });
   }
});
</script>
