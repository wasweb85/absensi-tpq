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
                    <div class="card-header">
                        <h2>Absensi Siswa</h2>
                        <p>Manajemen Data Absensi Siswa TPQ</p>
                    </div>

                    <div class="filter-row">
                        <div class="field">
                            <label>Tanggal</label>
                            <input type="date" wire:model.live="filter_tanggal">
                        </div>
                        <div class="field">
                            <label>Pilih Kelas</label>
                            <select wire:model.live="filter_kelas">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelasList as $k)
                                    <option value="{{ $k->id_kelas }}">{{ $k->tingkat }} {{ $k->index_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        @if($filter_kelas)
                            @if($siswaList->count() > 0)
                                <div class="table-responsive">
                                    <table class="data-table">
                                        <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>NAMA SISWA</th>
                                                <th style="text-align:center">HADIR</th>
                                                <th style="text-align:center">SAKIT</th>
                                                <th style="text-align:center">IZIN</th>
                                                <th style="text-align:center">ALFA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($siswaList as $index => $siswa)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td><b>{{ $siswa->nama_siswa }}</b></td>
                                                    <td style="text-align:center">
                                                        <input type="radio" class="hadir" wire:model="kehadiran.{{ $siswa->id_siswa }}" name="absen_{{ $siswa->id_siswa }}" value="1">
                                                    </td>
                                                    <td style="text-align:center">
                                                        <input type="radio" class="sakit" wire:model="kehadiran.{{ $siswa->id_siswa }}" name="absen_{{ $siswa->id_siswa }}" value="2">
                                                    </td>
                                                    <td style="text-align:center">
                                                        <input type="radio" class="izin" wire:model="kehadiran.{{ $siswa->id_siswa }}" name="absen_{{ $siswa->id_siswa }}" value="3">
                                                    </td>
                                                    <td style="text-align:center">
                                                        <input type="radio" class="alfa" wire:model="kehadiran.{{ $siswa->id_siswa }}" name="absen_{{ $siswa->id_siswa }}" value="4">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="card-footer">
                                    <button wire:click="saveAttendance" class="btn-save">
                                        <i class="ti ti-device-floppy"></i> Simpan Absensi
                                    </button>
                                </div>
                            @else
                                <div class="alert alert-warning text-center m-4">
                                    Belum ada siswa di kelas ini.
                                </div>
                            @endif
                        @else
                            <div class="alert alert-info text-center m-4">
                                Silakan pilih kelas terlebih dahulu untuk mengelola absensi.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

