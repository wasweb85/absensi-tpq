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
                <div class="t-card-title">Absensi Manual Siswa</div>
                <div class="t-card-subtitle">Input Kehadiran Siswa Secara Manual</div>
            </div>
        </div>

        <div class="t-card-body">
            {{-- Filter Row --}}
            <div style="display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label class="t-label">Tanggal</label>
                        <input type="date" wire:model.live="tanggal" class="t-input">
                    </div>
                    <div>
                        <label class="t-label">Pilih Kelas</label>
                        <select wire:model.live="id_kelas" class="t-select">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($allKelas as $k)
                                <option value="{{ $k->id_kelas }}">{{ $k->tingkat }} {{ $k->index_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            @if($id_kelas)
                @if($siswaList->count() > 0)
                    <div style="overflow-x: auto;">
                        <table class="t-table">
                            <thead>
                                <tr>
                                    <th style="width: 3rem;">No</th>
                                    <th>Nama Siswa</th>
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
                                        <td style="font-weight: 600;">{{ $siswa->nama_siswa }}</td>
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
                        <span>Belum ada siswa di kelas ini.</span>
                    </div>
                @endif
            @else
                <div class="t-alert info" style="margin-top: 1rem;">
                    <i class="material-icons">touch_app</i>
                    <span>Silakan pilih kelas terlebih dahulu untuk melihat daftar siswa.</span>
                </div>
            @endif
        </div>
    </div>
</div>
