<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <style>
                    .modern-table-card {
                        background: #fff;
                        border-radius: 12px;
                        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
                        padding: 20px;
                        margin-bottom: 30px;
                    }
                    .modern-table-header {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        margin-bottom: 25px;
                    }
                    .modern-table-title {
                        font-size: 1.25rem;
                        font-weight: 700;
                        color: #1e293b;
                        margin: 0;
                    }
                    .modern-table-subtitle {
                        font-size: 0.85rem;
                        color: #94a3b8;
                        margin-top: 3px;
                        margin-bottom: 0;
                    }
                    .modern-table-actions .btn {
                        margin: 0 0 0 10px;
                        border-radius: 8px;
                        padding: 8px 16px;
                        font-weight: 600;
                        text-transform: none;
                    }
                    .modern-table-actions .btn-refresh {
                        background: #f1f5f9;
                        color: #475569;
                        border: 1px solid #e2e8f0;
                        box-shadow: none;
                    }
                    .modern-table-actions .btn-add {
                        background: #2563eb;
                        color: #fff;
                        box-shadow: none;
                    }
                    .modern-table-toolbar {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        margin-bottom: 20px;
                    }
                    .toolbar-left {
                        display: flex;
                        gap: 15px;
                        align-items: center;
                    }
                    .toolbar-left select, .toolbar-right input {
                        border: 1px solid #e2e8f0;
                        border-radius: 8px;
                        padding: 8px 12px;
                        color: #475569;
                        font-size: 0.9rem;
                        outline: none;
                        background: #fff;
                    }
                    .toolbar-left .show-label {
                        font-size: 0.9rem;
                        color: #64748b;
                        font-weight: 500;
                    }
                    .toolbar-right {
                        position: relative;
                    }
                    .toolbar-right i {
                        position: absolute;
                        left: 12px;
                        top: 50%;
                        transform: translateY(-50%);
                        color: #94a3b8;
                        font-size: 18px;
                    }
                    .toolbar-right input {
                        padding-left: 35px;
                        width: 250px;
                    }
                    .modern-table {
                        width: 100%;
                        border-collapse: separate;
                        border-spacing: 0;
                    }
                    .modern-table th {
                        background: #f8fafc;
                        color: #64748b;
                        font-weight: 600;
                        font-size: 0.75rem;
                        text-transform: uppercase;
                        padding: 15px 20px;
                        border-bottom: 1px solid #f1f5f9;
                        letter-spacing: 0.5px;
                    }
                    .modern-table td {
                        padding: 15px 20px;
                        vertical-align: middle;
                        border-bottom: 1px solid #f8fafc;
                        color: #475569;
                        font-size: 0.95rem;
                    }
                    .modern-table tbody tr:hover {
                        background: #fcfcfc;
                    }
                    .avatar-circle {
                        width: 36px;
                        height: 36px;
                        border-radius: 50%;
                        background: #e0e7ff;
                        color: #3b82f6;
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        font-weight: 700;
                        margin-right: 15px;
                        font-size: 14px;
                    }
                    .user-name {
                        font-weight: 700;
                        color: #1e293b;
                    }
                    .badge-role {
                        padding: 5px 12px;
                        border-radius: 6px;
                        font-size: 0.75rem;
                        font-weight: 700;
                        text-transform: uppercase;
                        display: inline-block;
                    }
                    .badge-admin { background: #fee2e2; color: #dc2626; }
                    .badge-kepsek { background: #fef3c7; color: #d97706; }
                    .badge-staf { background: #e0e7ff; color: #4338ca; }
                    .badge-guru { background: #e0f2fe; color: #0284c7; }
                    .action-btns {
                        display: flex;
                        gap: 8px;
                    }
                    .btn-action {
                        width: 32px;
                        height: 32px;
                        border-radius: 6px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        border: none;
                        cursor: pointer;
                        transition: all 0.2s;
                        padding: 0;
                    }
                    .btn-action i {
                        font-size: 16px;
                    }
                    .btn-edit { background: #fef3c7; color: #d97706; }
                    .btn-edit:hover { background: #fde68a; }
                    .btn-delete { background: #fee2e2; color: #dc2626; }
                    .btn-delete:hover { background: #fecaca; }
                </style>

                <div class="row">
                    <div class="col-12">
                        <div class="modern-table-card">
                            <div class="modern-table-header">
                                <div>
                                    <h4 class="modern-table-title">Data Petugas / Pengguna</h4>
                                    <p class="modern-table-subtitle">Manajemen Akun Login Sistem</p>
                                </div>
                                <div class="modern-table-actions">
                                    <button wire:click="$refresh" class="btn btn-refresh">
                                        <i class="material-icons" style="font-size: 18px; vertical-align: middle;">refresh</i>
                                    </button>
                                    <button wire:click="create" class="btn btn-add">
                                        <i class="material-icons" style="font-size: 18px; vertical-align: middle; margin-right: 5px;">person_add</i> Tambah
                                    </button>
                                </div>
                            </div>

                            <div class="modern-table-toolbar">
                                <div class="toolbar-left">
                                    <span class="show-label">Show</span>
                                    <select wire:model.live="perPage">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                    </select>
                                </div>
                                <div class="toolbar-right">
                                    <i class="material-icons">search</i>
                                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari Nama/Email...">
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="modern-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">NO</th>
                                            <th>USERNAME</th>
                                            <th>EMAIL</th>
                                            <th>ROLE</th>
                                            <th>GURU TERKAIT</th>
                                            <th style="text-align: right;">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($petugasList as $index => $item)
                                            <tr>
                                                <td>{{ $petugasList->firstItem() + $index }}</td>
                                                <td>
                                                    <div style="display: flex; align-items: center;">
                                                        <div class="avatar-circle">
                                                            {{ strtoupper(substr($item->name, 0, 1)) }}
                                                        </div>
                                                        <span class="user-name">{{ $item->name }}</span>
                                                    </div>
                                                </td>
                                                <td>{{ $item->email }}</td>
                                                <td>
                                                    @if($item->is_superadmin == 1)
                                                        <span class="badge-role badge-admin">Super Admin</span>
                                                    @elseif($item->is_superadmin == 2)
                                                        <span class="badge-role badge-kepsek">Kepsek</span>
                                                    @elseif($item->is_superadmin == 3)
                                                        <span class="badge-role badge-staf">Staf Petugas</span>
                                                    @else
                                                        <span class="badge-role badge-guru">Guru</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->guru->nama_guru ?? '-' }}</td>
                                                <td>
                                                    <div class="action-btns" style="justify-content: flex-end;">
                                                        <button wire:click="edit({{ $item->id }})" class="btn-action btn-edit" title="Edit">
                                                            <i class="material-icons">edit</i>
                                                        </button>
                                                        @if(auth()->id() != $item->id)
                                                            <button wire:click="deleteId({{ $item->id }})" class="btn-action btn-delete" title="Hapus">
                                                                <i class="material-icons">delete</i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-muted">Belum ada data petugas</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                {{ $petugasList->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Modal (Add / Edit Petugas) -->
    @if($showModal)
    <div class="modal fade show" style="display: block; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 1050; overflow-y: auto;" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04); overflow: hidden; background: #fff;">
                <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                    <div class="modal-header" style="padding: 20px 24px; border-bottom: 1px solid #f1f5f9; background: #fafafa; display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; border-radius: 10px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center;">
                                <i class="material-icons" style="font-size: 22px;">{{ $isEdit ? 'manage_accounts' : 'person_add_alt' }}</i>
                            </div>
                            <div>
                                <h5 class="modal-title" style="font-size: 1.1rem; font-weight: 700; color: #0f172a; margin: 0;">{{ $isEdit ? 'Edit Petugas' : 'Tambah Petugas' }}</h5>
                                <p style="font-size: 0.78rem; color: #64748b; margin: 2px 0 0 0;">Isi formulir akun petugas / pengelola sistem</p>
                            </div>
                        </div>
                        <button type="button" wire:click="$set('showModal', false)" style="background: transparent; border: none; font-size: 20px; color: #94a3b8; cursor: pointer; padding: 4px;">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body" style="padding: 24px; background: #fff;">
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label style="font-size: 0.82rem; font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Username <span class="text-danger">*</span></label>
                                <input type="text" wire:model="name" class="form-control" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 9px 12px; font-size: 0.9rem;" required minlength="4" placeholder="Username login">
                                @error('name') <span class="text-danger" style="font-size: 0.78rem;">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label style="font-size: 0.82rem; font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Email <span class="text-danger">*</span></label>
                                <input type="email" wire:model="email" class="form-control" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 9px 12px; font-size: 0.9rem;" required placeholder="Alamat email aktif">
                                @error('email') <span class="text-danger" style="font-size: 0.78rem;">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label style="font-size: 0.82rem; font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Password {{ $isEdit ? '(Kosongkan jika tidak ingin mengubah)' : '*' }}</label>
                                <input type="password" wire:model="password" class="form-control" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 9px 12px; font-size: 0.9rem;" {{ $isEdit ? '' : 'required' }} minlength="6" placeholder="Minimal 6 karakter">
                                @error('password') <span class="text-danger" style="font-size: 0.78rem;">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label style="font-size: 0.82rem; font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Role / Akses <span class="text-danger">*</span></label>
                                <select wire:model="is_superadmin" class="form-control" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 9px 12px; font-size: 0.9rem; background: #fff;" required>
                                    <option value="">-- Pilih Role --</option>
                                    <option value="1">Admin / Kepala TPQ</option>
                                    <option value="0">Guru</option>
                                </select>
                                @error('is_superadmin') <span class="text-danger" style="font-size: 0.78rem;">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-12 form-group mb-2">
                                <label style="font-size: 0.82rem; font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Hubungkan ke Data Guru (Jika Role Guru)</label>
                                <select wire:model="id_guru" class="form-control" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 9px 12px; font-size: 0.9rem; background: #fff;">
                                    <option value="">-- Tidak dihubungkan --</option>
                                    @foreach($guruList as $g)
                                        <option value="{{ $g->id_guru }}">{{ $g->nama_guru }}</option>
                                    @endforeach
                                </select>
                                @error('id_guru') <span class="text-danger" style="font-size: 0.78rem;">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="padding: 16px 24px; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 10px;">
                        <button type="button" class="btn" style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 18px; font-weight: 600; font-size: 0.85rem;" wire:click="$set('showModal', false)">Batal</button>
                        <button type="submit" class="btn" style="background: #16a34a; color: #ffffff; border: none; border-radius: 8px; padding: 8px 22px; font-weight: 700; font-size: 0.85rem; box-shadow: 0 4px 6px -1px rgba(22, 163, 74, 0.2);">
                            <i class="material-icons" style="font-size: 18px; vertical-align: middle; margin-right: 4px;">save</i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Petugas</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus akun ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" wire:click="delete">Hapus</button>
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
</div>
