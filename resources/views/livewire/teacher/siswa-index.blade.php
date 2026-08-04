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
        {{-- Header with search and actions --}}
        <div class="t-card-header" style="flex-wrap: wrap; gap: 1rem;">
            <div class="t-card-header-icon purple">
                <i class="material-icons">people</i>
            </div>
            <div style="flex: 1; min-width: 150px;">
                <div class="t-card-title">Daftar Siswa Kelas</div>
                <div class="t-card-subtitle">Manajemen Data Santri di Kelas Anda</div>
            </div>
            <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                <div style="position: relative;">
                    <i class="material-icons" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: var(--t-on-surface-subtle); font-size: 1.15rem;">search</i>
                    <input type="text" wire:model.live.debounce.300ms="search" class="t-input" placeholder="Cari Nama / NIS..." style="padding-left: 2.25rem; width: 220px;">
                </div>
                @if($can_crud)
                <button wire:click="create" class="t-btn t-btn-primary t-btn-sm">
                    <i class="material-icons" style="font-size: 1.1rem;">add</i> Baru
                </button>
                @endif
                <button wire:click="$refresh" class="t-btn t-btn-outline t-btn-sm" title="Refresh">
                    <i class="material-icons" style="font-size: 1.1rem;">refresh</i>
                </button>
            </div>
        </div>

        {{-- Table Body --}}
        <div style="overflow-x: auto;">
            @if(!$kelas_id)
                <div class="t-alert warning" style="margin: 1.5rem;">
                    <i class="material-icons">info</i>
                    <span>Anda belum ditugaskan sebagai wali kelas di kelas manapun, sehingga tidak ada data siswa yang ditampilkan.</span>
                </div>
            @endif

            <table class="t-table">
                <thead>
                    <tr>
                        <th style="width: 3rem;">No</th>
                        <th>NIS</th>
                        <th>Nama Santri</th>
                        <th>L/P</th>
                        <th>No HP</th>
                        @if($can_crud)
                        <th style="text-align: center;">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswaList as $index => $item)
                        <tr>
                            <td style="color: var(--t-on-surface-subtle); font-weight: 500;">{{ $siswaList->firstItem() + $index }}</td>
                            <td>
                                <span class="t-badge purple" style="font-size: 0.78rem;">{{ $item->nis }}</span>
                            </td>
                            <td style="font-weight: 600;">{{ $item->nama_siswa }}</td>
                            <td>
                                <span class="t-badge {{ $item->jenis_kelamin === 'Laki-laki' ? 'blue' : 'purple' }}">
                                    {{ $item->jenis_kelamin === 'Laki-laki' ? 'L' : 'P' }}
                                </span>
                            </td>
                            <td style="color: var(--t-on-surface-muted);">{{ $item->no_hp ?? '-' }}</td>
                            @if($can_crud)
                            <td style="text-align: center;">
                                <div style="display: flex; gap: 0.375rem; justify-content: center;">
                                    <button wire:click="edit({{ $item->id_siswa }})" class="t-btn t-btn-icon t-btn-ghost" title="Edit">
                                        <i class="material-icons" style="font-size: 1.15rem; color: var(--t-primary);">edit</i>
                                    </button>
                                    <button wire:click="deleteId({{ $item->id_siswa }})" class="t-btn t-btn-icon t-btn-ghost" title="Hapus">
                                        <i class="material-icons" style="font-size: 1.15rem; color: var(--t-alpa);">delete</i>
                                    </button>
                                </div>
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $can_crud ? 6 : 5 }}">
                                <div class="t-empty" style="padding: 2rem;">
                                    <i class="material-icons">school</i>
                                    <p>Belum ada data siswa di kelas ini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--t-divider);">
            {{ $siswaList->links() }}
        </div>
    </div>

    @if($can_crud)
    {{-- Add / Edit Modal --}}
    @if($showModal)
    <div class="modal fade show" style="display: block; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 1050; overflow-y: auto;" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04); overflow: hidden; background: #fff;">
                <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                    <div class="modal-header" style="padding: 20px 24px; border-bottom: 1px solid #f1f5f9; background: #fafafa; display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; border-radius: 10px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center;">
                                <i class="material-icons" style="font-size: 22px;">{{ $isEdit ? 'edit_note' : 'person_add' }}</i>
                            </div>
                            <div>
                                <h5 class="modal-title" style="font-size: 1.1rem; font-weight: 700; color: #0f172a; margin: 0;">{{ $isEdit ? 'Edit Data Siswa' : 'Tambah Data Siswa' }}</h5>
                                <p style="font-size: 0.78rem; color: #64748b; margin: 2px 0 0 0;">Isi formulir data santri untuk kelas Anda</p>
                            </div>
                        </div>
                        <button type="button" wire:click="$set('showModal', false)" style="background: transparent; border: none; font-size: 20px; color: #94a3b8; cursor: pointer; padding: 4px;">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body" style="padding: 24px; background: #fff;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div>
                                <label style="font-size: 0.82rem; font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">NIS <span class="text-danger">*</span></label>
                                <input type="number" wire:model="nis" class="t-input" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 9px 12px; font-size: 0.9rem;" required placeholder="Masukkan NIS">
                                @error('nis') <span class="t-error">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label style="font-size: 0.82rem; font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" wire:model="nama_siswa" class="t-input" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 9px 12px; font-size: 0.9rem;" required minlength="3" placeholder="Masukkan Nama Lengkap">
                                @error('nama_siswa') <span class="t-error">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label style="font-size: 0.82rem; font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select wire:model="jenis_kelamin" class="t-select" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 9px 12px; font-size: 0.9rem; background: #fff;" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="Laki-laki">Laki-Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                                @error('jenis_kelamin') <span class="t-error">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label style="font-size: 0.82rem; font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Nomor HP / WA Ortu</label>
                                <input type="text" wire:model="no_hp" class="t-input" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 9px 12px; font-size: 0.9rem;" placeholder="08xxxxxxxxxx">
                                @error('no_hp') <span class="t-error">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label style="font-size: 0.82rem; font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Kode RFID (Opsional)</label>
                                <input type="text" wire:model="rfid_code" class="t-input" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 9px 12px; font-size: 0.9rem;" placeholder="Tempelkan kartu RFID">
                                @error('rfid_code') <span class="t-error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="padding: 16px 24px; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 10px;">
                        <button type="button" class="t-btn t-btn-outline" style="border-radius: 8px; padding: 8px 18px;" wire:click="$set('showModal', false)">Batal</button>
                        <button type="submit" class="t-btn" style="background: #16a34a; color: #ffffff; border: none; border-radius: 8px; padding: 8px 22px; font-weight: 700; font-size: 0.85rem; box-shadow: 0 4px 6px -1px rgba(22, 163, 74, 0.2);">
                            <i class="material-icons" style="font-size: 1.1rem; vertical-align: middle; margin-right: 4px;">save</i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="border-radius: var(--t-radius-xl); border: none; overflow: hidden;">
                <div class="t-modal-header">
                    <h3>Hapus Data Siswa</h3>
                    <button type="button" class="t-btn t-btn-icon t-btn-ghost" data-dismiss="modal">
                        <i class="material-icons">close</i>
                    </button>
                </div>
                <div class="t-modal-body">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div style="width: 2.5rem; height: 2.5rem; border-radius: 50%; background: var(--t-alpa-bg); color: var(--t-alpa); display: flex; align-items: center; justify-content: center;">
                            <i class="material-icons">warning</i>
                        </div>
                        <p style="margin: 0;">Apakah Anda yakin ingin menghapus data siswa ini?</p>
                    </div>
                </div>
                <div class="t-modal-footer">
                    <button type="button" class="t-btn t-btn-outline" data-dismiss="modal">Batal</button>
                    <button type="button" class="t-btn t-btn-primary" style="background: var(--t-alpa);" wire:click="delete">
                        <i class="material-icons" style="font-size: 1.1rem;">delete</i> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        $wire.on('show-delete-modal', () => {
            $('#deleteModal').modal('show');
        });
        $wire.on('hide-delete-modal', () => {
            $('#deleteModal').modal('hide');
        });
    </script>
    @endscript
    @endif
</div>
