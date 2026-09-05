@extends('layouts.admin')

@section('styles')
<style>
    .dashboard-wrapper {
        padding: 10px 0;
        min-height: 100vh;
        background-color: #f1f5f9;
    }

    .chart-container {
        position: relative;
        height: 210px;
        width: 100%;
    }

    /* Stat Cards */
    .stat-card {
        border: none !important;
        border-radius: 16px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: all 0.2s ease-in-out;
        margin-bottom: 4px;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .stat-card .card-body {
        padding: 20px !important;
    }

    .stat-flex {
        display: flex;
        align-items: center;
        width: 100%;
    }

    .stat-icon-box {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 16px;
        flex-shrink: 0;
        transition: transform 0.2s ease;
    }

    .stat-icon-box i {
        font-size: 26px;
        color: #ffffff;
    }

    .stat-info {
        flex-grow: 1;
        overflow: hidden;
    }

    .stat-category {
        color: #64748b;
        font-size: 0.8rem;
        font-weight: 700;
        margin-bottom: 2px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-title {
        color: #0f172a;
        margin: 0;
        font-weight: 800;
        font-size: 1.6rem;
        line-height: 1.2;
    }

    /* Card Themes */
    .card-purple .stat-icon-box { background: linear-gradient(135deg, #a855f7, #7e22ce); box-shadow: 0 4px 14px rgba(168, 85, 247, 0.35); }
    .card-emerald .stat-icon-box { background: linear-gradient(135deg, #10b981, #047857); box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35); }
    .card-sky .stat-icon-box { background: linear-gradient(135deg, #0284c7, #0369a1); box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35); }
    .card-rose .stat-icon-box { background: linear-gradient(135deg, #f43f5e, #be123c); box-shadow: 0 4px 14px rgba(244, 63, 94, 0.35); }

    .stat-card .card-footer {
        padding: 10px 20px !important;
        border-top: 1px solid #f1f5f9 !important;
        background: #f8fafc !important;
        font-size: 0.78rem;
        color: #64748b;
        font-weight: 600;
    }

    /* Section Cards */
    .section-card {
        border: none !important;
        border-radius: 14px !important;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05) !important;
        background: #ffffff !important;
        margin-bottom: 0;
        overflow: hidden;
    }

    .section-card .card-header-primary {
        background: linear-gradient(135deg, #0284c7, #0369a1) !important;
        border-radius: 14px 14px 0 0 !important;
        padding: 12px 16px !important;
        color: #ffffff;
    }

    .section-card .card-header-success {
        background: linear-gradient(135deg, #059669, #047857) !important;
        border-radius: 14px 14px 0 0 !important;
        padding: 12px 16px !important;
        color: #ffffff;
    }

    .section-card .card-title {
        font-weight: 700;
        font-size: 0.95rem;
        margin: 0;
        color: #ffffff;
    }

    .section-card .card-category {
        font-size: 0.72rem;
        opacity: 0.85;
        margin: 1px 0 0 0;
        color: #e0f2fe;
    }

    .custom-select-dashboard {
        background-color: rgba(255, 255, 255, 0.95) !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        border-radius: 10px !important;
        padding: 6px 14px !important;
        font-size: 0.82rem !important;
        font-weight: 700 !important;
        color: #0f172a !important;
        outline: none !important;
        cursor: pointer;
    }

    /* ══════════════════════════════════════
       STAT CARDS — Proporsional & Kotak
       Layout: Vertikal (ikon atas, data bawah)
       ══════════════════════════════════════ */
    .stat-kpi-row {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        margin-bottom: 20px;
    }

    .stat-kpi-col {
        flex: 1 1 0;
        min-width: 120px;
    }

    .stat-kpi-card {
        border: none !important;
        border-radius: 14px !important;
        background: #ffffff !important;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.07) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        overflow: hidden;
        text-decoration: none !important;
        display: block;
        cursor: pointer;
    }

    .stat-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.11) !important;
    }

    .stat-kpi-inner {
        padding: 16px 14px 14px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .stat-kpi-icon {
        width: 40px;
        height: 40px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.20);
    }

    .stat-kpi-icon i {
        color: #ffffff;
        font-size: 20px;
    }

    .stat-kpi-text {
        width: 100%;
    }

    .stat-kpi-label {
        color: #94a3b8;
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin: 0 0 3px 0;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .stat-kpi-value {
        color: #1e293b;
        font-size: 1.5rem;
        font-weight: 800;
        margin: 0;
        line-height: 1;
        letter-spacing: -0.5px;
    }

    .stat-kpi-value.is-currency {
        font-size: 1.0rem;
        letter-spacing: -0.3px;
    }

    .stat-kpi-accent {
        display: block;
        height: 3px;
        border-radius: 0 0 14px 14px;
        margin-top: 2px;
    }

    a.stat-kpi-card:hover .stat-kpi-label {
        color: #64748b;
    }
</style>
@endsection

@section('content')
<div class="content dashboard-wrapper">
    <div class="container-fluid">

        <!-- 1. RINGKASAN INFO — 6 kartu KPI proporsional sejajar -->
        <div class="stat-kpi-row">

            {{-- Siswa --}}
            <div class="stat-kpi-col">
                <a href="{{ url('admin/siswa') }}" class="stat-kpi-card">
                    <div class="stat-kpi-inner">
                        <div class="stat-kpi-icon" style="background: linear-gradient(135deg, #a855f7, #7c3aed);">
                            <i class="material-icons">person</i>
                        </div>
                        <div class="stat-kpi-text">
                            <p class="stat-kpi-label">Siswa</p>
                            <h4 class="stat-kpi-value">{{ number_format($totalSiswa) }}</h4>
                        </div>
                    </div>
                    <span class="stat-kpi-accent" style="background: linear-gradient(90deg, #a855f7, #7c3aed);"></span>
                </a>
            </div>

            {{-- Ustadzah --}}
            <div class="stat-kpi-col">
                <a href="{{ url('admin/guru') }}" class="stat-kpi-card">
                    <div class="stat-kpi-inner">
                        <div class="stat-kpi-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                            <i class="material-icons">person_4</i>
                        </div>
                        <div class="stat-kpi-text">
                            <p class="stat-kpi-label">Ustadzah</p>
                            <h4 class="stat-kpi-value">{{ number_format($totalGuru) }}</h4>
                        </div>
                    </div>
                    <span class="stat-kpi-accent" style="background: linear-gradient(90deg, #10b981, #059669);"></span>
                </a>
            </div>

            {{-- Kelas --}}
            <div class="stat-kpi-col">
                <a href="{{ url('admin/kelas') }}" class="stat-kpi-card">
                    <div class="stat-kpi-inner">
                        <div class="stat-kpi-icon" style="background: linear-gradient(135deg, #38bdf8, #0284c7);">
                            <i class="material-icons">grade</i>
                        </div>
                        <div class="stat-kpi-text">
                            <p class="stat-kpi-label">Kelas</p>
                            <h4 class="stat-kpi-value">{{ number_format($totalKelas) }}</h4>
                        </div>
                    </div>
                    <span class="stat-kpi-accent" style="background: linear-gradient(90deg, #38bdf8, #0284c7);"></span>
                </a>
            </div>

            {{-- Petugas --}}
            <div class="stat-kpi-col">
                <a href="{{ url('admin/petugas') }}" class="stat-kpi-card">
                    <div class="stat-kpi-inner">
                        <div class="stat-kpi-icon" style="background: linear-gradient(135deg, #fb7185, #f43f5e);">
                            <i class="material-icons">settings</i>
                        </div>
                        <div class="stat-kpi-text">
                            <p class="stat-kpi-label">Petugas</p>
                            <h4 class="stat-kpi-value">{{ number_format($totalPetugas) }}</h4>
                        </div>
                    </div>
                    <span class="stat-kpi-accent" style="background: linear-gradient(90deg, #fb7185, #f43f5e);"></span>
                </a>
            </div>

            {{-- Kas Tabungan --}}
            <div class="stat-kpi-col">
                <div class="stat-kpi-card" style="cursor: default;">
                    <div class="stat-kpi-inner">
                        <div class="stat-kpi-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                            <i class="material-icons">account_balance</i>
                        </div>
                        <div class="stat-kpi-text">
                            <p class="stat-kpi-label">Kas Tabungan</p>
                            <h4 class="stat-kpi-value is-currency">Rp {{ number_format($totalKasTabungan, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                    <span class="stat-kpi-accent" style="background: linear-gradient(90deg, #10b981, #059669);"></span>
                </div>
            </div>

            {{-- Dana di Guru --}}
            <div class="stat-kpi-col">
                <div class="stat-kpi-card" style="cursor: default;">
                    <div class="stat-kpi-inner">
                        <div class="stat-kpi-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                            <i class="material-icons">front_hand</i>
                        </div>
                        <div class="stat-kpi-text">
                            <p class="stat-kpi-label">Dana di Guru</p>
                            <h4 class="stat-kpi-value is-currency">Rp {{ number_format($totalBelumDisetor, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                    <span class="stat-kpi-accent" style="background: linear-gradient(90deg, #f59e0b, #d97706);"></span>
                </div>
            </div>

        </div>

        <!-- 2. TODAY ATTENDANCE SECTION -->
        <div class="row g-2 mb-2">
            <!-- ABSENSI SISWA HARI INI -->
            <div class="col-lg-6">
                <div class="card section-card">
                    <div class="card-header card-header-primary">
                        <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap: 8px;">
                            <div>
                                <h4 class="card-title" id="titleSiswaStats">Absensi Siswa Hari Ini</h4>
                                <p class="card-category">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</p>
                            </div>
                            <!-- FILTER KELAS -->
                            <div class="d-flex align-items-center gap-2">
                                <div id="filterLoader" style="display: none;" class="mr-2">
                                    <div class="spinner-border spinner-border-sm text-white" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                                <select name="id_kelas" id="filterKelas" class="custom-select-dashboard">
                                    <option value="">Semua Kelas ({{ $totalSiswa }} siswa)</option>
                                    @foreach($kelases as $k)
                                        <option value="{{ $k->id_kelas }}" data-kelas="{{ $k->kelas }}">
                                            {{ $k->kelas }} ({{ $k->siswa_count }} siswa)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0" id="siswaStatsContainer">
                        @include('admin._dashboard_siswa_stats', [
                            'hadir' => $jumlahKehadiranSiswa['hadir'],
                            'sakit' => $jumlahKehadiranSiswa['sakit'],
                            'izin' => $jumlahKehadiranSiswa['izin'],
                            'alfa' => $jumlahKehadiranSiswa['alfa'],
                            'totalSiswa' => $totalSiswa
                        ])
                    </div>
                </div>
            </div>

            <!-- ABSENSI USTADZAH HARI INI -->
            <div class="col-lg-6">
                <div class="card section-card">
                    <div class="card-header card-header-success">
                        <h4 class="card-title">Absensi Ustadzah Hari Ini</h4>
                        <p class="card-category">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</p>
                    </div>
                    <div class="card-body p-0">
                        <div class="px-3 py-3">
                            <div class="row text-center m-0 g-2">
                                <div class="col-3 px-1">
                                    <div style="background: rgba(22, 163, 74, 0.08); border-radius: 10px; padding: 10px 6px;">
                                        <h5 class="text-nowrap m-0 pb-1" style="color: #16a34a; font-size: 10px; font-weight: 800; letter-spacing: 1px;">HADIR</h5>
                                        <h4 class="text-dark font-weight-bold m-0" style="font-size: 22px; line-height: 1;">{{ $jumlahKehadiranGuru['hadir'] }}</h4>
                                    </div>
                                </div>
                                <div class="col-3 px-1">
                                    <div style="background: rgba(147, 51, 234, 0.08); border-radius: 10px; padding: 10px 6px;">
                                        <h5 class="text-nowrap m-0 pb-1" style="color: #9333ea; font-size: 10px; font-weight: 800; letter-spacing: 1px;">SAKIT</h5>
                                        <h4 class="text-dark font-weight-bold m-0" style="font-size: 22px; line-height: 1;">{{ $jumlahKehadiranGuru['sakit'] }}</h4>
                                    </div>
                                </div>
                                <div class="col-3 px-1">
                                    <div style="background: rgba(37, 99, 235, 0.08); border-radius: 10px; padding: 10px 6px;">
                                        <h5 class="text-nowrap m-0 pb-1" style="color: #2563eb; font-size: 10px; font-weight: 800; letter-spacing: 1px;">IZIN</h5>
                                        <h4 class="text-dark font-weight-bold m-0" style="font-size: 22px; line-height: 1;">{{ $jumlahKehadiranGuru['izin'] }}</h4>
                                    </div>
                                </div>
                                <div class="col-3 px-1">
                                    <div style="background: rgba(220, 38, 38, 0.08); border-radius: 10px; padding: 10px 6px;">
                                        <h5 class="text-nowrap m-0 pb-1" style="color: #dc2626; font-size: 10px; font-weight: 800; letter-spacing: 1px;">ALFA</h5>
                                        <h4 class="text-dark font-weight-bold m-0" style="font-size: 22px; line-height: 1;">{{ $jumlahKehadiranGuru['alfa'] }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-transparent py-2 px-3 d-flex justify-content-between align-items-center" style="border-top: 1px solid #f1f5f9 !important;">
                            <span class="text-muted font-weight-bold m-0" style="font-size: 10px; letter-spacing: 1px; text-transform: uppercase;">Total Pengajar</span>
                            <span class="text-dark font-weight-bold m-0" style="font-size: 12px; line-height: 1;">{{ $totalGuru }} Ustadzah</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. ATTENDANCE TREND CHARTS -->
        <div class="row g-2 mb-2">
            <!-- CHART SISWA -->
            <div class="col-lg-6">
                <div class="card section-card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title" id="titleSiswaChart">Kehadiran Siswa — 7 Hari Terakhir</h4>
                        <p class="card-category">Grafik rekapitulasi presensi siswa</p>
                    </div>
                    <div class="card-body p-2">
                        <div class="chart-container" style="position: relative;">
                            @if(empty($chartSiswa) || collect($chartSiswa)->flatten()->sum() === 0)
                                <div class="d-flex flex-column align-items-center justify-content-center h-100 w-100 text-muted" style="position: absolute; top:0; left:0; z-index: 10;">
                                    <i class="material-icons" style="font-size: 40px; color: #cbd5e1; margin-bottom: 6px;">bar_chart</i>
                                    <p style="font-weight: 600; font-size: 13px; color: #94a3b8;">Belum ada data presensi siswa</p>
                                </div>
                            @endif
                            <canvas id="kehadiranSiswa"></canvas>
                        </div>
                    </div>
                    @if (auth()->user() && auth()->user()->is_superadmin != 1)
                    <div class="card-footer bg-light px-3 py-1" style="border-top: 1px solid #f1f5f9;">
                        <a class="text-primary font-weight-bold" style="font-size: 12px;" href="{{ url('admin/absen-siswa') }}">
                            <i class="material-icons text-primary mr-1" style="font-size: 14px; vertical-align: middle;">checklist</i>
                            Detail Presensi Siswa &rarr;
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- CHART USTADZAH -->
            <div class="col-lg-6">
                <div class="card section-card">
                    <div class="card-header card-header-success">
                        <h4 class="card-title">Kehadiran Ustadzah — 7 Hari Terakhir</h4>
                        <p class="card-category">Grafik rekapitulasi presensi ustadzah</p>
                    </div>
                    <div class="card-body p-2">
                        <div class="chart-container" style="position: relative;">
                            @if(empty($chartGuru) || collect($chartGuru)->flatten()->sum() === 0)
                                <div class="d-flex flex-column align-items-center justify-content-center h-100 w-100 text-muted" style="position: absolute; top:0; left:0; z-index: 10;">
                                    <i class="material-icons" style="font-size: 40px; color: #cbd5e1; margin-bottom: 6px;">bar_chart</i>
                                    <p style="font-weight: 600; font-size: 13px; color: #94a3b8;">Belum ada data presensi ustadzah</p>
                                </div>
                            @endif
                            <canvas id="kehadiranGuru"></canvas>
                        </div>
                    </div>
                    @if (auth()->user() && auth()->user()->is_superadmin != 1)
                    <div class="card-footer bg-light px-3 py-1" style="border-top: 1px solid #f1f5f9;">
                        <a class="font-weight-bold" style="font-size: 12px; color: #047857;" href="{{ url('admin/absen-guru') }}">
                            <i class="material-icons text-success mr-1" style="font-size: 14px; vertical-align: middle;">checklist</i>
                            Detail Presensi Ustadzah &rarr;
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script src="{{ url('assets/js/plugins/chartjs/chart.umd.min.js') }}"></script>
<script>
    let kehadiranSiswaChart = null;
    let kehadiranGuruChart = null;

    const chartLabels = @json($chartLabels);
    const initialChartSiswa = @json($chartSiswa);
    const initialChartGuru = @json($chartGuru);

    const chartColors = {
        hadir: { border: '#16a34a', bg: 'rgba(22, 163, 74, 0.85)' },
        sakit: { border: '#9333ea', bg: 'rgba(147, 51, 234, 0.85)' },
        izin:  { border: '#2563eb', bg: 'rgba(37, 99, 235, 0.85)' },
        alfa:  { border: '#dc2626', bg: 'rgba(220, 38, 38, 0.85)' }
    };

    function createChartConfig(labels, data) {
        return {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Hadir',
                        data: data.hadir,
                        borderColor: chartColors.hadir.border,
                        backgroundColor: chartColors.hadir.bg,
                        borderRadius: 6,
                    },
                    {
                        label: 'Sakit',
                        data: data.sakit,
                        borderColor: chartColors.sakit.border,
                        backgroundColor: chartColors.sakit.bg,
                        borderRadius: 6,
                    },
                    {
                        label: 'Izin',
                        data: data.izin,
                        borderColor: chartColors.izin.border,
                        backgroundColor: chartColors.izin.bg,
                        borderRadius: 6,
                    },
                    {
                        label: 'Alfa',
                        data: data.alfa,
                        borderColor: chartColors.alfa.border,
                        backgroundColor: chartColors.alfa.bg,
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 16,
                            font: { size: 12, weight: 'bold' }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        padding: 12,
                        cornerRadius: 10,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y + ' orang';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0,
                            callback: function(value) {
                                if (Number.isInteger(value)) return value;
                            }
                        },
                        grid: { color: 'rgba(241, 245, 249, 1)' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        };
    }

    function updateSiswaChart(newData) {
        if (kehadiranSiswaChart && newData) {
            kehadiranSiswaChart.data.datasets[0].data = newData.hadir || [];
            kehadiranSiswaChart.data.datasets[1].data = newData.sakit || [];
            kehadiranSiswaChart.data.datasets[2].data = newData.izin || [];
            kehadiranSiswaChart.data.datasets[3].data = newData.alfa || [];
            kehadiranSiswaChart.update();
        }
    }

    function initDashboardCharts() {
        const siswaCanvas = document.getElementById('kehadiranSiswa');
        if (siswaCanvas) {
            kehadiranSiswaChart = new Chart(siswaCanvas, createChartConfig(chartLabels, initialChartSiswa));
        }

        const guruCanvas = document.getElementById('kehadiranGuru');
        if (guruCanvas) {
            kehadiranGuruChart = new Chart(guruCanvas, createChartConfig(chartLabels, initialChartGuru));
        }
    }

    $(document).ready(function() {
        initDashboardCharts();

        $('#filterKelas').on('change', function() {
            const idKelas = $(this).val();
            const loader = $('#filterLoader');
            loader.show();

            $.ajax({
                url: '{{ route("admin.dashboard.filter-data") }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id_kelas: idKelas,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    const data = (typeof response === 'string') ? JSON.parse(response) : response;
                    if (data.result === 1) {
                        $('#siswaStatsContainer').html(data.htmlContent);
                        if (data.chartData) {
                            updateSiswaChart(data.chartData);
                        }

                        const className = $('#filterKelas option:selected').attr('data-kelas');
                        if (idKelas === "") {
                            $('#titleSiswaStats').text("Absensi Siswa Hari Ini");
                            $('#titleSiswaChart').text("Tingkat Kehadiran Siswa (7 Hari Terakhir)");
                        } else {
                            $('#titleSiswaStats').text("Absensi Siswa Kelas " + className + " Hari Ini");
                            $('#titleSiswaChart').text("Tingkat Kehadiran Siswa Kelas " + className + " (7 Hari Terakhir)");
                        }
                    }
                },
                error: function(xhr, status, thrown) {
                    console.error("Filter error:", thrown);
                },
                complete: function() {
                    loader.hide();
                }
            });
        });
    });
</script>
@endsection
