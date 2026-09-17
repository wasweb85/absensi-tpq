@extends('layouts.laporan')

@section('content')
@php 
   $logoImg = (!empty($generalSettings->logo) && file_exists(public_path('uploads/logo/' . $generalSettings->logo))) 
      ? asset('uploads/logo/' . $generalSettings->logo) 
      : (file_exists(public_path('assets/img/logo-sekolah.jpg')) ? asset('assets/img/logo-sekolah.jpg') : asset('assets/img/logo-tpq.png'));

   $totalSantriSemua = array_sum(array_column($laporanKelas, 'santri_count'));
   $totalSetorSemua = array_sum(array_column($laporanKelas, 'setor_bulan_ini'));
   $totalTarikSemua = array_sum(array_column($laporanKelas, 'tarik_bulan_ini'));
   $totalSaldoSemua = array_sum(array_column($laporanKelas, 'saldo_akhir'));
   $totalMengendapSemua = array_sum(array_column($laporanKelas, 'dana_mengendap'));
@endphp

<style>
   @media print {
      .no-print {
         display: none !important;
      }
      body {
         background: #fff !important;
         padding: 0 !important;
      }
      .page-container {
         box-shadow: none !important;
         border: none !important;
         padding: 0 !important;
         width: 100% !important;
      }
   }

   .no-print-bar {
      background: #1e293b;
      color: #fff;
      padding: 10px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 16px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.15);
   }

   .btn-print {
      background: #2563eb;
      color: #ffffff;
      border: none;
      padding: 8px 18px;
      border-radius: 6px;
      font-weight: 600;
      font-size: 13px;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 6px;
   }
   .btn-print:hover {
      background: #1d4ed8;
   }
   .btn-back {
      background: #475569;
      color: #ffffff;
      border: none;
      padding: 8px 16px;
      border-radius: 6px;
      font-weight: 600;
      font-size: 13px;
      text-decoration: none;
   }
   .btn-back:hover {
      background: #334155;
   }

   .page-container {
      background: #ffffff;
      padding: 10px 15px;
      color: #0f172a;
      font-size: 11.5px;
   }

   /* Header Kop */
   .header-box {
      position: relative;
      margin-bottom: 14px;
      min-height: 65px;
      border-bottom: 2.5px solid #0f172a;
      padding-bottom: 10px;
   }
   .header-logo {
      position: absolute;
      left: 0;
      top: 0;
   }
   .header-logo img {
      max-height: 60px;
      max-width: 80px;
      object-fit: contain;
   }
   .header-title {
      text-align: center;
      width: 100%;
   }
   .header-title h2 {
      margin: 0;
      font-size: 16px;
      font-weight: 800;
      color: #0f172a;
      letter-spacing: 0.5px;
      text-transform: uppercase;
   }
   .header-title h3 {
      margin: 3px 0 0 0;
      font-size: 13.5px;
      font-weight: 700;
      color: #1e3a8a;
      text-transform: uppercase;
   }
   .header-title p {
      margin: 2px 0 0 0;
      font-size: 11px;
      font-weight: 600;
      color: #475569;
      text-transform: uppercase;
   }

   /* Executive Summary Cards */
   .summary-cards-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 10px;
      margin-bottom: 12px;
   }
   .card-summary {
      border: 1px solid #cbd5e1;
      border-radius: 6px;
      padding: 8px 12px;
      background: #f8fafc;
   }
   .card-summary.accent-blue {
      border-left: 4px solid #0284c7;
      background: #f0f9ff;
   }
   .card-summary.accent-emerald {
      border-left: 4px solid #059669;
      background: #ecfdf5;
   }
   .card-summary.accent-rose {
      border-left: 4px solid #e11d48;
      background: #fff1f2;
   }
   .card-summary.accent-navy {
      border-left: 4px solid #1e3a8a;
      background: #eff6ff;
   }
   .card-title {
      font-size: 10px;
      font-weight: 700;
      text-transform: uppercase;
      color: #64748b;
      margin-bottom: 3px;
   }
   .card-value {
      font-size: 14px;
      font-weight: 800;
      color: #0f172a;
   }

   /* Accountability Box */
   .accountability-box {
      border: 1px dashed #94a3b8;
      background: #f8fafc;
      border-radius: 6px;
      padding: 8px 14px;
      margin-bottom: 14px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 11px;
   }
   .accountability-box .badge {
      display: inline-block;
      padding: 2px 8px;
      border-radius: 4px;
      font-weight: 700;
      font-size: 10px;
   }
   .badge-success {
      background: #dcfce7;
      color: #15803d;
   }
   .badge-warning {
      background: #fef3c7;
      color: #b45309;
   }

   /* Table styling */
   .table-laporan {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
   }
   .table-laporan th, .table-laporan td {
      border: 1px solid #94a3b8;
      padding: 6px 8px;
      font-size: 11px;
   }
   .table-laporan th {
      background-color: #f1f5f9;
      color: #0f172a;
      font-weight: 700;
      text-align: center;
   }
   .table-laporan tbody tr:nth-child(even) {
      background-color: #fcfcfd;
   }
   .table-laporan tfoot tr {
      background-color: #f8fafc;
      font-weight: 800;
   }

   /* Signatures */
   .signature-container {
      display: flex;
      justify-content: space-between;
      margin-top: 25px;
      page-break-inside: avoid;
   }
   .signature-box {
      width: 250px;
      text-align: center;
      font-size: 11px;
   }
   .signature-space {
      height: 60px;
   }
   .signature-name {
      font-weight: 800;
      text-decoration: underline;
      color: #0f172a;
   }
   .signature-role {
      font-size: 10.5px;
      color: #475569;
      margin-top: 2px;
   }
</style>

<div class="no-print-bar no-print">
   <div>
      <span style="font-weight: 700; font-size: 14px;">🖨️ Cetak Dokumen Rekapitulasi Tabungan</span>
      <span style="color: #94a3b8; font-size: 12px; margin-left: 8px;">(Format resmi A4 Landscape)</span>
   </div>
   <div style="display: flex; gap: 8px;">
      <a href="{{ url()->previous() }}" class="btn-back">← Kembali</a>
      <button onclick="window.print()" class="btn-print">Cetak Laporan (Print / PDF)</button>
   </div>
</div>

<div class="page-container">
   <!-- Header Kop -->
   <div class="header-box">
      <div class="header-logo">
         <img src="{{ $logoImg }}" alt="Logo">
      </div>
      <div class="header-title">
         <h2>LAPORAN REKAPITULASI KEUANGAN TABUNGAN SANTRI</h2>
         <h3>{{ strtoupper($generalSettings->school_name ?? 'TPQ DARUL HUDA') }}</h3>
         <p>PERIODE: {{ strtoupper($periodeLabel) }} | TAHUN AJARAN {{ strtoupper($generalSettings->school_year ?? '2026/2027') }}</p>
      </div>
   </div>

   <!-- Executive Summary Cards -->
   <div class="summary-cards-grid">
      <div class="card-summary accent-blue">
         <div class="card-title">Saldo Awal Periode</div>
         <div class="card-value">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</div>
      </div>
      <div class="card-summary accent-emerald">
         <div class="card-title">Setoran Masuk (+)</div>
         <div class="card-value" style="color: #059669;">Rp {{ number_format($totalSetoran, 0, ',', '.') }}</div>
      </div>
      <div class="card-summary accent-rose">
         <div class="card-title">Penarikan (-)</div>
         <div class="card-value" style="color: #e11d48;">Rp {{ number_format($totalPenarikan, 0, ',', '.') }}</div>
      </div>
      <div class="card-summary accent-navy">
         <div class="card-title">Total Saldo Akhir (=)</div>
         <div class="card-value" style="color: #1e3a8a;">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</div>
      </div>
   </div>

   <!-- Accountability Box -->
   <div class="accountability-box">
      <div>
         <strong>Akuntabilitas Fisik Kas TPQ:</strong>
         <span style="margin-left: 12px;">
            Fisik Kas di Bendahara: <b style="color: #15803d;">Rp {{ number_format($kasDiBendahara, 0, ',', '.') }}</b> <span class="badge badge-success">Lunas Disetor</span>
         </span>
         <span style="margin-left: 16px;">
            Dana Mengendap di Guru/Wali Kelas: <b style="color: {{ $danaMengendap > 0 ? '#b45309' : '#15803d' }};">Rp {{ number_format($danaMengendap, 0, ',', '.') }}</b> 
            @if($danaMengendap > 0)
               <span class="badge badge-warning">Belum Disetor</span>
            @else
               <span class="badge badge-success">Nihil</span>
            @endif
         </span>
      </div>
      <div style="color: #64748b; font-style: italic; font-size: 10px;">
         *Dicetak otomatis per tanggal {{ $tanggalCetak }}
      </div>
   </div>

   <!-- Table Rekap Per Kelas -->
   <table class="table-laporan">
      <thead>
         <tr>
            <th width="35">No</th>
            <th width="110">Kelas</th>
            <th>Ustadz / Wali Kelas</th>
            <th width="75">Santri</th>
            <th width="125">Setoran Masuk</th>
            <th width="125">Penarikan</th>
            <th width="135">Saldo Akhir</th>
            <th width="145">Status Setoran Guru</th>
         </tr>
      </thead>
      <tbody>
         @forelse($laporanKelas as $index => $row)
         <tr>
            <td align="center">{{ $index + 1 }}</td>
            <td align="center"><b>{{ $row['nama_kelas'] }}</b></td>
            <td>{{ $row['nama_guru'] }}</td>
            <td align="center">{{ $row['santri_count'] }}</td>
            <td align="right">Rp {{ number_format($row['setor_bulan_ini'], 0, ',', '.') }}</td>
            <td align="right">Rp {{ number_format($row['tarik_bulan_ini'], 0, ',', '.') }}</td>
            <td align="right" style="font-weight: 700;">Rp {{ number_format($row['saldo_akhir'], 0, ',', '.') }}</td>
            <td align="center">
               @if($row['dana_mengendap'] > 0)
                  <span style="color: #b45309; font-weight: 600;">Mengendap Rp {{ number_format($row['dana_mengendap'], 0, ',', '.') }}</span>
               @else
                  <span style="color: #15803d; font-weight: 600;">Lunas Disetor</span>
               @endif
            </td>
         </tr>
         @empty
         <tr>
            <td colspan="8" align="center" style="padding: 15px; color: #64748b;">Belum ada data kelas yang terdaftar.</td>
         </tr>
         @endforelse
      </tbody>
      <tfoot>
         <tr>
            <td colspan="3" align="center">TOTAL KESELURUHAN</td>
            <td align="center">{{ $totalSantriSemua }}</td>
            <td align="right">Rp {{ number_format($totalSetorSemua, 0, ',', '.') }}</td>
            <td align="right">Rp {{ number_format($totalTarikSemua, 0, ',', '.') }}</td>
            <td align="right" style="color: #1e3a8a;">Rp {{ number_format($totalSaldoSemua, 0, ',', '.') }}</td>
            <td align="center">
               @if($totalMengendapSemua > 0)
                  <span style="color: #b45309;">Total Mengendap: Rp {{ number_format($totalMengendapSemua, 0, ',', '.') }}</span>
               @else
                  <span style="color: #15803d;">Semua Kas Tertib Disetor</span>
               @endif
            </td>
         </tr>
      </tfoot>
   </table>

   <!-- Signature Box -->
   <div class="signature-container">
      <div class="signature-box">
         <div>Mengetahui / Menyetujui,</div>
         <div style="font-weight: 700; margin-top: 2px;">Kepala TPQ</div>
         <div class="signature-space"></div>
         <div class="signature-name">{{ $namaKepala }}</div>
         <div class="signature-role">{{ !empty($niupKepala) ? 'NIUP: ' . $niupKepala : 'Kepala Sekolah / TPQ' }}</div>
      </div>

      <div class="signature-box">
         <div>{{ $generalSettings->city ?? 'Jombang' }}, {{ $tanggalCetak }}</div>
         <div style="font-weight: 700; margin-top: 2px;">Dibuat Oleh: Bendahara TPQ</div>
         <div class="signature-space"></div>
         <div class="signature-name">{{ $namaBendahara }}</div>
         <div class="signature-role">{{ !empty($niupBendahara) ? 'NIUP: ' . $niupBendahara : 'Bendahara TPQ' }}</div>
      </div>
   </div>
</div>
@endsection
