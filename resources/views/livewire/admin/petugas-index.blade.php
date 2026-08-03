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
                                        <div class="col-md-6 col-lg-6">
                                            <h4 class="card-title"><b>Data Petugas / Pengguna</b></h4>
                                            <p class="card-category">Manajemen Akun Login Sistem</p>
                                        </div>
                                        <div class="col-md-6 col-lg-6 text-right">
                                            <div class="d-flex justify-content-end align-items-center flex-wrap">
                                                <div class="mr-2 mb-2">
                                                    <input type="text" wire:model.live="search" class="form-control" placeholder="Cari Nama/Email..." style="border: 1px solid #ddd; padding: 5px 10px; border-radius: 5px; height: 38px;">
                                                </div>
                                                <div class="mb-2">
                                                    <button wire:click="create" class="btn btn-sm btn-white text-primary m-0" style="border: 1px solid #ddd; height: 38px;">
                                                        <i class="material-icons">person_add</i> Tambah
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
                                            <th><b>Username</b></th>
                                            <th><b>Email</b></th>
                                            <th><b>Role</b></th>
                                            <th><b>Guru Terkait</b></th>
                                            <th><b>Aksi</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($petugasList as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td><b>{{ $item->name }}</b></td>
                                                <td>{{ $item->email }}</td>
                                                <td>
                                                    @if($item->is_superadmin)
                                                        <span class="badge badge-danger">Admin / Kepsek</span>
                                                    @else
                                                        <span class="badge badge-info">Guru</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->guru->nama_guru ?? '-' }}</td>
                                                <td>
                                                    <button wire:click="edit({{ $item->id }})" class="btn btn-primary p-2">
                                                        <i class="material-icons">edit</i>
                                                    </button>
                                                    @if(auth()->id() != $item->id)
                                                        <button wire:click="deleteId({{ $item->id }})" class="btn btn-danger p-2">
                                                            <i class="material-icons">delete_forever</i>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Belum ada data petugas</td>
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
                        <h5 class="modal-title">{{ $isEdit ? 'Edit Petugas' : 'Tambah Petugas' }}</h5>
                        <button type="button" class="close" wire:click="$set('showModal', false)">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Username <span class="text-danger">*</span></label>
                                <input type="text" wire:model="name" class="form-control" required minlength="4">
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Email <span class="text-danger">*</span></label>
                                <input type="email" wire:model="email" class="form-control" required>
                                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Password {{ $isEdit ? '(Kosongkan jika tidak ingin mengubah)' : '*' }}</label>
                                <input type="password" wire:model="password" class="form-control" {{ $isEdit ? '' : 'required' }} minlength="6">
                                @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Role / Akses <span class="text-danger">*</span></label>
                                <select wire:model="is_superadmin" class="form-control" required>
                                    <option value="">-- Pilih Role --</option>
                                    <option value="1">Admin / Kepala TPQ</option>
                                    <option value="0">Guru</option>
                                </select>
                                @error('is_superadmin') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-12 form-group">
                                <label>Hubungkan ke Data Guru (Jika Role Guru)</label>
                                <select wire:model="id_guru" class="form-control">
                                    <option value="">-- Tidak dihubungkan --</option>
                                    @foreach($guruList as $g)
                                        <option value="{{ $g->id_guru }}">{{ $g->nama_guru }}</option>
                                    @endforeach
                                </select>
                                @error('id_guru') <span class="text-danger">{{ $message }}</span> @enderror
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
