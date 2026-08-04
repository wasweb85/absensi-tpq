<?php
// Helper equivalent in Blade
if (!isset($context)) {
    $context = $ctx ?? 'dashboard';
}

function sbActive(string $ctx, string|array $match): string {
    if (is_array($match)) return in_array($ctx, $match) ? 'active' : '';
    return $ctx === $match ? 'active' : '';
}

function sbOpen(string $ctx, array $contexts): string {
    return in_array($ctx, $contexts) ? 'open' : '';
}

$user = auth()->user();
$isWaliKelas = $user ? !empty($user->id_guru) : false;
$isSuperadmin = $user ? ($user->is_superadmin == 1) : false;
$isKepsek = $user ? ($user->is_superadmin == 2) : false;
$canGenerateQR = $user ? in_array($user->is_superadmin, [1, 3]) : false;
$canViewReport = $user ? in_array($user->is_superadmin, [1, 2, 3]) : false;
$roleLabel = $user ? match($user->is_superadmin) {
    0 => 'Scanner',
    1 => 'Super Admin',
    2 => 'Kepsek',
    3 => 'Staf Petugas',
    default => 'User'
} : 'User';
?>

<aside id="appSidebar" class="app-sidebar" data-context="{{ $context }}">

   <!-- ── Header ──────────────────────────── -->
   <div class="sb-header">
      <div class="sb-brand-icon">
         <i class="material-icons">school</i>
      </div>
      <div class="sb-brand-text">
         <div class="sb-role">{{ $isWaliKelas ? 'Wali Kelas' : 'Operator' }}</div>
         <div class="sb-school">Sekolah</div>
      </div>
      <button class="sb-toggle" id="sidebarToggle" title="Toggle Sidebar">
         <i class="material-icons">menu_open</i>
      </button>
   </div>

   <!-- ── Search ──────────────────────────── -->
   <div class="sb-search-wrap">
      <div class="sb-search-inner">
         <i class="material-icons">search</i>
         <input type="text" id="sidebarSearch" placeholder="Cari menu" autocomplete="off">
      </div>
   </div>

   <!-- ── Nav ─────────────────────────────── -->
   <nav class="sb-nav">

      @if ($isWaliKelas)
      <!-- ======= WALI KELAS MENU ======= -->

      <!-- Dashboard -->
      <a wire:navigate.hover class="sb-item {{ sbActive($context, 'dashboard') }}" href="{{ url('teacher/dashboard') }}" data-tooltip="Dashboard">
         <i class="material-icons">dashboard</i>
         <span class="sb-item-label">Dashboard Wali Kelas</span>
      </a>

      <!-- Kelas group -->
      @php $kGrp = sbOpen($context, ['absen-manual', 'scan', 'siswa-kelas', 'laporan-kelas']); @endphp
      <div class="sb-section-label">Kelas</div>
      <div class="sb-group">
         <div class="sb-group-header {{ $kGrp }}" data-tooltip="Manajemen Kelas">
            <i class="material-icons">school</i>
            <span class="sb-group-title">Manajemen Kelas</span>
            <i class="material-icons sb-chevron">expand_more</i>
         </div>
         <div class="sb-children {{ $kGrp }}">
            <a wire:navigate.hover class="sb-child-item {{ sbActive($context, 'absen-manual') }}" href="{{ url('manual-attendance') }}">
               <i class="material-icons">edit_note</i>
               <span class="sb-child-label">Input Absensi</span>
            </a>
            <a class="sb-child-item {{ sbActive($context, 'scan') }}" href="{{ url('scan') }}">
               <i class="material-icons">qr_code_scanner</i>
               <span class="sb-child-label">Scan QR Code</span>
            </a>
            <a wire:navigate.hover class="sb-child-item {{ sbActive($context, 'siswa-kelas') }}" href="{{ url('teacher/siswa') }}">
               <i class="material-icons">people</i>
               <span class="sb-child-label">Data Siswa</span>
            </a>
            <a wire:navigate.hover class="sb-child-item {{ sbActive($context, 'laporan-kelas') }}" href="{{ url('teacher/laporan') }}">
               <i class="material-icons">print</i>
               <span class="sb-child-label">Laporan Kelas</span>
            </a>
         </div>
      </div>


      @else
      <!-- ======= ADMIN MENU ======= -->

      <!-- Dashboard -->
      <a wire:navigate.hover class="sb-item {{ sbActive($context, 'dashboard') }}" href="{{ url('dashboard') }}" data-tooltip="Dashboard">
         <i class="material-icons">dashboard</i>
         <span class="sb-item-label">Dashboard</span>
      </a>

      @if (!$isSuperadmin)
      <!-- Absensi group -->
      @php $aGrp = sbOpen($context, ['absen-siswa', 'absen-guru']); @endphp
      <div class="sb-section-label">Absensi</div>
      <div class="sb-group">
         <div class="sb-group-header {{ $aGrp }}" data-tooltip="Absensi">
            <i class="material-icons">checklist</i>
            <span class="sb-group-title">Absensi</span>
            <i class="material-icons sb-chevron">expand_more</i>
         </div>
         <div class="sb-children {{ $aGrp }}">
            <a wire:navigate.hover class="sb-child-item {{ sbActive($context, 'absen-siswa') }}" href="{{ url('admin/absen-siswa') }}">
               <i class="material-icons">person</i>
               <span class="sb-child-label">Absensi Siswa</span>
            </a>
            <a wire:navigate.hover class="sb-child-item {{ sbActive($context, 'absen-guru') }}" href="{{ url('admin/absen-guru') }}">
               <i class="material-icons">person_4</i>
               <span class="sb-child-label">Absensi Guru</span>
            </a>
         </div>
      </div>
      @endif

      <!-- Data Master group -->
      @php $mGrp = sbOpen($context, ['siswa', 'guru', 'kelas', 'mapel', 'jadwal-pelajaran', 'petugas']); @endphp
      <div class="sb-section-label">Data Master</div>
      <div class="sb-group">
         <div class="sb-group-header {{ $mGrp }}" data-tooltip="Data Master">
            <i class="material-icons">storage</i>
            <span class="sb-group-title">Data Master</span>
            <i class="material-icons sb-chevron">expand_more</i>
         </div>
         <div class="sb-children {{ $mGrp }}">
            @if ($isSuperadmin || ($user && in_array($user->is_superadmin, [0, 1, 3])))
            <a wire:navigate.hover class="sb-child-item {{ sbActive($context, 'siswa') }}" href="{{ url('admin/siswa') }}">
               <i class="material-icons">person</i>
               <span class="sb-child-label">Data Santri</span>
            </a>
            <a wire:navigate.hover class="sb-child-item {{ sbActive($context, 'guru') }}" href="{{ url('admin/guru') }}">
               <i class="material-icons">person_4</i>
               <span class="sb-child-label">Data Guru</span>
            </a>
            <a wire:navigate.hover class="sb-child-item {{ sbActive($context, 'kelas') }}" href="{{ url('admin/kelas') }}">
               <i class="material-icons">school</i>
               <span class="sb-child-label">Kelas</span>
            </a>
            @endif
            <a wire:navigate.hover class="sb-child-item {{ sbActive($context, 'mapel') }}" href="{{ url('admin/mapel') }}">
               <i class="material-icons">class</i>
               <span class="sb-child-label">Mata Pelajaran</span>
            </a>
            <a wire:navigate.hover class="sb-child-item {{ sbActive($context, 'jadwal-pelajaran') }}" href="{{ url('admin/jadwal-pelajaran') }}">
               <i class="material-icons">menu_book</i>
               <span class="sb-child-label">Jadwal Pelajaran</span>
            </a>
            @if ($isSuperadmin)
            <a wire:navigate.hover class="sb-child-item {{ sbActive($context, 'petugas') }}" href="{{ url('admin/petugas') }}">
               <i class="material-icons">computer</i>
               <span class="sb-child-label">Data Petugas</span>
            </a>
            @endif
         </div>
      </div>

      <!-- Laporan & QR group -->
      @php $lGrp = sbOpen($context, ['scan', 'qr', 'laporan']); @endphp
      <div class="sb-section-label">Laporan &amp; QR</div>
      <div class="sb-group">
         <div class="sb-group-header {{ $lGrp }}" data-tooltip="Laporan &amp; QR">
            <i class="material-icons">assessment</i>
            <span class="sb-group-title">Laporan &amp; QR</span>
            <i class="material-icons sb-chevron">expand_more</i>
         </div>
         <div class="sb-children {{ $lGrp }}">
            <a class="sb-child-item {{ sbActive($context, 'scan') }}" href="{{ url('scan') }}">
               <i class="material-icons">qr_code_scanner</i>
               <span class="sb-child-label">Scan QR Code</span>
            </a>
            @if ($canGenerateQR)
            <a wire:navigate.hover class="sb-child-item {{ sbActive($context, 'qr') }}" href="{{ url('admin/qr') }}">
               <i class="material-icons">qr_code</i>
               <span class="sb-child-label">Generate QR Code</span>
            </a>
            @endif
            @if ($canViewReport)
            <a wire:navigate.hover class="sb-child-item {{ sbActive($context, 'laporan') }}" href="{{ url('admin/laporan') }}">
               <i class="material-icons">print</i>
               <span class="sb-child-label">Generate Laporan</span>
            </a>
            @endif
         </div>
      </div>

      <!-- Sistem group -->
      @if ($isSuperadmin || $isKepsek)
      @php $sGrp = sbOpen($context, ['backup', 'general_settings']); @endphp
      <div class="sb-section-label">Sistem</div>
      <div class="sb-group">
         <div class="sb-group-header {{ $sGrp }}" data-tooltip="Sistem">
            <i class="material-icons">settings</i>
            <span class="sb-group-title">Sistem</span>
            <i class="material-icons sb-chevron">expand_more</i>
         </div>
         <div class="sb-children {{ $sGrp }}">
            @if ($isSuperadmin)
            <a wire:navigate.hover class="sb-child-item {{ sbActive($context, 'backup') }}" href="{{ url('admin/backup') }}">
               <i class="material-icons">backup</i>
               <span class="sb-child-label">Backup &amp; Restore</span>
            </a>
            @endif
            <a wire:navigate.hover class="sb-child-item {{ sbActive($context, 'general_settings') }}" href="{{ url('admin/general-settings') }}">
               <i class="material-icons">tune</i>
               <span class="sb-child-label">Pengaturan</span>
            </a>
         </div>
      </div>
      @endif

      @endif

   </nav>

   <div class="sb-footer p-4">
      <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display: none;">
         @csrf
      </form>
      <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center text-gray-400 hover:text-white transition-colors" title="Logout">
         <i class="material-icons mr-2">logout</i>
         <span class="text-sm font-medium">Keluar Aplikasi</span>
      </a>
   </div>
</aside>

<script data-navigate-once>
document.addEventListener('livewire:navigated', function () {
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
         if (isMobile()) {
            closeMobileSidebar();
         } else {
            applyCollapse(!sidebar.classList.contains('collapsed'));
         }
      });
   }

   if (mobileBtn) {
      mobileBtn.addEventListener('click', function () {
         if (sidebar.classList.contains('mobile-open')) {
            closeMobileSidebar();
         } else {
            openMobileSidebar();
         }
      });
   }

   if (overlay) {
      overlay.addEventListener('click', closeMobileSidebar);
   }

   sidebar.querySelectorAll('.sb-item, .sb-child-item').forEach(function (link) {
      link.addEventListener('click', function () {
         if (isMobile()) closeMobileSidebar();
      });
   });

   if (!window._sidebarResizeAttached) {
      window.addEventListener('resize', function () {
         if (!isMobile()) {
            closeMobileSidebar(); 
            try {
               applyCollapse(localStorage.getItem(STORAGE_KEY) === '1');
            } catch(e){}
         } else {
            sidebar.classList.remove('collapsed');
            if (wrapper) wrapper.classList.remove('sb-collapsed');
         }
      });
      window._sidebarResizeAttached = true;
   }

   document.querySelectorAll('.sb-group-header').forEach(function (header) {
      header.addEventListener('click', function () {
         if (!isMobile() && sidebar.classList.contains('collapsed')) return;
         const children = header.nextElementSibling;
         const isOpen   = header.classList.contains('open');
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
