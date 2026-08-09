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
                        <div class="t-card-subtitle">Grafik perbandingan status kehadiran seluruh santri binaan</div>
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

            {{-- JADWAL KBM --}}
            <div class="t-card">
                <div class="t-card-header">
                    <div class="t-card-header-icon purple">
                        <i class="material-icons">menu_book</i>
                    </div>
                    <div>
                        <div class="t-card-title">Jadwal KBM Kelas Binaan</div>
                        <div class="t-card-subtitle">Jadwal pelajaran hari ini ({{ $hariIni }})</div>
                    </div>
                </div>

                @if($jadwalKelasHariIni->isEmpty())
                    <div class="t-empty" style="padding: 2rem; text-align: center; color: #64748b;">
                        <i class="material-icons" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 0.5rem;">event_busy</i>
                        <p style="margin: 0; font-weight: 500;">Tidak ada jadwal KBM untuk kelas binaan Anda hari ini.</p>
                    </div>
                @else
                    <div style="overflow-x: auto;">
                        <table class="t-table">
                            <thead>
                                <tr>
                                    <th>Kelas</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Guru Pengajar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jadwalKelasHariIni as $j)
                                    <tr>
                                        <td>
                                            <span style="background: #f1f5f9; color: #334155; padding: 3px 8px; border-radius: 6px; font-size: 0.78rem; font-weight: 600; border: 1px solid #e2e8f0;">
                                                {{ $j->kelas->tingkat ?? '-' }} {{ $j->kelas->index_kelas ?? '' }}
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

        @else
            <div class="t-card" style="padding: 2.5rem; text-align: center;">
                <div style="width: 4rem; height: 4rem; border-radius: 50%; background: #e0f2fe; color: #0284c7; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                    <i class="material-icons" style="font-size: 2rem;">info</i>
                </div>
                <h3 style="font-size: 1.2rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">Belum Ada Kelas Binaan</h3>
                <p style="color: #64748b; font-size: 0.9rem; max-width: 28rem; margin: 0 auto 1.5rem auto;">
                    Anda belum ditugaskan mengampu kelas/jilid binaan oleh Admin. Silakan hubungi Administrator untuk penugasan kelas.
                </p>
            </div>
        @endif
    @endif
</div>
