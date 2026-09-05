<div>
    <div class="content">
        <div class="container-fluid">
    @if (session()->has('msg'))
        <div class="mb-3 p-3 rounded-xl border flex items-center justify-between shadow-sm {{ session('error') ? 'bg-rose-50 border-rose-200 text-rose-800' : 'bg-emerald-50 border-emerald-200 text-emerald-800' }}">
            <div class="flex items-center space-x-2">
                <i class="material-icons text-lg">{{ session('error') ? 'error' : 'check_circle' }}</i>
                <span class="text-xs font-medium">{{ session('msg') }}</span>
            </div>
            <button type="button" class="text-slate-400 hover:text-slate-600 font-bold text-sm" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    <div class="card shadow-sm border border-slate-200 rounded-xl overflow-hidden bg-white">
        <div class="card-header px-4 py-3 border-b border-slate-200 bg-white">
            <h2 class="text-lg font-bold text-purple-700 m-0">Download Kartu QR Code Massal</h2>
            <p class="text-xs text-slate-500 mt-0.5 mb-0">Unduh bundle file gambar Kartu Absensi berdesain resmi (.zip) berdasarkan kelas atau semua santri/guru</p>
        </div>

        <div class="p-4 bg-slate-50/50">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <!-- Siswa -->
                <div class="card bg-white border border-slate-200 rounded-xl p-4 flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow">
                    <div>
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                            <div class="flex items-center space-x-2.5">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                                    <i class="material-icons text-lg">school</i>
                                </div>
                                <h3 class="text-sm font-bold text-slate-800 m-0">Kartu Santri</h3>
                            </div>
                            <a href="{{ url('admin/siswa') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline flex items-center space-x-0.5">
                                <span>Lihat data</span>
                                <i class="material-icons text-xs">arrow_forward</i>
                            </a>
                        </div>

                        <div class="py-2.5">
                            <p class="text-xs text-slate-600 m-0">
                                Total jumlah santri : <b class="text-slate-800 text-sm font-extrabold">{{ $totalSiswa }}</b>
                            </p>
                        </div>

                        <div class="pt-2 border-t border-slate-100">
                            <h4 class="text-xs font-bold text-slate-800 mb-1.5">Pilih Kelas / Jilid</h4>
                            
                            <select wire:model="kelas" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all cursor-pointer">
                                <option value="all">-- Semua Kelas / Jilid --</option>
                                @foreach ($kelasList as $k)
                                    <option value="{{ $k->id_kelas }}">
                                        {{ $k->tingkat }} {{ $k->index_kelas }} ({{ $k->total_siswa }} santri)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="pt-3 mt-3 border-t border-slate-100">
                        <button wire:click="downloadSiswa" class="w-full py-2.5 px-3 bg-purple-600 hover:bg-purple-700 active:bg-purple-800 text-white font-bold text-xs rounded-lg transition-all shadow-sm flex items-center justify-center space-x-1.5">
                            <i class="material-icons text-lg">cloud_download</i>
                            <span>Download ZIP Kartu Santri</span>
                        </button>
                    </div>
                </div>

                <!-- Guru -->
                <div class="card bg-white border border-slate-200 rounded-xl p-4 flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow">
                    <div>
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                            <div class="flex items-center space-x-2.5">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                                    <i class="material-icons text-lg">record_voice_over</i>
                                </div>
                                <h3 class="text-sm font-bold text-slate-800 m-0">Kartu Pengajar / Guru</h3>
                            </div>
                            <a href="{{ url('admin/guru') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline flex items-center space-x-0.5">
                                <span>Lihat data</span>
                                <i class="material-icons text-xs">arrow_forward</i>
                            </a>
                        </div>

                        <div class="py-2.5">
                            <p class="text-xs text-slate-600 m-0">
                                Total jumlah guru : <b class="text-slate-800 text-sm font-extrabold">{{ $totalGuru }}</b>
                            </p>
                        </div>

                        <div class="pt-2 border-t border-slate-100">
                            <h4 class="text-xs font-bold text-slate-800 mb-1">Download Semua Guru</h4>
                            <p class="text-xs text-slate-500 m-0">Unduh seluruh file gambar Kartu Absensi Pengajar milik semua guru dalam format ZIP.</p>
                        </div>
                    </div>

                    <div class="pt-3 mt-3 border-t border-slate-100">
                        <button wire:click="downloadGuru" class="w-full py-2.5 px-3 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-bold text-xs rounded-lg transition-all shadow-sm flex items-center justify-center space-x-1.5">
                            <i class="material-icons text-lg">cloud_download</i>
                            <span>Download ZIP Kartu Guru</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
        </div>
    </div>
</div>
