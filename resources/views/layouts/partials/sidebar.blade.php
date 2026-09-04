<?php

use App\Models\RolePermission;

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
$isStaf = $user ? ($user->is_superadmin == 3) : false;

$roleLabel = $user ? match((int)$user->is_superadmin) {
    1 => 'Super Admin',
    2 => 'Kepala TPQ',
    3 => 'Staf Petugas',
    default => ($isWaliKelas ? 'Wali Kelas' : 'Operator')
} : 'User';
?>

<aside id="appSidebar" class="app-sidebar" data-context="{{ $context }}">

   <!-- ── Header ──────────────────────────── -->
   <div class="sb-header">
      <div class="sb-brand-icon flex items-center justify-center">
         @if (!empty($appSettings->logo) && file_exists(public_path('uploads/logo/' . $appSettings->logo)))
            <img src="{{ asset('uploads/logo/' . $appSettings->logo) }}" alt="Logo" style="max-height: 32px; max-width: 32px; object-fit: contain;">
         @else
            <i class="material-icons">school</i>
         @endif
      </div>
      <div class="sb-brand-text">
         <div class="sb-role">{{ $roleLabel }}</div>
         <div class="sb-school">{{ $appSettings->school_name ?? 'Sekolah' }}</div>
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

      @if ((int)($user->is_superadmin ?? 0) === 0 && !empty($user->id_guru))
      <!-- ======= GURU / WALI KELAS MENU (DYNAMIC PERMISSIONS) ======= -->

      <!-- Dashboard -->
      @if (RolePermission::hasAccess($user, 'dashboard'))
      <a wire:navigate.hover class="sb-item {{ sbActive($context, 'dashboard') }}" href="{{ url('teacher/dashboard') }}" data-tooltip="Dashboard Guru">
         <i class="material-icons">dashboard</i>
         <span class="sb-item-label">Dashboard Guru</span>
      </a>
      @endif

      @if (RolePermission::hasAccess($user, 'monitoring') || RolePermission::hasAccess($user, 'scan_qr') || RolePermission::hasAccess($user, 'data_santri') || RolePermission::hasAccess($user, 'laporan'))
      <div class="sb-section-label">Manajemen Kelas</div>
      @endif

      @if (RolePermission::hasAccess($user, 'monitoring'))
      <a wire:navigate.hover class="sb-item {{ sbActive($context, 'absen-manual') }}" href="{{ url('manual-attendance') }}" data-tooltip="Monitoring & Absensi">
         <i class="material-icons">fact_check</i>
         <span class="sb-item-label">Monitoring &amp; Absensi</span>
      </a>
      
      <a wire:navigate.hover class="sb-item {{ sbActive($context, 'riwayat-tabungan') }}" href="{{ url('teacher/riwayat-tabungan') }}" data-tooltip="Riwayat Tabungan">
         <i class="material-icons">savings</i>
         <span class="sb-item-label">Riwayat Tabungan</span>
      </a>
      @endif

      @if (RolePermission::hasAccess($user, 'scan_qr'))
      <a wire:navigate.hover class="sb-item {{ sbActive($context, 'scan') }}" href="{{ url('scan') }}" data-tooltip="Scan QR Code">
         <i class="material-icons">qr_code_scanner</i>
         <span class="sb-item-label">Scan QR Code</span>
      </a>
      @endif

      @if (RolePermission::hasAccess($user, 'data_santri'))
      <a wire:navigate.hover class="sb-item {{ sbActive($context, 'siswa-kelas') }}" href="{{ url('teacher/siswa') }}" data-tooltip="Data Santri">
         <i class="material-icons">people</i>
         <span class="sb-item-label">Data Santri Kelas</span>
      </a>
      @endif

      @if (RolePermission::hasAccess($user, 'generate_qr'))
      <a wire:navigate.hover class="sb-item {{ sbActive($context, 'qr') }}" href="{{ url('teacher/qr') }}" data-tooltip="Generate QR Code">
         <i class="material-icons">qr_code</i>
         <span class="sb-item-label">Generate QR Code</span>
      </a>
      @endif

      @if (RolePermission::hasAccess($user, 'laporan'))
      <a wire:navigate.hover class="sb-item {{ sbActive($context, 'laporan-kelas') }}" href="{{ url('teacher/laporan') }}" data-tooltip="Laporan Kelas">
         <i class="material-icons">print</i>
         <span class="sb-item-label">Laporan Kelas</span>
      </a>
      @endif

      @if (RolePermission::hasAccess($user, 'absen_guru'))
      <a wire:navigate.hover class="sb-item {{ sbActive($context, 'absen-guru') }}" href="{{ url('admin/absen-guru') }}" data-tooltip="Absensi Guru">
         <i class="material-icons">person_4</i>
         <span class="sb-item-label">Absensi Guru</span>
      </a>
      @endif

      @if (RolePermission::hasAccess($user, 'setoran_bendahara'))
      <a wire:navigate.hover class="sb-item {{ sbActive($context, 'setoran-guru') }}" href="{{ url('admin/setoran-guru') }}" data-tooltip="Rekap Setoran">
         <i class="material-icons">account_balance_wallet</i>
         <span class="sb-item-label">Rekap Setoran</span>
      </a>
      @endif

      @if (RolePermission::hasAccess($user, 'laporan_tabungan'))
      <a wire:navigate.hover class="sb-item {{ sbActive($context, 'laporan-tabungan') }}" href="{{ url('admin/laporan-tabungan') }}" data-tooltip="Laporan Tabungan">
         <i class="material-icons">savings</i>
         <span class="sb-item-label">Laporan Tabungan</span>
      </a>
      @endif

      @else
      <!-- ======= ADMIN / KEPSEK / STAF MENU ======= -->

      <!-- Dashboard -->
      @if ($isSuperadmin || RolePermission::hasAccess($user, 'dashboard'))
      <a wire:navigate.hover class="sb-item {{ sbActive($context, 'dashboard') }}" href="{{ $isKepsek ? url('kepsek/dashboard') : url('dashboard') }}" data-tooltip="{{ $isKepsek ? 'Executive Dashboard' : 'Dashboard' }}">
         <i class="material-icons">dashboard</i>
         <span class="sb-item-label">{{ $isKepsek ? 'Executive Dashboard' : 'Dashboard' }}</span>
      </a>
      @endif

      @if (!$isSuperadmin && RolePermission::hasAccess($user, 'monitoring'))
      <a wire:navigate.hover class="sb-item {{ sbActive($context, 'absen-manual') }}" href="{{ url('manual-attendance') }}" data-tooltip="Monitoring & Absensi">
         <i class="material-icons">fact_check</i>
         <span class="sb-item-label">Monitoring &amp; Absensi</span>
      </a>
      @endif

      @if (!$isSuperadmin && RolePermission::hasAccess($user, 'absen_guru'))
      <a wire:navigate.hover class="sb-item {{ sbActive($context, 'absen-guru') }}" href="{{ url('admin/absen-guru') }}" data-tooltip="Absensi Guru">
         <i class="material-icons">person_4</i>
         <span class="sb-item-label">Absensi Guru</span>
      </a>
      @endif

      {{-- Data Master group --}}
      @php
         $canDataSantri = $isSuperadmin || RolePermission::hasAccess($user, 'data_santri');
         $canDataGuru = $isSuperadmin || RolePermission::hasAccess($user, 'data_guru');
         $canKelas = $isSuperadmin;
         $canMapel = $isSuperadmin;
         $canJadwal = $isSuperadmin;
         $canPetugas = $isSuperadmin;
         $hasDataMasterGroup = $canDataSantri || $canDataGuru || $canKelas || $canMapel || $canJadwal || $canPetugas;
         $mGrp = sbOpen($context, ['siswa', 'guru', 'kelas', 'mapel', 'jadwal-pelajaran', 'petugas']);
      @endphp

      @if ($hasDataMasterGroup)
      <div class="sb-section-label">Data Master</div>
      <div class="sb-group">
         <div class="sb-group-header {{ $mGrp || sbActive($context, ['siswa', 'guru', 'kelas', 'mapel', 'jadwal-pelajaran', 'petugas']) ? 'open' : '' }}" data-tooltip="Data Master">
            <i class="material-icons">storage</i>
            <span class="sb-group-title">Data Master</span>
            <i class="material-icons sb-chevron">expand_more</i>
         </div>
         <div class="sb-children {{ $mGrp || sbActive($context, ['siswa', 'guru', 'kelas', 'mapel', 'jadwal-pelajaran', 'petugas']) ? 'open' : '' }}">
            @if ($canDataSantri)
            <a wire:navigate.hover class="sb-child-item {{ sbActive($context, 'siswa') }}" href="{{ url('admin/siswa') }}">
               <i class="material-icons">person</i>
               <span class="sb-child-label">Data Santri</span>
            </a>
            @endif
            @if ($canDataGuru)
            <a wire:navigate.hover class="sb-child-item {{ sbActive($context, 'guru') }}" href="{{ url('admin/guru') }}">
               <i class="material-icons">person_4</i>
               <span class="sb-child-label">Data Guru</span>
            </a>
            @endif
            @if ($canKelas)
            <a wire:navigate.hover class="sb-child-item {{ sbActive($context, 'kelas') }}" href="{{ url('admin/kelas') }}">
               <i class="material-icons">school</i>
               <span class="sb-child-label">Kelas</span>
            </a>
            @endif
            @if ($canMapel)
            <a wire:navigate.hover class="sb-child-item {{ sbActive($context, 'mapel') }}" href="{{ url('admin/mapel') }}">
               <i class="material-icons">class</i>
               <span class="sb-child-label">Mata Pelajaran</span>
            </a>
            @endif
            @if ($canJadwal)
            <a wire:navigate.hover class="sb-child-item {{ sbActive($context, 'jadwal-pelajaran') }}" href="{{ url('admin/jadwal-pelajaran') }}">
               <i class="material-icons">menu_book</i>
               <span class="sb-child-label">Jadwal Pelajaran</span>
            </a>
            @endif
            @if ($canPetugas)
            <a wire:navigate.hover class="sb-child-item {{ sbActive($context, 'petugas') }}" href="{{ url('admin/petugas') }}">
               <i class="material-icons">computer</i>
               <span class="sb-child-label">Data Petugas</span>
            </a>
            @endif
         </div>
      </div>
      @endif

      {{-- Laporan & QR group --}}
      @php
         $canScan = $isSuperadmin || RolePermission::hasAccess($user, 'scan_qr');
         $canGenQR = $isSuperadmin || RolePermission::hasAccess($user, 'generate_qr');
         $canLaporan = $isSuperadmin || RolePermission::hasAccess($user, 'laporan');
         $canLaporanTabungan = $isSuperadmin || RolePermission::hasAccess($user, 'laporan_tabungan');
         $hasLaporanGroup = $canScan || $canGenQR || $canLaporan || $canLaporanTabungan;
         $lGrp = sbOpen($context, ['scan', 'qr', 'laporan', 'laporan-tabungan']);
      @endphp

      @if ($hasLaporanGroup)
      <div class="sb-section-label">Laporan &amp; QR</div>
      <div class="sb-group">
         <div class="sb-group-header {{ $lGrp || sbActive($context, ['scan', 'qr', 'laporan', 'laporan-tabungan']) ? 'open' : '' }}" data-tooltip="Laporan &amp; QR">
            <i class="material-icons">assessment</i>
            <span class="sb-group-title">Laporan &amp; QR</span>
            <i class="material-icons sb-chevron">expand_more</i>
         </div>
         <div class="sb-children {{ $lGrp || sbActive($context, ['scan', 'qr', 'laporan', 'laporan-tabungan']) ? 'open' : '' }}">
            @if ($canScan)
            <a wire:navigate.hover class="sb-child-item {{ sbActive($context, 'scan') }}" href="{{ url('scan') }}">
               <i class="material-icons">qr_code_scanner</i>
               <span class="sb-child-label">Scan QR Code</span>
            </a>
            @endif
            @if ($canGenQR)
            <a wire:navigate.hover class="sb-child-item {{ sbActive($context, 'qr') }}" href="{{ url('admin/qr') }}">
               <i class="material-icons">qr_code</i>
               <span class="sb-child-label">Generate QR Code</span>
            </a>
            @endif
            @if ($canLaporanTabungan)
            <a wire:navigate.hover class="sb-child-item {{ sbActive($context, 'laporan-tabungan') }}" href="{{ url('admin/laporan-tabungan') }}">
               <i class="material-icons">savings</i>
               <span class="sb-child-label">Laporan Tabungan</span>
            </a>
            @endif
            @if ($canLaporan)
            <a wire:navigate.hover class="sb-child-item {{ sbActive($context, 'laporan') }}" href="{{ url('admin/laporan') }}">
               <i class="material-icons">print</i>
               <span class="sb-child-label">Generate Laporan</span>
            </a>
            @endif
         </div>
      </div>
      @endif

      {{-- Sistem group --}}
      @php
         $canBackup = $isSuperadmin || RolePermission::hasAccess($user, 'backup');
         $canSettings = $isSuperadmin || RolePermission::hasAccess($user, 'general_settings');
         $canHakAkses = $isSuperadmin;
         $hasSistemGroup = $canBackup || $canSettings || $canHakAkses;
         $sGrp = sbOpen($context, ['backup', 'general_settings', 'hak-akses']);
      @endphp

      @if ($hasSistemGroup)
      <div class="sb-section-label">Sistem</div>
      <div class="sb-group">
         <div class="sb-group-header {{ $sGrp || sbActive($context, ['backup', 'general_settings', 'hak-akses']) ? 'open' : '' }}" data-tooltip="Sistem">
            <i class="material-icons">settings</i>
            <span class="sb-group-title">Sistem</span>
            <i class="material-icons sb-chevron">expand_more</i>
         </div>
         <div class="sb-children {{ $sGrp || sbActive($context, ['backup', 'general_settings', 'hak-akses']) ? 'open' : '' }}">
            @if ($canHakAkses)
            <a wire:navigate.hover class="sb-child-item {{ sbActive($context, 'hak-akses') }}" href="{{ url('admin/hak-akses') }}">
               <i class="material-icons">admin_panel_settings</i>
               <span class="sb-child-label">Hak Akses Role</span>
            </a>
            @endif
            @if ($canBackup)
            <a wire:navigate.hover class="sb-child-item {{ sbActive($context, 'backup') }}" href="{{ url('admin/backup') }}">
               <i class="material-icons">backup</i>
               <span class="sb-child-label">Backup &amp; Restore</span>
            </a>
            @endif
            @if ($canSettings)
            <a wire:navigate.hover class="sb-child-item {{ sbActive($context, 'general_settings') }}" href="{{ url('admin/general-settings') }}">
               <i class="material-icons">tune</i>
               <span class="sb-child-label">Pengaturan</span>
            </a>
            @endif
         </div>
      </div>
      @endif

      @endif

   </nav>

   <div class="sb-footer">
      <div class="sb-user">
         <div class="sb-avatar">
            {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
         </div>
         <div class="sb-user-info">
            <div class="sb-uname">{{ $user->name ?? 'User' }}</div>
            <div class="sb-urole">{{ $roleLabel }}</div>
         </div>
         <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display: none;">
            @csrf
         </form>
         <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="sb-user-action" title="Keluar Aplikasi">
            <i class="material-icons">logout</i>
         </a>
      </div>
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
