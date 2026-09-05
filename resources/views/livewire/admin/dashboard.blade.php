<div class="px-4 py-6 w-full">
    <!-- Header Section -->
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Overview</h2>
            <p class="text-gray-500 mt-1">Sistem Informasi Absensi TPQ</p>
        </div>
        <div class="mt-4 md:mt-0 flex items-center bg-white/60 backdrop-blur-md px-4 py-2 rounded-full border border-gray-100 shadow-sm">
            <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span class="text-sm font-medium text-gray-700">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card 1: Siswa -->
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl p-6 border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center transition-transform duration-300 group-hover:scale-110 group-hover:bg-purple-600 group-hover:text-white shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 bg-green-100 text-green-700 rounded-full flex items-center">
                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span> Aktif
                </span>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ $totalSiswa }}</h3>
                <p class="text-sm font-medium text-gray-500 mt-1">Total Siswa Terdaftar</p>
            </div>
        </div>

        <!-- Card 2: Guru -->
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl p-6 border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center transition-transform duration-300 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                </div>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ $totalGuru }}</h3>
                <p class="text-sm font-medium text-gray-500 mt-1">Total Guru / Ustadz</p>
            </div>
        </div>

        <!-- Card 3: Kelas -->
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl p-6 border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center transition-transform duration-300 group-hover:scale-110 group-hover:bg-amber-500 group-hover:text-white shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ $totalKelas }}</h3>
                <p class="text-sm font-medium text-gray-500 mt-1">Total Rombel Kelas</p>
            </div>
        </div>

        <!-- Card 4: Petugas -->
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl p-6 border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center transition-transform duration-300 group-hover:scale-110 group-hover:bg-rose-500 group-hover:text-white shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ $totalPetugas }}</h3>
                <p class="text-sm font-medium text-gray-500 mt-1">Admin & Operator</p>
            </div>
        </div>
    </div>

    <!-- Attendance Operational Data -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Absensi Siswa Section -->
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden relative">
            <div class="absolute top-0 right-0 w-32 h-32 bg-purple-500/5 rounded-bl-full -z-10"></div>
            
            <div class="px-8 py-6 border-b border-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Absensi Siswa</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Pantauan hari ini</p>
                </div>
                <div class="relative">
                    <select wire:model.live="selectedKelas" class="appearance-none bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-purple-500 focus:border-purple-500 block w-full py-2.5 pl-4 pr-10 transition-colors cursor-pointer hover:bg-gray-100">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasOptions as $k)
                            <option value="{{ $k->id_kelas }}">{{ $k->kelas }} ({{ $k->siswa_count }} Siswa)</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>
            
            <div class="p-8 relative">
                <!-- Loader overlay -->
                <div wire:loading wire:target="selectedKelas" class="absolute inset-0 bg-white/80 backdrop-blur-sm z-10 flex items-center justify-center rounded-b-3xl">
                    <div class="w-10 h-10 border-4 border-purple-200 border-t-purple-600 rounded-full animate-spin"></div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
                    <div class="flex flex-col p-4 bg-green-50/50 rounded-2xl border border-green-100/50 hover:bg-green-50 transition-colors">
                        <span class="text-green-600 font-bold text-sm tracking-wide uppercase mb-2">Hadir</span>
                        <span class="text-3xl font-black text-gray-900">{{ $siswaStats['hadir'] }}</span>
                    </div>
                    <div class="flex flex-col p-4 bg-yellow-50/50 rounded-2xl border border-yellow-100/50 hover:bg-yellow-50 transition-colors">
                        <span class="text-yellow-600 font-bold text-sm tracking-wide uppercase mb-2">Sakit</span>
                        <span class="text-3xl font-black text-gray-900">{{ $siswaStats['sakit'] }}</span>
                    </div>
                    <div class="flex flex-col p-4 bg-blue-50/50 rounded-2xl border border-blue-100/50 hover:bg-blue-50 transition-colors">
                        <span class="text-blue-600 font-bold text-sm tracking-wide uppercase mb-2">Izin</span>
                        <span class="text-3xl font-black text-gray-900">{{ $siswaStats['izin'] }}</span>
                    </div>
                    <div class="flex flex-col p-4 bg-red-50/50 rounded-2xl border border-red-100/50 hover:bg-red-50 transition-colors">
                        <span class="text-red-600 font-bold text-sm tracking-wide uppercase mb-2">Alfa</span>
                        <span class="text-3xl font-black text-gray-900">{{ $siswaStats['alfa'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Absensi Guru Section -->
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden relative">
            <div class="absolute top-0 left-0 w-32 h-32 bg-blue-500/5 rounded-br-full -z-10"></div>
            
            <div class="px-8 py-6 border-b border-gray-50">
                <h3 class="text-xl font-bold text-gray-900">Absensi Guru</h3>
                <p class="text-sm text-gray-500 mt-0.5">Pantauan kehadiran guru hari ini</p>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
                    <div class="flex flex-col p-4 bg-green-50/50 rounded-2xl border border-green-100/50 hover:bg-green-50 transition-colors">
                        <span class="text-green-600 font-bold text-sm tracking-wide uppercase mb-2">Hadir</span>
                        <span class="text-3xl font-black text-gray-900">{{ $guruStats['hadir'] }}</span>
                    </div>
                    <div class="flex flex-col p-4 bg-yellow-50/50 rounded-2xl border border-yellow-100/50 hover:bg-yellow-50 transition-colors">
                        <span class="text-yellow-600 font-bold text-sm tracking-wide uppercase mb-2">Sakit</span>
                        <span class="text-3xl font-black text-gray-900">{{ $guruStats['sakit'] }}</span>
                    </div>
                    <div class="flex flex-col p-4 bg-blue-50/50 rounded-2xl border border-blue-100/50 hover:bg-blue-50 transition-colors">
                        <span class="text-blue-600 font-bold text-sm tracking-wide uppercase mb-2">Izin</span>
                        <span class="text-3xl font-black text-gray-900">{{ $guruStats['izin'] }}</span>
                    </div>
                    <div class="flex flex-col p-4 bg-red-50/50 rounded-2xl border border-red-100/50 hover:bg-red-50 transition-colors">
                        <span class="text-red-600 font-bold text-sm tracking-wide uppercase mb-2">Alfa</span>
                        <span class="text-3xl font-black text-gray-900">{{ $guruStats['alfa'] }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
