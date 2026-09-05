<style>
.status-select {
    appearance: none;
    padding: 6px 30px 6px 12px;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 8px center;
    background-size: 16px;
    border: 1px solid transparent;
    transition: all 0.2s ease;
    width: 140px;
}
.status-select:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(148, 163, 184, 0.2);
}
.status-belum {
    background-color: #f1f5f9;
    color: #475569;
    border-color: #cbd5e1;
}
.status-hadir {
    background-color: #dcfce7;
    color: #15803d;
    border-color: #bbf7d0;
}
.status-sakit {
    background-color: #fce7f3;
    color: #be185d;
    border-color: #fbcfe8;
}
.status-izin {
    background-color: #e0f2fe;
    color: #0369a1;
    border-color: #bae6fd;
}
.status-alpa {
    background-color: #fee2e2;
    color: #b91c1c;
    border-color: #fecaca;
}
</style>
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
                                                <th style="text-align:center">STATUS KEHADIRAN</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($siswaList as $index => $siswa)
                                                <tr wire:key="siswa-{{ $siswa->id_siswa }}">
                                                    <td>{{ $index + 1 }}</td>
                                                    <td><b>{{ $siswa->nama_siswa }}</b></td>
                                                    <td style="text-align:center">
                                                        @php
                                                            $statusClass = 'status-belum';
                                                            if (!empty($kehadiran[$siswa->id_siswa])) {
                                                                if ($kehadiran[$siswa->id_siswa] == 1) $statusClass = 'status-hadir';
                                                                elseif ($kehadiran[$siswa->id_siswa] == 2) $statusClass = 'status-sakit';
                                                                elseif ($kehadiran[$siswa->id_siswa] == 3) $statusClass = 'status-izin';
                                                                elseif ($kehadiran[$siswa->id_siswa] == 4) $statusClass = 'status-alpa';
                                                            }
                                                        @endphp
                                                        <select wire:model="kehadiran.{{ $siswa->id_siswa }}" onchange="updateSelectColor(this)" class="status-select {{ $statusClass }}">
                                                            <option value="" {{ empty($kehadiran[$siswa->id_siswa]) ? 'selected' : '' }}>Belum Absen</option>
                                                            <option value="1" {{ (!empty($kehadiran[$siswa->id_siswa]) && $kehadiran[$siswa->id_siswa] == 1) ? 'selected' : '' }}>Hadir</option>
                                                            <option value="2" {{ (!empty($kehadiran[$siswa->id_siswa]) && $kehadiran[$siswa->id_siswa] == 2) ? 'selected' : '' }}>Sakit</option>
                                                            <option value="3" {{ (!empty($kehadiran[$siswa->id_siswa]) && $kehadiran[$siswa->id_siswa] == 3) ? 'selected' : '' }}>Izin</option>
                                                            <option value="4" {{ (!empty($kehadiran[$siswa->id_siswa]) && $kehadiran[$siswa->id_siswa] == 4) ? 'selected' : '' }}>Alpa</option>
                                                        </select>
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

<script>
function updateSelectColor(select) {
    select.classList.remove('status-belum', 'status-hadir', 'status-sakit', 'status-izin', 'status-alpa');
    if (select.value === '') select.classList.add('status-belum');
    else if (select.value === '1') select.classList.add('status-hadir');
    else if (select.value === '2') select.classList.add('status-sakit');
    else if (select.value === '3') select.classList.add('status-izin');
    else if (select.value === '4') select.classList.add('status-alpa');
}
</script>

