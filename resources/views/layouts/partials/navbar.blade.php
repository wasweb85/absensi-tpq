<!-- Navbar -->
<nav class="navbar fixed-top bg-white border-b border-gray-100 shadow-sm z-50" style="padding: 0; min-height: 64px;">
   <div class="container-fluid px-6 w-full flex justify-between items-center h-full h-16">
      
      <!-- Left: Hamburger & Brand -->
      <div class="flex items-center">
         <button class="sb-mobile-btn text-gray-500 hover:text-gray-800 transition-colors mr-4 bg-transparent border-none focus:outline-none flex items-center" id="sidebarMobileToggle" type="button" aria-label="Buka Sidebar">
            <i class="material-icons text-[26px]">menu</i>
         </button>
         <h1 class="text-xl font-bold text-[#1f2937] m-0 tracking-tight">{{ $title ?? 'Dashboard' }}</h1>
      </div>
      
      <!-- Right: Date, Notification & Profile -->
      <div class="flex items-center gap-5">
         <!-- Date Display -->
         <div class="text-right hidden sm:block">
            <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-0.5">HARI INI</div>
            <div class="text-[13px] font-bold text-gray-700">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</div>
         </div>
         
         <!-- Divider -->
         <div class="hidden sm:block h-8 w-px bg-gray-200 mx-1"></div>

         <!-- Notification Icon -->
         <div class="relative flex items-center justify-center w-10 h-10 rounded-full border border-gray-200 bg-white hover:bg-gray-50 transition-colors cursor-pointer shadow-sm">
            <i class="material-icons text-gray-500 text-[22px]">notifications_none</i>
            <!-- Red Dot -->
            <span class="absolute top-2 right-[9px] w-[9px] h-[9px] bg-red-500 rounded-full border-[2px] border-white"></span>
         </div>
         
         <!-- User Profile -->
         <div class="flex items-center gap-3 pl-2">
            <div class="text-right hidden sm:block">
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
            <div class="h-10 w-10 rounded-full bg-purple-50 flex items-center justify-center text-purple-600 font-bold text-lg border border-purple-100 shadow-sm">
               {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>
         </div>
      </div>

   </div>
</nav>
<!-- End Navbar -->
