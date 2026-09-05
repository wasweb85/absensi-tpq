<div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
        <div class="col-md-12">
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
                <div class="card-header card-header-info">
                    <h4 class="card-title"><b>Pengaturan Utama</b></h4>
                    <p class="card-category">Update profil dan logo TPQ</p>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="updateSettings">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Nama TPQ</label>
                                    <input type="text" wire:model="school_name" class="form-control" required>
                                    @error('school_name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label class="bmd-label-floating">Tahun Ajaran</label>
                                    <input type="text" wire:model="school_year" class="form-control" required>
                                    @error('school_year') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label class="bmd-label-floating">Hari Libur Rutin Mingguan</label>
                                    <select wire:model="hari_libur_mingguan" class="form-control">
                                        <option value="selasa_jumat">Hari Selasa &amp; Jum'at (Rutin TPQ Nurul Mun'im)</option>
                                        <option value="jumat">Hari Jum'at Saja</option>
                                        <option value="ahad">Hari Ahad / Minggu</option>
                                        <option value="sabtu">Hari Sabtu</option>
                                        <option value="jumat_ahad">Hari Jum'at &amp; Ahad</option>
                                    </select>
                                    <small class="text-muted d-block">Menentukan hari libur mingguan pada kalender & mesin presensi</small>
                                </div>
                                <div class="form-group">
                                    <label class="bmd-label-floating">Copyright / Footer (Opsional)</label>
                                    <input type="text" wire:model="copyright" class="form-control">
                                    @error('copyright') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-center">
                                    <label class="bmd-label-floating d-block">Logo Saat Ini</label>
                                    @if ($logo)
                                        <img src="{{ $logo->temporaryUrl() }}" width="150px" height="150px" class="img-thumbnail">
                                    @elseif ($currentLogo)
                                        <img src="{{ asset('uploads/logo/' . $currentLogo) }}" width="150px" height="150px" class="img-thumbnail">
                                    @else
                                        <img src="{{ asset('uploads/logo/logo-tpq.png') }}" width="150px" height="150px" class="img-thumbnail">
                                    @endif
                                </div>
                                <div class="form-group mt-3 text-center">
                                    <label class="bmd-label-floating">Ganti Logo</label>
                                    <input type="file" wire:model="logo" class="form-control-file text-center mx-auto" accept="image/*">
                                    @error('logo') <span class="text-danger">{{ $message }}</span> @enderror
                                    <small class="text-muted d-block mt-1">Maksimal ukuran 2MB (jpg, jpeg, png)</small>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-info pull-right mt-4">Simpan Pengaturan</button>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
