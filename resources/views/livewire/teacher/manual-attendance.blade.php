<div class="t-page">
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
    .quick-nom-btn {
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        background: #fff;
        border-radius: 8px;
        font-weight: 600;
        color: #1e293b;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .quick-nom-btn:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }
    @keyframes slideInRight {
        from { transform: translateX(100%); }
        to { transform: translateX(0); }
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    </style>

    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div class="t-alert success">
            <i class="material-icons">check_circle</i>
            <span>{{ session('success') }}</span>
            <button type="button" class="t-alert-close" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="t-alert danger">
            <i class="material-icons">error</i>
            <span>{{ session('error') }}</span>
            <button type="button" class="t-alert-close" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    <div class="t-card">
        <div class="t-card-header">
            <div class="t-card-header-icon purple">
                <i class="material-icons">edit_note</i>
            </div>
            <div>
                <div class="t-card-title">Monitoring &amp; Absensi Santri</div>
                <div class="t-card-subtitle">Pantau Kehadiran Santri Realtime &amp; Input Status Absensi</div>
            </div>
        </div>

        <div class="t-card-body">
            {{-- Compact Clean Filter Bar --}}
            <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1.25rem; padding: 12px 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
                <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 1.25rem;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <label style="font-size: 0.78rem; font-weight: 700; color: #475569; margin: 0; white-space: nowrap; text-transform: uppercase; letter-spacing: 0.03em;">
                            <i class="material-icons" style="font-size: 16px; vertical-align: text-bottom; color: #64748b; margin-right: 2px;">calendar_today</i> Tanggal:
                        </label>
                        <input type="date" wire:model.live="tanggal" class="t-input" style="width: 155px; padding: 6px 10px; font-size: 0.85rem; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <label style="font-size: 0.78rem; font-weight: 700; color: #475569; margin: 0; white-space: nowrap; text-transform: uppercase; letter-spacing: 0.03em;">
                            <i class="material-icons" style="font-size: 16px; vertical-align: text-bottom; color: #64748b; margin-right: 2px;">filter_alt</i> Pilih Gender:
                        </label>
                        <select wire:model.live="filter_jk" class="t-select" style="width: 240px; padding: 6px 10px; font-size: 0.85rem; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
                            <option value="">Semua</option>
                            <option value="Laki-laki">Laki-laki (Putra)</option>
                            <option value="Perempuan">Perempuan (Putri)</option>
                        </select>
                    </div>
                </div>

                @if($allKelas->count() > 0)
                    <div style="display: flex; gap: 6px; align-items: center; flex-wrap: wrap;">
                        <span style="font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase;">Kelas Binaan:</span>
                        @foreach($allKelas as $k)
                            <span class="badge" style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; font-size: 0.75rem; padding: 4px 9px; border-radius: 6px; font-weight: 600;">
                                {{ $k->tingkat }} {{ $k->index_kelas }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>

            @if($siswaList->count() > 0)
                <div style="overflow-x: auto;">
                    <table class="t-table">
                        <thead>
                            <tr>
                                <th style="width: 3rem;">No</th>
                                <th>Nama Santri</th>
                                <th>Kelas / Jilid</th>
                                <th style="width: 4rem; text-align: center;">L/P</th>
                                <th style="text-align: center;">Jam Masuk</th>
                                <th style="text-align: center;">Status Kehadiran</th>
                                <th style="text-align: right;">Saldo Tabungan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswaList as $index => $siswa)
                                <tr wire:key="siswa-row-{{ $siswa->id_siswa }}">
                                    <td style="color: var(--t-on-surface-subtle); font-weight: 500;">{{ $index + 1 }}</td>
                                    <td style="font-weight: 600;">
                                        {{ $siswa->nama_siswa }}
                                        <div style="font-size: 0.75rem; color: #64748b; font-weight: 400;">NIS: {{ $siswa->nis }}</div>
                                    </td>
                                    <td>
                                        <span style="background: #f1f5f9; color: #334155; padding: 2px 8px; border-radius: 6px; font-size: 0.78rem; font-weight: 600; border: 1px solid #e2e8f0;">
                                            {{ $siswa->kelas->tingkat ?? '' }} {{ $siswa->kelas->index_kelas ?? '' }}
                                        </span>
                                    </td>
                                    <td style="text-align: center; font-weight: 600; color: #475569;">
                                        <span style="padding: 2px 6px; border-radius: 4px; font-size: 0.75rem; background: {{ $siswa->jenis_kelamin == 'Laki-laki' ? '#e0f2fe' : '#fce7f3' }}; color: {{ $siswa->jenis_kelamin == 'Laki-laki' ? '#0369a1' : '#be185d' }};">
                                            {{ $siswa->jenis_kelamin == 'Laki-laki' ? 'L' : 'P' }}
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        @if(isset($jamScan[$siswa->id_siswa]) && $jamScan[$siswa->id_siswa] !== '-')
                                            <span class="badge" style="background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; font-size: 0.82rem; padding: 4px 10px; border-radius: 6px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                                <i class="material-icons" style="font-size: 14px;">qr_code_scanner</i>
                                                {{ $jamScan[$siswa->id_siswa] }}
                                            </span>
                                        @else
                                            <span style="color: #94a3b8; font-size: 0.85rem;">-</span>
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        @php
                                            $statusClass = 'status-belum';
                                            $curStatus = $kehadiran[$siswa->id_siswa] ?? '';
                                            if ($curStatus == '1') $statusClass = 'status-hadir';
                                            elseif ($curStatus == '2') $statusClass = 'status-sakit';
                                            elseif ($curStatus == '3') $statusClass = 'status-izin';
                                            elseif ($curStatus == '4') $statusClass = 'status-alpa';
                                        @endphp
                                        <select wire:model.live="kehadiran.{{ $siswa->id_siswa }}" onchange="window.updateSelectColor(this)" class="status-select {{ $statusClass }}">
                                            <option value="">Belum Absen</option>
                                            <option value="1">Hadir</option>
                                            <option value="2">Sakit</option>
                                            <option value="3">Izin</option>
                                            <option value="4">Alpa</option>
                                        </select>
                                    </td>
                                    <td style="text-align: right; font-weight: 600; color: #334155;">
                                        <div style="display: flex; justify-content: flex-end; align-items: center; gap: 8px;">
                                            <span>Rp {{ number_format($siswa->saldo_tabungan ?? 0, 0, ',', '.') }}</span>
                                            <button type="button" wire:click="openTabunganPanel({{ $siswa->id_siswa }})" class="t-btn" style="padding: 4px 10px; font-size: 0.75rem; background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: all 0.15s ease;">
                                                <i class="material-icons" style="font-size: 14px;">account_balance_wallet</i> Input
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="display: flex; justify-content: flex-end; margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--t-divider);">
                    <button type="button" wire:click="saveAttendance" wire:loading.attr="disabled" class="t-btn t-btn-primary t-btn-lg" style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer;">
                        <span wire:loading.remove wire:target="saveAttendance" style="display: inline-flex; align-items: center; gap: 8px;">
                            <i class="material-icons" style="font-size: 1.25rem;">save</i>
                            Simpan Absensi
                        </span>
                        <span wire:loading wire:target="saveAttendance" style="display: inline-flex; align-items: center; gap: 8px;">
                            <i class="material-icons" style="font-size: 1.25rem; animation: spin 1s linear infinite;">sync</i>
                            Menyimpan Absensi...
                        </span>
                    </button>
                </div>
            @else
                <div class="t-alert warning" style="margin-top: 1rem;">
                    <i class="material-icons">info</i>
                    <span>Belum ada santri untuk kelas binaan / filter jenis kelamin ini.</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Slide-over Panel Tabungan --}}
    @if($showTabunganPanel)
        <div style="position: fixed; inset: 0; z-index: 1000; display: flex; justify-content: flex-end; background: rgba(0,0,0,0.5);">
            <div style="width: 420px; max-width: 100%; background: #fff; height: 100%; display: flex; flex-direction: column; box-shadow: -4px 0 20px rgba(0,0,0,0.15); animation: slideInRight 0.25s ease-out;">
                
                {{-- Header --}}
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 24px; border-bottom: 1px solid #e2e8f0; background: #fafafa;">
                    <h3 style="margin: 0; font-size: 1.1rem; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                        <i class="material-icons" style="color: #2563eb; font-size: 22px;">account_balance_wallet</i> Input Tabungan Santri
                    </h3>
                    <button type="button" wire:click="closeTabunganPanel" style="background: none; border: 1px solid #e2e8f0; border-radius: 6px; padding: 4px; cursor: pointer; color: #64748b; display: flex; align-items: center; justify-content: center; transition: all 0.15s ease;">
                        <i class="material-icons" style="font-size: 18px;">close</i>
                    </button>
                </div>

                {{-- Body --}}
                <div style="flex: 1; overflow-y: auto; padding: 24px;">
                    
                    {{-- Alert inside panel --}}
                    @if (session()->has('tabungan_error'))
                        <div class="t-alert danger" style="margin-bottom: 16px; padding: 10px 14px; font-size: 0.85rem;">
                            <i class="material-icons" style="font-size: 18px;">error</i>
                            <span>{{ session('tabungan_error') }}</span>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="t-alert danger" style="margin-bottom: 16px; padding: 10px 14px; font-size: 0.85rem;">
                            <i class="material-icons" style="font-size: 18px;">error</i>
                            <div>
                                @foreach ($errors->all() as $err)
                                    <div>{{ $err }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Student Info Card --}}
                    <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 16px; display: flex; align-items: center; gap: 14px; background: #f8fafc;">
                        <div style="width: 46px; height: 46px; border-radius: 50%; background: #ede9fe; color: #7c3aed; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.1rem; flex-shrink: 0;">
                            {{ $tabungan_siswa_inisial }}
                        </div>
                        <div style="min-width: 0;">
                            <div style="font-weight: 700; color: #1e293b; font-size: 1rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $tabungan_siswa_nama }}</div>
                            <div style="font-size: 0.82rem; color: #64748b; margin-top: 3px;">
                                Kelas {{ $tabungan_siswa_kelas }} &middot; <span style="font-weight: 600; color: #0284c7;">{{ $tabungan_siswa_status_absen }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Current Balance --}}
                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 14px 16px; margin-bottom: 20px;">
                        <div style="font-size: 0.8rem; color: #166534; font-weight: 600; margin-bottom: 2px;">Saldo tabungan saat ini</div>
                        <div style="font-size: 1.4rem; font-weight: 800; color: #14532d;">Rp {{ number_format($tabungan_saldo_saat_ini, 0, ',', '.') }}</div>
                    </div>

                    {{-- Transaction Type --}}
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 8px;">Jenis Transaksi</label>
                        <div style="display: flex; gap: 12px;">
                            <label style="flex: 1; text-align: center; cursor: pointer;">
                                <input type="radio" wire:model.live="tabungan_jenis" value="setor" style="display: none;">
                                <div style="padding: 10px; border-radius: 8px; font-weight: 700; font-size: 0.9rem; border: 2px solid {{ $tabungan_jenis == 'setor' ? '#16a34a' : '#e2e8f0' }}; background: {{ $tabungan_jenis == 'setor' ? '#dcfce7' : '#fff' }}; color: {{ $tabungan_jenis == 'setor' ? '#15803d' : '#64748b' }}; display: flex; align-items: center; justify-content: center; gap: 6px;">
                                    <i class="material-icons" style="font-size: 18px;">arrow_upward</i> Setor
                                </div>
                            </label>
                            <label style="flex: 1; text-align: center; cursor: pointer;">
                                <input type="radio" wire:model.live="tabungan_jenis" value="tarik" style="display: none;">
                                <div style="padding: 10px; border-radius: 8px; font-weight: 700; font-size: 0.9rem; border: 2px solid {{ $tabungan_jenis == 'tarik' ? '#dc2626' : '#e2e8f0' }}; background: {{ $tabungan_jenis == 'tarik' ? '#fee2e2' : '#fff' }}; color: {{ $tabungan_jenis == 'tarik' ? '#b91c1c' : '#64748b' }}; display: flex; align-items: center; justify-content: center; gap: 6px;">
                                    <i class="material-icons" style="font-size: 18px;">arrow_downward</i> Tarik
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Quick Nominal Buttons --}}
                    <div style="margin-bottom: 10px;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 6px;">Pilihan Cepat</label>
                        <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                            <button type="button" wire:click="setTabunganNominal(5000)" class="quick-nom-btn">+ 5rb</button>
                            <button type="button" wire:click="setTabunganNominal(10000)" class="quick-nom-btn">+ 10rb</button>
                            <button type="button" wire:click="setTabunganNominal(15000)" class="quick-nom-btn">+ 15rb</button>
                            <button type="button" wire:click="setTabunganNominal(20000)" class="quick-nom-btn">+ 20rb</button>
                            <button type="button" wire:click="setTabunganNominal(50000)" class="quick-nom-btn">+ 50rb</button>
                            <button type="button" wire:click="$set('tabungan_nominal', '')" class="quick-nom-btn" style="color: #ef4444; border-color: #fecaca; background: #fff5f5;">Reset</button>
                        </div>
                    </div>

                    {{-- Nominal Input --}}
                    <div style="margin-bottom: 18px;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 6px;">Nominal (Rp)</label>
                        <input type="number" wire:model.live="tabungan_nominal" placeholder="Contoh: 10000" class="t-input" min="100" style="width: 100%; padding: 10px 12px; font-size: 1.05rem; font-weight: 600; border-radius: 8px; border: 1px solid #cbd5e1; box-sizing: border-box;">
                        @error('tabungan_nominal') <span style="color: #ef4444; font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    {{-- Catatan --}}
                    <div style="margin-bottom: 24px;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 6px;">Catatan (Opsional)</label>
                        <input type="text" wire:model="tabungan_keterangan" placeholder="Contoh: Tabungan rutin santri" class="t-input" style="width: 100%; padding: 10px 12px; font-size: 0.9rem; border-radius: 8px; border: 1px solid #cbd5e1; box-sizing: border-box;">
                    </div>

                    {{-- Submit Button --}}
                    <button type="button" wire:click="simpanTabungan" wire:loading.attr="disabled" class="t-btn" style="width: 100%; padding: 12px; font-size: 1rem; font-weight: 700; justify-content: center; background: #2563eb; border: 1px solid #1d4ed8; color: #ffffff; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 2px 4px rgba(37,99,235,0.25);">
                        <span wire:loading.remove wire:target="simpanTabungan" style="display: inline-flex; align-items: center; gap: 8px;">
                            <i class="material-icons" style="font-size: 18px;">check_circle</i> Simpan Tabungan
                        </span>
                        <span wire:loading wire:target="simpanTabungan" style="display: inline-flex; align-items: center; gap: 8px;">
                            <i class="material-icons" style="font-size: 18px; animation: spin 1s linear infinite;">sync</i> Menyimpan Transaksi...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <script>
    window.updateSelectColor = function(select) {
        if (!select) return;
        select.classList.remove('status-belum', 'status-hadir', 'status-sakit', 'status-izin', 'status-alpa');
        if (select.value === '') select.classList.add('status-belum');
        else if (select.value === '1') select.classList.add('status-hadir');
        else if (select.value === '2') select.classList.add('status-sakit');
        else if (select.value === '3') select.classList.add('status-izin');
        else if (select.value === '4') select.classList.add('status-alpa');
    };
    </script>
</div>
