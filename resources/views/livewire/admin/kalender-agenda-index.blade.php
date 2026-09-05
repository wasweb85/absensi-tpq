<div class="kalender-agenda-container pb-12">
    {{-- CSS Khusus Kanvas Kaca & Desain Modern --}}
    <style>
        .glass-hero {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #9333ea 100%);
            box-shadow: 0 10px 25px -5px rgba(124, 58, 237, 0.35), 0 8px 10px -6px rgba(124, 58, 237, 0.2);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(241, 245, 249, 0.85);
        }
        .calendar-cell {
            min-height: 108px;
            transition: all 0.2s ease-in-out;
        }
        .calendar-cell:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -4px rgba(0, 0, 0, 0.06);
        }
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(1.15); }
        }
        .pulse-live {
            animation: pulse-dot 2s infinite ease-in-out;
        }
    </style>

    {{-- Alert Notifikasi Sukses --}}
    @if (session()->has('success_message'))
        <div class="mb-5 flex items-center justify-between p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl shadow-sm transition-all">
            <div class="flex items-center gap-3">
                <i class="material-icons text-emerald-600">check_circle</i>
                <span class="font-medium text-sm">{{ session('success_message') }}</span>
            </div>
            <button type="button" class="text-emerald-500 hover:text-emerald-700" onclick="this.parentElement.remove()">
                <i class="material-icons text-lg">close</i>
            </button>
        </div>
    @endif

    {{-- 1. HERO HEADER BANNER --}}
    <div class="glass-hero rounded-3xl p-6 sm:p-8 text-white relative overflow-hidden mb-6">
        {{-- Aksen Latar Belakang Geometris Kaca --}}
        <div class="absolute -right-12 -top-12 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute right-40 -bottom-16 w-48 h-48 bg-fuchsia-500/20 rounded-full blur-xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            {{-- Info Judul & Subjudul --}}
            <div class="flex items-start sm:items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white/15 backdrop-blur-md border border-white/25 flex items-center justify-center shrink-0 shadow-inner">
                    <i class="material-icons text-3xl text-white">calendar_today</i>
                </div>
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">Kalender &amp; Agenda</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold tracking-wide uppercase bg-white/20 text-white/95 border border-white/30 backdrop-blur-sm">
                            TPQ SISTEM
                        </span>
                    </div>
                    <p class="text-white/85 text-xs sm:text-sm mt-1 max-w-2xl font-normal leading-relaxed">
                        Kelola agenda belajar, pantau libur nasional, dan jadwalkan tugas secara terpadu di dalam kanvas kaca.
                    </p>
                </div>
            </div>

            {{-- Toggle Mode: BULANAN | TAHUNAN --}}
            <div class="bg-black/20 backdrop-blur-md p-1.5 rounded-2xl border border-white/20 self-start md:self-auto flex items-center gap-1 shadow-inner">
                <button 
                    type="button" 
                    wire:click="changeViewMode('bulanan')"
                    class="px-5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all duration-200 {{ $viewMode === 'bulanan' ? 'bg-white text-purple-700 shadow-md scale-[1.02]' : 'text-white/80 hover:text-white' }}">
                    BULANAN
                </button>
                <button 
                    type="button" 
                    wire:click="changeViewMode('tahunan')"
                    class="px-5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all duration-200 {{ $viewMode === 'tahunan' ? 'bg-white text-purple-700 shadow-md scale-[1.02]' : 'text-white/80 hover:text-white' }}">
                    TAHUNAN
                </button>
            </div>
        </div>
    </div>

    {{-- 2. BILAH FILTER & PENCARIAN (FLOATING SEARCH & FILTER BAR) --}}
    <div class="glass-card rounded-2xl p-3 sm:p-4 shadow-sm mb-6 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        {{-- Input Pencarian --}}
        <div class="relative flex-1 max-w-md">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <i class="material-icons text-xl">search</i>
            </span>
            <input 
                type="text" 
                wire:model.live.debounce.300ms="searchQuery" 
                placeholder="Cari kegiatan atau agenda..." 
                class="w-full pl-10 pr-4 py-2 text-xs sm:text-sm bg-slate-50/80 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all placeholder-slate-400 text-slate-700">
        </div>

        {{-- Kategori Filter Pills & Tombol Tambah --}}
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-1">
            <button 
                type="button" 
                wire:click="setCategory('semua')" 
                class="px-3.5 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-all {{ $selectedCategory === 'semua' ? 'bg-slate-900 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua
            </button>
            <button 
                type="button" 
                wire:click="setCategory('umum')" 
                class="px-3.5 py-1.5 rounded-full text-xs font-medium whitespace-nowrap transition-all flex items-center gap-1.5 {{ $selectedCategory === 'umum' ? 'bg-blue-600 text-white shadow-sm font-semibold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <span class="w-2 h-2 rounded-full bg-blue-500 {{ $selectedCategory === 'umum' ? 'bg-white' : '' }}"></span>
                Umum
            </button>
            <button 
                type="button" 
                wire:click="setCategory('akademik')" 
                class="px-3.5 py-1.5 rounded-full text-xs font-medium whitespace-nowrap transition-all flex items-center gap-1.5 {{ $selectedCategory === 'akademik' ? 'bg-emerald-600 text-white shadow-sm font-semibold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <span class="w-2 h-2 rounded-full bg-emerald-500 {{ $selectedCategory === 'akademik' ? 'bg-white' : '' }}"></span>
                Akademik
            </button>
            <button 
                type="button" 
                wire:click="setCategory('penting')" 
                class="px-3.5 py-1.5 rounded-full text-xs font-medium whitespace-nowrap transition-all flex items-center gap-1.5 {{ $selectedCategory === 'penting' ? 'bg-purple-600 text-white shadow-sm font-semibold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <span class="w-2 h-2 rounded-full bg-purple-500 {{ $selectedCategory === 'penting' ? 'bg-white' : '' }}"></span>
                Penting
            </button>
            <button 
                type="button" 
                wire:click="setCategory('tugas')" 
                class="px-3.5 py-1.5 rounded-full text-xs font-medium whitespace-nowrap transition-all flex items-center gap-1.5 {{ $selectedCategory === 'tugas' ? 'bg-amber-600 text-white shadow-sm font-semibold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <span class="w-2 h-2 rounded-full bg-amber-500 {{ $selectedCategory === 'tugas' ? 'bg-white' : '' }}"></span>
                Tugas
            </button>

            {{-- Tombol Tambah Agenda (Khusus Admin / Petugas) --}}
            @if ($isManageable)
                <button 
                    type="button" 
                    wire:click="openCreateModal" 
                    class="ml-2 px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white shadow-md hover:shadow-lg transition-all flex items-center gap-1.5 shrink-0">
                    <i class="material-icons text-base">add</i>
                    <span>Tambah Agenda</span>
                </button>
            @endif
        </div>
    </div>

    {{-- 3. AREA UTAMA (GRID 2 KOLOM) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- KOLOM KIRI (70% WIDTH PADA DESKTOP): KALENDER BULANAN / TAHUNAN --}}
        <div class="lg:col-span-8 space-y-6">

            @if ($viewMode === 'bulanan')
                {{-- CARD PERIODE AKADEMIK AKTIF --}}
                <div class="bg-emerald-50/75 border border-emerald-200/80 rounded-2xl p-4 shadow-sm backdrop-blur-md">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 pulse-live"></span>
                            <h3 class="text-xs sm:text-sm font-bold tracking-wide uppercase text-emerald-900">
                                Periode Akademik Aktif — {{ $currentMonthName }} {{ $selectedYear }}
                            </h3>
                        </div>
                        <button 
                            type="button" 
                            wire:click="toggleLiburPekan" 
                            class="text-[11px] font-bold px-3 py-1 rounded-full border transition-all self-start sm:self-auto {{ $showLiburPekan ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : 'bg-white text-slate-500 border-slate-300' }}">
                            JUMAT &amp; LIBUR {{ $showLiburPekan ? 'ON' : 'OFF' }}
                        </button>
                    </div>

                    {{-- Pills Kegiatan Akademik Aktif --}}
                    <div class="flex flex-wrap gap-2.5">
                        @forelse ($activePeriods as $period)
                            <div class="bg-white/90 border border-emerald-200 rounded-xl px-3 py-1.5 flex items-center gap-2 shadow-xs text-xs font-medium text-slate-800">
                                <i class="material-icons text-emerald-600 text-base">school</i>
                                <span>{{ $period->judul }}</span>
                                <span class="bg-emerald-100 text-emerald-700 text-[10px] px-2 py-0.5 rounded-md font-semibold">
                                    {{ $period->tanggal_mulai ? $period->tanggal_mulai->format('d M') : '' }} 
                                    @if ($period->tanggal_selesai && $period->tanggal_selesai != $period->tanggal_mulai)
                                        s/d {{ $period->tanggal_selesai->format('d M Y') }}
                                    @endif
                                </span>
                            </div>
                        @empty
                            <div class="bg-white/90 border border-emerald-200 rounded-xl px-3.5 py-1.5 flex items-center gap-2 shadow-xs text-xs font-medium text-slate-800">
                                <i class="material-icons text-emerald-600 text-base">school</i>
                                <span>Tahun Ajaran Aktif {{ $selectedYear }}</span>
                                <span class="bg-emerald-100 text-emerald-700 text-[10px] px-2 py-0.5 rounded-md font-semibold">
                                    Semester Aktif TPQ
                                </span>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- NAVIGASI BULAN & TAHUN --}}
                <div class="flex items-center justify-between px-2">
                    <button 
                        type="button" 
                        wire:click="prevMonth" 
                        class="w-9 h-9 rounded-xl glass-card flex items-center justify-center text-slate-600 hover:text-purple-700 hover:border-purple-300 transition-all shadow-xs">
                        <i class="material-icons">chevron_left</i>
                    </button>
                    <div class="flex items-center gap-2">
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight">
                            {{ $currentMonthName }}
                        </h2>
                        <span class="px-2.5 py-0.5 rounded-lg bg-purple-100 text-purple-700 text-xs sm:text-sm font-bold border border-purple-200">
                            {{ $selectedYear }}
                        </span>
                    </div>
                    <button 
                        type="button" 
                        wire:click="nextMonth" 
                        class="w-9 h-9 rounded-xl glass-card flex items-center justify-center text-slate-600 hover:text-purple-700 hover:border-purple-300 transition-all shadow-xs">
                        <i class="material-icons">chevron_right</i>
                    </button>
                </div>

                {{-- GRID KALENDER BULANAN (7 HARI: MIN S/D SAB) --}}
                <div class="glass-card rounded-3xl p-3 sm:p-5 shadow-sm">
                    {{-- Header 7 Kolom Hari --}}
                    <div class="grid grid-cols-7 gap-1.5 sm:gap-2 text-center mb-3">
                        <div class="text-[11px] sm:text-xs font-bold text-slate-400 py-1">MIN</div>
                        <div class="text-[11px] sm:text-xs font-bold text-slate-400 py-1">SEN</div>
                        <div class="text-[11px] sm:text-xs font-bold text-slate-400 py-1">SEL</div>
                        <div class="text-[11px] sm:text-xs font-bold text-slate-400 py-1">RAB</div>
                        <div class="text-[11px] sm:text-xs font-bold text-slate-400 py-1">KAM</div>
                        <div class="py-1">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-rose-100 text-rose-600 text-[10px] sm:text-[11px] font-bold">
                                JUM <span class="text-[9px] uppercase px-1 rounded bg-rose-600 text-white leading-tight">Libur</span>
                            </span>
                        </div>
                        <div class="text-[11px] sm:text-xs font-bold text-slate-400 py-1">SAB</div>
                    </div>

                    {{-- Kotak-kotak Tanggal Kalender --}}
                    <div class="grid grid-cols-7 gap-1.5 sm:gap-2">
                        @foreach ($matrix as $cell)
                            @php
                                $isJumLibur = $cell['is_weekly_holiday'] && $showLiburPekan;
                                $hasLiburKhusus = $cell['has_holiday_event'];
                            @endphp
                            <div 
                                @if ($isManageable) wire:click="openCreateModal('{{ $cell['date_string'] }}')" @endif
                                class="calendar-cell rounded-2xl p-2 sm:p-2.5 flex flex-col justify-between border cursor-pointer select-none relative
                                {{ !$cell['is_current_month'] ? 'opacity-30 bg-slate-50/50 border-slate-100' : 'bg-white/90' }}
                                {{ $isJumLibur ? 'bg-rose-50/50 border-rose-100' : 'border-slate-100/90' }}
                                {{ $cell['is_today'] ? 'ring-2 ring-purple-600 ring-offset-1 shadow-sm' : '' }}
                                ">
                                
                                {{-- Baris Atas: Tanggal Masehi & Tanggal Hijriah --}}
                                <div class="flex items-center justify-between w-full">
                                    {{-- Tanggal Masehi --}}
                                    @if ($cell['is_today'])
                                        <span class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-purple-600 text-white font-bold text-xs sm:text-sm flex items-center justify-center shadow-xs">
                                            {{ $cell['day'] }}
                                        </span>
                                    @else
                                        <span class="font-bold text-xs sm:text-sm {{ $isJumLibur ? 'text-rose-600' : 'text-slate-700' }}">
                                            {{ $cell['day'] }}
                                        </span>
                                    @endif

                                    {{-- Tanggal Hijriah (contoh: 18 Rob) --}}
                                    <span class="text-[9px] sm:text-[10px] font-medium text-slate-400 tracking-tight" title="{{ $cell['hijri_formatted'] }}">
                                        {{ $cell['hijri_formatted'] }}
                                    </span>
                                </div>

                                {{-- Isi Agenda & Badge Libur --}}
                                <div class="mt-1 space-y-1 w-full overflow-hidden">
                                    {{-- Badge Libur Jumat Rutin --}}
                                    @if ($isJumLibur && $cell['is_current_month'])
                                        <div class="px-1.5 py-0.5 rounded-md bg-rose-100/80 text-rose-700 text-[9px] sm:text-[10px] font-semibold truncate leading-tight">
                                            Libur Jumat
                                        </div>
                                    @endif

                                    {{-- Agenda Event Pills --}}
                                    @foreach ($cell['events']->take(2) as $ev)
                                        @php
                                            $pillBg = match($ev->kategori) {
                                                'akademik' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                                'penting'  => 'bg-purple-50 text-purple-800 border-purple-200',
                                                'tugas'    => 'bg-amber-50 text-amber-800 border-amber-200',
                                                default    => 'bg-blue-50 text-blue-800 border-blue-200',
                                            };
                                        @endphp
                                        <div 
                                            wire:click.stop="{{ $isManageable ? 'editAgenda(' . $ev->id . ')' : 'showDetail(' . $ev->id . ')' }}" 
                                            class="px-1.5 py-0.5 rounded-md border text-[9px] sm:text-[10px] font-medium truncate flex items-center gap-1 shadow-2xs hover:brightness-95 transition-all {{ $pillBg }}" 
                                            title="{{ $ev->judul }}">
                                            @if ($ev->is_libur)
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 shrink-0"></span>
                                            @endif
                                            <span class="truncate">{{ $ev->judul }}</span>
                                        </div>
                                    @endforeach

                                    @if ($cell['events']->count() > 2)
                                        <div class="text-[9px] font-bold text-purple-600 pl-1">
                                            +{{ $cell['events']->count() - 2 }} lainnya
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            @else
                {{-- MODE TAHUNAN: 12 KARTU BULAN MINI (JAN - DES) --}}
                <div class="space-y-6">
                    {{-- Navigasi Tahun --}}
                    <div class="flex items-center justify-between px-2">
                        <button 
                            type="button" 
                            wire:click="prevYear" 
                            class="w-9 h-9 rounded-xl glass-card flex items-center justify-center text-slate-600 hover:text-purple-700 transition-all shadow-xs">
                            <i class="material-icons">chevron_left</i>
                        </button>
                        <div class="flex items-center gap-2">
                            <h2 class="text-xl sm:text-2xl font-bold text-slate-800">Kalender Tahunan</h2>
                            <span class="px-3 py-1 rounded-lg bg-purple-100 text-purple-700 text-sm font-bold border border-purple-200">
                                {{ $selectedYear }}
                            </span>
                        </div>
                        <button 
                            type="button" 
                            wire:click="nextYear" 
                            class="w-9 h-9 rounded-xl glass-card flex items-center justify-center text-slate-600 hover:text-purple-700 transition-all shadow-xs">
                            <i class="material-icons">chevron_right</i>
                        </button>
                    </div>

                    {{-- Grid 12 Bulan Mini (3x4 Desktop, 2x6 Tablet, 1x12 Mobile) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                        @foreach ($yearMonths as $m)
                            <div 
                                wire:click="jumpToMonth({{ $m['month_num'] }})" 
                                class="glass-card rounded-2xl p-3.5 border border-slate-100 hover:border-purple-300 hover:shadow-md transition-all cursor-pointer group flex flex-col justify-between">
                                
                                {{-- Header Bulan Mini --}}
                                <div class="flex items-center justify-between pb-2 mb-2 border-b border-slate-100">
                                    <div class="flex items-center gap-1.5">
                                        <h4 class="font-bold text-sm text-slate-800 group-hover:text-purple-700 transition-colors">
                                            {{ $m['name'] }}
                                        </h4>
                                        <i class="material-icons text-xs text-slate-400 group-hover:text-purple-600 group-hover:translate-x-0.5 transition-all">chevron_right</i>
                                    </div>
                                    @if ($m['is_current'])
                                        <span class="px-1.5 py-0.5 rounded bg-purple-600 text-white text-[9px] font-bold uppercase">
                                            NOW
                                        </span>
                                    @endif
                                </div>

                                {{-- Header Hari Mini (M S S R K J S) --}}
                                <div class="grid grid-cols-7 text-center text-[10px] font-bold text-slate-400 mb-1">
                                    <span>M</span><span>S</span><span>S</span><span>R</span><span>K</span>
                                    <span class="text-rose-500 font-extrabold">J</span>
                                    <span>S</span>
                                </div>

                                {{-- Kotak Hari Mini --}}
                                <div class="grid grid-cols-7 gap-1 text-center text-[10px]">
                                    @foreach ($m['days'] as $dayItem)
                                        @if ($dayItem['day'] === null)
                                            <span class="py-0.5"></span>
                                        @else
                                            <span class="py-0.5 rounded font-medium 
                                                {{ $dayItem['is_today'] ? 'bg-purple-600 text-white font-bold' : '' }}
                                                {{ $dayItem['is_weekly'] ? 'text-rose-500 font-bold bg-rose-50/50' : '' }}
                                                {{ $dayItem['has_event'] && !$dayItem['is_today'] ? 'bg-emerald-100 text-emerald-800 font-semibold' : '' }}
                                                {{ !$dayItem['is_today'] && !$dayItem['is_weekly'] && !$dayItem['has_event'] ? 'text-slate-600' : '' }}
                                            ">
                                                {{ $dayItem['day'] }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        {{-- KOLOM KANAN (30% WIDTH PADA DESKTOP): STATISTIK & TIMELINE --}}
        <div class="lg:col-span-4 space-y-6">

            {{-- 1. KARTU STATISTIK BULAN INI (LIVE) --}}
            <div class="glass-card rounded-3xl p-5 shadow-sm">
                {{-- Header Statistik --}}
                <div class="flex items-center justify-between mb-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                            <i class="material-icons text-xl">insights</i>
                        </div>
                        <div>
                            <h3 class="font-bold text-sm text-slate-800">Statistik Bulan Ini</h3>
                            <p class="text-[11px] text-slate-500">Ringkasan otomatis</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 pulse-live"></span>
                        LIVE
                    </span>
                </div>

                {{-- 3 Metrik Utama --}}
                <div class="grid grid-cols-3 gap-2.5 text-center">
                    {{-- Hari Kerja --}}
                    <div class="p-3 rounded-2xl bg-blue-50/60 border border-blue-100/80">
                        <div class="w-8 h-8 mx-auto mb-1.5 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                            <i class="material-icons text-base">work_outline</i>
                        </div>
                        <div class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight">
                            {{ $stats['hari_kerja'] }}
                        </div>
                        <div class="text-[9px] font-bold uppercase tracking-wider text-slate-500 mt-0.5">
                            Hari Kerja
                        </div>
                        <div class="w-full bg-blue-200 h-1 rounded-full mt-2 overflow-hidden">
                            <div class="bg-blue-600 h-1 rounded-full" style="width: {{ min(100, ($stats['hari_kerja'] / 31) * 100) }}%"></div>
                        </div>
                    </div>

                    {{-- Hari Libur --}}
                    <div class="p-3 rounded-2xl bg-rose-50/60 border border-rose-100/80">
                        <div class="w-8 h-8 mx-auto mb-1.5 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center">
                            <i class="material-icons text-base">error_outline</i>
                        </div>
                        <div class="text-xl sm:text-2xl font-black text-rose-600 tracking-tight">
                            {{ $stats['hari_libur'] }}
                        </div>
                        <div class="text-[9px] font-bold uppercase tracking-wider text-slate-500 mt-0.5">
                            Hari Libur
                        </div>
                        <div class="w-full bg-rose-200 h-1 rounded-full mt-2 overflow-hidden">
                            <div class="bg-rose-500 h-1 rounded-full" style="width: {{ min(100, ($stats['hari_libur'] / 10) * 100) }}%"></div>
                        </div>
                    </div>

                    {{-- Agenda --}}
                    <div class="p-3 rounded-2xl bg-purple-50/60 border border-purple-100/80">
                        <div class="w-8 h-8 mx-auto mb-1.5 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center">
                            <i class="material-icons text-base">description</i>
                        </div>
                        <div class="text-xl sm:text-2xl font-black text-purple-600 tracking-tight">
                            {{ $stats['total_agenda'] }}
                        </div>
                        <div class="text-[9px] font-bold uppercase tracking-wider text-slate-500 mt-0.5">
                            Agenda
                        </div>
                        <div class="w-full bg-purple-200 h-1 rounded-full mt-2 overflow-hidden">
                            <div class="bg-purple-600 h-1 rounded-full" style="width: {{ min(100, ($stats['total_agenda'] / 5) * 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. KARTU TIMELINE AGENDA & LIBUR --}}
            <div class="glass-card rounded-3xl p-5 shadow-sm">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center border border-purple-100">
                            <i class="material-icons text-xl">event_note</i>
                        </div>
                        <div>
                            <h3 class="font-bold text-sm text-slate-800">Agenda &amp; Libur</h3>
                            <p class="text-[11px] text-slate-500">Timeline bulan ini</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-lg bg-purple-100 text-purple-700 text-xs font-bold">
                        {{ $currentMonthName }}
                    </span>
                </div>

                {{-- Daftar Agenda Kronologis --}}
                <div class="space-y-3 max-h-[460px] overflow-y-auto pr-1">
                    @forelse ($timelineEvents as $item)
                        <div class="p-3 rounded-2xl border transition-all flex items-start justify-between gap-3 group
                            {{ $item['is_libur'] ? 'bg-rose-50/40 border-rose-100/90' : 'bg-slate-50/70 border-slate-100 hover:border-purple-200' }}">
                            
                            {{-- Tanggal & Hari di Kiri --}}
                            <div class="text-center min-w-[42px] shrink-0 pt-0.5">
                                <div class="text-base font-black {{ $item['is_libur'] ? 'text-rose-600' : 'text-slate-800' }}">
                                    {{ $item['day_num'] }}
                                </div>
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                    {{ $item['day_name'] }}
                                </div>
                            </div>

                            {{-- Informasi Kegiatan --}}
                            <div class="flex-1 min-w-0">
                                <h5 class="text-xs sm:text-sm font-semibold text-slate-800 leading-snug truncate">
                                    {{ $item['judul'] }}
                                </h5>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold
                                        {{ $item['is_libur'] ? 'bg-rose-100 text-rose-700' : 'bg-purple-100 text-purple-700' }}">
                                        {{ $item['kategori_label'] }}
                                    </span>
                                </div>
                            </div>

                            {{-- Aksi Edit / Hapus Admin --}}
                            @if ($isManageable && $item['is_custom'] && !empty($item['agenda_id']))
                                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity shrink-0">
                                    <button 
                                        type="button" 
                                        wire:click="editAgenda({{ $item['agenda_id'] }})" 
                                        class="p-1 rounded-lg hover:bg-white text-slate-400 hover:text-purple-600 transition-colors"
                                        title="Edit Agenda">
                                        <i class="material-icons text-base">edit</i>
                                    </button>
                                    <button 
                                        type="button" 
                                        wire:confirm="Yakin ingin menghapus agenda ini?" 
                                        wire:click="deleteAgenda({{ $item['agenda_id'] }})" 
                                        class="p-1 rounded-lg hover:bg-white text-slate-400 hover:text-rose-600 transition-colors"
                                        title="Hapus Agenda">
                                        <i class="material-icons text-base">delete</i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-400">
                            <i class="material-icons text-4xl mb-1 text-slate-300">event_busy</i>
                            <p class="text-xs">Tidak ada agenda kegiatan pada bulan ini</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    {{-- 4. MODAL CRUD AGENDA (KHUSUS ADMIN / PETUGAS) --}}
    @if ($isOpenModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-all">
            <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-100 animate-in fade-in zoom-in duration-200">
                
                {{-- Header Modal --}}
                <div class="px-6 py-5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <i class="material-icons text-2xl">event</i>
                        <h4 class="font-bold text-lg">
                            {{ $agendaId ? 'Edit Agenda Kegiatan' : 'Tambah Agenda Baru' }}
                        </h4>
                    </div>
                    <button type="button" wire:click="closeModals" class="text-white/80 hover:text-white rounded-full p-1 transition-colors">
                        <i class="material-icons">close</i>
                    </button>
                </div>

                {{-- Form Body --}}
                <form wire:submit.prevent="saveAgenda" class="p-6 space-y-4">
                    {{-- Judul Kegiatan --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nama Kegiatan / Agenda *
                        </label>
                        <input 
                            type="text" 
                            wire:model="form_judul" 
                            placeholder="Contoh: Imtihan Semester Ganjil / Libur Ramadhan" 
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-slate-800">
                        @error('form_judul') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Kategori & Pilihan Warna --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Kategori *
                            </label>
                            <select 
                                wire:model="form_kategori" 
                                class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 text-slate-800">
                                <option value="umum">Umum</option>
                                <option value="akademik">Akademik</option>
                                <option value="penting">Penting</option>
                                <option value="tugas">Tugas</option>
                            </select>
                            @error('form_kategori') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Aksen Warna
                            </label>
                            <div class="flex items-center gap-2 pt-1.5">
                                @foreach (['#10b981' => 'bg-emerald-500', '#3b82f6' => 'bg-blue-500', '#8b5cf6' => 'bg-purple-500', '#f59e0b' => 'bg-amber-500', '#f43f5e' => 'bg-rose-500'] as $hex => $class)
                                    <button 
                                        type="button" 
                                        wire:click="$set('form_warna', '{{ $hex }}')" 
                                        class="w-7 h-7 rounded-full {{ $class }} flex items-center justify-center transition-all {{ $form_warna === $hex ? 'ring-2 ring-purple-600 ring-offset-2 scale-110' : 'opacity-70 hover:opacity-100' }}">
                                        @if ($form_warna === $hex)
                                            <i class="material-icons text-white text-xs">check</i>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Tanggal Mulai & Tanggal Selesai --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Tanggal Mulai *
                            </label>
                            <input 
                                type="date" 
                                wire:model="form_tanggal_mulai" 
                                class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 text-slate-800">
                            @error('form_tanggal_mulai') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Tanggal Selesai (Opsional)
                            </label>
                            <input 
                                type="date" 
                                wire:model="form_tanggal_selesai" 
                                class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 text-slate-800">
                            @error('form_tanggal_selesai') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Switch / Checkbox Hari Libur TPQ --}}
                    <div class="p-3.5 bg-rose-50/70 border border-rose-200 rounded-2xl flex items-center justify-between">
                        <div>
                            <span class="block text-xs font-bold text-rose-900">Tetapkan Sebagai Hari Libur TPQ</span>
                            <span class="block text-[11px] text-rose-700">Jika aktif, sistem presensi otomatis menandai hari libur (tidak dihitung alpa)</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="form_is_libur" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-600"></div>
                        </label>
                    </div>

                    {{-- Deskripsi / Keterangan --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Deskripsi / Keterangan (Opsional)
                        </label>
                        <textarea 
                            wire:model="form_deskripsi" 
                            rows="3" 
                            placeholder="Tuliskan catatan tambahan mengenai kegiatan ini..." 
                            class="w-full px-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 text-slate-800"></textarea>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="pt-3 flex items-center justify-end gap-2.5 border-t border-slate-100">
                        <button 
                            type="button" 
                            wire:click="closeModals" 
                            class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-6 py-2.5 rounded-xl text-sm font-semibold bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white shadow-md hover:shadow-lg transition-all">
                            Simpan Agenda
                        </button>
                    </div>
                </form>

            </div>
        </div>
    @endif

    {{-- 5. MODAL DETAIL AGENDA (READ-ONLY VIEWER) --}}
    @if ($isOpenDetailModal && $detailEvent)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-all">
            <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden border border-slate-100 p-6 animate-in fade-in zoom-in duration-200">
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold uppercase
                            {{ $detailEvent->is_libur ? 'bg-rose-100 text-rose-700' : 'bg-purple-100 text-purple-700' }}">
                            {{ $detailEvent->is_libur ? 'Hari Libur TPQ' : ucfirst($detailEvent->kategori) }}
                        </span>
                    </div>
                    <button type="button" wire:click="closeModals" class="text-slate-400 hover:text-slate-600">
                        <i class="material-icons">close</i>
                    </button>
                </div>

                <h3 class="text-lg font-bold text-slate-800 mb-2">
                    {{ $detailEvent->judul }}
                </h3>

                <div class="text-xs text-slate-500 mb-4 flex items-center gap-1.5">
                    <i class="material-icons text-base text-purple-600">event</i>
                    <span>
                        {{ $detailEvent->tanggal_mulai ? $detailEvent->tanggal_mulai->translatedFormat('d F Y') : '' }}
                        @if ($detailEvent->tanggal_selesai && $detailEvent->tanggal_selesai != $detailEvent->tanggal_mulai)
                            s/d {{ $detailEvent->tanggal_selesai->translatedFormat('d F Y') }}
                        @endif
                    </span>
                </div>

                @if ($detailEvent->deskripsi)
                    <div class="bg-slate-50 p-3.5 rounded-2xl text-xs text-slate-600 mb-5 leading-relaxed border border-slate-100">
                        {{ $detailEvent->deskripsi }}
                    </div>
                @endif

                <div class="text-right">
                    <button 
                        type="button" 
                        wire:click="closeModals" 
                        class="px-5 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
