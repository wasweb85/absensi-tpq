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

    <div class="t-card mb-4" style="margin-bottom: 1.5rem;">
        <div class="t-card-header" style="flex-wrap: wrap; gap: 1rem;">
            <div class="t-card-header-icon blue">
                <i class="material-icons">history</i>
            </div>
            <div style="flex: 1; min-width: 150px;">
                <div class="t-card-title">Riwayat Tabungan</div>
                <div class="t-card-subtitle">Kelola riwayat transaksi tabungan santri Anda.</div>
            </div>
        </div>
        
        <div class="t-card-body" style="padding: 1.5rem;">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label style="font-weight: 600; color: var(--t-on-surface-subtle); font-size: 0.85rem; margin-bottom: 0.5rem; display: block;">Dari Tanggal</label>
                    <input type="date" wire:model.live="tanggal_awal" class="t-input">
                </div>
                <div class="col-md-3 mb-3">
                    <label style="font-weight: 600; color: var(--t-on-surface-subtle); font-size: 0.85rem; margin-bottom: 0.5rem; display: block;">Sampai Tanggal</label>
                    <input type="date" wire:model.live="tanggal_akhir" class="t-input">
                </div>
                <div class="col-md-3 mb-3">
                    <label style="font-weight: 600; color: var(--t-on-surface-subtle); font-size: 0.85rem; margin-bottom: 0.5rem; display: block;">Filter Santri</label>
                    <select wire:model.live="filter_santri_id" class="t-input">
                        <option value="">Semua Santri</option>
                        @foreach($daftarSantri as $santri)
                            <option value="{{ $santri->id_siswa }}">{{ $santri->nama_siswa }} (Kelas {{ $santri->kelas->tingkat ?? '' }} {{ $santri->kelas->index_kelas ?? '' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label style="font-weight: 600; color: var(--t-on-surface-subtle); font-size: 0.85rem; margin-bottom: 0.5rem; display: block;">Jenis Transaksi</label>
                    <select wire:model.live="filter_jenis" class="t-input">
                        <option value="">Semua Jenis</option>
                        <option value="setor">Setor</option>
                        <option value="tarik">Tarik</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="t-card">
        <div style="overflow-x: auto;">
            <table class="t-table">
                <thead>
                    <tr>
                        <th style="padding-left: 1.5rem;">Tanggal</th>
                        <th>Santri</th>
                        <th>Jenis</th>
                        <th style="text-align: right;">Nominal</th>
                        <th>Status</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $item)
                        <tr>
                            <td style="padding-left: 1.5rem;">
                                <div style="font-weight: 600; color: var(--t-on-surface);">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</div>
                                <div style="font-size: 0.75rem; color: var(--t-on-surface-subtle);">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB</div>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--t-on-surface);">{{ $item->siswa->nama_siswa ?? 'Unknown' }}</div>
                                <div style="font-size: 0.75rem; color: var(--t-on-surface-subtle);">Kelas {{ $item->siswa->kelas->tingkat ?? '' }} {{ $item->siswa->kelas->index_kelas ?? '' }}</div>
                            </td>
                            <td>
                                @if($item->jenis_transaksi == 'setor')
                                    <span class="t-badge t-badge-success">Setor</span>
                                @else
                                    <span class="t-badge t-badge-danger">Tarik</span>
                                @endif
                                
                                @if($item->keterangan)
                                    <div style="font-size: 0.75rem; color: var(--t-on-surface-subtle); margin-top: 4px;">
                                        <i class="material-icons" style="font-size: 10px; vertical-align: middle;">chat</i> {{ $item->keterangan }}
                                    </div>
                                @endif
                            </td>
                            <td style="text-align: right; font-weight: 600; color: {{ $item->jenis_transaksi == 'setor' ? 'var(--t-hadir-text)' : 'var(--t-alpa-text)' }};">
                                Rp {{ number_format($item->nominal, 0, ',', '.') }}
                            </td>
                            <td>
                                @if($item->status_setoran == 'sudah')
                                    <span style="font-size: 0.75rem; font-weight: 600; color: var(--t-hadir-text);">
                                        <i class="material-icons" style="font-size: 12px; vertical-align: middle;">check_circle</i> Disetor
                                    </span>
                                @else
                                    <span style="font-size: 0.75rem; font-weight: 600; color: var(--t-sakit-text);">
                                        <i class="material-icons" style="font-size: 12px; vertical-align: middle;">pending</i> Belum Disetor
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($item->status_setoran == 'belum')
                                    <div style="display: flex; justify-content: center; gap: 0.5rem;">
                                        <button wire:click="editTabungan({{ $item->id_tabungan }})" class="t-btn t-btn-outline t-btn-sm" title="Edit" style="padding: 0.25rem 0.5rem;">
                                            <i class="material-icons" style="font-size: 1.1rem;">edit</i>
                                        </button>
                                        <button wire:click="hapusTabungan({{ $item->id_tabungan }})" wire:confirm="Yakin ingin menghapus transaksi ini? Saldo tabungan santri akan dikembalikan otomatis." class="t-btn t-btn-outline t-btn-sm" style="color: var(--t-alpa-text); border-color: var(--t-alpa-light); padding: 0.25rem 0.5rem;" title="Hapus">
                                            <i class="material-icons" style="font-size: 1.1rem;">delete</i>
                                        </button>
                                    </div>
                                @else
                                    <span style="font-size: 0.75rem; color: var(--t-on-surface-subtle);">
                                        <i class="material-icons" style="font-size:14px; vertical-align:middle;">lock</i> Terkunci
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 4rem 1.5rem;">
                                <div style="color: var(--t-on-surface-subtle); display: flex; flex-direction: column; align-items: center; gap: 1rem;">
                                    <i class="material-icons" style="font-size: 3rem; opacity: 0.5;">history</i>
                                    <span>Tidak ada riwayat transaksi yang ditemukan.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($riwayat->hasPages())
            <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--t-divider);">
                {{ $riwayat->links() }}
            </div>
        @endif
    </div>

    {{-- MODAL EDIT TRANSAKSI --}}
    <div wire:ignore.self class="modal fade" id="modalEditTabungan" tabindex="-1" role="dialog" aria-labelledby="modalEditTabunganLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="border-radius: var(--t-radius-xl); border: none; box-shadow: var(--t-shadow-md);">
                <div class="modal-header" style="background: var(--t-surface-hover); border-bottom: 1px solid var(--t-divider); border-radius: var(--t-radius-xl) var(--t-radius-xl) 0 0; padding: 1.25rem 1.5rem;">
                    <h5 class="modal-title" id="modalEditTabunganLabel" style="font-weight: 700; color: var(--t-on-surface); font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <div class="t-card-header-icon amber" style="width: 2rem; height: 2rem; font-size: 1rem;">
                            <i class="material-icons">edit</i>
                        </div>
                        Edit Transaksi Tabungan
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding: 1rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 1.5rem;">
                    <div class="form-group mb-3">
                        <label style="font-weight: 600; color: var(--t-on-surface-muted); font-size: 0.85rem; margin-bottom: 0.5rem; display: block;">Jenis Transaksi</label>
                        <select wire:model="editJenis" class="t-input">
                            <option value="setor">Setor</option>
                            <option value="tarik">Tarik</option>
                        </select>
                        @error('editJenis') <span style="color: var(--t-alpa-text); font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label style="font-weight: 600; color: var(--t-on-surface-muted); font-size: 0.85rem; margin-bottom: 0.5rem; display: block;">Nominal (Rp)</label>
                        <input type="number" wire:model="editNominal" class="t-input">
                        @error('editNominal') <span style="color: var(--t-alpa-text); font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 600; color: var(--t-on-surface-muted); font-size: 0.85rem; margin-bottom: 0.5rem; display: block;">Catatan Tambahan (Opsional)</label>
                        <input type="text" wire:model="editKeterangan" class="t-input">
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--t-divider); padding: 1.25rem 1.5rem; gap: 0.5rem;">
                    <button type="button" class="t-btn t-btn-outline" data-dismiss="modal">Batal</button>
                    <button type="button" wire:click="updateTabungan" class="t-btn t-btn-primary" style="background: var(--t-sakit); border-color: var(--t-sakit); color: #fff;">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        $wire.on('show-edit-tabungan-modal', () => {
            $('#modalEditTabungan').modal('show');
        });
        $wire.on('hide-edit-tabungan-modal', () => {
            $('#modalEditTabungan').modal('hide');
        });
    </script>
    @endscript
</div>
