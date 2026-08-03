<?php
$context = $ctx ?? 'dashboard';
$namaSiswa = esc(session()->get('nama_siswa') ?? 'Siswa');
?>
<aside id="appSidebar" class="app-sidebar" data-context="<?= $context ?>">

   <!-- ── Header ─────────────────────────────── -->
   <div class="sb-header">
      <div class="sb-brand-icon">
         <i class="material-icons">school</i>
      </div>
      <div class="sb-brand-text">
         <div class="sb-role">Panel Siswa</div>
         <div class="sb-school">Halo, <?= $namaSiswa ?></div>
      </div>
      <button class="sb-toggle" id="sidebarToggle" title="Toggle Sidebar">
         <i class="material-icons">menu_open</i>
      </button>
   </div>

   <!-- ── Search ─────────────────────────────── -->
   <div class="sb-search-wrap">
      <div class="sb-search-inner">
         <i class="material-icons">search</i>
         <input type="text" id="sidebarSearch" placeholder="Cari menu…" autocomplete="off">
      </div>
   </div>

   <!-- ── Nav ───────────────────────────────── -->
   <nav class="sb-nav">

      <a class="sb-item <?= $context === 'dashboard' ? 'active' : '' ?>"
         href="<?= base_url('siswa/dashboard') ?>" data-tooltip="Dashboard">
         <i class="material-icons">dashboard</i>
         <span class="sb-item-label">Dashboard</span>
      </a>

      <a class="sb-item <?= $context === 'jadwal-pelajaran' ? 'active' : '' ?>"
         href="<?= base_url('siswa/jadwal') ?>" data-tooltip="Jadwal Pelajaran">
         <i class="material-icons">menu_book</i>
         <span class="sb-item-label">Jadwal Pelajaran</span>
      </a>

      <div class="sb-section-label">Akun</div>

      <a class="sb-item" href="<?= base_url('siswa/logout') ?>" data-tooltip="Logout">
         <i class="material-icons">exit_to_app</i>
         <span class="sb-item-label">Logout</span>
      </a>

   </nav>

   <!-- ── Footer / User ─────────────────────── -->
   <div class="sb-footer">
      <div class="sb-user" data-tooltip="<?= $namaSiswa ?>">
         <div class="sb-avatar"><i class="material-icons">account_circle</i></div>
         <div class="sb-user-info">
            <div class="sb-uname"><?= $namaSiswa ?></div>
            <div class="sb-urole">Siswa</div>
         </div>
         <a href="<?= base_url('siswa/logout') ?>" class="sb-user-action" title="Logout">
            <i class="material-icons">exit_to_app</i>
         </a>
      </div>
   </div>
</aside>

<script>
document.addEventListener('DOMContentLoaded', function () {
   'use strict';
   const sidebar     = document.getElementById('appSidebar');
   const wrapper     = document.querySelector('.app-wrapper');
   const toggleBtn   = document.getElementById('sidebarToggle');
   const mobileBtn   = document.getElementById('sidebarMobileToggle');
   const overlay     = document.getElementById('sidebarOverlay');
   const searchInp   = document.getElementById('sidebarSearch');
   const STORAGE_KEY = 'appSidebarCollapsed';
   const MOBILE_BP   = 991;

   function isMobile() { return window.innerWidth <= MOBILE_BP; }

   function applyCollapse(collapsed) {
      sidebar.classList.toggle('collapsed', collapsed);
      if (wrapper) wrapper.classList.toggle('sb-collapsed', collapsed);
      try { localStorage.setItem(STORAGE_KEY, collapsed ? '1' : '0'); } catch(e){}
   }

   try {
      if (!isMobile() && localStorage.getItem(STORAGE_KEY) === '1') applyCollapse(true);
   } catch(e){}

   function openMobileSidebar() {
      sidebar.classList.add('mobile-open');
      if (overlay) overlay.classList.add('active');
      document.body.style.overflow = 'hidden';
   }

   function closeMobileSidebar() {
      sidebar.classList.remove('mobile-open');
      if (overlay) overlay.classList.remove('active');
      document.body.style.overflow = '';
   }

   if (toggleBtn) {
      toggleBtn.addEventListener('click', function () {
         if (isMobile()) { closeMobileSidebar(); }
         else { applyCollapse(!sidebar.classList.contains('collapsed')); }
      });
   }

   if (mobileBtn) {
      mobileBtn.addEventListener('click', function () {
         sidebar.classList.contains('mobile-open') ? closeMobileSidebar() : openMobileSidebar();
      });
   }

   if (overlay) { overlay.addEventListener('click', closeMobileSidebar); }

   sidebar.querySelectorAll('.sb-item').forEach(function (link) {
      link.addEventListener('click', function () { if (isMobile()) closeMobileSidebar(); });
   });

   window.addEventListener('resize', function () {
      if (!isMobile()) {
         closeMobileSidebar();
         try { applyCollapse(localStorage.getItem(STORAGE_KEY) === '1'); } catch(e){}
      } else {
         sidebar.classList.remove('collapsed');
         if (wrapper) wrapper.classList.remove('sb-collapsed');
      }
   });

   if (searchInp) {
      searchInp.addEventListener('input', function () {
         const q = this.value.trim().toLowerCase();
         document.querySelectorAll('.sb-item').forEach(function (el) {
            const label = el.querySelector('.sb-item-label');
            const text  = (label ? label.textContent : el.textContent).toLowerCase();
            el.classList.toggle('sb-hidden', q !== '' && !text.includes(q));
         });
      });
   }
});
</script>