<div class="t-page">
    {{-- Flash Messages --}}
    @if (session()->has('msg'))
        <div class="t-alert {{ session('error') ? 'danger' : 'success' }}">
            <i class="material-icons">{{ session('error') ? 'error' : 'check_circle' }}</i>
            <span>{{ session('msg') }}</span>
            <button type="button" class="t-alert-close" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    <div class="t-card" style="max-width: 48rem; margin: 0 auto;">
        <div class="t-card-header">
            <div class="t-card-header-icon purple">
                <i class="material-icons">qr_code</i>
            </div>
            <div>
                <div class="t-card-title">Download Kartu QR Code Santri</div>
                <div class="t-card-subtitle">Unduh bundle file Kartu Absensi Santri berdesain resmi (.zip) kelas binaan Anda</div>
            </div>
        </div>

        <div class="t-card-body">
            @if(empty($kelasList) || count($kelasList) == 0)
                <div class="t-alert warning">
                    <i class="material-icons">info</i>
                    <span>Anda belum ditugaskan mengampu kelas/jilid binaan. Silakan hubungi Admin.</span>
                </div>
            @else
                {{-- Class Selector --}}
                <div style="margin-bottom: 1.5rem;">
                    <label class="t-label">Pilih Kelas / Jilid Binaan</label>
                    <select wire:model.live="kelas" class="t-select" style="max-width: 320px;">
                        @foreach ($kelasList as $k)
                            <option value="{{ $k->id_kelas }}">
                                {{ $k->tingkat }} {{ $k->index_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Student Count --}}
                <div style="display: flex; align-items: center; gap: 1rem; padding: 1.25rem; background: var(--t-primary-light); border-radius: var(--t-radius); margin-bottom: 1.5rem;">
                    <div style="width: 3rem; height: 3rem; border-radius: 50%; background: var(--t-primary); color: #fff; display: flex; align-items: center; justify-content: center;">
                        <i class="material-icons" style="font-size: 1.5rem;">groups</i>
                    </div>
                    <div>
                        <div style="font-size: 0.78rem; font-weight: 600; color: var(--t-on-surface-subtle); text-transform: uppercase; letter-spacing: 0.05em;">Total Santri di Kelas Ini</div>
                        <div style="font-size: 1.75rem; font-weight: 800; color: var(--t-primary);">{{ $totalSiswa }}</div>
                    </div>
                </div>

                {{-- Download Button --}}
                <button wire:click="downloadSiswa" class="t-btn t-btn-primary t-btn-lg" style="width: 100%; padding: 0.9rem; font-weight: 700;">
                    <i class="material-icons" style="font-size: 1.35rem;">cloud_download</i>
                    <span>Download ZIP Kartu Santri</span>
                </button>
            @endif
        </div>
    </div>
</div>
