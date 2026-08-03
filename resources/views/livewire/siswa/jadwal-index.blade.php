<div>
    <section class="section-content">
        <div class="header-tags">
            <span class="h-tag h-tag-primary">ID: {{ session('nis') }}</span>
            <span class="h-tag h-tag-secondary">JILID {{ $kelasInfo->kelas ?? '-' }}</span>
        </div>

        <h1 class="page-title">Jadwal Pelajaran &<br>Ketentuan Seragam</h1>
        <p class="page-subtitle">Tahun Ajaran {{ $tahun_ajaran }} • Semester {{ $semester }}</p>

        <div class="schedule-grid-modern">
            @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                @php
                    $s = $seragam[$hari] ?? null;
                    $nama_seragam = $s ? $s->nama_seragam : 'Batik';
                    $is_batik = stripos($nama_seragam, 'Batik') !== false;
                    $jadwalHariIni = $jadwalMingguan[$hari] ?? [];
                @endphp
                <div class="day-section">
                    <div class="day-header">
                        <h2 class="day-name">{{ strtoupper($hari) }}</h2>
                        <div class="uniform-badge {{ $is_batik ? 'uniform-badge-active' : '' }}">
                            <i class="material-icons" style="font-size: 14px;">checkroom</i>
                            <span>Seragam: {{ $nama_seragam }}</span>
                        </div>
                    </div>

                    <div class="schedule-card-modern">
                        @if (empty($jadwalHariIni))
                            <p class="text-muted small pl-2 py-2">Tidak ada pelajaran.</p>
                        @else
                            @foreach ($jadwalHariIni as $j)
                                @php
                                    $icon = 'book';
                                    if (stripos($j->mapel->nama_mapel ?? '', 'Matematika') !== false) $icon = 'calculate';
                                @endphp
                                <div class="modern-subject-item">
                                    <div class="subject-icon-circle">
                                        <i class="material-icons">{{ $icon }}</i>
                                    </div>
                                    <div class="subject-details">
                                        <h4>{{ $j->mapel->nama_mapel ?? '-' }}</h4>
                                        <div class="subject-time">
                                            <i class="material-icons" style="font-size: 14px;">schedule</i>
                                            <span>{{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</div>
