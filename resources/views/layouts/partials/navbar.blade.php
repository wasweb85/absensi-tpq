<!-- Navbar -->
<style>
@media (max-width: 991.98px) {
    .navbar.fixed-top {
        width: 100% !important;
        left: 0 !important;
        right: 0 !important;
    }
}
</style>
<nav class="navbar fixed-top bg-white border-b border-gray-100 shadow-sm z-50 flex-nowrap" style="padding: 0; min-height: 64px; flex-wrap: nowrap !important;">
   <div class="container-fluid px-4 sm:px-6 w-full flex flex-nowrap justify-between items-center h-full h-16" style="flex-wrap: nowrap !important;">
      
      <!-- Left: Hamburger & Brand -->
      <div class="flex items-center shrink-0 max-w-[60%] sm:max-w-none">
         <button class="sb-mobile-btn text-gray-500 hover:text-gray-800 transition-colors mr-2 sm:mr-4 bg-transparent border-none focus:outline-none flex items-center shrink-0" id="sidebarMobileToggle" type="button" aria-label="Buka Sidebar">
            <i class="material-icons text-[26px]">menu</i>
         </button>
         <h1 class="text-lg sm:text-xl font-bold text-[#1f2937] m-0 tracking-tight truncate">{{ $nav_title ?? $title ?? 'Dashboard' }}</h1>
      </div>
      
      <!-- Right: Date & Profile -->
      <div class="flex items-center gap-2 sm:gap-5 shrink-0">
         <!-- Date Display -->
         <div class="text-right hidden sm:block">
            <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-0.5">HARI INI</div>
            <div class="text-[13px] font-bold text-gray-700">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</div>
         </div>
         
         <!-- Divider -->
         <div class="hidden sm:block h-8 w-px bg-gray-200 mx-1"></div>
         
         <!-- User Profile -->
         <div class="flex items-center gap-3 pl-2">
            <div class="text-left hidden sm:block">
               <div class="text-[14px] font-bold text-gray-800 leading-tight">{{ auth()->user()->name ?? 'User' }}</div>
               <div class="text-[10px] text-purple-600 font-bold uppercase tracking-widest mt-0.5">
                  {{ match((int)(auth()->user()->is_superadmin ?? 0)) {
                      1 => 'Super Admin',
                      2 => 'Kepsek',
                      3 => 'Staf Petugas',
                      default => (!empty(auth()->user()->id_guru) ? 'Guru' : 'Operator')
                  } }}
               </div>
            </div>
            <!-- Avatar -->
            <div class="h-10 w-10 rounded-full bg-purple-50 flex items-center justify-center text-purple-600 font-bold text-lg border border-purple-100 shadow-sm shrink-0">
               {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>
         </div>
      </div>

   </div>
</nav>
<!-- End Navbar -->
