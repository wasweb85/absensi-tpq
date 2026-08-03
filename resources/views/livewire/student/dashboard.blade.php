<div>
    @if(!$isStudent)
        <div class="bg-red-50 text-red-600 p-4 rounded-xl border border-red-200">
            Akses ditolak. Anda bukan Siswa.
        </div>
    @else
        <div class="mb-6 flex justify-between items-end">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">Dashboard Siswa</h3>
                <p class="text-gray-500">Selamat datang, {{ $siswa->nama_siswa }} (Kelas {{ $siswa->kelas->kelas ?? '-' }})</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- LEFT COLUMN: Summary & Jadwal -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- SUMMARY STATS -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h4 class="text-lg font-bold text-gray-800">Kehadiran Bulan Ini</h4>
                        <span class="text-sm font-medium text-gray-500 bg-white px-3 py-1 rounded-full shadow-sm border border-gray-100">{{ $bulanTahun }}</span>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                            <div class="bg-green-50 rounded-lg p-4 border border-green-100">
                                <p class="text-green-600 font-bold mb-1">Hadir</p>
                                <h4 class="text-2xl font-bold text-gray-800">{{ $summary['hadir'] }}</h4>
                            </div>
                            <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-100">
                                <p class="text-yellow-600 font-bold mb-1">Sakit</p>
                                <h4 class="text-2xl font-bold text-gray-800">{{ $summary['sakit'] }}</h4>
                            </div>
                            <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                                <p class="text-blue-600 font-bold mb-1">Izin</p>
                                <h4 class="text-2xl font-bold text-gray-800">{{ $summary['izin'] }}</h4>
                            </div>
                            <div class="bg-red-50 rounded-lg p-4 border border-red-100">
                                <p class="text-red-600 font-bold mb-1">Alfa</p>
                                <h4 class="text-2xl font-bold text-gray-800">{{ $summary['alfa'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- JADWAL KELAS -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h4 class="text-lg font-bold text-gray-800">Jadwal Pelajaran Kelas {{ $siswa->kelas->kelas ?? '-' }}</h4>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($jadwalMingguan as $hari => $jadwals)
                                <div class="border border-gray-200 rounded-lg overflow-hidden">
                                    <div class="bg-gray-100 px-3 py-2 text-center font-bold text-gray-700 text-sm">
                                        {{ $hari }}
                                    </div>
                                    <div class="p-3 text-sm space-y-2">
                                        @if(empty($jadwals))
                                            <p class="text-gray-400 text-center italic">Libur / Kosong</p>
                                        @else
                                            @foreach($jadwals as $j)
                                                <div class="flex justify-between items-center border-b border-gray-100 pb-2 last:border-0 last:pb-0">
                                                    <div>
                                                        <span class="font-medium text-gray-800 block">{{ $j->mapel->nama_mapel ?? '-' }}</span>
                                                        <span class="text-xs text-gray-500">{{ $j->guru->nama_guru ?? '-' }}</span>
                                                    </div>
                                                    <span class="text-xs font-mono bg-purple-50 text-purple-700 px-1.5 py-0.5 rounded">{{ substr($j->jam_mulai, 0, 5) }}</span>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: Riwayat Kehadiran Terakhir -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h4 class="text-lg font-bold text-gray-800">Aktivitas Terakhir</h4>
                    </div>
                    <div class="p-0">
                        @if($riwayat->isEmpty())
                            <div class="p-6 text-center text-gray-500">
                                Belum ada riwayat absensi.
                            </div>
                        @else
                            <ul class="divide-y divide-gray-100">
                                @foreach($riwayat as $log)
                                    @php
                                        $statusColor = 'bg-gray-100 text-gray-800';
                                        $statusText = 'Belum Ada';
                                        if($log->id_kehadiran == 1) { $statusColor = 'bg-green-100 text-green-800'; $statusText = 'Hadir'; }
                                        elseif($log->id_kehadiran == 2) { $statusColor = 'bg-yellow-100 text-yellow-800'; $statusText = 'Sakit'; }
                                        elseif($log->id_kehadiran == 3) { $statusColor = 'bg-blue-100 text-blue-800'; $statusText = 'Izin'; }
                                        elseif($log->id_kehadiran == 4) { $statusColor = 'bg-red-100 text-red-800'; $statusText = 'Alfa'; }
                                    @endphp
                                    <li class="p-4 hover:bg-gray-50 transition flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-bold text-gray-800">{{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('l, d M Y') }}</p>
                                            @if($log->keterangan)
                                                <p class="text-xs text-gray-500 mt-1">{{ $log->keterangan }}</p>
                                            @endif
                                        </div>
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
                                            {{ $statusText }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="p-4 border-t border-gray-100 bg-gray-50 text-center">
                                <a href="#" class="text-sm font-medium text-purple-600 hover:text-purple-800 transition">Lihat Semua Riwayat &rarr;</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    @endif
</div>
