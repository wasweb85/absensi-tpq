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
                    .btn-add {
                        background: #2563eb;
                        color: #fff;
                        border: none;
                        border-radius: 8px;
                        padding: 8px 16px;
                        font-weight: 600;
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
                        flex-wrap: wrap;
                        gap: 15px;
                    }
                    .toolbar-left {
                        display: flex;
                        gap: 15px;
                        align-items: center;
                    }
                    .toolbar-left select {
                        border: 1px solid #e2e8f0;
                        border-radius: 8px;
                        padding: 8px 12px;
                        color: #475569;
                        font-size: 0.9rem;
                        outline: none;
                        background: #fff;
                    }
                    .custom-day-pills {
                        display: flex;
                        gap: 8px;
                        flex-wrap: wrap;
                        padding: 0;
                        margin-bottom: 20px;
                        list-style: none;
                        border-bottom: 1px solid #f1f5f9;
                        padding-bottom: 15px;
                    }
                    .custom-day-pills .nav-link {
                        padding: 8px 18px;
                        border-radius: 8px;
                        font-weight: 600;
                        font-size: 0.875rem;
                        color: #64748b;
                        background: #f8fafc;
                        border: 1px solid #e2e8f0;
                        transition: all 0.2s;
                    }
                    .custom-day-pills .nav-link.active {
                        background: #2563eb;
                        color: #fff;
                        border-color: #2563eb;
                        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
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
                        .toolbar-left select {
                            width: 100%;
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
                                    <h4 class="modern-table-title">Jadwal KBM</h4>
                                    <p class="modern-table-subtitle">Manajemen Jadwal Kegiatan Belajar Mengajar TPQ</p>
                                </div>
                                <button wire:click="create" class="btn btn-add desktop-only">
                                    <i class="material-icons" style="font-size: 18px; vertical-align: middle; margin-right: 5px;">add</i> Tambah
                                </button>
                            </div>

                            <div class="modern-table-toolbar">
                                <div class="toolbar-left">
                                    <select wire:model.live="filter_kelas">
                                        <option value="">Semua Kelas</option>
                                        @foreach($kelasList as $k)
                                            <option value="{{ $k->id_kelas }}">{{ $k->tingkat }} {{ $k->index_kelas }}</option>
                                        @endforeach
                                    </select>
                                    <button wire:click="create" class="btn btn-add mobile-only">
                                        <i class="material-icons" style="font-size: 16px; vertical-align: middle; margin-right: 4px;">add</i> Tambah
                                    </button>
                                </div>
                            </div>

                            <ul class="custom-day-pills nav" role="tablist">
                                @php
                                    $hariUrut = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                @endphp
                                @foreach($hariUrut as $h)
                                    <li class="nav-item">
                                        <a class="nav-link {{ $loop->first ? 'active' : '' }}" data-toggle="tab" href="#hari-{{ strtolower($h) }}" role="tab">
                                            {{ $h }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content">
                                @foreach($hariUrut as $h)
                                <div class="tab-pane {{ $loop->first ? 'active' : '' }}" id="hari-{{ strtolower($h) }}">
                                    <div class="table-responsive">
                                        <table class="modern-table">
                                            <thead>
                                                <tr>
                                                    <th>JILID</th>
                                                    <th>MATA PELAJARAN</th>
                                                    <th>GURU</th>
                                                    <th>KETERANGAN</th>
                                                    <th style="text-align: right;">AKSI</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($jadwalList[$h] as $item)
                                                    <tr>
                                                        <td>
                                                            @if($item->kelas)
                                                                <span class="badge-kelas">{{ $item->kelas->tingkat }} {{ $item->kelas->index_kelas }}</span>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td><b>{{ $item->mapel->nama_mapel ?? '-' }}</b></td>
                                                        <td>{{ $item->guru->nama_guru ?? '-' }}</td>
                                                        <td>{{ $item->keterangan ?: '-' }}</td>
                                                        <td>
                                                            <div class="action-btns" style="justify-content: flex-end;">
                                                                <button wire:click="edit({{ $item->id_jadwal }})" class="btn-action btn-edit" title="Edit">
                                                                    <i class="material-icons">edit</i>
                                                                </button>
                                                                <button wire:click="deleteId({{ $item->id_jadwal }})" class="btn-action btn-delete" title="Hapus">
                                                                    <i class="material-icons">delete</i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center py-4 text-muted">Belum ada jadwal hari ini</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Modal (Add / Edit Jadwal) -->
    @if($showModal)
    <div class="modal fade show" style="display: block; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 1050; overflow-y: auto;" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04); overflow: hidden; background: #fff;">
                <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                    <div class="modal-header" style="padding: 20px 24px; border-bottom: 1px solid #f1f5f9; background: #fafafa; display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; border-radius: 10px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center;">
                                <i class="material-icons" style="font-size: 22px;">{{ $isEdit ? 'edit_calendar' : 'edit_calendar' }}</i>
                            </div>
                            <div>
                                <h5 class="modal-title" style="font-size: 1.1rem; font-weight: 700; color: #0f172a; margin: 0;">{{ $isEdit ? 'Edit Jadwal' : 'Tambah Jadwal' }}</h5>
                                <p style="font-size: 0.78rem; color: #64748b; margin: 2px 0 0 0;">Atur jadwal mata pelajaran, pengajar, dan hari pelaksanaan</p>
                            </div>
                        </div>
                        <button type="button" wire:click="$set('showModal', false)" style="background: transparent; border: none; font-size: 20px; color: #94a3b8; cursor: pointer; padding: 4px;">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body" style="padding: 24px; background: #fff;">
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label style="font-size: 0.82rem; font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Kelas <span class="text-danger">*</span></label>
                                <select wire:model="id_kelas" class="form-control" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 9px 12px; font-size: 0.9rem; background: #fff;" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($kelasList as $k)
                                        <option value="{{ $k->id_kelas }}">{{ $k->tingkat }} {{ $k->index_kelas }}</option>
                                    @endforeach
                                </select>
                                @error('id_kelas') <span class="text-danger" style="font-size: 0.78rem;">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label style="font-size: 0.82rem; font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Mata Pelajaran <span class="text-danger">*</span></label>
                                <select wire:model="id_mapel" class="form-control" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 9px 12px; font-size: 0.9rem; background: #fff;" required>
                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                    @foreach($mapelList as $m)
                                        <option value="{{ $m->id_mapel }}">{{ $m->nama_mapel }}</option>
                                    @endforeach
                                </select>
                                @error('id_mapel') <span class="text-danger" style="font-size: 0.78rem;">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label style="font-size: 0.82rem; font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Guru / Ustadz <span class="text-danger">*</span></label>
                                <select wire:model="id_guru" class="form-control" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 9px 12px; font-size: 0.9rem; background: #fff;" required>
                                    <option value="">-- Pilih Guru --</option>
                                    @foreach($guruList as $g)
                                        <option value="{{ $g->id_guru }}">{{ $g->nama_guru }}</option>
                                    @endforeach
                                </select>
                                @error('id_guru') <span class="text-danger" style="font-size: 0.78rem;">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label style="font-size: 0.82rem; font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Hari <span class="text-danger">*</span></label>
                                <select wire:model="hari" class="form-control" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 9px 12px; font-size: 0.9rem; background: #fff;" required>
                                    <option value="">-- Pilih Hari --</option>
                                    <option value="Senin">Senin</option>
                                    <option value="Selasa">Selasa</option>
                                    <option value="Rabu">Rabu</option>
                                    <option value="Kamis">Kamis</option>
                                    <option value="Jumat">Jumat</option>
                                    <option value="Sabtu">Sabtu</option>
                                </select>
                                @error('hari') <span class="text-danger" style="font-size: 0.78rem;">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-12 form-group mb-2">
                                <label style="font-size: 0.82rem; font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Keterangan (Opsional)</label>
                                <textarea wire:model="keterangan" class="form-control" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 9px 12px; font-size: 0.9rem;" rows="2" placeholder="Catatan tambahan mengenai jadwal"></textarea>
                                @error('keterangan') <span class="text-danger" style="font-size: 0.78rem;">{{ $message }}</span> @enderror
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
                    <h5 class="modal-title">Hapus Jadwal</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus jadwal ini?
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
