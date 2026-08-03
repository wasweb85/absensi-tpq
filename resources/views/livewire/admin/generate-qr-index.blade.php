<div>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            @if (session()->has('msg'))
                <div class="pb-2 px-3">
                    <div class="alert alert-{{ session('error') ? 'danger' : 'success' }}">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <i class="material-icons">close</i>
                        </button>
                        {{ session('msg') }}
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-header card-header-danger">
                    <h4 class="card-title"><b>Generate QR Code</b></h4>
                    <p class="card-category">Unduh bundle QR Code (.zip) berdasarkan kelas atau untuk seluruh guru</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Siswa -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body d-flex flex-column">
                                    <h4 class="text-primary"><b>Data Siswa</b></h4>
                                    <p>Total jumlah siswa : <b>{{ $totalSiswa }}</b>
                                        <br>
                                        <a href="{{ url('admin/siswa') }}">Lihat data</a>
                                    </p>

                                    <hr>
                                    <h4 class="text-primary mt-2"><b>Generate per kelas</b></h4>
                                    
                                    <select wire:model="kelas" class="custom-select mb-3">
                                        <option value="">--Pilih kelas--</option>
                                        @foreach ($kelasList as $k)
                                            <option value="{{ $k->id_kelas }}">
                                                {{ $k->tingkat }} {{ $k->index_kelas }} - {{ $k->total_siswa }} siswa
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kelas') <span class="text-danger">{{ $message }}</span> @enderror

                                    <div class="mt-auto pt-3">
                                        <button wire:click="downloadSiswa" class="btn btn-primary p-2 px-md-4 w-100">
                                            <div class="d-flex align-items-center justify-content-center" style="gap: 12px;">
                                                <div>
                                                    <i class="material-icons" style="font-size: 24px;">cloud_download</i>
                                                </div>
                                                <div>
                                                    <h4 class="d-inline font-weight-bold">Download ZIP Kelas</h4>
                                                </div>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Guru -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body d-flex flex-column">
                                    <h4 class="text-primary"><b>Data Guru</b></h4>
                                    <p>Total jumlah guru : <b>{{ $totalGuru }}</b>
                                        <br>
                                        <a href="{{ url('admin/guru') }}">Lihat data</a>
                                    </p>
                                    <hr>

                                    <h4 class="text-primary mt-2"><b>Generate Semua Guru</b></h4>
                                    <p class="text-muted">Unduh seluruh QR Code milik semua guru dalam format ZIP.</p>

                                    <div class="mt-auto pt-3">
                                        <button wire:click="downloadGuru" class="btn btn-success p-2 px-md-4 w-100">
                                            <div class="d-flex align-items-center justify-content-center" style="gap: 12px;">
                                                <div>
                                                    <i class="material-icons" style="font-size: 24px;">cloud_download</i>
                                                </div>
                                                <div>
                                                    <h4 class="d-inline font-weight-bold">Download ZIP Guru</h4>
                                                </div>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
