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
                                            <h4 class="card-title"><b>Jadwal Pelajaran</b></h4>
                                            <p class="card-category">Manajemen Jadwal Pelajaran TPQ</p>
                                        </div>
                                        <div class="col-md-6 col-lg-6 text-right">
                                            <div class="d-flex justify-content-end align-items-center flex-wrap">
                                                <div class="mr-2 mb-2">
                                                    <select wire:model.live="filter_kelas" class="form-control" style="border: 1px solid #ddd; padding: 5px 10px; border-radius: 5px; height: 38px;">
                                                        <option value="">Semua Kelas</option>
                                                        @foreach($kelasList as $k)
                                                            <option value="{{ $k->id_kelas }}">{{ $k->tingkat }} {{ $k->index_kelas }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <button wire:click="create" class="btn btn-sm btn-white text-primary m-0" style="border: 1px solid #ddd; height: 38px;">
                                                        <i class="material-icons">add</i> Baru
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <ul class="nav nav-pills nav-pills-primary" role="tablist">
                                    @php
                                        $hariUrut = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                    @endphp
                                    @foreach($hariUrut as $h)
                                        <li class="nav-item">
                                            <a class="nav-link {{ $loop->first ? 'active' : '' }}" data-toggle="tab" href="#hari-{{ strtolower($h) }}" role="tablist" aria-expanded="true">
                                                {{ $h }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="tab-content tab-space">
                                    @foreach($hariUrut as $h)
                                    <div class="tab-pane {{ $loop->first ? 'active' : '' }}" id="hari-{{ strtolower($h) }}">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead class="text-primary">
                                                    <tr>
                                                        <th><b>Kelas</b></th>
                                                        <th><b>Mata Pelajaran</b></th>
                                                        <th><b>Guru</b></th>
                                                        <th><b>Keterangan</b></th>
                                                        <th><b>Aksi</b></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($jadwalList[$h] as $item)
                                                        <tr>
                                                            <td>
                                                                @if($item->kelas)
                                                                    {{ $item->kelas->tingkat }} {{ $item->kelas->index_kelas }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td>{{ $item->mapel->nama_mapel ?? '-' }}</td>
                                                            <td>{{ $item->guru->nama_guru ?? '-' }}</td>
                                                            <td>{{ $item->keterangan }}</td>
                                                            <td>
                                                                <button wire:click="edit({{ $item->id_jadwal }})" class="btn btn-primary p-2">
                                                                    <i class="material-icons">edit</i>
                                                                </button>
                                                                <button wire:click="deleteId({{ $item->id_jadwal }})" class="btn btn-danger p-2">
                                                                    <i class="material-icons">delete_forever</i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center">Belum ada jadwal hari ini</td>
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
    </div>

    <!-- Form Modal (Add / Edit) -->
    @if($showModal)
    <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $isEdit ? 'Edit Jadwal' : 'Tambah Jadwal' }}</h5>
                        <button type="button" class="close" wire:click="$set('showModal', false)">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Kelas <span class="text-danger">*</span></label>
                                <select wire:model="id_kelas" class="form-control" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($kelasList as $k)
                                        <option value="{{ $k->id_kelas }}">{{ $k->tingkat }} {{ $k->index_kelas }}</option>
                                    @endforeach
                                </select>
                                @error('id_kelas') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Mata Pelajaran <span class="text-danger">*</span></label>
                                <select wire:model="id_mapel" class="form-control" required>
                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                    @foreach($mapelList as $m)
                                        <option value="{{ $m->id_mapel }}">{{ $m->nama_mapel }}</option>
                                    @endforeach
                                </select>
                                @error('id_mapel') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Guru / Ustadz <span class="text-danger">*</span></label>
                                <select wire:model="id_guru" class="form-control" required>
                                    <option value="">-- Pilih Guru --</option>
                                    @foreach($guruList as $g)
                                        <option value="{{ $g->id_guru }}">{{ $g->nama_guru }}</option>
                                    @endforeach
                                </select>
                                @error('id_guru') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Hari <span class="text-danger">*</span></label>
                                <select wire:model="hari" class="form-control" required>
                                    <option value="">-- Pilih Hari --</option>
                                    <option value="Senin">Senin</option>
                                    <option value="Selasa">Selasa</option>
                                    <option value="Rabu">Rabu</option>
                                    <option value="Kamis">Kamis</option>
                                    <option value="Jumat">Jumat</option>
                                    <option value="Sabtu">Sabtu</option>
                                </select>
                                @error('hari') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-12 form-group">
                                <label>Keterangan (Opsional)</label>
                                <textarea wire:model="keterangan" class="form-control" rows="2"></textarea>
                                @error('keterangan') <span class="text-danger">{{ $message }}</span> @enderror
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
