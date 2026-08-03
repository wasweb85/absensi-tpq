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
                                            <h4 class="card-title"><b>Daftar Jilid / Kelas</b></h4>
                                            <p class="card-category">Manajemen Kelas TPQ</p>
                                        </div>
                                        <div class="col-md-8 col-lg-7 text-right">
                                            <div class="d-flex justify-content-end align-items-center flex-wrap">
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
                                            <th><b>Jilid/Kelas</b></th>
                                            <th><b>Indeks</b></th>
                                            <th><b>Wali Kelas</b></th>
                                            <th><b>Aksi</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($kelas as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td><b>{{ $item->tingkat }}</b></td>
                                                <td>{{ $item->index_kelas }}</td>
                                                <td>{{ $item->nama_guru ?? '-' }}</td>
                                                <td>
                                                    <button wire:click="edit({{ $item->id_kelas }})" class="btn btn-primary p-2">
                                                        <i class="material-icons">edit</i>
                                                    </button>
                                                    <button wire:click="deleteId({{ $item->id_kelas }})" class="btn btn-danger p-2">
                                                        <i class="material-icons">delete_forever</i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Belum ada data kelas</td>
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
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $isEdit ? 'Edit Kelas' : 'Tambah Kelas' }}</h5>
                        <button type="button" class="close" wire:click="$set('showModal', false)">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tingkat / Jilid *</label>
                            <input type="text" wire:model="tingkat" class="form-control" required>
                            @error('tingkat') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label>Indeks (Opsional)</label>
                            <input type="text" wire:model="index_kelas" class="form-control">
                            @error('index_kelas') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label>Wali Kelas (Opsional)</label>
                            <select wire:model="id_wali_kelas" class="form-control">
                                <option value="">Tanpa Wali Kelas</option>
                                @foreach($guruList as $g)
                                    <option value="{{ $g->id_guru }}">{{ $g->nama_guru }}</option>
                                @endforeach
                            </select>
                            @error('id_wali_kelas') <span class="text-danger">{{ $message }}</span> @enderror
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
                    <h5 class="modal-title">Hapus Kelas</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus kelas ini?
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
