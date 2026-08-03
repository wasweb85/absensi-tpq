<div>
    <div class="container-fluid" style="padding: 20px 30px;">
        <!-- Header Section -->
        <div style="margin-bottom: 25px;">
            <h3 style="font-weight: 800; color: #1e293b; margin: 0 0 5px 0; font-size: 1.5rem;">Laporan Kehadiran</h3>
            <p style="color: #64748b; font-size: 0.95rem; margin: 0;">Rekap data absensi siswa dan guru berdasarkan periode.</p>
        </div>

        @if (session()->has('msg'))
            <div class="alert alert-{{ session('error') ? 'danger' : 'success' }} mb-4" style="border-radius: 10px;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="material-icons">close</i>
                </button>
                {{ session('msg') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger mb-4" style="border-radius: 10px;">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Card Siswa -->
        <div class="card" style="border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); border: 1px solid #f1f5f9; margin-bottom: 30px;">
            <div class="card-header" style="background: white; border-bottom: 1px solid #f1f5f9; padding: 20px 25px; border-radius: 16px 16px 0 0;">
                <h4 style="font-weight: 800; color: #3b82f6; margin: 0; font-size: 1.15rem; display: flex; align-items: center; gap: 8px;">
                    <i class="material-icons">school</i> Laporan Kehadiran Siswa
                </h4>
            </div>
            <div class="card-body p-4">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label style="font-size: 0.75rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Dari Tanggal</label>
                        <input type="date" wire:model="tanggalMulai" class="form-control" style="border: none; border-bottom: 2px solid #f1f5f9; border-radius: 0; padding: 10px 0; background: transparent; font-weight: 600; color: #334155;">
                    </div>
                    
                    <div class="col-md-3">
                        <label style="font-size: 0.75rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Sampai Tanggal</label>
                        <input type="date" wire:model="tanggalAkhir" class="form-control" style="border: none; border-bottom: 2px solid #f1f5f9; border-radius: 0; padding: 10px 0; background: transparent; font-weight: 600; color: #334155;">
                    </div>
                    
                    <div class="col-md-4">
                        <label style="font-size: 0.75rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Filter Kelas</label>
                        <select wire:model="kelas" class="form-control custom-select" style="border: none; border-bottom: 2px solid #f1f5f9; border-radius: 0; padding: 10px 0; background: transparent; font-weight: 600; color: #334155; cursor: pointer;">
                            <option value="">Semua Kelas</option>
                            @foreach ($kelasList as $k)
                                <option value="{{ $k->id_kelas }}">{{ $k->tingkat }} {{ $k->index_kelas }} ({{ $k->total_siswa }} Siswa)</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-2 mt-3 mt-md-0 text-right">
                        <button wire:click="exportSiswa('xls')" class="btn" style="background: #10b981; color: white; border-radius: 10px; padding: 12px 20px; font-weight: 700; text-transform: none; display: inline-flex; align-items: center; justify-content: center; width: 100%; border: none; box-shadow: 0 4px 10px rgba(16,185,129,0.2); transition: all 0.3s ease;">
                            <i class="material-icons" style="font-size: 20px; margin-right: 8px;">table_view</i> Export Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Guru -->
        <div class="card" style="border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); border: 1px solid #f1f5f9; margin-bottom: 30px;">
            <div class="card-header" style="background: white; border-bottom: 1px solid #f1f5f9; padding: 20px 25px; border-radius: 16px 16px 0 0;">
                <h4 style="font-weight: 800; color: #8b5cf6; margin: 0; font-size: 1.15rem; display: flex; align-items: center; gap: 8px;">
                    <i class="material-icons">record_voice_over</i> Laporan Kehadiran Guru
                </h4>
            </div>
            <div class="card-body p-4">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label style="font-size: 0.75rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Dari Tanggal</label>
                        <input type="date" wire:model="tanggalMulaiGuru" class="form-control" style="border: none; border-bottom: 2px solid #f1f5f9; border-radius: 0; padding: 10px 0; background: transparent; font-weight: 600; color: #334155;">
                    </div>
                    
                    <div class="col-md-3">
                        <label style="font-size: 0.75rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Sampai Tanggal</label>
                        <input type="date" wire:model="tanggalAkhirGuru" class="form-control" style="border: none; border-bottom: 2px solid #f1f5f9; border-radius: 0; padding: 10px 0; background: transparent; font-weight: 600; color: #334155;">
                    </div>
                    
                    <div class="col-md-4">
                        <div style="color: #94a3b8; font-size: 0.85rem; padding: 10px 0; margin-top: 20px;">
                            <i>*Filter kelas tidak berlaku untuk laporan guru.</i>
                        </div>
                    </div>
                    
                    <div class="col-md-2 mt-3 mt-md-0 text-right">
                        <button wire:click="exportGuru('xls')" class="btn" style="background: #10b981; color: white; border-radius: 10px; padding: 12px 20px; font-weight: 700; text-transform: none; display: inline-flex; align-items: center; justify-content: center; width: 100%; border: none; box-shadow: 0 4px 10px rgba(16,185,129,0.2); transition: all 0.3s ease;">
                            <i class="material-icons" style="font-size: 20px; margin-right: 8px;">table_view</i> Export Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

