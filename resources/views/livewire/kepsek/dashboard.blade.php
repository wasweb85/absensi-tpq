<div class="content dashboard-wrapper" wire:poll.60s>
    <div class="container-fluid">

        <style>
            /* ════════════════════════════════════════════════
               EXECUTIVE DASHBOARD STYLES (KEPALA TPQ)
               ════════════════════════════════════════════════ */
            .exec-banner {
                background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
                border-radius: 18px;
                padding: 24px 28px;
                color: #ffffff;
                margin-bottom: 22px;
                box-shadow: 0 8px 24px rgba(15, 23, 42, 0.12);
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
            }

            .exec-banner-title {
                font-size: 1.45rem;
                font-weight: 800;
                margin: 0 0 6px 0;
                letter-spacing: -0.5px;
                color: #ffffff;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .exec-banner-sub {
                font-size: 0.88rem;
                color: #94a3b8;
                margin: 0;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .exec-banner-badge {
                background: rgba(16, 185, 129, 0.15);
                color: #34d399;
                border: 1px solid rgba(52, 211, 153, 0.3);
                padding: 4px 12px;
                border-radius: 30px;
                font-size: 0.75rem;
                font-weight: 700;
                letter-spacing: 0.5px;
                text-transform: uppercase;
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }

            .btn-refresh-exec {
                background: rgba(255, 255, 255, 0.1);
                color: #ffffff;
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: 12px;
                padding: 9px 18px;
                font-size: 0.85rem;
                font-weight: 700;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                transition: all 0.2s;
            }

            .btn-refresh-exec:hover {
                background: rgba(255, 255, 255, 0.2);
                color: #ffffff;
                text-decoration: none;
            }

            /* ── KPI Cards ── */
            .kpi-row {
                display: flex;
                flex-wrap: wrap;
                gap: 16px;
                margin-bottom: 22px;
            }

            .kpi-col {
                flex: 1 1 calc(25% - 16px);
                min-width: 220px;
            }

            .kpi-card {
                background: #ffffff;
                border-radius: 16px;
                box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
                padding: 18px 20px;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                border: 1px solid #f1f5f9;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
                height: 100%;
            }

            .kpi-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            }

            .kpi-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 12px;
            }

            .kpi-icon-wrap {
                width: 44px;
                height: 44px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            }

            .kpi-icon-wrap i {
                font-size: 22px;
                color: #ffffff;
            }

            .kpi-tag {
                font-size: 0.72rem;
                font-weight: 700;
                padding: 3px 8px;
                border-radius: 6px;
            }

            .kpi-label {
                font-size: 0.75rem;
                font-weight: 700;
                color: #64748b;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin: 0 0 4px 0;
            }

            .kpi-value {
                font-size: 1.65rem;
                font-weight: 800;
                color: #0f172a;
                margin: 0;
                line-height: 1.1;
                letter-spacing: -0.5px;
            }

            .kpi-subtext {
                font-size: 0.78rem;
                color: #94a3b8;
                margin-top: 6px;
                display: flex;
                align-items: center;
                gap: 4px;
            }

            /* ── Section Cards ── */
            .exec-card {
                background: #ffffff;
                border-radius: 16px;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
                border: 1px solid #f1f5f9;
                margin-bottom: 22px;
                overflow: hidden;
            }

            .exec-card-header {
                padding: 18px 24px;
                border-bottom: 1px solid #f1f5f9;
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 10px;
            }

            .exec-card-title {
                font-size: 1.05rem;
                font-weight: 800;
                color: #0f172a;
                margin: 0;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .exec-card-title i {
                color: #2563eb;
                font-size: 22px;
            }

            .exec-card-body {
                padding: 20px 24px;
            }

            /* ── Table Matriks ── */
            .table-matrix {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
            }

            .table-matrix th {
                background: #f8fafc;
                font-size: 0.75rem;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                color: #64748b;
                padding: 12px 16px;
                border-bottom: 1px solid #e2e8f0;
            }

            .table-matrix td {
                padding: 14px 16px;
                font-size: 0.88rem;
                color: #334155;
                border-bottom: 1px solid #f1f5f9;
                vertical-align: middle;
            }

            .table-matrix tr:last-child td {
                border-bottom: none;
            }

            .table-matrix tr:hover td {
                background: #fcfcfd;
            }

            .pill-stat {
                display: inline-flex;
                align-items: center;
                padding: 3px 8px;
                border-radius: 6px;
                font-size: 0.72rem;
                font-weight: 700;
                margin-right: 4px;
            }
            .pill-h { background: #dcfce7; color: #16a34a; }
            .pill-s { background: #f3e8ff; color: #9333ea; }
            .pill-i { background: #dbeafe; color: #2563eb; }
            .pill-a { background: #fee2e2; color: #dc2626; }

            .progress-exec {
                height: 7px;
                border-radius: 10px;
                background: #e2e8f0;
                overflow: hidden;
                margin-top: 5px;
            }

            .progress-exec-bar {
                height: 100%;
                border-radius: 10px;
                background: linear-gradient(90deg, #10b981, #059669);
            }

            .badge-status-absen {
                padding: 5px 12px;
                border-radius: 20px;
                font-size: 0.72rem;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                display: inline-flex;
                align-items: center;
                gap: 5px;
            }
            .badge-status-lengkap { background: #dcfce7; color: #15803d; }
            .badge-status-sebagian { background: #fef3c7; color: #b45309; }
            .badge-status-belum { background: #f1f5f9; color: #64748b; }

            /* ── Inval Card Item ── */
            .inval-item {
                background: #fff;
                border-radius: 12px;
                border: 1px solid #fed7aa;
                padding: 14px 16px;
                margin-bottom: 12px;
                background: linear-gradient(135deg, #fffaf5 0%, #ffffff 100%);
            }

            .inval-item-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 6px;
            }

            .inval-name {
                font-weight: 800;
                font-size: 0.95rem;
                color: #9a3412;
            }

            .inval-badge {
                font-size: 0.72rem;
                font-weight: 700;
                padding: 2px 8px;
                border-radius: 6px;
                background: #ffedd5;
                color: #c2410c;
            }

            .inval-reason {
                font-size: 0.82rem;
                color: #475569;
                margin: 4px 0 8px 0;
            }

            .inval-class-badge {
                font-size: 0.75rem;
                font-weight: 700;
                color: #ea580c;
                display: inline-flex;
                align-items: center;
                gap: 4px;
                background: #fff;
                padding: 3px 8px;
                border-radius: 6px;
                border: 1px solid #fdba74;
            }

            /* ── Quick Action Cards ── */
            .report-box {
                background: #ffffff;
                border-radius: 16px;
                border: 1px solid #e2e8f0;
                padding: 20px;
                display: flex;
                align-items: center;
                gap: 16px;
                text-decoration: none !important;
                color: #0f172a !important;
                transition: all 0.2s ease;
            }

            .report-box:hover {
                transform: translateY(-3px);
                border-color: #2563eb;
                box-shadow: 0 8px 20px rgba(37, 99, 235, 0.08);
            }

            .report-icon {
                width: 52px;
                height: 52px;
                border-radius: 14px;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }

            .report-icon i {
                font-size: 26px;
                color: #fff;
            }
        </style>

        <!-- ── 1. WELCOME BANNER EKSEKUTIF ── -->
        <div class="exec-banner">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="exec-banner-badge">
                        <i class="material-icons" style="font-size: 14px;">verified_user</i> Portal Supervisi Pimpinan
                    </span>
                </div>
                <h2 class="exec-banner-title">
                    Selamat Datang, {{ auth()->user()->name ?? 'Kepala TPQ' }}
                </h2>
                <p class="exec-banner-sub">
                    <i class="material-icons" style="font-size: 16px; color: #38bdf8;">calendar_today</i>
                    {{ $formattedDate }} &bull; Memantau seluruh aktivitas pembelajaran & kedisiplinan TPQ
                </p>
            </div>
            <div>
                <button wire:click="refreshData" class="btn-refresh-exec">
                    <i class="material-icons" style="font-size: 18px;" wire:loading.class="spin">refresh</i>
                    <span wire:loading.remove>Segarkan Data</span>
                    <span wire:loading>Memperbarui...</span>
                </button>
            </div>
        </div>

        <!-- ── 2. EXECUTIVE KPI CARDS (4 KARTU) ── -->
        <div class="kpi-row">

            {{-- KPI 1: Kehadiran Santri --}}
            <div class="kpi-col">
                <div class="kpi-card">
                    <div class="kpi-header">
                        <div class="kpi-icon-wrap" style="background: linear-gradient(135deg, #0284c7, #0369a1);">
                            <i class="material-icons">groups</i>
                        </div>
                        <span class="kpi-tag" style="background: #e0f2fe; color: #0369a1;">Hari Ini</span>
                    </div>
                    <div>
                        <p class="kpi-label">Kehadiran Santri</p>
                        <h3 class="kpi-value">{{ $persenSiswaHadir }}%</h3>
                        <div class="kpi-subtext">
                            <i class="material-icons" style="font-size: 14px; color: #16a34a;">check_circle</i>
                            <strong>{{ number_format($siswaHadir) }}</strong> dari {{ number_format($totalSiswa) }} Santri Hadir
                        </div>
                    </div>
                </div>
            </div>

            {{-- KPI 2: Kehadiran Ustadzah --}}
            <div class="kpi-col">
                <div class="kpi-card">
                    <div class="kpi-header">
                        <div class="kpi-icon-wrap" style="background: linear-gradient(135deg, #10b981, #047857);">
                            <i class="material-icons">person_4</i>
                        </div>
                        @if($guruHadir == $totalGuru && $totalGuru > 0)
                            <span class="kpi-tag" style="background: #dcfce7; color: #15803d;">Lengkap</span>
                        @else
                            <span class="kpi-tag" style="background: #fef3c7; color: #b45309;">Ada Izin/Absen</span>
                        @endif
                    </div>
                    <div>
                        <p class="kpi-label">Kehadiran Ustadzah</p>
                        <h3 class="kpi-value">{{ $guruHadir }} / {{ $totalGuru }}</h3>
                        <div class="kpi-subtext">
                            @if($guruInval->count() > 0)
                                <span class="text-warning font-weight-bold">
                                    <i class="material-icons" style="font-size: 14px;">warning</i> {{ $guruInval->count() }} Guru Izin / Sakit
                                </span>
                            @else
                                <span class="text-success font-weight-bold">
                                    <i class="material-icons" style="font-size: 14px;">check</i> 100% Pengajar Hadir
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- KPI 3: Total Kas Tabungan --}}
            <div class="kpi-col">
                <div class="kpi-card">
                    <div class="kpi-header">
                        <div class="kpi-icon-wrap" style="background: linear-gradient(135deg, #8b5cf6, #6d28d9);">
                            <i class="material-icons">account_balance</i>
                        </div>
                        <span class="kpi-tag" style="background: #ede9fe; color: #6d28d9;">Kas TPQ</span>
                    </div>
                    <div>
                        <p class="kpi-label">Kas Tabungan Santri</p>
                        <h3 class="kpi-value" style="font-size: 1.35rem;">Rp {{ number_format($totalKasTabungan, 0, ',', '.') }}</h3>
                        <div class="kpi-subtext">
                            <i class="material-icons" style="font-size: 14px; color: #6d28d9;">shield</i>
                            Saldo aman di kas lembaga
                        </div>
                    </div>
                </div>
            </div>

            {{-- KPI 4: Dana Belum Disetor --}}
            <div class="kpi-col">
                <div class="kpi-card">
                    <div class="kpi-header">
                        <div class="kpi-icon-wrap" style="background: linear-gradient(135deg, #f59e0b, #b45309);">
                            <i class="material-icons">front_hand</i>
                        </div>
                        @if($totalBelumDisetor > 0)
                            <span class="kpi-tag" style="background: #fef3c7; color: #b45309;">Di Guru</span>
                        @else
                            <span class="kpi-tag" style="background: #dcfce7; color: #15803d;">Tuntas</span>
                        @endif
                    </div>
                    <div>
                        <p class="kpi-label">Dana di Ustadzah</p>
                        <h3 class="kpi-value" style="font-size: 1.35rem;">Rp {{ number_format($totalBelumDisetor, 0, ',', '.') }}</h3>
                        <div class="kpi-subtext">
                            @if($totalBelumDisetor > 0)
                                <span class="text-warning font-weight-bold">
                                    <i class="material-icons" style="font-size: 14px;">schedule</i> Menunggu setoran ke kasir
                                </span>
                            @else
                                <span class="text-success font-weight-bold">
                                    <i class="material-icons" style="font-size: 14px;">done_all</i> Seluruh setoran telah disetor
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- ── 3. MAIN GRID: MATRIKS KELAS & WIDGET USTADZAH ── -->
        <div class="row g-3">

            <!-- TABEL MATRIKS KELAS REALTIME (70%) -->
            <div class="col-lg-8">
                <div class="exec-card">
                    <div class="exec-card-header">
                        <div>
                            <h4 class="exec-card-title">
                                <i class="material-icons">fact_check</i>
                                Matriks Kehadiran Kelas Realtime
                            </h4>
                            <p class="text-muted m-0" style="font-size: 0.8rem;">
                                Progres absensi seluruh rombel santri hari ini
                            </p>
                        </div>
                        <div>
                            <span class="badge badge-pill badge-primary px-3 py-2" style="font-size: 0.75rem; font-weight: 700;">
                                {{ $kelasSelesai }} / {{ $totalKelas }} Kelas Lengkap
                            </span>
                        </div>
                    </div>
                    <div class="exec-card-body p-0">
                        <div class="table-responsive">
                            <table class="table-matrix">
                                <thead>
                                    <tr>
                                        <th>Kelas</th>
                                        <th>Wali Kelas</th>
                                        <th>Rincian Presensi</th>
                                        <th style="min-width: 140px;">Kehadiran (%)</th>
                                        <th class="text-center">Status Absensi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($matriksKelas as $item)
                                        <tr>
                                            <td>
                                                <div class="font-weight-bold text-dark" style="font-size: 0.95rem;">{{ $item['nama_kelas'] }}</div>
                                                <span class="text-muted" style="font-size: 0.78rem;">{{ $item['total_siswa'] }} Santri</span>
                                            </td>
                                            <td>
                                                <div class="font-weight-bold" style="color: #334155;">{{ $item['wali_kelas'] }}</div>
                                                @if($item['wali_kelas_hp'])
                                                    <span class="text-muted" style="font-size: 0.75rem;">
                                                        <i class="material-icons" style="font-size: 12px; vertical-align: middle;">phone</i> {{ $item['wali_kelas_hp'] }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center flex-wrap">
                                                    <span class="pill-stat pill-h" title="Hadir">H: {{ $item['hadir'] }}</span>
                                                    <span class="pill-stat pill-s" title="Sakit">S: {{ $item['sakit'] }}</span>
                                                    <span class="pill-stat pill-i" title="Izin">I: {{ $item['izin'] }}</span>
                                                    <span class="pill-stat pill-a" title="Alfa">A: {{ $item['alfa'] }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <span class="font-weight-bold text-dark" style="font-size: 0.85rem;">{{ $item['persen'] }}%</span>
                                                </div>
                                                <div class="progress-exec">
                                                    <div class="progress-exec-bar" style="width: {{ $item['persen'] }}%;"></div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if($item['status'] === 'lengkap')
                                                    <span class="badge-status-absen badge-status-lengkap">
                                                        <i class="material-icons" style="font-size: 14px;">check_circle</i> Lengkap
                                                    </span>
                                                @elseif($item['status'] === 'sebagian')
                                                    <span class="badge-status-absen badge-status-sebagian">
                                                        <i class="material-icons" style="font-size: 14px;">timelapse</i> Sebagian
                                                    </span>
                                                @else
                                                    <span class="badge-status-absen badge-status-belum">
                                                        <i class="material-icons" style="font-size: 14px;">hourglass_empty</i> Belum Diabsen
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                Belum ada data kelas yang terdaftar.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- WIDGET KESIAPAN USTADZAH & INVAL (30%) -->
            <div class="col-lg-4">
                <div class="exec-card">
                    <div class="exec-card-header">
                        <div>
                            <h4 class="exec-card-title">
                                <i class="material-icons" style="color: #ea580c;">support_agent</i>
                                Kesiapan Ustadzah &amp; Inval
                            </h4>
                            <p class="text-muted m-0" style="font-size: 0.8rem;">
                                Pengajar berhalangan hadir hari ini
                            </p>
                        </div>
                    </div>
                    <div class="exec-card-body">

                        @if($guruInval->count() > 0)
                            <div class="mb-3">
                                <div class="alert alert-warning p-2 text-dark font-weight-bold" style="font-size: 0.78rem; border-radius: 10px; background: #fff7ed; border-color: #ffedd5;">
                                    <i class="material-icons text-warning mr-1" style="font-size: 16px; vertical-align: middle;">announcement</i>
                                    Perlu penugasan Ustadzah Pengganti (Inval):
                                </div>
                            </div>

                            @foreach($guruInval as $inval)
                                <div class="inval-item">
                                    <div class="inval-item-header">
                                        <div class="inval-name">{{ $inval->guru->nama_guru ?? 'Ustadzah' }}</div>
                                        <span class="inval-badge">
                                            {{ $inval->kehadiran->kehadiran ?? 'Tidak Hadir' }}
                                        </span>
                                    </div>
                                    <div class="inval-reason">
                                        <i class="material-icons" style="font-size: 13px; vertical-align: middle; color: #94a3b8;">chat</i>
                                        <em>"{{ $inval->keterangan ?: 'Tidak ada keterangan' }}"</em>
                                    </div>
                                    <div>
                                        @if($inval->guru && $inval->guru->kelas->count() > 0)
                                            <span class="inval-class-badge">
                                                <i class="material-icons" style="font-size: 13px;">school</i>
                                                Wali Kelas: {{ $inval->guru->kelas->pluck('tingkat')->join(', ') }}
                                            </span>
                                        @else
                                            <span class="text-muted" style="font-size: 0.75rem;">Guru Pengajar</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                        @else
                            {{-- State jika semua hadir --}}
                            <div class="text-center py-4 px-2" style="background: #f0fdf4; border-radius: 14px; border: 1px dashed #86efac;">
                                <div style="width: 52px; height: 52px; background: #dcfce7; color: #16a34a; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                                    <i class="material-icons" style="font-size: 28px;">sentiment_very_satisfied</i>
                                </div>
                                <h5 class="font-weight-bold text-success mb-1" style="font-size: 1rem;">Alhamdulillah, Pengajar Lengkap!</h5>
                                <p class="text-muted m-0" style="font-size: 0.8rem;">
                                    Seluruh ustadzah hadir untuk mendampingi pembelajaran hari ini.
                                </p>
                            </div>
                        @endif

                        @if($guruBelumPresensi->count() > 0)
                            <div class="mt-3 pt-3" style="border-top: 1px dashed #e2e8f0;">
                                <span class="text-muted font-weight-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                    Belum Presensi ({{ $guruBelumPresensi->count() }} orang):
                                </span>
                                <ul class="list-unstyled mt-2 mb-0" style="font-size: 0.8rem; color: #64748b;">
                                    @foreach($guruBelumPresensi->take(4) as $gb)
                                        <li class="py-1 d-flex justify-content-between align-items-center">
                                            <span>&bull; {{ $gb->nama_guru }}</span>
                                            <span class="badge badge-light text-muted" style="font-size: 0.68rem;">Belum Hadir</span>
                                        </li>
                                    @endforeach
                                    @if($guruBelumPresensi->count() > 4)
                                        <li class="text-muted" style="font-size: 0.72rem;">+{{ $guruBelumPresensi->count() - 4 }} pengajar lainnya</li>
                                    @endif
                                </ul>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>

        <!-- ── 4. PUSAT LAPORAN & CETAK DOKUMEN RESMI ── -->
        <div class="row g-3 mt-1">
            <div class="col-12">
                <div class="exec-card">
                    <div class="exec-card-header">
                        <div>
                            <h4 class="exec-card-title">
                                <i class="material-icons" style="color: #7c3aed;">print</i>
                                Pusat Cetak Laporan &amp; Pengesahan Resmi
                            </h4>
                            <p class="text-muted m-0" style="font-size: 0.8rem;">
                                Unduh dan cetak rekapitulasi data resmi format tanda tangan Kepala TPQ
                            </p>
                        </div>
                    </div>
                    <div class="exec-card-body">
                        <div class="row g-3">

                            {{-- Laporan Presensi Santri --}}
                            <div class="col-md-4">
                                <a href="{{ route('admin.laporan.siswa') }}" class="report-box" target="_blank">
                                    <div class="report-icon" style="background: linear-gradient(135deg, #0284c7, #0369a1);">
                                        <i class="material-icons">assignment_ind</i>
                                    </div>
                                    <div>
                                        <h5 class="font-weight-bold m-0" style="font-size: 0.95rem; color: #0f172a;">Rekap Presensi Santri</h5>
                                        <p class="text-muted m-0" style="font-size: 0.78rem;">Laporan rekapitulasi absensi santri bulanan/semester</p>
                                        <span class="text-primary font-weight-bold" style="font-size: 0.75rem;">Buka &amp; Cetak PDF &rarr;</span>
                                    </div>
                                </a>
                            </div>

                            {{-- Laporan Presensi Ustadzah --}}
                            <div class="col-md-4">
                                <a href="{{ route('admin.laporan.guru') }}" class="report-box" target="_blank">
                                    <div class="report-icon" style="background: linear-gradient(135deg, #10b981, #047857);">
                                        <i class="material-icons">badge</i>
                                    </div>
                                    <div>
                                        <h5 class="font-weight-bold m-0" style="font-size: 0.95rem; color: #0f172a;">Rekap Presensi Ustadzah</h5>
                                        <p class="text-muted m-0" style="font-size: 0.78rem;">Laporan kedisiplinan dan kehadiran ustadzah pengajar</p>
                                        <span class="text-success font-weight-bold" style="font-size: 0.75rem;">Buka &amp; Cetak PDF &rarr;</span>
                                    </div>
                                </a>
                            </div>

                            {{-- Laporan Tabungan Global --}}
                            <div class="col-md-4">
                                <a href="{{ route('admin.laporan-tabungan') }}" class="report-box">
                                    <div class="report-icon" style="background: linear-gradient(135deg, #8b5cf6, #6d28d9);">
                                        <i class="material-icons">savings</i>
                                    </div>
                                    <div>
                                        <h5 class="font-weight-bold m-0" style="font-size: 0.95rem; color: #0f172a;">Laporan Tabungan Global</h5>
                                        <p class="text-muted m-0" style="font-size: 0.78rem;">Rekapitulasi setoran, tarikan, &amp; saldo per kelas</p>
                                        <span class="text-purple font-weight-bold" style="font-size: 0.75rem; color: #7c3aed;">Lihat Rekapitulasi &rarr;</span>
                                    </div>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
