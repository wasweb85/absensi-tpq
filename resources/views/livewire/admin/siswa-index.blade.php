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
                    .modern-table-actions .btn {
                        margin: 0 0 0 10px;
                        border-radius: 10px;
                        padding: 9px 18px;
                        font-weight: 700;
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
                        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
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
                        width: 38px;
                        height: 38px;
                        border-radius: 50%;
                        background: #e0e7ff;
                        color: #3b82f6;
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        font-weight: 800;
                        margin-right: 15px;
                        font-size: 14px;
                    }
                    .student-name {
                        font-weight: 700;
                        color: #0f172a;
                    }
                    .badge-kelas {
                        background: #eff6ff;
                        color: #2563eb;
                        padding: 5px 12px;
                        border-radius: 8px;
                        font-size: 0.75rem;
                        font-weight: 700;
                        text-transform: uppercase;
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
                                    <select wire:model.live="filter_kelas">
                                        <option value="">Semua Kelas</option>
                                        @foreach($kelasList as $k)
                                            <option value="{{ $k->id_kelas }}">{{ $k->tingkat }} {{ $k->index_kelas }}</option>
                                        @endforeach
                                    </select>
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
                                            <th>NIS</th>
                                            <th>KELAS</th>
                                            <th>L/P</th>
                                            <th style="text-align: right;">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($siswaList as $index => $item)
                                            <tr>
                                                <td>{{ $siswaList->firstItem() + $index }}</td>
                                                <td>
                                                    <div style="display: flex; align-items: center;">
                                                        <div class="avatar-circle">
                                                            {{ strtoupper(substr($item->nama_siswa, 0, 1)) }}
                                                        </div>
                                                        <span class="student-name">{{ $item->nama_siswa }}</span>
                                                    </div>
                                                </td>
                                                <td>{{ $item->nis }}</td>
                                                <td>
                                                    @if($item->kelas)
                                                        <span class="badge-kelas">{{ $item->kelas->tingkat }} {{ $item->kelas->index_kelas }}</span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ $item->jenis_kelamin == 'Laki-laki' ? 'L' : 'P' }}</td>
                                                <td>
                                                    <div class="action-btns" style="justify-content: flex-end;">
                                                        <button wire:click="showDetail({{ $item->id_siswa }})" class="btn-action btn-view" title="Detail Data Santri">
                                                            <i class="material-icons">visibility</i>
                                                        </button>
                                                        <button wire:click="edit({{ $item->id_siswa }})" class="btn-action btn-edit" title="Edit">
                                                            <i class="material-icons">edit</i>
                                                        </button>
                                                        <button wire:click="deleteId({{ $item->id_siswa }})" class="btn-action btn-delete" title="Hapus">
                                                            <i class="material-icons">delete</i>
                                                        </button>
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
    @if($showDetailModal && $detailStudent)
    <div class="modal fade show" style="display: block; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 1050; overflow-y: auto;" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 540px;">
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
                    <button type="button" wire:click="closeDetailModal" style="background: transparent; border: none; font-size: 24px; color: #94a3b8; cursor: pointer; padding: 4px;">
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
                    <button type="button" class="btn-save-custom" wire:click="closeDetailModal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Form Modal (Registrasi Siswa Baru / Edit Data Siswa) -->
    @if($showModal)
    <div class="modal fade show" style="display: block; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 1050; overflow-y: auto;" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
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
                        <button type="button" wire:click="$set('showModal', false)" style="background: transparent; border: none; font-size: 24px; color: #94a3b8; cursor: pointer; padding: 4px;">
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
                        <button type="button" class="btn-cancel-custom" wire:click="$set('showModal', false)">Batal</button>
                        <button type="submit" class="btn-save-custom">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Digital ID Card Modal (QR) -->
    @if($showQrModal)
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
    <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); overflow-y: auto;" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered my-3" role="document" style="max-width: 420px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
            
            <div id="qrPrintCard" style="width: 360px; height: 530px; background: #ffffff; border-radius: 24px; position: relative; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); font-family: 'Inter', sans-serif;">
                
                <!-- Background Shapes -->
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 210px; background: #0284c7; clip-path: ellipse(120% 100% at 50% 0%); z-index: 1;"></div>
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 225px; background: #bae6fd; clip-path: ellipse(120% 100% at 50% 0%); z-index: 0;"></div>
                
                <div style="position: relative; z-index: 2; height: 514px; padding: 15px 20px 20px 20px; display: flex; flex-direction: column; align-items: center; box-sizing: border-box;">
                    <!-- Logo & Header -->
                    <div style="background: white; padding: 6px; border-radius: 50%; display: inline-flex; justify-content: center; align-items: center; box-shadow: 0 4px 10px rgba(0,0,0,0.1); margin-bottom: 5px;">
                        <img src="{{ asset('assets/img/logo-sekolah.jpg') }}" alt="Logo" style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover;">
                    </div>
                    
                    <h2 style="font-size: 0.95rem; font-weight: 800; color: #ffffff; margin: 0; letter-spacing: 0.5px; text-transform: uppercase; text-align: center; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">Kartu Absensi Santri</h2>
                    
                    <div style="display: flex; align-items: center; gap: 6px; margin-top: 2px; color: #e0f2fe; font-weight: 700; font-size: 0.65rem; letter-spacing: 1px; text-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                        <div style="width: 15px; height: 2px; background: #e0f2fe; border-radius: 2px;"></div>
                        TPQ NURUL MUNI'IM
                        <div style="width: 15px; height: 2px; background: #e0f2fe; border-radius: 2px;"></div>
                    </div>
                    
                    <!-- QR Box -->
                    <div style="margin-top: 25px; background: white; padding: 10px; border-radius: 20px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.15); display: flex; flex-direction: column; align-items: center; width: 220px; height: 220px; justify-content: center; box-sizing: border-box;">
                        <img src="{{ $qrDataUrl }}" alt="QR Code" style="width: 200px; height: 200px; object-fit: contain;">
                    </div>
                    
                    <!-- Name & NIS (Pushed to bottom) -->
                    <div style="margin-top: auto; text-align: center; width: 100%;">
                        <h3 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin: 0 0 6px 0; line-height: 1.2;">
                            {{ $qrStudent->nama_siswa }}
                        </h3>
                        <div style="color: #0284c7; font-weight: 700; font-size: 1rem; margin: 0;">
                            NIS : {{ $qrStudent->nis }}
                        </div>
                    </div>
                </div>
                
                <!-- Bottom Thick Border -->
                <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 16px; background: #0284c7; z-index: 3;"></div>
            </div>

            <!-- Action Buttons Footer -->
            <div class="no-print" style="width: 360px; margin-top: 15px; margin-bottom: 20px; display: flex; gap: 15px; position: relative; z-index: 9999; pointer-events: auto;">
                <button type="button" onclick="window.print()" style="flex: 1; background: #0ea5e9; color: white; border-radius: 12px; padding: 12px; font-weight: 700; border: none; box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3); display: flex; align-items: center; justify-content: center; cursor: pointer; text-transform: uppercase; font-size: 0.85rem; pointer-events: auto;" title="Cetak Kartu">
                    <i class="material-icons" style="font-size: 18px; margin-right: 8px;">print</i> Cetak
                </button>
                <button type="button" wire:click="closeQrModal" onclick="const modal = this.closest('.modal'); if(modal) modal.style.display='none';" style="flex: 1; background: white; color: #475569; border-radius: 12px; padding: 12px; font-weight: 700; border: none; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); display: flex; align-items: center; justify-content: center; cursor: pointer; text-transform: uppercase; font-size: 0.85rem; pointer-events: auto;" title="Tutup">
                    Tutup
                </button>
            </div>
            
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header" style="border-bottom: 1px solid #f1f5f9;">
                    <h5 class="modal-title" style="font-weight: 700;">Hapus Data Siswa</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="color: #475569;">
                    Apakah Anda yakin ingin menghapus data siswa ini?
                </div>
                <div class="modal-footer" style="border-top: 1px solid #f1f5f9;">
                    <button type="button" class="btn-cancel-custom" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" style="border-radius: 10px; font-weight: 700; padding: 8px 20px;" wire:click="delete">Hapus</button>
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
