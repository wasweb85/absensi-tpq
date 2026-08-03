@extends('layouts.laporan')

@section('content')
<table>
   <tr>
      <td><img src="{{ asset('uploads/logo/logo-tpq.png') }}" width="100px" height="100px"></img></td>
      <td width="100%">
         <h2 align="center">DAFTAR HADIR SISWA</h2>
         <h4 align="center">{{ $generalSettings->school_name ?? 'TPQ Absensi' }}</h4>
         <h4 align="center">TAHUN PELAJARAN {{ $generalSettings->school_year ?? '2026/2027' }}</h4>
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
         <td align="center"><b>{{ $value->translatedFormat('D') }}</b></td>
      @endforeach
      <td colspan="4" align="center">Total</td>
   </tr>
   <tr>
      <th align="center">No</th>
      <th width="1000px">Nama</th>
      @foreach ($tanggal as $value)
         <th align="center">{{ $value->format('d') }}</th>
      @endforeach
      <th align="center" style="background-color:lightgreen;">H</th>
      <th align="center" style="background-color:yellow;">S</th>
      <th align="center" style="background-color:yellow;">I</th>
      <th align="center" style="background-color:red;">A</th>
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
               <td align="center" style="background-color: #e0e0e0;">L</td>
            @else
               @php
                  $kehadiran = $absen[$i]['id_kehadiran'] ?? ($absen['lewat'] ? 5 : 4);
                  $text = "<td></td>";
                  if ($kehadiran == 1) $text = "<td align='center' style='background-color:lightgreen;'>H</td>";
                  if ($kehadiran == 2) $text = "<td align='center' style='background-color:yellow;'>S</td>";
                  if ($kehadiran == 3) $text = "<td align='center' style='background-color:yellow;'>I</td>";
                  if ($kehadiran == 4) $text = "<td align='center' style='background-color:red;'>A</td>";
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
@endsection