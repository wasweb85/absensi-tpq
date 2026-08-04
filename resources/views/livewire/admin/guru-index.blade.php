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
                    .teacher-name {
                        font-weight: 700;
                        color: #1e293b;
                    }
                    .badge-akses {
                        background: #dcfce7;
                        color: #15803d;
                        padding: 3px 8px;
                        border-radius: 6px;
                        font-size: 0.7rem;
                        font-weight: 700;
                        margin-top: 4px;
                        display: inline-block;
                    }
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
                                <h4 class="modern-table-title">Direktori Guru</h4>
                                <div class="modern-table-actions">
                                    <button wire:click="$refresh" class="btn btn-refresh">
                                        <i class="material-icons" style="font-size: 18px; vertical-align: middle;">refresh</i>
                                    </button>
                                    <button wire:click="create" class="btn btn-add">
                                        <i class="material-icons" style="font-size: 18px; vertical-align: middle; margin-right: 5px;">add</i> Tambah
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
                                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari Nama / NUPTK...">
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="modern-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">NO</th>
                                            <th>NAMA GURU</th>
                                            <th>NUPTK</th>
                                            <th>L/P</th>
                                            <th>NO HP</th>
                                            <th style="text-align: right;">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($guruList as $index => $item)
                                            <tr>
                                                <td>{{ $guruList->firstItem() + $index }}</td>
                                                <td>
                                                    <div style="display: flex; align-items: center;">
                                                        <div class="avatar-circle">
                                                            {{ strtoupper(substr($item->nama_guru, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <span class="teacher-name">{{ $item->nama_guru }}</span>
                                                            @if($item->can_crud_siswa)
                                                                <br><span class="badge-akses">Akses CRUD Siswa</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $item->nuptk }}</td>
                                                <td>{{ ($item->jenis_kelamin == 'Laki-laki' || $item->jk == 'Laki-laki') ? 'L' : 'P' }}</td>
                                                <td>{{ $item->no_hp }}</td>
                                                <td>
                                                    <div class="action-btns" style="justify-content: flex-end;">
                                                        <button wire:click="edit({{ $item->id_guru }})" class="btn-action btn-edit" title="Edit">
                                                            <i class="material-icons">edit</i>
                                                        </button>
                                                        <button wire:click="deleteId({{ $item->id_guru }})" class="btn-action btn-delete" title="Hapus">
                                                            <i class="material-icons">delete</i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-muted">Belum ada data guru</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                {{ $guruList->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Modal (Add / Edit) -->
    @if($showModal)
    <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $isEdit ? 'Edit Data Guru' : 'Tambah Data Guru' }}</h5>
                        <button type="button" class="close" wire:click="$set('showModal', false)">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>NUPTK <span class="text-danger">*</span></label>
                                <input type="text" wire:model="nuptk" class="form-control" required minlength="16" maxlength="20">
                                @error('nuptk') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Nama <span class="text-danger">*</span></label>
                                <input type="text" wire:model="nama_guru" class="form-control" required minlength="3">
                                @error('nama_guru') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Jenis Kelamin <span class="text-danger">*</span></label>
                                <select wire:model="jk" class="form-control" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="Laki-laki">Laki-Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                                @error('jk') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Nomor HP <span class="text-danger">*</span></label>
                                <input type="text" wire:model="no_hp" class="form-control" required>
                                @error('no_hp') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Alamat (Opsional)</label>
                                <input type="text" wire:model="alamat" class="form-control">
                                @error('alamat') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Kode RFID (Opsional)</label>
                                <input type="text" wire:model="rfid" class="form-control">
                                @error('rfid') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="form-check">
                                    <label class="form-check-label text-dark">
                                        <input class="form-check-input" type="checkbox" wire:model="can_crud_siswa" value="1">
                                        Beri Akses CRUD Data Siswa (Guru ini bisa Menambah, Mengedit, Menghapus Data Siswa di kelasnya)
                                        <span class="form-check-sign">
                                            <span class="check"></span>
                                        </span>
                                    </label>
                                </div>
                                @error('can_crud_siswa') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
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
                    <h5 class="modal-title">Hapus Data Guru</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus data guru ini?
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

