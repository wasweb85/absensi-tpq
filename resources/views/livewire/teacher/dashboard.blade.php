<div class="t-page">
    @if(!$isTeacher)
        <div class="t-alert danger">
            <i class="material-icons">warning</i>
            <span>Akses ditolak. Anda bukan Guru.</span>
        </div>
    @else

        @if($isWaliKelas)
            {{-- ── 4 SUMMARY CARDS ──────────────────────── --}}
            <div class="t-stats-grid" style="margin-bottom: 2rem;">
                {{-- Total Siswa --}}
                <div class="t-stat-card purple">
                    <div class="t-stat-orb"></div>
                    <div class="t-stat-icon">
                        <i class="material-icons">school</i>
                    </div>
                    <div>
                        <p class="t-stat-label">Total Siswa</p>
                        <h3 class="t-stat-value">{{ $summary['total_siswa'] }}</h3>
                    </div>
                </div>

                {{-- Sakit --}}
                <div class="t-stat-card amber">
                    <div class="t-stat-orb"></div>
                    <div class="t-stat-icon">
                        <i class="material-icons">sick</i>
                    </div>
                    <div>
                        <p class="t-stat-label">Sakit</p>
                        <h3 class="t-stat-value">{{ $summary['sakit_hari_ini'] }}</h3>
                    </div>
                </div>

                {{-- Izin --}}
                <div class="t-stat-card blue">
                    <div class="t-stat-orb"></div>
                    <div class="t-stat-icon">
                        <i class="material-icons">mark_email_unread</i>
                    </div>
                    <div>
                        <p class="t-stat-label">Izin</p>
                        <h3 class="t-stat-value">{{ $summary['izin_hari_ini'] }}</h3>
                    </div>
                </div>

                {{-- Alpa --}}
                <div class="t-stat-card red">
                    <div class="t-stat-orb"></div>
                    <div class="t-stat-icon">
                        <i class="material-icons">cancel</i>
                    </div>
                    <div>
                        <p class="t-stat-label">Alpa</p>
                        <h3 class="t-stat-value">{{ $summary['alfa_hari_ini'] }}</h3>
                    </div>
                </div>
            </div>

            {{-- ── ATTENDANCE CHART ─────────────────────── --}}
            <div class="t-card" style="margin-bottom: 2rem;">
                <div class="t-card-header">
                    <div class="t-card-header-icon purple">
                        <i class="material-icons">bar_chart</i>
                    </div>
                    <div style="flex: 1;">
                        <div class="t-card-title">Statistik Kehadiran Hari Ini</div>
                        <div class="t-card-subtitle">Grafik perbandingan status kehadiran</div>
                    </div>
                    <span class="t-badge purple" style="font-size: 0.7rem;">
                        <i class="material-icons" style="font-size: 0.85rem;">schedule</i> Realtime
                    </span>
                </div>
                <div class="t-card-body">
                    @php
                        $max = $summary['total_siswa'] > 0 ? $summary['total_siswa'] : 1;
                        $belumAbsen = $summary['total_siswa'] - ($summary['hadir_hari_ini'] + $summary['sakit_hari_ini'] + $summary['izin_hari_ini'] + $summary['alfa_hari_ini']);
                        if($belumAbsen < 0) $belumAbsen = 0;

                        $hHeight = ($summary['hadir_hari_ini'] / $max) * 100;
                        $sHeight = ($summary['sakit_hari_ini'] / $max) * 100;
                        $iHeight = ($summary['izin_hari_ini'] / $max) * 100;
                        $aHeight = ($summary['alfa_hari_ini'] / $max) * 100;
                        $bHeight = ($belumAbsen / $max) * 100;
                    @endphp

                    <div style="position: relative; height: 16rem; width: 100%; display: flex; align-items: flex-end; justify-content: space-around; padding-bottom: 2.5rem; border-bottom: 1px solid var(--t-divider);">
                        {{-- Y-Axis --}}
                        <div style="position: absolute; left: 0; top: 0; bottom: 2.5rem; width: 2rem; display: flex; flex-direction: column; justify-content: space-between; font-size: 0.7rem; color: var(--t-on-surface-subtle); font-weight: 500; z-index: 2;">
                            <span>{{ $max }}</span>
                            <span>{{ round($max * 0.75) }}</span>
                            <span>{{ round($max * 0.5) }}</span>
                            <span>{{ round($max * 0.25) }}</span>
                            <span>0</span>
                        </div>

                        {{-- Grid Lines --}}
                        <div style="position: absolute; inset: 0; display: flex; flex-direction: column; justify-content: space-between; padding-bottom: 2.5rem; z-index: 0;">
                            <div style="border-bottom: 1px dashed var(--t-divider);"></div>
                            <div style="border-bottom: 1px dashed var(--t-divider);"></div>
                            <div style="border-bottom: 1px dashed var(--t-divider);"></div>
                            <div style="border-bottom: 1px dashed var(--t-divider);"></div>
                            <div></div>
                        </div>

                        {{-- Bars --}}
                        <div style="width: 100%; height: 100%; display: flex; align-items: flex-end; justify-content: space-around; padding-left: 2.5rem; z-index: 1;">
                            {{-- Hadir --}}
                            <div style="display: flex; flex-direction: column; align-items: center; width: 18%;">
                                <span style="font-size: 0.8rem; font-weight: 700; color: var(--t-hadir-text); margin-bottom: 0.35rem;">{{ $summary['hadir_hari_ini'] }}</span>
                                <div class="t-chart-bar hadir" style="width: 2.5rem; height: {{ max($hHeight, 2) }}%;"></div>
                                <span style="font-size: 0.75rem; font-weight: 600; color: var(--t-on-surface-subtle); margin-top: 0.5rem;">Hadir</span>
                            </div>
                            {{-- Sakit --}}
                            <div style="display: flex; flex-direction: column; align-items: center; width: 18%;">
                                <span style="font-size: 0.8rem; font-weight: 700; color: var(--t-sakit-text); margin-bottom: 0.35rem;">{{ $summary['sakit_hari_ini'] }}</span>
                                <div class="t-chart-bar sakit" style="width: 2.5rem; height: {{ max($sHeight, 2) }}%;"></div>
                                <span style="font-size: 0.75rem; font-weight: 600; color: var(--t-on-surface-subtle); margin-top: 0.5rem;">Sakit</span>
                            </div>
                            {{-- Izin --}}
                            <div style="display: flex; flex-direction: column; align-items: center; width: 18%;">
                                <span style="font-size: 0.8rem; font-weight: 700; color: var(--t-izin-text); margin-bottom: 0.35rem;">{{ $summary['izin_hari_ini'] }}</span>
                                <div class="t-chart-bar izin" style="width: 2.5rem; height: {{ max($iHeight, 2) }}%;"></div>
                                <span style="font-size: 0.75rem; font-weight: 600; color: var(--t-on-surface-subtle); margin-top: 0.5rem;">Izin</span>
                            </div>
                            {{-- Alpa --}}
                            <div style="display: flex; flex-direction: column; align-items: center; width: 18%;">
                                <span style="font-size: 0.8rem; font-weight: 700; color: var(--t-alpa-text); margin-bottom: 0.35rem;">{{ $summary['alfa_hari_ini'] }}</span>
                                <div class="t-chart-bar alpa" style="width: 2.5rem; height: {{ max($aHeight, 2) }}%;"></div>
                                <span style="font-size: 0.75rem; font-weight: 600; color: var(--t-on-surface-subtle); margin-top: 0.5rem;">Alpa</span>
                            </div>
                            {{-- Belum Absen --}}
                            <div style="display: flex; flex-direction: column; align-items: center; width: 18%;">
                                <span style="font-size: 0.8rem; font-weight: 700; color: var(--t-belum-text); margin-bottom: 0.35rem;">{{ $belumAbsen }}</span>
                                <div class="t-chart-bar belum" style="width: 2.5rem; height: {{ max($bHeight, 2) }}%;"></div>
                                <span style="font-size: 0.75rem; font-weight: 600; color: var(--t-on-surface-subtle); margin-top: 0.5rem;">Belum</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ── BOTTOM 2-COLUMN SECTION ──────────────── --}}
        <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;">
            @media (min-width: 1024px) {}

            @if($isWaliKelas)
                {{-- JADWAL KBM --}}
                <div class="t-card">
                    <div class="t-card-header">
                        <div class="t-card-header-icon purple">
                            <i class="material-icons">menu_book</i>
                        </div>
                        <div>
                            <div class="t-card-title">Jadwal KBM</div>
                            <div class="t-card-subtitle">Jadwal pelajaran hari ini</div>
                        </div>
                    </div>

                    @if($jadwalKelasHariIni->isEmpty())
                        <div class="t-empty">
                            <i class="material-icons">event_busy</i>
                            <p>Tidak ada jadwal KBM hari ini.</p>
                        </div>
                    @else
                        <div style="overflow-x: auto;">
                            <table class="t-table">
                                <thead>
                                    <tr>
                                        <th>Jam</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Guru</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jadwalKelasHariIni as $j)
                                        <tr>
                                            <td>
                                                <span class="t-badge purple">
                                                    <i class="material-icons" style="font-size: 0.85rem;">schedule</i>
                                                    {{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}
                                                </span>
                                            </td>
                                            <td style="font-weight: 600;">{{ $j->mapel->nama_mapel ?? '-' }}</td>
                                            <td>
                                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                    <div style="width: 1.75rem; height: 1.75rem; border-radius: 50%; background: var(--t-primary-light); color: var(--t-primary); display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 700;">
                                                        {{ substr($j->guru->nama_guru ?? '?', 0, 1) }}
                                                    </div>
                                                    {{ $j->guru->nama_guru ?? '-' }}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @endif

            {{-- KETENTUAN SERAGAM --}}
            <div class="t-card">
                <div class="t-card-header">
                    <div class="t-card-header-icon teal">
                        <i class="material-icons">checkroom</i>
                    </div>
                    <div>
                        <div class="t-card-title">Ketentuan Seragam</div>
                        <div class="t-card-subtitle">Pakaian wajib untuk hari ini</div>
                    </div>
                </div>

                @if($seragam->isEmpty())
                    <div class="t-empty">
                        <i class="material-icons">styler</i>
                        <p>Belum ada ketentuan seragam hari ini.</p>
                    </div>
                @else
                    <div style="overflow-x: auto;">
                        <table class="t-table">
                            <thead>
                                <tr>
                                    <th>Nama Seragam</th>
                                    <th>Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($seragam as $s)
                                    <tr>
                                        <td>
                                            <span class="t-badge teal">{{ $s->nama_seragam }}</span>
                                        </td>
                                        <td>
                                            {{ $s->deskripsi ?? '-' }}
                                            @if($s->keterangan)
                                                <div style="font-size: 0.75rem; color: var(--t-on-surface-subtle); margin-top: 0.25rem;">{{ $s->keterangan }}</div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    @endif

    <style>
    @media (min-width: 1024px) {
        .t-page > div:last-of-type {
            grid-template-columns: 1fr 1fr !important;
        }
    }
    </style>
</div>
