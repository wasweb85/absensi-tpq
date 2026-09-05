<div>
    <div class="content">
        <div class="container-fluid">
        <!-- Header Section -->
        <div style="margin-bottom: 25px;">
            <h3 style="font-weight: 800; color: #1e293b; margin: 0 0 5px 0; font-size: 1.5rem;">Laporan Kehadiran</h3>
            <p style="color: #64748b; font-size: 0.95rem; margin: 0;">Rekap data absensi siswa dan guru berdasarkan periode.</p>
        </div>

        @if (session()->has('msg'))
            <div class="alert alert-{{ session('error') ? 'danger' : 'success' }} mb-4" style="border-radius: 10px;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="material-icons">close</i>
                </button>
                {{ session('msg') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger mb-4" style="border-radius: 10px;">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if((int)(auth()->user()->is_superadmin ?? 0) !== 2)
        <!-- Card Siswa -->
        <div class="card" style="border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); border: 1px solid #f1f5f9; margin-bottom: 30px;">
            <div class="card-header" style="background: white; border-bottom: 1px solid #f1f5f9; padding: 20px 25px; border-radius: 16px 16px 0 0;">
                <h4 style="font-weight: 800; color: #3b82f6; margin: 0; font-size: 1.15rem; display: flex; align-items: center; gap: 8px;">
                    <i class="material-icons">school</i> Laporan Kehadiran Siswa
                </h4>
            </div>
            <div class="card-body p-4">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label style="font-size: 0.75rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Dari Tanggal</label>
                        <input type="date" wire:model.live="tanggalMulai" class="form-control" style="border: none; border-bottom: 2px solid #f1f5f9; border-radius: 0; padding: 10px 0; background: transparent; font-weight: 600; color: #334155;">
                    </div>
                    
                    <div class="col-md-3">
                        <label style="font-size: 0.75rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Sampai Tanggal</label>
                        <input type="date" wire:model.live="tanggalAkhir" class="form-control" style="border: none; border-bottom: 2px solid #f1f5f9; border-radius: 0; padding: 10px 0; background: transparent; font-weight: 600; color: #334155;">
                    </div>
                    
                    <div class="col-md-4">
                        <label style="font-size: 0.75rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Filter Kelas</label>
                        <select wire:model.live="kelas" class="form-control custom-select" style="border: none; border-bottom: 2px solid #f1f5f9; border-radius: 0; padding: 10px 0; background: transparent; font-weight: 600; color: #334155; cursor: pointer;">
                            <option value="">Semua Kelas</option>
                            @foreach ($kelasList as $k)
                                <option value="{{ $k->id_kelas }}">{{ $k->tingkat }} {{ $k->index_kelas }} ({{ $k->total_siswa }} Siswa)</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-2 mt-3 mt-md-0 text-right">
                        <button wire:click="exportSiswa('xls')" class="btn" style="background: #10b981; color: white; border-radius: 10px; padding: 12px 20px; font-weight: 700; text-transform: none; display: inline-flex; align-items: center; justify-content: center; width: 100%; border: none; box-shadow: 0 4px 10px rgba(16,185,129,0.2); transition: all 0.3s ease;">
                            <i class="material-icons" style="font-size: 20px; margin-right: 8px;">table_view</i> Export Excel
                        </button>
                    </div>
                </div>

                <div class="mt-4">
                    @if ($presensiSiswa->isEmpty())
                        <div class="text-center py-5" style="background: #f8fafc; border-radius: 12px; border: 1px dashed #cbd5e1;">
                            <i class="material-icons" style="font-size: 48px; color: #94a3b8; margin-bottom: 10px;">assignment_late</i>
                            <h5 style="color: #64748b; font-weight: 600; margin: 0;">Tidak ada data presensi pada rentang tanggal tersebut.</h5>
                        </div>
                    @else
                        <div class="table-responsive" style="border-radius: 12px; border: 1px solid #f1f5f9;">
                            <table class="table mb-0" style="color: #334155;">
                                <thead style="background: #f8fafc;">
                                    <tr>
                                        <th style="font-weight: 800; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; border: none; padding: 15px 20px;">Tanggal</th>
                                        <th style="font-weight: 800; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; border: none; padding: 15px 20px;">NIS</th>
                                        <th style="font-weight: 800; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; border: none; padding: 15px 20px;">Nama Siswa</th>
                                        <th style="font-weight: 800; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; border: none; padding: 15px 20px;">Kelas</th>
                                        <th style="font-weight: 800; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; border: none; padding: 15px 20px;">Status</th>
                                        <th style="font-weight: 800; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; border: none; padding: 15px 20px;">Jam Masuk</th>
                                        <th style="font-weight: 800; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; border: none; padding: 15px 20px;">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($presensiSiswa as $p)
                                        <tr style="border-bottom: 1px solid #f1f5f9;">
                                            <td style="padding: 15px 20px; vertical-align: middle; font-weight: 600;">{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</td>
                                            <td style="padding: 15px 20px; vertical-align: middle; color: #64748b;">{{ $p->siswa->nis ?? '-' }}</td>
                                            <td style="padding: 15px 20px; vertical-align: middle; font-weight: 700; color: #1e293b;">{{ $p->siswa->nama_siswa ?? '-' }}</td>
                                            <td style="padding: 15px 20px; vertical-align: middle;">
                                                <span class="badge" style="background: #f1f5f9; color: #475569; font-weight: 700; padding: 6px 10px; border-radius: 6px;">
                                                    {{ $p->kelas->tingkat ?? '' }} {{ $p->kelas->index_kelas ?? '' }}
                                                </span>
                                            </td>
                                            <td style="padding: 15px 20px; vertical-align: middle;">
                                                @php
                                                    $status = $p->kehadiran->kehadiran ?? '-';
                                                    $badgeStyle = 'background: #f1f5f9; color: #475569;';
                                                    if($status == 'Hadir') $badgeStyle = 'background: #dcfce7; color: #15803d;';
                                                    elseif($status == 'Sakit') $badgeStyle = 'background: #fef9c3; color: #a16207;';
                                                    elseif($status == 'Izin') $badgeStyle = 'background: #e0f2fe; color: #0369a1;';
                                                    elseif($status == 'Alpha' || $status == 'Tanpa keterangan') $badgeStyle = 'background: #fee2e2; color: #b91c1c;';
                                                @endphp
                                                <span class="badge" style="{{ $badgeStyle }} font-weight: 800; padding: 6px 12px; border-radius: 8px;">
                                                    {{ $status }}
                                                </span>
                                            </td>
                                            <td style="padding: 15px 20px; vertical-align: middle; font-weight: 600; color: #475569;">{{ $p->jam_masuk ?? '-' }}</td>
                                            <td style="padding: 15px 20px; vertical-align: middle; color: #64748b;">{{ $p->keterangan ?: '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $presensiSiswa->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Card Guru -->
        <div class="card" style="border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); border: 1px solid #f1f5f9; margin-bottom: 30px;">
            <div class="card-header" style="background: white; border-bottom: 1px solid #f1f5f9; padding: 20px 25px; border-radius: 16px 16px 0 0;">
                <h4 style="font-weight: 800; color: #8b5cf6; margin: 0; font-size: 1.15rem; display: flex; align-items: center; gap: 8px;">
                    <i class="material-icons">record_voice_over</i> Laporan Kehadiran Guru
                </h4>
            </div>
            <div class="card-body p-4">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label style="font-size: 0.75rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Dari Tanggal</label>
                        <input type="date" wire:model.live="tanggalMulaiGuru" class="form-control" style="border: none; border-bottom: 2px solid #f1f5f9; border-radius: 0; padding: 10px 0; background: transparent; font-weight: 600; color: #334155;">
                    </div>
                    
                    <div class="col-md-3">
                        <label style="font-size: 0.75rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Sampai Tanggal</label>
                        <input type="date" wire:model.live="tanggalAkhirGuru" class="form-control" style="border: none; border-bottom: 2px solid #f1f5f9; border-radius: 0; padding: 10px 0; background: transparent; font-weight: 600; color: #334155;">
                    </div>
                    
                    <div class="col-md-4">
                        <div style="color: #94a3b8; font-size: 0.85rem; padding: 10px 0; margin-top: 20px;">
                            <i>*Filter kelas tidak berlaku untuk laporan guru.</i>
                        </div>
                    </div>
                    
                    <div class="col-md-2 mt-3 mt-md-0 text-right">
                        <button wire:click="exportGuru('xls')" class="btn" style="background: #10b981; color: white; border-radius: 10px; padding: 12px 20px; font-weight: 700; text-transform: none; display: inline-flex; align-items: center; justify-content: center; width: 100%; border: none; box-shadow: 0 4px 10px rgba(16,185,129,0.2); transition: all 0.3s ease;">
                            <i class="material-icons" style="font-size: 20px; margin-right: 8px;">table_view</i> Export Excel
                        </button>
                    </div>
                </div>

                <!-- Preview Table Guru -->
                <div class="mt-4 pt-4" style="border-top: 1px solid #f1f5f9;">
                    <h5 style="font-weight: 700; color: #334155; margin-bottom: 15px; font-size: 1rem;">Preview Data Kehadiran</h5>
                    @if ($presensiGuru->isEmpty())
                        <div class="text-center py-4" style="color: #94a3b8; font-size: 0.9rem;">
                            <i class="material-icons" style="font-size: 40px; display: block; margin-bottom: 8px; color: #cbd5e1;">find_in_page</i>
                            Tidak ada data kehadiran guru pada periode yang dipilih.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" style="margin-bottom: 0;">
                                <thead style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                                    <tr>
                                        <th style="font-size: 0.75rem; font-weight: 800; color: #64748b; border: none; padding: 12px 15px;">TANGGAL</th>
                                        <th style="font-size: 0.75rem; font-weight: 800; color: #64748b; border: none; padding: 12px 15px;">NAMA GURU</th>
                                        <th style="font-size: 0.75rem; font-weight: 800; color: #64748b; border: none; padding: 12px 15px;">STATUS</th>
                                        <th style="font-size: 0.75rem; font-weight: 800; color: #64748b; border: none; padding: 12px 15px;">JAM ABSEN</th>
                                        <th style="font-size: 0.75rem; font-weight: 800; color: #64748b; border: none; padding: 12px 15px;">KETERANGAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($presensiGuru as $p)
                                        <tr style="border-bottom: 1px solid #f1f5f9;">
                                            <td style="padding: 12px 15px; font-weight: 600; color: #475569; border: none;">{{ date('d-m-Y', strtotime($p->tanggal)) }}</td>
                                            <td style="padding: 12px 15px; font-weight: 700; color: #1e293b; border: none;">{{ $p->guru->nama_guru ?? '-' }}</td>
                                            <td style="padding: 12px 15px; border: none;">
                                                @php
                                                    $badgeColor = match($p->id_kehadiran) {
                                                        1 => 'background: #ecfdf5; color: #047857;',
                                                        2 => 'background: #fffbeb; color: #b45309;',
                                                        3 => 'background: #eff6ff; color: #1d4ed8;',
                                                        4 => 'background: #fef2f2; color: #b91c1c;',
                                                        default => 'background: #f1f5f9; color: #475569;'
                                                    };
                                                @endphp
                                                <span class="badge" style="{{ $badgeColor }} font-weight: 700; border-radius: 6px; padding: 5px 10px; text-transform: uppercase;">
                                                    {{ $p->kehadiran->kehadiran ?? '-' }}
                                                </span>
                                            </td>
                                            <td style="padding: 12px 15px; color: #64748b; border: none;">
                                                {{ $p->jam_masuk ? substr($p->jam_masuk, 0, 5) : '-' }}
                                            </td>
                                            <td style="padding: 12px 15px; color: #64748b; border: none; font-style: italic;">
                                                {{ $p->keterangan ? $p->keterangan : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $presensiGuru->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        </div>
    </div>
</div>

