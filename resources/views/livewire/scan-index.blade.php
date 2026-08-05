<div class="scan-page-wrapper" style="min-height: 100vh; background: #f8fafc; font-family: 'Inter', system-ui, sans-serif; color: #0f172a; padding: 20px 24px;">
    
    {{-- TOP NAVBAR --}}
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
        {{-- Left: Back Button & Live Date/Time --}}
        <div style="display: flex; align-items: center; gap: 16px;">
            @php
                $dashUrl = url('/');
                if (auth()->check()) {
                    $dashUrl = !empty(auth()->user()->id_guru) ? url('/teacher/dashboard') : url('/dashboard');
                }
            @endphp
            <a href="{{ $dashUrl }}" onclick="if(window.history.length > 1){ window.history.back(); return false; }" style="width: 40px; height: 40px; border-radius: 12px; background: #ffffff; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; color: #334155; text-decoration: none; box-shadow: 0 1px 3px rgba(0,0,0,0.05); transition: all 0.2s;" title="Kembali">
                <i class="material-icons" style="font-size: 22px;">arrow_back</i>
            </a>

            <div>
                <div id="liveDate" style="font-weight: 700; font-size: 0.95rem; color: #1e293b;">
                    {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, j F Y') }}
                </div>
                <div id="liveTime" style="font-size: 0.78rem; font-weight: 600; color: #94a3b8; letter-spacing: 0.5px;">
                    {{ \Carbon\Carbon::now()->format('H.i.s') }} WIB
                </div>
            </div>
        </div>

        {{-- Right: Mode Badges --}}
        <div style="display: flex; align-items: center; gap: 10px;">
            <div style="display: flex; align-items: center; gap: 6px; padding: 6px 14px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; font-weight: 700; font-size: 0.8rem; color: #0ea5e9; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                <i class="material-icons" style="font-size: 18px;">qr_code_scanner</i>
                <span>QR CODE</span>
            </div>
        </div>
    </div>

    {{-- CENTER SECTION --}}
    <div style="max-width: 600px; margin: 0 auto; text-align: center;">
        
        {{-- Tabs --}}
        <div style="display: inline-flex; background: #e2e8f0; padding: 4px; border-radius: 20px; margin-bottom: 20px; gap: 4px;">
            <button type="button" id="tabKamera" onclick="switchMode('kamera')" style="padding: 6px 18px; border-radius: 16px; border: none; background: #ffffff; color: #0284c7; font-weight: 700; font-size: 0.82rem; display: flex; align-items: center; gap: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); cursor: pointer;">
                <i class="material-icons" style="font-size: 16px;">photo_camera</i> Kamera
            </button>
            <button type="button" id="tabScanner" onclick="switchMode('scanner')" style="padding: 6px 18px; border-radius: 16px; border: none; background: transparent; color: #64748b; font-weight: 600; font-size: 0.82rem; display: flex; align-items: center; gap: 6px; cursor: pointer;">
                <i class="material-icons" style="font-size: 16px;">scanner</i> Alat Scanner
            </button>
        </div>

        {{-- Camera Active Indicator & Titles --}}
        <div id="kameraTitleSection" style="margin-bottom: 16px;">
            <div id="kameraStatusBadge" style="display: inline-flex; align-items: center; gap: 6px; color: #0284c7; font-size: 0.72rem; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">
                <i class="material-icons" style="font-size: 14px;">photo_camera</i> KAMERA AKTIF
            </div>
            <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin: 0 0 6px 0;">Arahkan QR Code Ke Kamera</h2>
            <p style="font-size: 0.85rem; color: #64748b; margin: 0; max-width: 420px; margin: 0 auto;">Posisikan QR Code di dalam kotak pemindaian tengah kamera</p>
        </div>

        <div id="scannerTitleSection" style="margin-bottom: 16px; display: none;">
            <div style="display: inline-flex; align-items: center; gap: 6px; color: #0284c7; font-size: 0.72rem; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">
                <i class="material-icons" style="font-size: 14px;">scanner</i> ALAT SCANNER AKTIF
            </div>
            <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin: 0 0 6px 0;">Scan Kartu Dengan Barcode Scanner</h2>
            <p style="font-size: 0.85rem; color: #64748b; margin: 0; max-width: 420px; margin: 0 auto;">Arahkan pemindai ke kartu siswa/guru atau ketikkan nomor unik/NIS</p>
        </div>

        {{-- VIEWFINDER SCANNER BOX (Inside wire:ignore) --}}
        <div id="cameraSection" wire:ignore style="max-width: 420px; margin: 0 auto; position: relative;">
            {{-- Camera Select Dropdown & Switch Button --}}
            <div style="margin-bottom: 12px; display: flex; gap: 8px; align-items: center;">
                <select id="pilihKamera" class="form-control" style="width: 100%; border-radius: 10px; border: 1px solid #cbd5e1; padding: 7px 12px; font-size: 0.82rem; background: #ffffff; color: #334155; font-weight: 600;">
                    <option value="">Pilih Kamera...</option>
                </select>
                <button type="button" onclick="toggleFrontBackCamera()" style="padding: 7px 14px; background: #0284c7; color: white; border: none; border-radius: 10px; font-size: 0.8rem; font-weight: 700; white-space: nowrap; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(2, 132, 199, 0.2);" title="Putar Kamera (Depan / Belakang)">
                    <i class="material-icons" style="font-size: 16px;">flip_camera_ios</i>
                    <span>Putar Kamera</span>
                </button>
            </div>

            <div style="width: 100%; height: 380px; background: #000000; border-radius: 24px; position: relative; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);">
                {{-- Video Element --}}
                <video id="previewKamera" style="width: 100%; height: 100%; object-fit: cover; background: #000; display: block;"></video>

                {{-- Corner Guides --}}
                <div style="position: absolute; top: 24px; left: 24px; width: 36px; height: 36px; border-top: 4px solid #ffffff; border-left: 4px solid #ffffff; border-top-left-radius: 10px; pointer-events: none; z-index: 5;"></div>
                <div style="position: absolute; top: 24px; right: 24px; width: 36px; height: 36px; border-top: 4px solid #ffffff; border-right: 4px solid #ffffff; border-top-right-radius: 10px; pointer-events: none; z-index: 5;"></div>
                <div style="position: absolute; bottom: 24px; left: 24px; width: 36px; height: 36px; border-bottom: 4px solid #ffffff; border-left: 4px solid #ffffff; border-bottom-left-radius: 10px; pointer-events: none; z-index: 5;"></div>
                <div style="position: absolute; bottom: 24px; right: 24px; width: 36px; height: 36px; border-bottom: 4px solid #ffffff; border-right: 4px solid #ffffff; border-bottom-right-radius: 10px; pointer-events: none; z-index: 5;"></div>

                {{-- Laser Scan Animation Line --}}
                <div class="scan-laser-line"></div>
            </div>
        </div>

        {{-- CAMERA ERROR ALERT NOTICE --}}
        <div id="cameraErrorAlert" style="max-width: 420px; margin: 12px auto 0 auto; display: none;">
            <div style="background: #fef2f2; border: 1.5px solid #fca5a5; border-radius: 16px; padding: 14px 16px; text-align: left; color: #991b1b; display: flex; align-items: flex-start; gap: 12px; box-shadow: 0 4px 6px -1px rgba(220,38,38,0.1);">
                <i class="material-icons" style="font-size: 24px; color: #dc2626; flex-shrink: 0; margin-top: 2px;">error_outline</i>
                <div>
                    <div style="font-weight: 800; font-size: 0.9rem; margin-bottom: 2px;" id="cameraErrorTitle">Gagal Mengakses Kamera</div>
                    <div style="font-size: 0.8rem; color: #7f1d1d; font-weight: 500; line-height: 1.4;" id="cameraErrorMessage">Pastikan izin kamera diizinkan di browser Anda. Klik ikon gembok di address bar untuk memberi izin.</div>
                </div>
            </div>
        </div>

        {{-- ALAT SCANNER INPUT SECTION --}}
        <div id="scannerSection" style="max-width: 420px; margin: 0 auto; display: none;">
            <div style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 20px; padding: 24px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); text-align: left;">
                <label for="scannerInput" style="font-size: 0.82rem; font-weight: 700; color: #475569; display: block; margin-bottom: 8px;">
                    Kode RFID / Unique Code / NIS:
                </label>
                <form onsubmit="handleManualSubmit(event)" style="display: flex; gap: 8px;">
                    <input type="text" id="scannerInput" placeholder="Ketik atau tempelkan scanner di sini..." style="flex: 1; border: 2px solid #0284c7; border-radius: 12px; padding: 12px 16px; font-size: 1rem; font-weight: 600; outline: none; background: #f8fafc;" autofocus>
                    <button type="submit" style="background: #0284c7; color: white; border: none; border-radius: 12px; padding: 0 20px; font-weight: 700; font-size: 0.9rem; cursor: pointer;">
                        Submit
                    </button>
                </form>
                <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 10px; display: flex; align-items: center; gap: 4px;">
                    <i class="material-icons" style="font-size: 14px;">info</i> Scanner hardware akan otomatis mengirimkan kode dan melakukan submit saat tombol Enter tertekan.
                </div>
            </div>
        </div>

        {{-- SCAN RESULT OUTPUT --}}
        <div id="hasilScan" style="max-width: 420px; margin: 20px auto 0 auto;">
            @if($scanResult)
                <div style="border-radius: 16px; border: 1px solid {{ $scanSuccess ? '#bbf7d0' : '#fecaca' }}; background: {{ $scanSuccess ? '#f0fdf4' : '#fef2f2' }}; padding: 18px; text-align: left; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                        <i class="material-icons" style="font-size: 24px; color: {{ $scanSuccess ? '#16a34a' : '#dc2626' }};">{{ $scanSuccess ? 'check_circle' : 'error' }}</i>
                        <span style="font-weight: 800; font-size: 0.95rem; color: {{ $scanSuccess ? '#15803d' : '#991b1b' }};">{{ $scanMessage }}</span>
                    </div>

                    @if(isset($scanResult['user']))
                        <div style="background: #ffffff; border-radius: 12px; padding: 12px 14px; border: 1px solid {{ $scanSuccess ? '#dcfce7' : '#fee2e2' }};">
                            <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">{{ ucfirst($scanResult['type']) }}</div>
                            <div style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin-top: 2px;">
                                {{ $scanResult['type'] == 'guru' ? $scanResult['user']->nama_guru : $scanResult['user']->nama_siswa }}
                            </div>
                            
                            <div style="display: flex; gap: 16px; margin-top: 8px; font-size: 0.82rem; color: #475569;">
                                @if($scanResult['type'] == 'siswa')
                                    <div>NIS: <b>{{ $scanResult['user']->nis }}</b></div>
                                    <div>Kelas: <b>{{ $scanResult['user']->kelas->tingkat ?? '' }} {{ $scanResult['user']->kelas->index_kelas ?? '' }}</b></div>
                                @else
                                    <div>NIUP: <b>{{ $scanResult['user']->niup ?? '-' }}</b></div>
                                @endif
                                <div>Jam: <b>{{ $scanResult['presensi']->jam_masuk ?? '-' }}</b></div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

    </div>

    {{-- Audio element fallback --}}
    <audio id="audioBeep" src="/assets/audio/beep.mp3" preload="auto"></audio>

    {{-- Laser Animation Style --}}
    <style>
        @keyframes laserScanAnimation {
            0% { top: 12%; opacity: 0.7; }
            50% { top: 82%; opacity: 1; }
            100% { top: 12%; opacity: 0.7; }
        }
        .scan-laser-line {
            position: absolute;
            left: 10%;
            right: 10%;
            height: 3px;
            background: linear-gradient(90deg, transparent, #22c55e, #4ade80, #22c55e, transparent);
            box-shadow: 0 0 15px #22c55e, 0 0 8px #4ade80;
            border-radius: 2px;
            animation: laserScanAnimation 2.2s infinite ease-in-out;
            z-index: 10;
            pointer-events: none;
        }
    </style>

    <script>
        // Live Clock
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const timeEl = document.getElementById('liveTime');
            if (timeEl) {
                timeEl.innerText = `${hours}.${minutes}.${seconds} WIB`;
            }
        }
        setInterval(updateClock, 1000);

        var selectedDeviceId = window.selectedDeviceId || null;
        var codeReader = window.codeReader || null;
        var isProcessingScan = false;
        var availableVideoDevices = [];

        function showCameraError(title, message) {
            $('#cameraErrorTitle').text(title);
            $('#cameraErrorMessage').text(message);
            $('#cameraErrorAlert').slideDown();
            $('#kameraStatusBadge').html('<i class="material-icons" style="font-size: 14px;">videocam_off</i> KAMERA GAGAL / TERKUNCI').css('color', '#dc2626');
        }

        function hideCameraError() {
            $('#cameraErrorAlert').slideUp();
            $('#kameraStatusBadge').html('<i class="material-icons" style="font-size: 14px;">photo_camera</i> KAMERA AKTIF').css('color', '#0284c7');
        }

        function getCodeReader() {
            if (!codeReader && typeof ZXing !== 'undefined') {
                codeReader = new ZXing.BrowserMultiFormatReader();
                window.codeReader = codeReader;
            }
            return codeReader;
        }

        function playBeepSound(isSuccess) {
            try {
                let audio = document.getElementById('audioBeep');
                if (audio) {
                    audio.currentTime = 0;
                    audio.play().catch(e => console.log("Audio play error:", e));
                }
            } catch(e) {}

            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (AudioCtx) {
                    const ctx = new AudioCtx();
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = isSuccess ? 'sine' : 'sawtooth';
                    osc.frequency.setValueAtTime(isSuccess ? 880 : 220, ctx.currentTime);
                    gain.gain.setValueAtTime(0.3, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + (isSuccess ? 0.25 : 0.4));
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start();
                    osc.stop(ctx.currentTime + (isSuccess ? 0.25 : 0.4));
                }
            } catch(e) {}
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Livewire !== 'undefined') {
                Livewire.on('play-beep', (event) => {
                    let isSuccess = true;
                    if (Array.isArray(event) && event.length > 0) {
                        isSuccess = event[0].success !== false;
                    } else if (event && typeof event.success !== 'undefined') {
                        isSuccess = event.success !== false;
                    }
                    playBeepSound(isSuccess);
                });
            }
        });

        function stopCamera() {
            if (codeReader) {
                try {
                    codeReader.reset();
                } catch(e) {}
            }
        }

        window.toggleFrontBackCamera = function() {
            if (!availableVideoDevices || availableVideoDevices.length === 0) {
                const reader = getCodeReader();
                if (reader) {
                    reader.listVideoInputDevices().then(devices => {
                        availableVideoDevices = devices;
                        switchNextCamera();
                    });
                }
                return;
            }
            switchNextCamera();
        };

        function switchNextCamera() {
            if (!availableVideoDevices || availableVideoDevices.length <= 1) {
                alert("Hanya 1 kamera yang terdeteksi pada perangkat ini.");
                return;
            }

            let currentIndex = availableVideoDevices.findIndex(d => d.deviceId === selectedDeviceId);
            let nextIndex = (currentIndex + 1) % availableVideoDevices.length;
            if (currentIndex === -1) nextIndex = 0;
            
            selectedDeviceId = availableVideoDevices[nextIndex].deviceId;
            window.selectedDeviceId = selectedDeviceId;
            $('#pilihKamera').val(selectedDeviceId);

            if (codeReader) {
                try { codeReader.reset(); } catch(e) {}
            }
            initScanner();
        }

        function initScanner() {
            hideCameraError();
            const reader = getCodeReader();
            if (!reader) {
                setTimeout(initScanner, 200);
                return;
            }

            const sourceSelect = $('#pilihKamera');

            reader.listVideoInputDevices()
                .then(videoInputDevices => {
                    availableVideoDevices = videoInputDevices;
                    if (videoInputDevices.length < 1) {
                        sourceSelect.html('<option value="">Kamera tidak ditemukan!</option>');
                        showCameraError("Kamera Tidak Ditemukan", "Perangkat tidak mendeteksi adanya kamera. Pastikan webcam terpasang.");
                        return;
                    }

                    if (selectedDeviceId == null) {
                        if (videoInputDevices.length <= 1) {
                            selectedDeviceId = videoInputDevices[0].deviceId;
                        } else {
                            selectedDeviceId = videoInputDevices[1].deviceId;
                        }
                        window.selectedDeviceId = selectedDeviceId;
                    }

                    sourceSelect.html('');
                    videoInputDevices.forEach((element) => {
                        const sourceOption = document.createElement('option');
                        sourceOption.text = element.label || 'Kamera ' + (sourceSelect.children().length + 1);
                        sourceOption.value = element.deviceId;
                        if (element.deviceId == selectedDeviceId) {
                            sourceOption.selected = 'selected';
                        }
                        sourceSelect.append(sourceOption);
                    });

                    reader.decodeOnceFromVideoDevice(selectedDeviceId, 'previewKamera')
                        .then(result => {
                            let text = result.text ? result.text.trim() : '';
                            if (text.length > 0 && !isProcessingScan) {
                                isProcessingScan = true;
                                console.log("QR Scanned:", text);
                                
                                @this.processScan(text).then(() => {
                                    if (codeReader) {
                                        try { codeReader.reset(); } catch(e) {}
                                    }
                                    setTimeout(() => {
                                        isProcessingScan = false;
                                        initScanner();
                                    }, 2500);
                                }).catch(err => {
                                    console.error("Process scan error:", err);
                                    if (codeReader) {
                                        try { codeReader.reset(); } catch(e) {}
                                    }
                                    setTimeout(() => {
                                        isProcessingScan = false;
                                        initScanner();
                                    }, 1500);
                                });
                            } else {
                                initScanner();
                            }
                        })
                        .catch(err => {
                            console.error("ZXing decode error:", err);
                            let errStr = err ? (err.name || err.toString()) : '';
                            if (errStr.includes('NotReadableError') || errStr.includes('TrackStartError')) {
                                showCameraError("Kamera Terkunci / Digunakan", "Kamera laptop Anda sedang digunakan oleh aplikasi/tab lain (seperti Zoom, Teams, OBS, atau tab browser lain). Tutup aplikasi tersebut atau klik tombol Putar Kamera.");
                            } else if (errStr.includes('NotAllowedError') || errStr.includes('PermissionDeniedError')) {
                                showCameraError("Izin Kamera Ditolak", "Browser menolak akses ke kamera. Klik ikon gembok (🔒) pada address bar di pojok atas browser untuk mengizinkan kamera.");
                            } else if (errStr.includes('NotFoundError') || errStr.includes('DevicesNotFoundError')) {
                                showCameraError("Kamera Tidak Ditemukan", "Perangkat kamera yang dipilih tidak dapat ditemukan.");
                            }
                        });
                })
                .catch(err => {
                    console.error("Camera access error:", err);
                    sourceSelect.html('<option value="">Gagal Mengakses Kamera</option>');
                    showCameraError("Gagal Mengakses Kamera", "Browser tidak mendapat izin membuka kamera. Klik ikon gembok (🔒) di address bar browser dan izinkan akses kamera.");
                });
        }

        window.switchMode = function(mode) {
            if (mode === 'kamera') {
                $('#cameraSection').show();
                $('#kameraTitleSection').show();
                $('#scannerSection').hide();
                $('#scannerTitleSection').hide();
                $('#tabKamera').css({ background: '#ffffff', color: '#0284c7', boxShadow: '0 1px 3px rgba(0,0,0,0.08)' });
                $('#tabScanner').css({ background: 'transparent', color: '#64748b', boxShadow: 'none' });
                initScanner();
            } else {
                stopCamera();
                hideCameraError();
                $('#cameraSection').hide();
                $('#kameraTitleSection').hide();
                $('#scannerSection').show();
                $('#scannerTitleSection').show();
                $('#tabScanner').css({ background: '#ffffff', color: '#0284c7', boxShadow: '0 1px 3px rgba(0,0,0,0.08)' });
                $('#tabKamera').css({ background: 'transparent', color: '#64748b', boxShadow: 'none' });
                setTimeout(function() {
                    $('#scannerInput').focus();
                }, 100);
            }
        };

        window.handleManualSubmit = function(e) {
            e.preventDefault();
            const input = document.getElementById('scannerInput');
            const val = input ? input.value.trim() : '';
            if (val.length > 0 && !isProcessingScan) {
                isProcessingScan = true;
                @this.processScan(val).then(() => {
                    input.value = '';
                    setTimeout(() => { isProcessingScan = false; }, 1500);
                }).catch(err => {
                    console.error("Scan error:", err);
                    setTimeout(() => { isProcessingScan = false; }, 1000);
                });
            }
        };

        $(document).on('change', '#pilihKamera', function () {
            selectedDeviceId = $(this).val();
            window.selectedDeviceId = selectedDeviceId;
            if (codeReader) {
                try { codeReader.reset(); } catch(e) {}
            }
            initScanner();
        });

        $(document).ready(function() {
            if (navigator.mediaDevices) {
                initScanner();
            } else {
                $('#pilihKamera').html('<option value="">Tidak dapat mengakses kamera.</option>');
                showCameraError("Kamera Tidak Didukung", "Browser Anda tidak mendukung WebRTC atau tidak dijalankan melalui protokol aman (HTTPS / Localhost).");
            }
        });
    </script>

</div>
