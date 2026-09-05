@extends('layouts.laporan')

@section('content')
@php 
   $logoImg = (!empty($appSettings->logo) && file_exists(public_path('uploads/logo/' . $appSettings->logo))) 
      ? asset('uploads/logo/' . $appSettings->logo) 
      : asset('assets/img/logo-sekolah.jpg');
@endphp
<table>
   <tr>
      <td><img src="{{ $logoImg }}" width="90px" height="90px" style="object-fit:contain;"></td>
      <td width="100%">
         <h2 align="center">DAFTAR HADIR SISWA</h2>
         <h4 align="center">{{ $appSettings->school_name ?? ($generalSettings->school_name ?? 'TPQ Absensi') }}</h4>
         <h4 align="center">TAHUN PELAJARAN {{ $appSettings->school_year ?? ($generalSettings->school_year ?? '2026/2027') }}</h4>
      </td>
      <td>
         <div style="width:100px"></div>
      </td>
   </tr>
</table>
<span>Bulan : {{ $bulan }}</span>
<span style="position: absolute;right: 0;">Kelas : {{ $kelas->tingkat ?? '' }} {{ $kelas->index_kelas ?? '' }}</span>
<table align="center" border="1">
   <tr>
      <td></td>
      <td></td>
      <th colspan="{{ count($tanggal) }}">Hari/Tanggal</th>
   </tr>
   <tr>
      <td></td>
      <td></td>
      @foreach ($tanggal as $value)
         @php 
            $d = $value->date ?? $value;
            $isLiburCol = !empty($value->is_libur);
         @endphp
         <td align="center" style="{{ $isLiburCol ? 'background-color: #fee2e2; color: #b91c1c;' : '' }}">
            <b>{{ $d->translatedFormat('D') }}</b>
         </td>
      @endforeach
      <td colspan="4" align="center">Total</td>
   </tr>
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

   @foreach ($listSiswa as $siswa)
      @php
      $jumlahHadir = count(array_filter($listAbsen, function ($a) use ($i) {
         if ($a['lewat'] || is_null($a[$i]['id_kehadiran']))
            return false;
         return $a[$i]['id_kehadiran'] == 1;
      }));
      $jumlahSakit = count(array_filter($listAbsen, function ($a) use ($i) {
         if ($a['lewat'] || is_null($a[$i]['id_kehadiran']))
            return false;
         return $a[$i]['id_kehadiran'] == 2;
      }));
      $jumlahIzin = count(array_filter($listAbsen, function ($a) use ($i) {
         if ($a['lewat'] || is_null($a[$i]['id_kehadiran']))
            return false;
         return $a[$i]['id_kehadiran'] == 3;
      }));
      $jumlahTidakHadir = count(array_filter($listAbsen, function ($a) use ($i) {
         if (!empty($a['status_libur'])) 
            return false;
      
         if ($a['lewat'])
            return false;
         if (is_null($a[$i]['id_kehadiran']) || $a[$i]['id_kehadiran'] == 4)
            return true;
         return false;
      }));
      @endphp
      <tr>
         <td align="center">{{ $i + 1 }}</td>
         <td>{{ $siswa->nama_siswa }}</td>
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
      <td>Jumlah siswa</td>
      <td>: {{ count($listSiswa) }}</td>
   </tr>
   <tr>
      <td>Laki-laki</td>
      <td>: {{ $rekapSiswa['laki'] ?? 0 }}</td>
   </tr>
   <tr>
      <td>Perempuan</td>
      <td>: {{ $rekapSiswa['perempuan'] ?? 0 }}</td>
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

@if(!empty($agendaLiburBulanIni) && $agendaLiburBulanIni->count() > 0)
   <div style="margin-top: 12px; padding: 8px 12px; border: 1px dashed #ef4444; background: #fff5f5; border-radius: 4px; font-size: 11px;">
      <b style="color: #991b1b;">Rincian Agenda Libur Kalender Periode Ini:</b>
      <ul style="margin: 4px 0 0 16px; padding: 0;">
         @foreach($agendaLiburBulanIni as $agenda)
            <li>
               <b>{{ \Carbon\Carbon::parse($agenda->tanggal_mulai)->translatedFormat('d M Y') }}
               @if($agenda->tanggal_selesai && $agenda->tanggal_selesai != $agenda->tanggal_mulai)
                  s/d {{ \Carbon\Carbon::parse($agenda->tanggal_selesai)->translatedFormat('d M Y') }}
               @endif:</b>
               {{ $agenda->judul }}
               @if($agenda->keterangan) - <span style="color: #475569;">{{ $agenda->keterangan }}</span> @endif
            </li>
         @endforeach
      </ul>
   </div>
@endif
@endsection