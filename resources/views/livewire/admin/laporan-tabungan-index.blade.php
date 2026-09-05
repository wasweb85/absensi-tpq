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
                        <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #0284c7, #0369a1); border-radius: 12px 12px 0 0; padding: 20px;">
                            <div>
                                <h4 class="card-title text-white m-0" style="font-weight: 700;">Laporan Keuangan TPQ</h4>
                                <p class="category text-white-50 m-0 mt-1">Rekapitulasi tabungan seluruh kelas binaan</p>
                            </div>
                            <button onclick="window.print()" class="btn btn-sm btn-light font-weight-bold d-inline-flex align-items-center gap-1 shadow-sm" style="border-radius: 8px; color: #0284c7;">
                                <i class="material-icons" style="font-size: 18px;">print</i> Cetak Laporan
                            </button>
                        </div>
                        <div class="card-body" style="padding: 24px;">
                            
                            {{-- GLOBAL STATS --}}
                            <div class="row mb-5">
                                <div class="col-md-6">
                                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; display: flex; align-items: center;">
                                        <div style="width: 50px; height: 50px; background: #dcfce7; color: #16a34a; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 16px;">
                                            <i class="material-icons" style="font-size: 28px;">account_balance_wallet</i>
                                        </div>
                                        <div>
                                            <div style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Total Kas Tabungan</div>
                                            <div style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">Rp {{ number_format($totalTabunganKeseluruhan, 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div style="background: #fefce8; border: 1px solid #fef08a; border-radius: 12px; padding: 20px; display: flex; align-items: center;">
                                        <div style="width: 50px; height: 50px; background: #fef08a; color: #ca8a04; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 16px;">
                                            <i class="material-icons" style="font-size: 28px;">front_hand</i>
                                        </div>
                                        <div>
                                            <div style="font-size: 0.8rem; font-weight: 700; color: #a16207; text-transform: uppercase; letter-spacing: 0.5px;">Total Dana Mengendap (Di Guru)</div>
                                            <div style="font-size: 1.5rem; font-weight: 800; color: #854d0e;">Rp {{ number_format($totalDanaMengendap, 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- TABEL KELAS --}}
                            <h5 style="font-weight: 700; color: #1e293b; margin-bottom: 16px;"><i class="material-icons text-primary" style="vertical-align: middle; margin-top: -3px; margin-right: 5px;">assessment</i> Rincian Saldo per Kelas</h5>
                            
                            <div class="table-responsive mb-5">
                                <table class="table table-hover">
                                    <thead style="background: #f1f5f9;">
                                        <tr>
                                            <th style="font-weight: 700; color: #475569; border-top: none;">Kelas</th>
                                            <th style="font-weight: 700; color: #475569; border-top: none;">Wali Jilid</th>
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
                                                <td style="vertical-align: middle; text-align: right;">
                                                    @if($row['dana_mengendap'] > 0)
                                                        <span style="color: #b45309; font-weight: 700; background: #fef3c7; padding: 4px 8px; border-radius: 6px; font-size: 0.9rem;">
                                                            Rp {{ number_format($row['dana_mengendap'], 0, ',', '.') }}
                                                        </span>
                                                    @else
                                                        <span style="color: #94a3b8; font-size: 0.9rem;">Rp 0</span>
                                                    @endif
                                                </td>
                                                <td style="vertical-align: middle; text-align: right; font-weight: 800; color: #16a34a; font-size: 1.05rem;">
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
                                                <td colspan="5" class="text-center py-4 text-muted">Belum ada data kelas atau tabungan.</td>
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
