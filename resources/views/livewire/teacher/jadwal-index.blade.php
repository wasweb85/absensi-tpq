<div class="t-page">
    <div class="t-card">
        <div class="t-card-header" style="flex-wrap: wrap; gap: 1rem;">
            <div class="t-card-header-icon purple">
                <i class="material-icons">menu_book</i>
            </div>
            <div style="flex: 1; min-width: 150px;">
                <div class="t-card-title">Jadwal Pelajaran Kelas</div>
                <div class="t-card-subtitle">Jadwal KBM per hari dan kelas</div>
            </div>
            <div>
                <select wire:model.live="filter_kelas" class="t-select" style="width: 200px;">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($kelasList as $k)
                        <option value="{{ $k->id_kelas }}">{{ $k->tingkat }} {{ $k->index_kelas }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="t-card-body">
            {{-- Day Tabs --}}
            <div class="t-tabs">
                @foreach($jadwalList as $hari => $jadwals)
                    <a class="t-tab {{ $loop->first ? 'active' : '' }}"
                       data-toggle="tab"
                       href="#hari-{{ $hari }}"
                       role="tab"
                       onclick="document.querySelectorAll('.t-tab').forEach(t => t.classList.remove('active')); this.classList.add('active');">
                        {{ $hari }}
                    </a>
                @endforeach
            </div>

            {{-- Tab Content --}}
            <div class="tab-content">
                @foreach($jadwalList as $hari => $jadwals)
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="hari-{{ $hari }}" role="tabpanel">
                        <div style="overflow-x: auto;">
                            @if($jadwals->isEmpty())
                                <div class="t-empty">
                                    <i class="material-icons">event_busy</i>
                                    <p>Belum ada jadwal hari ini</p>
                                </div>
                            @else
                                <table class="t-table">
                                    <thead>
                                        <tr>
                                            <th>Kelas</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Guru / Ustadz</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($jadwals as $j)
                                            <tr>
                                                <td>
                                                    <span class="t-badge blue">{{ $j->kelas->tingkat ?? '' }} {{ $j->kelas->index_kelas ?? '' }}</span>
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
                                                <td style="color: var(--t-on-surface-subtle);">{{ $j->keterangan ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
