<div class="t-page">
    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div class="t-alert success">
            <i class="material-icons">check_circle</i>
            <span>{{ session('success') }}</span>
            <button type="button" class="t-alert-close" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="t-alert danger">
            <i class="material-icons">error</i>
            <span>{{ session('error') }}</span>
            <button type="button" class="t-alert-close" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    <div class="t-card">
        <div class="t-card-header">
            <div class="t-card-header-icon purple">
                <i class="material-icons">edit_note</i>
            </div>
            <div>
                <div class="t-card-title">Monitoring &amp; Absensi Santri</div>
                <div class="t-card-subtitle">Pantau Kehadiran Santri Realtime &amp; Input Status Absensi</div>
            </div>
        </div>

        <div class="t-card-body">
            {{-- Compact Clean Filter Bar --}}
            <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1.25rem; padding: 12px 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
                <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 1.25rem;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <label style="font-size: 0.78rem; font-weight: 700; color: #475569; margin: 0; white-space: nowrap; text-transform: uppercase; letter-spacing: 0.03em;">
                            <i class="material-icons" style="font-size: 16px; vertical-align: text-bottom; color: #64748b; margin-right: 2px;">calendar_today</i> Tanggal:
                        </label>
                        <input type="date" wire:model.live="tanggal" class="t-input" style="width: 155px; padding: 6px 10px; font-size: 0.85rem; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <label style="font-size: 0.78rem; font-weight: 700; color: #475569; margin: 0; white-space: nowrap; text-transform: uppercase; letter-spacing: 0.03em;">
                            <i class="material-icons" style="font-size: 16px; vertical-align: text-bottom; color: #64748b; margin-right: 2px;">filter_alt</i> Pilih Gender:
                        </label>
                        <select wire:model.live="filter_jk" class="t-select" style="width: 240px; padding: 6px 10px; font-size: 0.85rem; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
                            <option value="">Semua</option>
                            <option value="Laki-laki">Laki-laki (Putra)</option>
                            <option value="Perempuan">Perempuan (Putri)</option>
                        </select>
                    </div>
                </div>

                @if($allKelas->count() > 0)
                    <div style="display: flex; gap: 6px; align-items: center; flex-wrap: wrap;">
                        <span style="font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase;">Kelas Binaan:</span>
                        @foreach($allKelas as $k)
                            <span class="badge" style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; font-size: 0.75rem; padding: 4px 9px; border-radius: 6px; font-weight: 600;">
                                {{ $k->tingkat }} {{ $k->index_kelas }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>

            @if($siswaList->count() > 0)
                <div style="overflow-x: auto;">
                    <table class="t-table">
                        <thead>
                            <tr>
                                <th style="width: 3rem;">No</th>
                                <th>Nama Santri</th>
                                <th>Kelas / Jilid</th>
                                <th style="width: 4rem; text-align: center;">L/P</th>
                                <th style="text-align: center;">Jam Masuk</th>
                                <th style="text-align: center;">Hadir</th>
                                <th style="text-align: center;">Sakit</th>
                                <th style="text-align: center;">Izin</th>
                                <th style="text-align: center;">Alfa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswaList as $index => $siswa)
                                <tr>
                                    <td style="color: var(--t-on-surface-subtle); font-weight: 500;">{{ $index + 1 }}</td>
                                    <td style="font-weight: 600;">
                                        {{ $siswa->nama_siswa }}
                                        <div style="font-size: 0.75rem; color: #64748b; font-weight: 400;">NIS: {{ $siswa->nis }}</div>
                                    </td>
                                    <td>
                                        <span style="background: #f1f5f9; color: #334155; padding: 2px 8px; border-radius: 6px; font-size: 0.78rem; font-weight: 600; border: 1px solid #e2e8f0;">
                                            {{ $siswa->kelas->tingkat ?? '' }} {{ $siswa->kelas->index_kelas ?? '' }}
                                        </span>
                                    </td>
                                    <td style="text-align: center; font-weight: 600; color: #475569;">
                                        <span style="padding: 2px 6px; border-radius: 4px; font-size: 0.75rem; background: {{ $siswa->jenis_kelamin == 'Laki-laki' ? '#e0f2fe' : '#fce7f3' }}; color: {{ $siswa->jenis_kelamin == 'Laki-laki' ? '#0369a1' : '#be185d' }};">
                                            {{ $siswa->jenis_kelamin == 'Laki-laki' ? 'L' : 'P' }}
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        @if(isset($jamScan[$siswa->id_siswa]) && $jamScan[$siswa->id_siswa] !== '-')
                                            <span class="badge" style="background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; font-size: 0.82rem; padding: 4px 10px; border-radius: 6px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                                <i class="material-icons" style="font-size: 14px;">qr_code_scanner</i>
                                                {{ $jamScan[$siswa->id_siswa] }}
                                            </span>
                                        @else
                                            <span style="color: #94a3b8; font-size: 0.85rem;">-</span>
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        <div class="t-radio-group" style="justify-content: center;">
                                            <input type="radio" wire:model="kehadiran.{{ $siswa->id_siswa }}" value="1" id="h_{{ $siswa->id_siswa }}">
                                            <label for="h_{{ $siswa->id_siswa }}" class="hadir" title="Hadir">H</label>
                                        </div>
                                    </td>
                                    <td style="text-align: center;">
                                        <div class="t-radio-group" style="justify-content: center;">
                                            <input type="radio" wire:model="kehadiran.{{ $siswa->id_siswa }}" value="2" id="s_{{ $siswa->id_siswa }}">
                                            <label for="s_{{ $siswa->id_siswa }}" class="sakit" title="Sakit">S</label>
                                        </div>
                                    </td>
                                    <td style="text-align: center;">
                                        <div class="t-radio-group" style="justify-content: center;">
                                            <input type="radio" wire:model="kehadiran.{{ $siswa->id_siswa }}" value="3" id="i_{{ $siswa->id_siswa }}">
                                            <label for="i_{{ $siswa->id_siswa }}" class="izin" title="Izin">I</label>
                                        </div>
                                    </td>
                                    <td style="text-align: center;">
                                        <div class="t-radio-group" style="justify-content: center;">
                                            <input type="radio" wire:model="kehadiran.{{ $siswa->id_siswa }}" value="4" id="a_{{ $siswa->id_siswa }}">
                                            <label for="a_{{ $siswa->id_siswa }}" class="alpa" title="Alfa">A</label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="display: flex; justify-content: flex-end; margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--t-divider);">
                    <button wire:click="saveAttendance" class="t-btn t-btn-primary t-btn-lg">
                        <i class="material-icons" style="font-size: 1.25rem;">save</i>
                        Simpan Absensi
                    </button>
                </div>
            @else
                <div class="t-alert warning" style="margin-top: 1rem;">
                    <i class="material-icons">info</i>
                    <span>Belum ada santri untuk kelas binaan / filter jenis kelamin ini.</span>
                </div>
            @endif
        </div>
    </div>
</div>
