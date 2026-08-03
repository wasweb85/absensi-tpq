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

                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title"><b>Absensi Guru</b></h4>
                        <p class="card-category">Manajemen Data Absensi Guru / Pengajar TPQ</p>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="bmd-label-floating text-dark">Tanggal</label>
                                <input type="date" wire:model.live="filter_tanggal" class="form-control" style="border: 1px solid #ddd; padding: 5px 10px; border-radius: 5px; height: 38px;">
                            </div>
                        </div>

                        @if($guruList->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="text-primary">
                                        <tr>
                                            <th><b>No</b></th>
                                            <th><b>Nama Guru</b></th>
                                            <th class="text-center"><b>Hadir</b></th>
                                            <th class="text-center"><b>Sakit</b></th>
                                            <th class="text-center"><b>Izin</b></th>
                                            <th class="text-center"><b>Alfa</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($guruList as $index => $guru)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td><b>{{ $guru->nama_guru }}</b></td>
                                                <td class="text-center">
                                                    <div class="form-check form-check-radio form-check-inline">
                                                        <label class="form-check-label text-success">
                                                            <input class="form-check-input" type="radio" wire:model="kehadiran.{{ $guru->id_guru }}" value="1">
                                                            <span class="circle"><span class="check"></span></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="form-check form-check-radio form-check-inline">
                                                        <label class="form-check-label text-warning">
                                                            <input class="form-check-input" type="radio" wire:model="kehadiran.{{ $guru->id_guru }}" value="2">
                                                            <span class="circle"><span class="check"></span></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="form-check form-check-radio form-check-inline">
                                                        <label class="form-check-label text-info">
                                                            <input class="form-check-input" type="radio" wire:model="kehadiran.{{ $guru->id_guru }}" value="3">
                                                            <span class="circle"><span class="check"></span></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="form-check form-check-radio form-check-inline">
                                                        <label class="form-check-label text-danger">
                                                            <input class="form-check-input" type="radio" wire:model="kehadiran.{{ $guru->id_guru }}" value="4">
                                                            <span class="circle"><span class="check"></span></span>
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-4 text-right">
                                <button wire:click="saveAttendance" class="btn btn-primary">
                                    <i class="material-icons">save</i> Simpan Absensi
                                </button>
                            </div>
                        @else
                            <div class="alert alert-warning text-center mt-4">
                                Belum ada data guru.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
