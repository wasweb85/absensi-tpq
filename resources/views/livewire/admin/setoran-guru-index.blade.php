<div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
        <!-- Panel Kiri: Daftar Guru dengan Setoran Menggantung -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6>Daftar Setoran Belum Ditarik</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    @if (session()->has('success'))
                        <div class="alert alert-success mx-3 text-white text-sm" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session()->has('error'))
                        <div class="alert alert-danger mx-3 text-white text-sm" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Guru</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total Setoran Tunai</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($setoranList as $guru)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $guru->nama_guru }}</h6>
                                                    <p class="text-xs text-secondary mb-0">NIUP: {{ $guru->niup }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0 text-success">
                                                Rp {{ number_format($guru->unsettled_amount, 0, ',', '.') }}
                                            </p>
                                        </td>
                                        <td class="align-middle">
                                            <button 
                                                wire:click="terimaSetoran({{ $guru->id_guru }})"
                                                wire:confirm="Anda yakin telah menerima uang tunai sebesar Rp {{ number_format($guru->unsettled_amount, 0, ',', '.') }} dari {{ $guru->nama_guru }}?"
                                                class="btn btn-sm btn-primary mb-0">
                                                <i class="fas fa-hand-holding-usd me-1"></i> Tarik Setoran
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4">
                                            <p class="text-sm text-secondary mb-0">Tidak ada setoran yang menggantung.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Kanan: Riwayat Penarikan -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header pb-0 p-3">
                    <h6 class="mb-0">Riwayat Penarikan Terbaru</h6>
                </div>
                <div class="card-body p-3">
                    <div class="timeline timeline-one-side">
                        @forelse ($riwayatSetoran as $riwayat)
                            <div class="timeline-block mb-3">
                                <span class="timeline-step bg-success">
                                    <i class="fas fa-check text-white"></i>
                                </span>
                                <div class="timeline-content">
                                    <h6 class="text-dark text-sm font-weight-bold mb-0">Rp {{ number_format($riwayat->nominal, 0, ',', '.') }}</h6>
                                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">
                                        Dari: {{ $riwayat->guru->nama_guru ?? 'Tidak Diketahui' }}
                                    </p>
                                    <p class="text-xs text-muted mb-0">Diterima oleh: {{ $riwayat->bendahara->name ?? 'Admin' }}</p>
                                    <p class="text-xs text-muted mb-0">{{ \Carbon\Carbon::parse($riwayat->tanggal)->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-secondary mb-0">Belum ada riwayat penarikan.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
