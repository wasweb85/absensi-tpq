<div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card" style="border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: none;">
                        <style>
                            @media print {
                                .sidebar, .navbar, .btn, .modal, .card-header button { display: none !important; }
                                .main-panel { width: 100% !important; margin: 0 !important; padding: 0 !important; }
                                .content { padding: 0 !important; }
                                .card { box-shadow: none !important; border: none !important; }
                            }
                        </style>
                        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3" style="background: linear-gradient(135deg, #0284c7, #0369a1); border-radius: 12px 12px 0 0; padding: 20px;">
                            <div>
                                <h4 class="card-title text-white m-0" style="font-weight: 700;">Laporan Keuangan Tabungan Santri</h4>
                                <p class="category text-white-50 m-0 mt-1">Rekapitulasi bulanan resmi & akuntabilitas kas santri per kelas</p>
                            </div>
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <div class="d-flex align-items-center bg-white rounded px-2 py-1 shadow-sm" style="height: 38px;">
                                    <i class="material-icons text-muted mr-1" style="font-size: 18px;">calendar_today</i>
                                    <select wire:model.live="selectedMonth" class="form-control border-0 p-0 text-dark font-weight-bold" style="height: auto; font-size: 0.85rem; width: 110px; box-shadow: none;">
                                        @foreach($monthNames as $mNum => $mLabel)
                                            <option value="{{ $mNum }}">{{ $mLabel }}</option>
                                        @endforeach
                                    </select>
                                    <select wire:model.live="selectedYear" class="form-control border-0 p-0 text-dark font-weight-bold ml-1" style="height: auto; font-size: 0.85rem; width: 75px; box-shadow: none;">
                                        @php $currY = (int) date('Y'); @endphp
                                        @for($y = $currY - 2; $y <= $currY + 1; $y++)
                                            <option value="{{ $y }}">{{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <a href="{{ route('admin.laporan.tabungan', ['bulan' => $selectedMonth, 'tahun' => $selectedYear, 'type' => 'pdf']) }}" target="_blank" class="btn btn-sm btn-light font-weight-bold d-inline-flex align-items-center gap-1 shadow-sm m-0" style="border-radius: 8px; color: #0284c7; height: 38px;">
                                    <i class="material-icons" style="font-size: 18px;">print</i> Cetak PDF
                                </a>
                                <a href="{{ route('admin.laporan.tabungan', ['bulan' => $selectedMonth, 'tahun' => $selectedYear, 'type' => 'xlsx']) }}" class="btn btn-sm btn-success font-weight-bold d-inline-flex align-items-center gap-1 shadow-sm m-0" style="border-radius: 8px; background: #16a34a; border-color: #16a34a; height: 38px;">
                                    <i class="material-icons" style="font-size: 18px;">file_download</i> Ekspor Excel
                                </a>
                            </div>
                        </div>
                        <div class="card-body" style="padding: 24px;">
                            
                            {{-- 4 RINGKASAN EKSEKUTIF MUTASI --}}
                            <div class="row mb-4">
                                <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                                    <div style="background: #f0f9ff; border: 1px solid #bae6fd; border-left: 4px solid #0284c7; border-radius: 10px; padding: 16px;">
                                        <div style="font-size: 0.75rem; font-weight: 700; color: #0369a1; text-transform: uppercase; letter-spacing: 0.5px;">Saldo Awal Periode</div>
                                        <div style="font-size: 1.3rem; font-weight: 800; color: #0f172a; margin-top: 4px;">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</div>
                                        <small class="text-muted">Sebelum 1 {{ $monthNames[$selectedMonth] ?? 'Bulan' }}</small>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                                    <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-left: 4px solid #059669; border-radius: 10px; padding: 16px;">
                                        <div style="font-size: 0.75rem; font-weight: 700; color: #047857; text-transform: uppercase; letter-spacing: 0.5px;">Setoran Masuk (+)</div>
                                        <div style="font-size: 1.3rem; font-weight: 800; color: #059669; margin-top: 4px;">Rp {{ number_format($totalSetoranBulanIni, 0, ',', '.') }}</div>
                                        <small class="text-muted">Bulan {{ $monthNames[$selectedMonth] ?? 'Bulan' }} {{ $selectedYear }}</small>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                                    <div style="background: #fff1f2; border: 1px solid #fecdd3; border-left: 4px solid #e11d48; border-radius: 10px; padding: 16px;">
                                        <div style="font-size: 0.75rem; font-weight: 700; color: #be123c; text-transform: uppercase; letter-spacing: 0.5px;">Penarikan (-)</div>
                                        <div style="font-size: 1.3rem; font-weight: 800; color: #e11d48; margin-top: 4px;">Rp {{ number_format($totalPenarikanBulanIni, 0, ',', '.') }}</div>
                                        <small class="text-muted">Bulan {{ $monthNames[$selectedMonth] ?? 'Bulan' }} {{ $selectedYear }}</small>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-left: 4px solid #1e3a8a; border-radius: 10px; padding: 16px;">
                                        <div style="font-size: 0.75rem; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">Total Saldo Akhir (=)</div>
                                        <div style="font-size: 1.3rem; font-weight: 800; color: #1e3a8a; margin-top: 4px;">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</div>
                                        <small class="text-muted">Per Akhir {{ $monthNames[$selectedMonth] ?? 'Bulan' }} {{ $selectedYear }}</small>
                                    </div>
                                </div>
                            </div>

                            {{-- AKUNTABILITAS KAS FISIK TPQ --}}
                            <div class="card mb-4" style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 10px; box-shadow: none;">
                                <div class="card-body p-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
                                    <div class="d-flex flex-wrap align-items-center gap-4">
                                        <div class="d-flex align-items-center">
                                            <div style="width: 38px; height: 38px; background: #dcfce7; color: #15803d; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                                                <i class="material-icons" style="font-size: 22px;">savings</i>
                                            </div>
                                            <div>
                                                <div style="font-size: 0.75rem; font-weight: 700; color: #64748b;">KAS FISIK DI BENDAHARA</div>
                                                <div style="font-size: 1.1rem; font-weight: 800; color: #15803d;">Rp {{ number_format($kasDiBendahara, 0, ',', '.') }}</div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div style="width: 38px; height: 38px; background: {{ $totalDanaMengendap > 0 ? '#fef3c7' : '#dcfce7' }}; color: {{ $totalDanaMengendap > 0 ? '#b45309' : '#15803d' }}; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                                                <i class="material-icons" style="font-size: 22px;">pan_tool</i>
                                            </div>
                                            <div>
                                                <div style="font-size: 0.75rem; font-weight: 700; color: #64748b;">DANA MENGENDAP DI GURU</div>
                                                <div style="font-size: 1.1rem; font-weight: 800; color: {{ $totalDanaMengendap > 0 ? '#b45309' : '#15803d' }};">
                                                    Rp {{ number_format($totalDanaMengendap, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        @if($totalDanaMengendap > 0)
                                            <span class="badge" style="background: #fef3c7; color: #b45309; font-size: 0.8rem; padding: 6px 12px; border-radius: 6px;">
                                                ⚠️ Ada setoran guru belum disetor ke kas bendahara
                                            </span>
                                        @else
                                            <span class="badge" style="background: #dcfce7; color: #15803d; font-size: 0.8rem; padding: 6px 12px; border-radius: 6px;">
                                                ✓ Seluruh setoran guru telah tertib disetor ke bendahara
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- TABEL KELAS --}}
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 style="font-weight: 700; color: #1e293b; margin: 0;">
                                    <i class="material-icons text-primary" style="vertical-align: middle; margin-top: -3px; margin-right: 5px;">assessment</i> 
                                    Rincian Rekapitulasi per Kelas (Periode {{ $monthNames[$selectedMonth] ?? 'Bulan' }} {{ $selectedYear }})
                                </h5>
                            </div>
                            
                            <div class="table-responsive mb-5">
                                <table class="table table-hover">
                                    <thead style="background: #f1f5f9;">
                                        <tr>
                                            <th style="font-weight: 700; color: #475569; border-top: none;">Kelas</th>
                                            <th style="font-weight: 700; color: #475569; border-top: none;">Wali Jilid / Guru</th>
                                            <th style="font-weight: 700; color: #475569; border-top: none; text-align: center;">Santri</th>
                                            <th style="font-weight: 700; color: #475569; border-top: none; text-align: right;">Setoran (Bulan Ini)</th>
                                            <th style="font-weight: 700; color: #475569; border-top: none; text-align: right;">Penarikan (Bulan Ini)</th>
                                            <th style="font-weight: 700; color: #475569; border-top: none; text-align: right;">Dana Mengendap</th>
                                            <th style="font-weight: 700; color: #475569; border-top: none; text-align: right;">Total Saldo Kelas</th>
                                            <th style="font-weight: 700; color: #475569; border-top: none; text-align: center;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($laporanKelas as $row)
                                            <tr>
                                                <td style="vertical-align: middle;">
                                                    <span style="background: #e2e8f0; color: #334155; padding: 4px 10px; border-radius: 6px; font-size: 0.85rem; font-weight: 600;">
                                                        {{ $row['kelas']->tingkat }} {{ $row['kelas']->index_kelas }}
                                                    </span>
                                                </td>
                                                <td style="vertical-align: middle; font-weight: 600; color: #0f172a;">
                                                    {{ $row['guru']->nama_guru ?? '-' }}
                                                </td>
                                                <td style="vertical-align: middle; text-align: center;">
                                                    <span class="badge badge-light" style="font-size: 0.85rem; font-weight: 700;">{{ $row['santri_count'] }}</span>
                                                </td>
                                                <td style="vertical-align: middle; text-align: right; color: #059669; font-weight: 600;">
                                                    Rp {{ number_format($row['setor_bulan_ini'], 0, ',', '.') }}
                                                </td>
                                                <td style="vertical-align: middle; text-align: right; color: #e11d48; font-weight: 600;">
                                                    Rp {{ number_format($row['tarik_bulan_ini'], 0, ',', '.') }}
                                                </td>
                                                <td style="vertical-align: middle; text-align: right;">
                                                    @if($row['dana_mengendap'] > 0)
                                                        <span style="color: #b45309; font-weight: 700; background: #fef3c7; padding: 4px 8px; border-radius: 6px; font-size: 0.85rem;">
                                                            Rp {{ number_format($row['dana_mengendap'], 0, ',', '.') }}
                                                        </span>
                                                    @else
                                                        <span style="color: #94a3b8; font-size: 0.85rem;">Rp 0</span>
                                                    @endif
                                                </td>
                                                <td style="vertical-align: middle; text-align: right; font-weight: 800; color: #1e3a8a; font-size: 1.05rem;">
                                                    Rp {{ number_format($row['saldo'], 0, ',', '.') }}
                                                </td>
                                                <td style="vertical-align: middle; text-align: center;">
                                                    <button wire:click="bukaDetailKelas({{ $row['kelas']->id_kelas }})" class="btn btn-sm btn-info" style="border-radius: 6px; padding: 4px 10px; font-weight: 600; color: white; display: inline-flex; align-items: center; gap: 4px;">
                                                        <i class="material-icons" style="font-size: 16px;">visibility</i> Detail
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted">Belum ada data kelas atau tabungan.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- AKTIVITAS TERBARU GLOBAL --}}
                            <h5 style="font-weight: 700; color: #1e293b; margin-bottom: 16px;"><i class="material-icons text-primary" style="vertical-align: middle; margin-top: -3px; margin-right: 5px;">history</i> Riwayat Transaksi Terbaru</h5>
                            
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 0;">
                                @if($aktivitasTerbaru->isEmpty())
                                    <div style="text-align: center; padding: 2rem; color: #94a3b8;">
                                        <i class="material-icons" style="font-size: 3rem; opacity: 0.5;">hourglass_empty</i>
                                        <p style="margin-top: 10px; font-weight: 500;">Belum ada riwayat transaksi tabungan di sekolah ini.</p>
                                    </div>
                                @else
                                    <div style="display: flex; flex-direction: column;">
                                        @foreach($aktivitasTerbaru as $aktifitas)
                                            <div style="display: flex; align-items: center; padding: 16px 20px; border-bottom: 1px solid #e2e8f0; {{ $loop->last ? 'border-bottom: none;' : '' }}">
                                                <div style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.2rem; margin-right: 16px;
                                                    @if($aktifitas->jenis_transaksi == 'setor') background: #dcfce7; color: #16a34a; 
                                                    @else background: #fee2e2; color: #ef4444; 
                                                    @endif">
                                                    @if($aktifitas->jenis_transaksi == 'setor') +
                                                    @else -
                                                    @endif
                                                </div>
                                                <div style="flex: 1;">
                                                    <div style="font-weight: 700; font-size: 0.95rem; color: #1e293b;">
                                                        {{ $aktifitas->siswa->nama_siswa ?? 'Siswa Tidak Ditemukan' }}
                                                    </div>
                                                    <div style="font-size: 0.8rem; color: #64748b; margin-top: 4px; display: flex; align-items: center; gap: 8px;">
                                                        <span style="background: #e2e8f0; padding: 2px 6px; border-radius: 4px; font-weight: 600; color: #475569;">
                                                            {{ $aktifitas->siswa->kelas->tingkat ?? '' }} {{ $aktifitas->siswa->kelas->index_kelas ?? '' }}
                                                        </span>
                                                        <span><i class="material-icons" style="font-size: 12px; vertical-align: middle;">schedule</i> {{ \Carbon\Carbon::parse($aktifitas->created_at)->diffForHumans() }}</span>
                                                    </div>
                                                </div>
                                                <div style="text-align: right;">
                                                    <div style="font-weight: 800; font-size: 1.1rem; 
                                                        @if($aktifitas->jenis_transaksi == 'setor') color: #16a34a; 
                                                        @else color: #ef4444; 
                                                        @endif">
                                                        Rp {{ number_format($aktifitas->nominal, 0, ',', '.') }}
                                                    </div>
                                                    @if($aktifitas->jenis_transaksi == 'setor')
                                                        @if($aktifitas->status_setoran == 'sudah')
                                                            <div style="font-size: 0.75rem; color: #10b981; font-weight: 600; margin-top: 4px;"><i class="material-icons" style="font-size: 10px; vertical-align: middle;">check_circle</i> Sudah Disetor</div>
                                                        @else
                                                            <div style="font-size: 0.75rem; color: #f59e0b; font-weight: 600; margin-top: 4px;"><i class="material-icons" style="font-size: 10px; vertical-align: middle;">pending</i> Menunggu Setor</div>
                                                        @endif
                                                    @endif
                                                </div>
                                                <div style="margin-left: 16px; display: flex; flex-direction: column; gap: 4px;">
                                                    <button wire:click="editAktivitas({{ $aktifitas->id_tabungan }})" class="btn btn-sm" style="background: none; border: 1px solid #cbd5e1; color: #3b82f6; padding: 4px;" title="Edit">
                                                        <i class="material-icons" style="font-size: 18px;">edit</i>
                                                    </button>
                                                    <button wire:click="hapusAktivitas({{ $aktifitas->id_tabungan }})" wire:confirm="Hapus transaksi tabungan ini? Saldo santri akan disesuaikan secara otomatis." class="btn btn-sm" style="background: none; border: 1px solid #cbd5e1; color: #ef4444; padding: 4px;" title="Hapus">
                                                        <i class="material-icons" style="font-size: 18px;">delete</i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DETAIL KELAS --}}
    <div wire:ignore.self class="modal fade" id="modalDetailKelas" tabindex="-1" role="dialog" aria-labelledby="modalDetailKelasLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                <div class="modal-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; border-radius: 12px 12px 0 0; padding: 16px 24px;">
                    <h5 class="modal-title" id="modalDetailKelasLabel" style="font-weight: 700; color: #0f172a;">
                        <i class="material-icons text-info" style="vertical-align: middle; margin-top: -3px; margin-right: 8px;">groups</i>
                        Detail Saldo - Kelas {{ $namaKelasTerpilih }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding: 1rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 24px;">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead style="background: #f1f5f9;">
                                <tr>
                                    <th style="font-weight: 600; color: #475569;">No</th>
                                    <th style="font-weight: 600; color: #475569;">Nama Santri</th>
                                    <th style="font-weight: 600; color: #475569; text-align: right;">Dana Mengendap</th>
                                    <th style="font-weight: 600; color: #475569; text-align: right;">Total Saldo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($detailSiswa as $index => $siswa)
                                    <tr>
                                        <td style="vertical-align: middle;">{{ $index + 1 }}</td>
                                        <td style="vertical-align: middle; font-weight: 500;">{{ $siswa['nama'] }}</td>
                                        <td style="vertical-align: middle; text-align: right;">
                                            @if($siswa['dana_mengendap'] > 0)
                                                <span style="color: #b45309; font-weight: 600;">Rp {{ number_format($siswa['dana_mengendap'], 0, ',', '.') }}</span>
                                            @else
                                                <span style="color: #94a3b8;">Rp 0</span>
                                            @endif
                                        </td>
                                        <td style="vertical-align: middle; text-align: right; font-weight: 700; color: #16a34a;">
                                            Rp {{ number_format($siswa['saldo'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">Belum ada data santri di kelas ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #e2e8f0; padding: 16px 24px;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 6px; font-weight: 600;">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT TRANSAKSI --}}
    <div wire:ignore.self class="modal fade" id="modalEditAktivitas" tabindex="-1" role="dialog" aria-labelledby="modalEditAktivitasLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                <div class="modal-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; border-radius: 12px 12px 0 0; padding: 16px 24px;">
                    <h5 class="modal-title" id="modalEditAktivitasLabel" style="font-weight: 700; color: #0f172a;">
                        <i class="material-icons text-warning" style="vertical-align: middle; margin-top: -3px; margin-right: 8px;">edit</i>
                        Edit Transaksi Tabungan
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding: 1rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 24px;">
                    <div class="form-group mb-3">
                        <label style="font-weight: 600; color: #334155;">Jenis Transaksi</label>
                        <select wire:model="editAktivitasJenis" class="form-control" style="border-radius: 8px;">
                            <option value="setor">Setor</option>
                            <option value="tarik">Tarik</option>
                        </select>
                        @error('editAktivitasJenis') <span class="text-danger" style="font-size: 0.8rem;">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label style="font-weight: 600; color: #334155;">Nominal (Rp)</label>
                        <input type="number" wire:model="editAktivitasNominal" class="form-control" style="border-radius: 8px;">
                        @error('editAktivitasNominal') <span class="text-danger" style="font-size: 0.8rem;">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 600; color: #334155;">Catatan Tambahan</label>
                        <input type="text" wire:model="editAktivitasKeterangan" class="form-control" style="border-radius: 8px;">
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #e2e8f0; padding: 16px 24px;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 6px; font-weight: 600;">Batal</button>
                    <button type="button" wire:click="updateAktivitas" class="btn btn-warning" style="border-radius: 6px; font-weight: 600; color: #000;">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        $wire.on('show-detail-modal', () => {
            $('#modalDetailKelas').modal('show');
        });
        $wire.on('show-edit-aktivitas-modal', () => {
            $('#modalEditAktivitas').modal('show');
        });
        $wire.on('hide-edit-aktivitas-modal', () => {
            $('#modalEditAktivitas').modal('hide');
        });
    </script>
    @endscript
</div>
