@extends('layouts.laporan')

@section('content')
@php 
   $logoImg = (!empty($appSettings->logo) && file_exists(public_path('uploads/logo/' . $appSettings->logo))) 
      ? asset('uploads/logo/' . $appSettings->logo) 
      : asset('assets/img/logo-sekolah.jpg');
@endphp
<div style="position: relative; margin-bottom: 12px; min-height: 70px;">
   <div style="position: absolute; left: 0; top: 0;">
      <img src="{{ $logoImg }}" style="max-height: 65px; max-width: 85px; object-fit: contain;">
   </div>
   <div style="text-align: center; width: 100%;">
      <h2 style="margin: 0; font-size: 16px; font-weight: 800; color: #0f172a; letter-spacing: 0.5px;">DAFTAR HADIR USTADZ &amp; USTADZAH</h2>
      <h4 style="margin: 3px 0 0 0; font-size: 13.5px; font-weight: 700; color: #1e3a8a;">{{ strtoupper($appSettings->school_name ?? ($generalSettings->school_name ?? 'TPQ Darul Huda')) }}</h4>
      <h4 style="margin: 2px 0 0 0; font-size: 11.5px; font-weight: 600; color: #475569;">TAHUN PELAJARAN {{ strtoupper($appSettings->school_year ?? ($generalSettings->school_year ?? '2026/2027')) }}</h4>
   </div>
</div>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; font-size: 12px; font-weight: 700;">
   <span>Bulan / Periode : {{ $bulan }}</span>
   <span>Unit : TPQ</span>
</div>
<table align="center" border="1">
   <thead>
      <td></td>
      <td></td>
      <th colspan="{{ count($tanggal) }}">Hari/Tanggal</th>
   </thead>
   <thead>
      <td></td>
      <td></td>
      @foreach ($tanggal as $value)
         @php 
            $d = $value->date ?? $value;
            $isLiburCol = !empty($value->is_libur);
         @endphp
         <th align="center" style="{{ $isLiburCol ? 'background-color: #fee2e2; color: #b91c1c;' : '' }}">
            <b>{{ $d->translatedFormat('D') }}</b>
         </th>
      @endforeach
      <td colspan="4" align="center">Total</td>
   </thead>
   <tr>
      <th align="center">No</th>
      <th width="1000px">Nama</th>
      @foreach ($tanggal as $value)
         @php 
            $d = $value->date ?? $value;
            $isLiburCol = !empty($value->is_libur);
         @endphp
         <th align="center" style="{{ $isLiburCol ? 'background-color: #fee2e2; color: #b91c1c;' : '' }}" title="{{ $value->keterangan_libur ?? '' }}">
            {{ $d->format('d') }}
         </th>
      @endforeach
      <th align="center" style="background-color:lightgreen;">H</th>
      <th align="center" style="background-color:yellow;">S</th>
      <th align="center" style="background-color:yellow;">I</th>
      <th align="center" style="background-color:red; color:white;">A</th>
   </tr>

   @php $i = 0; @endphp

   @foreach ($listGuru as $guru)
      @php
      $jumlahHadir = count(array_filter($listAbsen, function ($a) use ($i) {
         if ($a['lewat'] || is_null($a[$i]['id_kehadiran'])) return false;
         return $a[$i]['id_kehadiran'] == 1;
      }));
      $jumlahSakit = count(array_filter($listAbsen, function ($a) use ($i) {
         if ($a['lewat'] || is_null($a[$i]['id_kehadiran'])) return false;
         return $a[$i]['id_kehadiran'] == 2;
      }));
      $jumlahIzin = count(array_filter($listAbsen, function ($a) use ($i) {
         if ($a['lewat'] || is_null($a[$i]['id_kehadiran'])) return false;
         return $a[$i]['id_kehadiran'] == 3;
      }));
      $jumlahTidakHadir = count(array_filter($listAbsen, function ($a) use ($i) {
         if (!empty($a['status_libur'])) return false;
         if ($a['lewat']) return false;
         if (is_null($a[$i]['id_kehadiran']) || $a[$i]['id_kehadiran'] == 4) return true;
         return false;
      }));
      @endphp
      <tr>
         <td align="center">{{ $i + 1 }}</td>
         <td>{{ $guru->nama_guru }}</td>
         @foreach ($listAbsen as $absen)
            @if (!empty($absen['status_libur']))
               <td align="center" style="background-color: #fee2e2; color: #b91c1c; font-weight: bold;" title="{{ $absen['keterangan_libur'] ?? 'Libur' }}">L</td>
            @else
               @php
                  $kehadiran = $absen[$i]['id_kehadiran'] ?? ($absen['lewat'] ? 5 : 4);
                  $text = "<td></td>";
                  if ($kehadiran == 1) $text = "<td align='center' style='background-color:lightgreen;'>H</td>";
                  if ($kehadiran == 2) $text = "<td align='center' style='background-color:yellow;'>S</td>";
                  if ($kehadiran == 3) $text = "<td align='center' style='background-color:yellow;'>I</td>";
                  if ($kehadiran == 4) $text = "<td align='center' style='background-color:red; color:white;'>A</td>";
               @endphp
               {!! $text !!}
            @endif
         @endforeach
         <td align="center">
            {{ $jumlahHadir != 0 ? $jumlahHadir : '-' }}
         </td>
         <td align="center">
            {{ $jumlahSakit != 0 ? $jumlahSakit : '-' }}
         </td>
         <td align="center">
            {{ $jumlahIzin != 0 ? $jumlahIzin : '-' }}
         </td>
         <td align="center">
            {{ $jumlahTidakHadir != 0 ? $jumlahTidakHadir : '-' }}
         </td>
      </tr>
      @php $i++; @endphp
   @endforeach

</table>
<br></br>
<table>
   <tr>
      <td>Jumlah guru</td>
      <td>: {{ count($listGuru) }}</td>
   </tr>
   <tr>
      <td>Laki-laki</td>
      <td>: {{ $jumlahGuru['laki'] ?? 0 }}</td>
   </tr>
   <tr>
      <td>Perempuan</td>
      <td>: {{ $jumlahGuru['perempuan'] ?? 0 }}</td>
   </tr>
</table>

<table style="margin-top: 15px; font-size: 11px;">
   <tr>
      <td colspan="4"><b>Keterangan Status Kehadiran:</b></td>
   </tr>
   <tr>
      <td width="20px" align="center" style="background-color:lightgreen;"><b>H</b></td>
      <td>: Hadir</td>
      <td width="20px" align="center" style="background-color:yellow;"><b>S</b></td>
      <td>: Sakit</td>
   </tr>
   <tr>
      <td width="20px" align="center" style="background-color:yellow;"><b>I</b></td>
      <td>: Izin</td>
      <td width="20px" align="center" style="background-color:red; color:white;"><b>A</b></td>
      <td>: Alpa / Tidak Hadir</td>
   </tr>
   <tr>
      <td width="20px" align="center" style="background-color:#fee2e2; color:#b91c1c;"><b>L</b></td>
      <td colspan="3">: Libur (Mengikuti Kalender & Agenda TPQ serta Hari Libur Mingguan, tidak dihitung Alpa)</td>
   </tr>
</table>
@endsection