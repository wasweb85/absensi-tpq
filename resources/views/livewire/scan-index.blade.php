<div class="main-panel" style="width: 100%;">
    <div class="content pt-2 px-0 px-sm-1 px-md-2">
        <div class="container-fluid px-0 px-md-2">
            <div class="row mx-auto">
                <div class="col-lg-6 col-xxl-5 order-1 order-lg-2">
                    <div class="card">
                        <div class="col-10 mx-auto card-header card-header-primary">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 class="card-title"><b>Absen <span style="text-transform: capitalize;">{{ $waktu }}</span></b></h4>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button wire:click="setWaktu('{{ $waktu == 'masuk' ? 'pulang' : 'masuk' }}')" class="btn btn-sm btn-{{ $waktu == 'masuk' ? 'warning' : 'success' }}">
                                        Ganti ke Absen {{ $waktu == 'masuk' ? 'Pulang' : 'Masuk' }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row" style="margin-top: 10px;">
                                <div class="col-12 px-0 px-sm-3">
                                    <div class="d-flex flex-column align-items-center form-group" style="padding-bottom: 5px;">
                                        <div class="d-flex align-items-center mb-1 w-100 px-3 px-sm-0">
                                            <div class="togglebutton d-inline-block m-0 h-100 align-items-center">
                                                <label class="m-0 text-dark">
                                                    <input type="checkbox" id="toggleKamera">
                                                    <span class="toggle"></span>
                                                    Gunakan Kamera (Scan QR)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12 mt-2" id="cameraSection" style="display: none;" wire:ignore>
                                    <div class="form-group w-100 px-3 px-sm-0 mt-0">
                                        <label for="pilihKamera">Pilih Kamera:</label>
                                        <select class="form-control" id="pilihKamera" style="width: 100%;">
                                            <option value="">Memuat kamera...</option>
                                        </select>
                                    </div>
                                    <div class="previewParent d-flex align-items-center justify-content-center">
                                        <video id="previewKamera" class="w-100"></video>
                                    </div>
                                </div>

                                <div class="col-12 mt-3 px-3">
                                    <div class="d-flex flex-column align-items-center form-group">
                                        <div class="d-flex align-items-center mb-2 w-100">
                                            <div class="togglebutton d-inline-block m-0 h-100 align-items-center">
                                                <label class="m-0 text-dark">
                                                    <input type="checkbox" id="toggleRFID" checked>
                                                    <span class="toggle"></span>
                                                    Gunakan RFID / Barcode Scanner (USB)
                                                </label>
                                            </div>
                                        </div>
                                        <span id="statusBadge" class="badge badge-success p-2 w-100 mb-2">
                                            <span id="statusText" style="font-size: 14px;">RFID Reader: Siap</span>
                                        </span>
                                        <div class="w-100 position-relative">
                                            <input type="text" id="rfidInput" class="form-control px-2" placeholder="Scan kartu/QR disini..." 
                                                autocomplete="off" style="border: 2px solid #4caf50; border-radius: 5px; height: 45px; background: rgba(0,0,0,0.02);"
                                                wire:model.live.debounce.300ms="unique_code">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Scan Result Output -->
                    <div id="hasilScan" class="mt-3">
                        @if($scanResult)
                            <div class="card mt-0">
                                <div class="card-body">
                                    @if($scanSuccess)
                                        <div class="alert alert-success">
                                            <h4 class="alert-heading"><i class="material-icons">check_circle</i> Berhasil!</h4>
                                            <p>{{ $scanMessage }}</p>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td width="30%"><strong>Tipe</strong></td>
                                                    <td>{{ ucfirst($scanResult['type']) }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Nama</strong></td>
                                                    <td>{{ $scanResult['type'] == 'guru' ? $scanResult['user']->nama_guru : $scanResult['user']->nama_siswa }}</td>
                                                </tr>
                                                @if($scanResult['type'] == 'siswa')
                                                    <tr>
                                                        <td><strong>NIS</strong></td>
                                                        <td>{{ $scanResult['user']->nis }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Kelas</strong></td>
                                                        <td>{{ $scanResult['user']->kelas->tingkat ?? '' }} {{ $scanResult['user']->kelas->index_kelas ?? '' }}</td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td><strong>NUPTK</strong></td>
                                                        <td>{{ $scanResult['user']->nuptk }}</td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td><strong>Jam Masuk</strong></td>
                                                    <td>{{ $scanResult['presensi']->jam_masuk ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Jam Keluar</strong></td>
                                                    <td>{{ $scanResult['presensi']->jam_keluar ?? '-' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-danger">
                                            <h4 class="alert-heading"><i class="material-icons">error</i> Gagal!</h4>
                                            <p>{{ $scanMessage }}</p>
                                        </div>
                                        @if(isset($scanResult['user']))
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <td width="30%"><strong>Tipe</strong></td>
                                                        <td>{{ ucfirst($scanResult['type']) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Nama</strong></td>
                                                        <td>{{ $scanResult['type'] == 'guru' ? $scanResult['user']->nama_guru : $scanResult['user']->nama_siswa }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Jam Masuk</strong></td>
                                                        <td>{{ $scanResult['presensi']->jam_masuk ?? '-' }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-3 col-xxl-3 order-first order-lg-1">
                    <div class="card" id="info-card">
                        <div class="card-body">
                            <h3 class="mt-2"><b>Informasi</b></h3>
                            <ul class="pl-3">
                                <li>Pastikan koneksi internet stabil</li>
                                <li>Jika menggunakan kamera/webcam, pastikan izin akses kamera diberikan</li>
                                <li>Posisikan qr code tidak terlalu jauh maupun terlalu dekat</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-xxl-4 order-last">
                    <div class="card" id="usage-card">
                        <div class="card-body">
                            <h3 class="mt-2"><b>Penggunaan</b></h3>
                            <ul class="pl-3">
                                <li>Jika berhasil scan maka akan muncul data siswa/guru dibawah form scan</li>
                                <li>Klik tombol <b><span class="text-success">Ganti ke Absen Masuk</span> / <span class="text-warning">Ganti ke Absen Pulang</span></b> untuk mengubah waktu absensi</li>
                                <li>Untuk melihat data absensi, klik tombol <span class="text-primary"><i class="material-icons" style="font-size: 16px;">dashboard</i> Dashboard</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        let selectedDeviceId = null;
        let audioSuccess = new Audio("{{ asset('assets/audio/beep.mp3') }}");
        let audioError = new Audio("{{ asset('assets/audio/beep.mp3') }}"); // optional: different sound
        const codeReader = new ZXing.BrowserMultiFormatReader();
        const sourceSelect = $('#pilihKamera');

        // Play beep sound triggered from Livewire
        $wire.on('play-beep', (event) => {
            if (event[0].success) {
                audioSuccess.play();
            } else {
                audioError.play();
            }
            
            // Auto focus back to input after scan
            setTimeout(() => {
                $('#rfidInput').val('');
                if ($('#toggleRFID').is(':checked')) {
                    $('#rfidInput').focus();
                }
            }, 1000);
        });

        // Initialize Camera Scanner
        function initScanner() {
            codeReader.getVideoInputDevices()
                .then((videoInputDevices) => {
                    sourceSelect.empty();
                    
                    if (videoInputDevices.length > 0) {
                        videoInputDevices.forEach((element) => {
                            const sourceOption = document.createElement('option')
                            sourceOption.text = element.label
                            sourceOption.value = element.deviceId
                            sourceSelect.append(sourceOption)
                        });

                        selectedDeviceId = videoInputDevices[0].deviceId;
                        
                        codeReader.decodeFromVideoDevice(selectedDeviceId, 'previewKamera', (result, err) => {
                            if (result) {
                                // Result found! Send to livewire
                                console.log("QR Code Scanned:", result.text);
                                @this.set('unique_code', result.text);
                                @this.processScan();
                            }
                            if (err && !(err instanceof ZXing.NotFoundException)) {
                                console.error(err)
                            }
                        });
                    } else {
                        sourceSelect.html('<option value="">Tidak ada kamera terdeteksi</option>');
                    }
                })
                .catch((err) => {
                    console.error(err)
                    alert('Tidak dapat mengakses kamera. Pastikan browser memberikan izin.');
                });
        }

        $(document).on('change', '#pilihKamera', function () {
            selectedDeviceId = $(this).val();
            if (codeReader && $('#toggleKamera').is(':checked')) {
                codeReader.reset();
                codeReader.decodeFromVideoDevice(selectedDeviceId, 'previewKamera', (result, err) => {
                    if (result) {
                        @this.set('unique_code', result.text);
                        @this.processScan();
                    }
                });
            }
        });

        $(document).on('change', '#toggleKamera', function () {
            if (this.checked) {
                $('#cameraSection').slideDown();
                initScanner();
            } else {
                codeReader.reset();
                $('#cameraSection').slideUp();
            }
        });

        // RFID Input Handling
        const rfidInput = $('#rfidInput');
        const statusBadge = $('#statusBadge');
        const statusText = $('#statusText');

        function updateStatus(focused) {
            if (focused) {
                statusBadge.removeClass('badge-secondary').addClass('badge-success');
                statusText.text('RFID Reader: Siap');
                rfidInput.css('border-color', '#4caf50');
            } else {
                statusBadge.removeClass('badge-success').addClass('badge-secondary');
                statusText.text('RFID Reader: Tidak Fokus (Klik Disini)');
                rfidInput.css('border-color', '#f44336');
            }
        }

        if ($('#toggleRFID').is(':checked')) {
            rfidInput.focus();
            updateStatus(true);
        } else {
            updateStatus(false);
        }

        rfidInput.on('focus', function () {
            updateStatus(true);
        });

        rfidInput.on('blur', function () {
            updateStatus(false);
            setTimeout(() => {
                if ($('#toggleRFID').is(':checked') && !$('#pilihKamera').is(':focus')) {
                    rfidInput.focus();
                }
            }, 3000);
        });

        $(document).on('click', function (e) {
            if ($('#toggleRFID').is(':checked') && !$(e.target).closest('#pilihKamera, #toggleKamera, #toggleRFID').length) {
                rfidInput.focus();
            }
        });
    </script>
    @endscript
</div>
