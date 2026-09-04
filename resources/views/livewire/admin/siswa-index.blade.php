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
                        border-radius: 16px;
                        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
                        padding: 24px;
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
                        font-weight: 800;
                        color: #0f172a;
                        margin: 0;
                    }
                    .btn-add {
                        background: #2563eb;
                        color: #fff;
                        border: none;
                        border-radius: 10px;
                        padding: 9px 16px;
                        font-weight: 700;
                        font-size: 0.85rem;
                        cursor: pointer;
                        box-shadow: 0 3px 10px rgba(37, 99, 235, 0.25);
                        display: inline-flex;
                        align-items: center;
                        white-space: nowrap;
                        transition: background 0.2s;
                    }
                    .btn-add:hover {
                        background: #1d4ed8;
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
                        border-radius: 10px;
                        padding: 9px 14px;
                        color: #475569;
                        font-size: 0.9rem;
                        outline: none;
                        background: #fff;
                    }
                    .toolbar-left .show-label {
                        font-size: 0.9rem;
                        color: #64748b;
                        font-weight: 600;
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
                        font-weight: 700;
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
                        display: none; /* dihapus per request */
                    }
                    .student-name {
                        font-weight: 700;
                        color: #0f172a;
                    }

                    .mobile-only {
                        display: none !important;
                    }
                    .desktop-only {
                        display: flex !important;
                    }

                    /* ── MOBILE RESPONSIVE ── */
                    @media (max-width: 640px) {
                        .mobile-only {
                            display: inline-flex !important;
                        }
                        .desktop-only {
                            display: none !important;
                        }
                        .modern-table-card {
                            padding: 14px;
                        }
                        .modern-table-header {
                            flex-direction: column;
                            align-items: flex-start;
                            gap: 10px;
                            margin-bottom: 14px;
                        }
                        .modern-table-toolbar {
                            flex-direction: column;
                            align-items: stretch;
                            gap: 10px;
                            margin-bottom: 12px;
                        }
                        .toolbar-left {
                            flex-wrap: wrap;
                            gap: 8px;
                        }
                        .toolbar-right input {
                            width: 100%;
                        }
                        /* Sembunyikan kolom NIS & L/P di mobile */
                        .modern-table th.col-nis,
                        .modern-table td.col-nis,
                        .modern-table th.col-lp,
                        .modern-table td.col-lp {
                            display: none;
                        }
                        .modern-table th,
                        .modern-table td {
                            padding: 10px 10px;
                            font-size: 0.85rem;
                        }
                        .btn-action {
                            width: 30px;
                            height: 30px;
                        }
                        .action-btns {
                            gap: 5px;
                        }
                    }
                    .badge-kelas {
                        background: transparent;
                        color: #374151;
                        padding: 0;
                        border-radius: 0;
                        font-size: 0.78rem;
                        font-weight: 700;
                        text-transform: uppercase;
                        white-space: nowrap;
                    }
                    .action-btns {
                        display: flex;
                        gap: 8px;
                    }
                    .btn-action {
                        width: 34px;
                        height: 34px;
                        border-radius: 8px;
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
                    .btn-view { background: #dcfce7; color: #16a34a; }
                    .btn-view:hover { background: #bbf7d0; }
                    .btn-edit { background: #fef3c7; color: #d97706; }
                    .btn-edit:hover { background: #fde68a; }
                    .btn-delete { background: #fee2e2; color: #dc2626; }
                    .btn-delete:hover { background: #fecaca; }
                    .btn-qr { background: #e0e7ff; color: #4f46e5; }
                    .btn-qr:hover { background: #c7d2fe; }

                    /* Custom Modal Styles */
                    .form-label-custom {
                        font-size: 0.72rem;
                        font-weight: 800;
                        color: #475569;
                        letter-spacing: 0.5px;
                        text-transform: uppercase;
                        margin-bottom: 6px;
                        display: block;
                    }
                    .form-input-custom {
                        border-radius: 10px !important;
                        border: 1px solid #e2e8f0 !important;
                        background: #f8fafc !important;
                        padding: 10px 14px !important;
                        font-size: 0.9rem !important;
                        color: #0f172a !important;
                        width: 100%;
                        outline: none;
                        transition: all 0.2s;
                    }
                    .form-input-custom:focus {
                        background: #ffffff !important;
                        border-color: #2563eb !important;
                        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1) !important;
                    }
                    .btn-cancel-custom {
                        background: transparent;
                        color: #475569;
                        border: none;
                        font-weight: 700;
                        font-size: 0.9rem;
                        padding: 10px 20px;
                        cursor: pointer;
                        border-radius: 10px;
                    }
                    .btn-cancel-custom:hover {
                        background: #f1f5f9;
                    }
                    .btn-save-custom {
                        background: #2563eb;
                        color: #ffffff;
                        border: none;
                        border-radius: 10px;
                        font-weight: 700;
                        font-size: 0.9rem;
                        padding: 10px 24px;
                        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
                        cursor: pointer;
                        transition: all 0.2s;
                    }
                    .btn-save-custom:hover {
                        background: #1d4ed8;
                    }

                    .detail-label {
                        font-size: 0.72rem;
                        font-weight: 800;
                        color: #64748b;
                        letter-spacing: 0.5px;
                        text-transform: uppercase;
                        margin-bottom: 2px;
                    }
                    .detail-value {
                        font-size: 0.95rem;
                        font-weight: 700;
                        color: #0f172a;
                    }
                </style>
                
                <div class="row">
                    <div class="col-12">
                        <div class="modern-table-card">
                            <div class="modern-table-header">
                                <h4 class="modern-table-title">Direktori Siswa</h4>
                                @if((int)(auth()->user()->is_superadmin ?? 0) !== 2)
                                <button wire:click="create" class="btn btn-add desktop-only">
                                    <i class="material-icons" style="font-size: 18px; vertical-align: middle; margin-right: 5px;">add</i> Tambah
                                </button>
                                @endif
                            </div>

                            <div class="modern-table-toolbar">
                                <div class="toolbar-left">
                                    <span class="show-label">Show</span>
                                    <select wire:model.live="perPage">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                    </select>
                                    <select wire:model.live="filter_kelas">
                                        <option value="">Semua Kelas</option>
                                        @foreach($kelasList as $k)
                                            <option value="{{ $k->id_kelas }}">{{ $k->tingkat }} {{ $k->index_kelas }}</option>
                                        @endforeach
                                    </select>
                                    @if((int)(auth()->user()->is_superadmin ?? 0) !== 2)
                                    <button wire:click="create" class="btn btn-add mobile-only">
                                        <i class="material-icons" style="font-size: 16px; vertical-align: middle; margin-right: 4px;">add</i> Tambah
                                    </button>
                                    @endif
                                </div>
                                <div class="toolbar-right">
                                    <i class="material-icons">search</i>
                                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari Nama / NIS...">
                                </div>
                            </div>
                        
                            <div class="table-responsive">
                                <table class="modern-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">NO</th>
                                            <th>NAMA</th>
                                            <th class="col-nis">NIS</th>
                                            <th>KELAS</th>
                                            <th class="col-lp">L/P</th>
                                            <th style="text-align: right;">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($siswaList as $index => $item)
                                            <tr>
                                                <td>{{ $siswaList->firstItem() + $index }}</td>
                                                <td>
                                                    <span class="student-name">{{ $item->nama_siswa }}</span>
                                                </td>
                                                <td class="col-nis">{{ $item->nis }}</td>
                                                <td class="col-kelas" style="white-space: nowrap;">
                                                    @if($item->kelas)
                                                        <span class="badge-kelas">{{ $item->kelas->tingkat }} {{ $item->kelas->index_kelas }}</span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="col-lp">{{ $item->jenis_kelamin == 'Laki-laki' ? 'L' : 'P' }}</td>
                                                <td>
                                                    <div class="action-btns" style="justify-content: flex-end;">
                                                        <button wire:click="showDetail({{ $item->id_siswa }})" class="btn-action btn-view" title="Detail Data Santri">
                                                            <i class="material-icons">visibility</i>
                                                        </button>
                                                        @if((int)(auth()->user()->is_superadmin ?? 0) !== 2)
                                                        <button wire:click="edit({{ $item->id_siswa }})" class="btn-action btn-edit" title="Edit">
                                                            <i class="material-icons">edit</i>
                                                        </button>
                                                        <button wire:click="deleteId({{ $item->id_siswa }})" class="btn-action btn-delete" title="Hapus">
                                                            <i class="material-icons">delete</i>
                                                        </button>
                                                        @endif
                                                        <button wire:click="generateQR({{ $item->id_siswa }})" class="btn-action btn-qr" title="QR Code">
                                                            <i class="material-icons">qr_code</i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-muted">Belum ada data siswa</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-4">
                                {{ $siswaList->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Modal (Tanda Mata / View Full Santri Data) -->
    <div x-data="{ open: @entangle('showDetailModal') }" x-show="open" style="display: none;">
        <div x-show="open" x-transition.opacity.duration.300ms class="modal fade show" style="display: block; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 1050; overflow-y: auto;" tabindex="-1" role="dialog">
            <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="modal-dialog modal-dialog-centered" role="document" style="max-width: 540px;" @click.outside="open = false; setTimeout(() => $wire.closeDetailModal(), 300)">
                @if($detailStudent)
                <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); overflow: hidden; background: #fff;">
                    <div class="modal-header" style="padding: 24px 28px 18px 28px; border-bottom: 1px solid #f1f5f9; background: #ffffff; display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <div style="width: 44px; height: 44px; border-radius: 12px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="material-icons" style="font-size: 24px;">visibility</i>
                            </div>
                            <div>
                                <h5 class="modal-title" style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin: 0;">Detail Data Santri</h5>
                                <p style="font-size: 0.82rem; color: #64748b; margin: 2px 0 0 0;">Informasi lengkap identitas santri</p>
                            </div>
                        </div>
                        <button type="button" @click="open = false; setTimeout(() => $wire.closeDetailModal(), 300)" style="background: transparent; border: none; font-size: 24px; color: #94a3b8; cursor: pointer; padding: 4px;">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body" style="padding: 24px 28px; background: #fff;">
                        <!-- Avatar & Header Banner -->
                        <div class="d-flex align-items-center mb-4 p-3" style="background: #f8fafc; border-radius: 14px; border: 1px solid #f1f5f9;">
                            <div class="avatar-circle" style="width: 52px; height: 52px; font-size: 20px; flex-shrink: 0;">
                                {{ strtoupper(substr($detailStudent->nama_siswa, 0, 1)) }}
                            </div>
                            <div style="overflow: hidden;">
                                <h4 style="font-weight: 800; color: #0f172a; margin: 0; font-size: 1.15rem;">{{ $detailStudent->nama_siswa }}</h4>
                                <span class="badge-kelas mt-1" style="display: inline-block;">
                                    {{ $detailStudent->kelas ? ($detailStudent->kelas->tingkat . ' ' . $detailStudent->kelas->index_kelas) : 'Belum Ada Kelas' }}
                                </span>
                            </div>
                        </div>

                        <!-- Details Grid -->
                        <div class="row" style="row-gap: 16px;">
                            <div class="col-6">
                                <div class="detail-label">NIS</div>
                                <div class="detail-value">{{ $detailStudent->nis }}</div>
                            </div>
                            <div class="col-6">
                                <div class="detail-label">JENIS KELAMIN</div>
                                <div class="detail-value">{{ $detailStudent->jenis_kelamin }}</div>
                            </div>
                            <div class="col-6">
                                <div class="detail-label">TANGGAL LAHIR</div>
                                <div class="detail-value">
                                    {{ $detailStudent->tanggal_lahir ? \Carbon\Carbon::parse($detailStudent->tanggal_lahir)->locale('id')->translatedFormat('d F Y') : '-' }}
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="detail-label">NO. HANDPHONE / WA</div>
                                <div class="detail-value">{{ $detailStudent->no_hp ?: '-' }}</div>
                            </div>
                        </div>

                        <!-- Parents Info Card -->
                        <div style="background: #f8fafc; border: 1px solid #f1f5f9; border-radius: 14px; padding: 16px 18px; margin-top: 20px;">
                            <div style="font-size: 0.75rem; font-weight: 800; color: #475569; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 12px;">Data Orang Tua Santri</div>
                            <div class="row" style="row-gap: 12px;">
                                <div class="col-6">
                                    <div class="detail-label">NAMA AYAH</div>
                                    <div class="detail-value" style="font-size: 0.9rem;">{{ $detailStudent->nama_ayah ?: '-' }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="detail-label">NAMA IBU</div>
                                    <div class="detail-value" style="font-size: 0.9rem;">{{ $detailStudent->nama_ibu ?: '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="padding: 16px 28px; background: #fff; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end;">
                        <button type="button" class="btn-save-custom" @click="open = false; setTimeout(() => $wire.closeDetailModal(), 300)">Tutup</button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Form Modal (Registrasi Siswa Baru / Edit Data Siswa) -->
    <div x-data="{ open: @entangle('showModal') }" x-show="open" style="display: none;">
        <div x-show="open" x-transition.opacity.duration.300ms class="modal fade show" style="display: block; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 1050; overflow-y: auto;" tabindex="-1" role="dialog">
            <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="modal-dialog modal-lg modal-dialog-centered" role="document" @click.outside="open = false; setTimeout(() => $wire.set('showModal', false), 300)">
                <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); overflow: hidden; background: #fff;">
                    <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                        <!-- Modal Header with Kept Text/Icons -->
                        <div class="modal-header" style="padding: 24px 28px 18px 28px; border-bottom: 1px solid #f1f5f9; background: #ffffff; display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 14px;">
                                <div style="width: 44px; height: 44px; border-radius: 12px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <i class="material-icons" style="font-size: 24px;">{{ $isEdit ? 'edit_note' : 'person_add' }}</i>
                                </div>
                                <div>
                                    <h5 class="modal-title" style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin: 0;">{{ $isEdit ? 'Edit Data Siswa' : 'Tambah Data Siswa' }}</h5>
                                    <p style="font-size: 0.82rem; color: #64748b; margin: 2px 0 0 0;">Isi formulir di bawah ini dengan data santri yang valid</p>
                                </div>
                            </div>
                            <button type="button" @click="open = false; setTimeout(() => $wire.set('showModal', false), 300)" style="background: transparent; border: none; font-size: 24px; color: #94a3b8; cursor: pointer; padding: 4px;">
                                &times;
                            </button>
                        </div>

                        <!-- Modal Body Form Layout matching requested Image -->
                        <div class="modal-body" style="padding: 24px 28px; background: #fff;">
                            <!-- Row 1: NAMA LENGKAP -->
                            <div class="mb-3">
                                <label class="form-label-custom">NAMA LENGKAP <span class="text-danger">*</span></label>
                                <input type="text" wire:model="nama_siswa" class="form-input-custom" placeholder="Sesuai Akta Kelahiran" required minlength="3">
                                @error('nama_siswa') <span class="text-danger" style="font-size: 0.78rem;">{{ $message }}</span> @enderror
                            </div>

                            <!-- Row 2: NIS & KELAS -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-custom">NIS <span class="text-danger">*</span></label>
                                    <input type="number" wire:model="nis" class="form-input-custom" placeholder="Nomor Induk Santri" required>
                                    @error('nis') <span class="text-danger" style="font-size: 0.78rem;">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-custom">KELAS <span class="text-danger">*</span></label>
                                    <select wire:model="id_kelas" class="form-input-custom" required>
                                        <option value="">Ketik atau pilih kelas</option>
                                        @foreach($kelasList as $k)
                                            <option value="{{ $k->id_kelas }}">{{ $k->tingkat }} {{ $k->index_kelas }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_kelas') <span class="text-danger" style="font-size: 0.78rem;">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Row 3: JENIS KELAMIN & TANGGAL LAHIR -->
                            <div class="row">   
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-custom">JENIS KELAMIN <span class="text-danger">*</span></label>
                                    <select wire:model="jenis_kelamin" class="form-input-custom" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin') <span class="text-danger" style="font-size: 0.78rem;">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-custom">TANGGAL LAHIR</label>
                                    <input type="date" wire:model="tanggal_lahir" class="form-input-custom">
                                    @error('tanggal_lahir') <span class="text-danger" style="font-size: 0.78rem;">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Row 4: Grouped Parent & Contact Container -->
                            <div style="background: #f8fafc; border: 1px solid #f1f5f9; border-radius: 14px; padding: 18px 20px; margin-top: 6px;">
                                <div class="row">
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <label class="form-label-custom">NAMA AYAH</label>
                                        <input type="text" wire:model="nama_ayah" class="form-input-custom" style="background: #ffffff !important;" placeholder="Nama Ayah Kandung">
                                        @error('nama_ayah') <span class="text-danger" style="font-size: 0.78rem;">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <label class="form-label-custom">NAMA IBU</label>
                                        <input type="text" wire:model="nama_ibu" class="form-input-custom" style="background: #ffffff !important;" placeholder="Nama Ibu Kandung">
                                        @error('nama_ibu') <span class="text-danger" style="font-size: 0.78rem;">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label-custom">NO. HANDPHONE</label>
                                        <input type="text" wire:model="no_hp" class="form-input-custom" style="background: #ffffff !important;" placeholder="08xxxxxxxxxx">
                                        @error('no_hp') <span class="text-danger" style="font-size: 0.78rem;">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer Buttons -->
                        <div class="modal-footer" style="padding: 18px 28px; background: #ffffff; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; align-items: center; gap: 12px;">
                            <button type="button" class="btn-cancel-custom" @click="open = false; setTimeout(() => $wire.set('showModal', false), 300)">Batal</button>
                            <button type="submit" class="btn-save-custom">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Digital ID Card Modal (QR) -->
    <style>
        @media print {
            @page {
                size: 3.75in 5.52in;
                margin: 0;
            }
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            body * {
                visibility: hidden;
            }
            body, html {
                margin: 0;
                padding: 0;
                background: white !important;
            }
            #qrPrintCard, #qrPrintCard * {
                visibility: visible;
            }
            #qrPrintCard {
                position: absolute;
                left: 0;
                top: 0;
                transform: none;
                width: 360px;
                height: 530px;
                margin: 0;
                border-radius: 0;
                box-shadow: none !important;
                page-break-inside: avoid;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
    <div x-data="{ open: @entangle('showQrModal') }" x-show="open" style="display: none;">
        <div x-show="open" x-transition.opacity.duration.300ms class="modal fade show" style="display: block; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); overflow-y: auto;" tabindex="-1" role="dialog">
            <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="modal-dialog modal-dialog-centered my-3" role="document" style="max-width: 420px; display: flex; flex-direction: column; align-items: center; justify-content: center;" @click.outside="open = false; setTimeout(() => $wire.closeQrModal(), 300)">
                @if($qrStudent)
                <div id="qrPrintCard" style="width: 360px; height: 530px; border-radius: 24px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
                    <img src="{{ route('admin.qr.siswa.view', $qrStudent->id_siswa) }}" alt="Kartu Absensi {{ $qrStudent->nama_siswa }}" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                </div>

                <!-- Action Buttons Footer -->
                <div class="no-print" style="width: 360px; margin-top: 15px; margin-bottom: 20px; display: flex; gap: 15px; position: relative; z-index: 9999; pointer-events: auto;">
                    <a href="{{ route('admin.qr.siswa.download', $qrStudent->id_siswa) }}" style="flex: 1; background: #0ea5e9; color: white; border-radius: 12px; padding: 12px; font-weight: 700; border: none; box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3); display: flex; align-items: center; justify-content: center; cursor: pointer; text-transform: uppercase; font-size: 0.85rem; text-decoration: none;" title="Unduh Kartu">
                        <i class="material-icons" style="font-size: 18px; margin-right: 8px;">download</i> Unduh
                    </a>
                    <button type="button" @click="open = false; setTimeout(() => $wire.closeQrModal(), 300)" style="flex: 1; background: white; color: #475569; border-radius: 12px; padding: 12px; font-weight: 700; border: none; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); display: flex; align-items: center; justify-content: center; cursor: pointer; text-transform: uppercase; font-size: 0.85rem; pointer-events: auto;" title="Tutup">
                        Tutup
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-data="{ open: false }" x-show="open" 
         x-on:show-delete-modal.window="open = true" 
         x-on:hide-delete-modal.window="open = false" 
         style="display: none;">
        <div x-show="open" x-transition.opacity.duration.300ms class="modal fade show" style="display: block; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 1050; overflow-y: auto;" tabindex="-1" role="dialog">
            <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="modal-dialog modal-dialog-centered" role="document" @click.outside="open = false">
                <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
                    <div class="modal-header" style="border-bottom: 1px solid #f1f5f9; padding: 20px 24px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; border-radius: 10px; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center;">
                                <i class="material-icons">delete_outline</i>
                            </div>
                            <h5 class="modal-title" style="font-weight: 800; color: #0f172a; margin: 0;">Hapus Data Siswa</h5>
                        </div>
                        <button type="button" @click="open = false" style="background: transparent; border: none; font-size: 24px; color: #94a3b8; cursor: pointer;">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body" style="padding: 24px; color: #475569; font-size: 0.95rem;">
                        Apakah Anda yakin ingin menghapus data siswa ini? Tindakan ini tidak dapat dibatalkan.
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid #f1f5f9; padding: 16px 24px; display: flex; justify-content: flex-end; gap: 12px;">
                        <button type="button" class="btn-cancel-custom" @click="open = false">Batal</button>
                        <button type="button" class="btn btn-danger" style="border-radius: 10px; font-weight: 700; padding: 10px 24px; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);" wire:click="delete">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
