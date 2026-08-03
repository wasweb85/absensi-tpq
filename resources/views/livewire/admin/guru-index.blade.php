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

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header card-header-tabs card-header-primary">
                                <div class="nav-tabs-navigation">
                                    <div class="row">
                                        <div class="col-md-4 col-lg-5">
                                            <h4 class="card-title"><b>Daftar Guru</b></h4>
                                            <p class="card-category">Manajemen Guru / Ustadz TPQ</p>
                                        </div>
                                        <div class="col-md-8 col-lg-7 text-right">
                                            <div class="d-flex justify-content-end align-items-center flex-wrap">
                                                <div class="mr-2 mb-2">
                                                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Cari Nama / NUPTK..." style="border: 1px solid #ddd; padding: 5px 10px; border-radius: 5px; height: 38px;">
                                                </div>
                                                <div class="mr-2 mb-2">
                                                    <button wire:click="create" class="btn btn-sm btn-white text-primary m-0" style="border: 1px solid #ddd; height: 38px;">
                                                        <i class="material-icons">add</i> Baru
                                                    </button>
                                                </div>
                                                <div class="mb-2">
                                                    <button wire:click="$refresh" class="btn btn-sm btn-white text-primary m-0" style="border: 1px solid #ddd; height: 38px;">
                                                        <i class="material-icons">refresh</i> Refresh
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-hover">
                                    <thead class="text-primary">
                                        <tr>
                                            <th><b>No</b></th>
                                            <th><b>NUPTK</b></th>
                                            <th><b>Nama</b></th>
                                            <th><b>L/P</b></th>
                                            <th><b>No HP</b></th>
                                            <th><b>Aksi</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($guruList as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $item->nuptk }}</td>
                                                <td><b>{{ $item->nama_guru }}</b></td>
                                                <td>{{ $item->jk }}</td>
                                                <td>{{ $item->no_hp }}
                                                    @if($item->can_crud_siswa)
                                                        <br><span class="badge badge-success" style="font-size: 10px;">Akses CRUD Siswa</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button wire:click="edit({{ $item->id_guru }})" class="btn btn-primary p-2">
                                                        <i class="material-icons">edit</i>
                                                    </button>
                                                    <button wire:click="deleteId({{ $item->id_guru }})" class="btn btn-danger p-2">
                                                        <i class="material-icons">delete_forever</i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Belum ada data guru</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
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
