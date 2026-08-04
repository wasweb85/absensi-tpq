<div>
    @php
        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $currentDay = $days[date('w')];
        if ($currentDay == 'Minggu') $currentDay = 'Senin'; // Fallback
        
        $jadwalHariIni = \App\Models\JadwalPelajaran::with('mapel')->where('id_kelas', session('id_kelas'))->where('hari', $currentDay)->orderBy('id_jadwal', 'asc')->get();
    @endphp

    <!-- Splash Area -->
    <section class="splash-area">
        <p class="splash-label">DASHBOARD UTAMA</p>
        <h1 class="splash-welcome">Selamat Datang, {{ $user }}</h1>
        <div class="splash-class">
            <span class="dot-point"></span>
            <span>Jilid: {{ $kelasInfo->kelas ?? '-' }}</span>
        </div>
        <div class="splash-class" style="margin-top: 4px;">
            <span class="dot-point" style="background-color: #0d9488;"></span>
            <span>Wali Jilid: {{ $kelasInfo->nama_wali_kelas ?? '-' }}</span>
        </div>
        <div class="splash-class" style="margin-top: 4px; opacity: 0.8; font-size: 0.85em;">
            <i class="material-icons" style="font-size: 14px; margin-right: 4px;">calendar_today</i>
            <span>Tahun Ajaran: {{ $generalSettings->school_year ?? '-' }}</span>
        </div>
    </section>

    <!-- Ringkasan Kehadiran -->
    <section class="section-top">
        <div class="section-title-wrap">
            <h2 class="section-title">Ringkasan Kehadiran</h2>
            <form action="" method="get" id="filterFormWaktu">
                <select wire:model.live="filterWaktu" class="form-control-sm" style="border: none; background: transparent; font-weight: 700; color: #0d9488; font-size: 11px;">
                    <option value="bulan">Bulan Ini</option>
                    <option value="minggu">Minggu Ini</option>
                </select>
            </form>
        </div>

        <div class="attendance-grid">
            <div class="card-hadir-large">
                <span class="card-label">TOTAL HADIR</span>
                <span class="card-value-large">{{ $summary['hadir'] ?? 0 }}</span>
            </div>

            <div class="attendance-mini-grid">
                <div class="card-mini">
                    <span class="card-label">SAKIT</span>
                    <span class="card-value-mini">{{ $summary['sakit'] ?? 0 }}</span>
                </div>
                <div class="card-mini">
                    <span class="card-label">IZIN</span>
                    <span class="card-value-mini">{{ $summary['izin'] ?? 0 }}</span>
                </div>
            </div>

            <div class="card-alpha">
                <div>
                    <span class="card-label">ALPHA</span>
                    <span class="card-value-large">{{ $summary['alpha'] ?? 0 }}</span>
                </div>
                <div class="alpha-icon-wrap">
                    <i class="material-icons">priority_high</i>
                </div>
            </div>
        </div>
    </section>

    <!-- Jadwal Hari Ini -->
    <section class="section-top">
        <div class="section-title-wrap">
            <h2 class="section-title">Jadwal Hari Ini</h2>
            <span class="badge badge-light px-3 py-2" style="border-radius: 12px; font-weight: 800; font-size: 10px; color: #64748b; background: #e2e8f0;">{{ strtoupper($currentDay) }}</span>
        </div>

        <div class="schedule-list">
            @if ($jadwalHariIni->isEmpty())
                <p class="text-muted text-center py-3">Tidak ada jadwal hari ini.</p>
            @else
                @foreach ($jadwalHariIni as $j)
                    <div class="schedule-card">
                        <div class="schedule-info">
                            <h4 style="margin: 0; font-size: 14px; font-weight: 600; color: #1e293b;">{{ $j->mapel->nama_mapel ?? '-' }}</h4>
                        </div>
                        <div class="schedule-icon">
                            <i class="material-icons">menu_book</i>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </section>

    <!-- Riwayat Terbaru -->
    <section class="section-top mb-5">
        <div class="section-title-wrap">
            <h2 class="section-title">Riwayat Terbaru</h2>
        </div>

        <div class="history-list">
            @if ($riwayat->isEmpty())
                <p class="text-center py-3 text-muted">Belum ada riwayat.</p>
            @else
                @foreach ($riwayat->take(3) as $r)
                    @php
                        $statusName = 'Hadir';
                        $statusColor = '#10b981';
                        if ($r->id_kehadiran == 2) { $statusName = 'Sakit'; $statusColor = '#f59e0b'; }
                        elseif ($r->id_kehadiran == 3) { $statusName = 'Izin'; $statusColor = '#3b82f6'; }
                        elseif ($r->id_kehadiran == 4) { $statusName = 'Alpha'; $statusColor = '#ef4444'; }
                    @endphp
                    <div class="history-item">
                        <div class="history-status-icon" style="background-color: {{ $statusColor }}20; color: {{ $statusColor }};">
                            <i class="material-icons" style="font-size: 20px;">
                                {{ $r->id_kehadiran == 1 ? 'check_circle' : ($r->id_kehadiran == 4 ? 'cancel' : 'info') }}
                            </i>
                        </div>
                        <div class="history-info">
                            <span class="history-status-txt" style="color: {{ $statusColor }};">{{ $statusName }}</span>
                            <span class="history-date">{{ date('l, d M Y', strtotime($r->tanggal)) }}</span>
                            <span class="text-xs text-muted" style="font-size: 10px;">{{ $r->nama_mapel ?? '-' }}</span>
                        </div>
                        <div class="history-badge">VERIFIED</div>
                    </div>
                @endforeach
            @endif
        </div>
    </section>
</div>
