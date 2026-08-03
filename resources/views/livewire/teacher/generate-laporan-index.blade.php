<div class="t-page">
    <div class="t-card" style="max-width: 48rem; margin: 0 auto;">
        <div class="t-card-header">
            <div class="t-card-header-icon purple">
                <i class="material-icons">print</i>
            </div>
            <div>
                <div class="t-card-title">Laporan Absen Siswa Kelas</div>
                <div class="t-card-subtitle">Generate laporan kehadiran per bulan (Wali Kelas)</div>
            </div>
        </div>

        <div class="t-card-body">
            @if(!$isWaliKelas)
                <div class="t-alert warning">
                    <i class="material-icons">info</i>
                    <span>Anda tidak ditugaskan sebagai Wali Kelas. Anda tidak dapat membuat laporan absensi siswa.</span>
                </div>
            @else
                <form wire:submit.prevent>
                    {{-- Month Picker --}}
                    <div style="margin-bottom: 1.5rem;">
                        <label class="t-label">Bulan Laporan</label>
                        <input type="month" wire:model="tanggalSiswa" class="t-input" style="max-width: 280px;">
                        @error('tanggalSiswa') <span class="t-error">{{ $message }}</span> @enderror
                    </div>

                    {{-- Class Selector --}}
                    <div style="margin-bottom: 2rem;">
                        <label class="t-label">Kelas</label>
                        <select wire:model="kelas" class="t-select" disabled style="max-width: 280px;">
                            @foreach ($kelasList as $k)
                                <option value="{{ $k->id_kelas }}">
                                    {{ $k->tingkat }} {{ $k->index_kelas }}
                                </option>
                            @endforeach
                        </select>
                        @error('kelas') <span class="t-error">{{ $message }}</span> @enderror
                        <div class="t-hint">Kelas diatur secara otomatis ke kelas yang Anda asuh.</div>
                    </div>

                    {{-- Separator --}}
                    <div style="border-top: 1px solid var(--t-divider); padding-top: 1.5rem;">
                        <p style="font-size: 0.82rem; font-weight: 600; color: var(--t-on-surface-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem;">
                            Pilih Format Export
                        </p>

                        {{-- PDF Button --}}
                        <button wire:click="exportSiswa('pdf')" type="button" class="t-export-btn pdf">
                            <div class="t-export-icon">
                                <i class="material-icons">picture_as_pdf</i>
                            </div>
                            <div>
                                <div class="t-export-label">Generate PDF</div>
                                <div class="t-export-desc">Format cetak siap pakai</div>
                            </div>
                        </button>

                        {{-- DOC Button --}}
                        <button wire:click="exportSiswa('doc')" type="button" class="t-export-btn doc">
                            <div class="t-export-icon">
                                <i class="material-icons">description</i>
                            </div>
                            <div>
                                <div class="t-export-label">Generate DOC</div>
                                <div class="t-export-desc">Format dokumen Word</div>
                            </div>
                        </button>

                        {{-- XLS Button --}}
                        <button wire:click="exportSiswa('xls')" type="button" class="t-export-btn xls">
                            <div class="t-export-icon">
                                <i class="material-icons">table_view</i>
                            </div>
                            <div>
                                <div class="t-export-label">Generate XLS</div>
                                <div class="t-export-desc">Format spreadsheet Excel</div>
                            </div>
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
