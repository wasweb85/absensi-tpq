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
            <button type="button" style="padding: 6px 18px; border-radius: 16px; border: none; background: #ffffff; color: #0284c7; font-weight: 700; font-size: 0.82rem; display: flex; align-items: center; gap: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
                <i class="material-icons" style="font-size: 16px;">photo_camera</i> Kamera
            </button>
            <button type="button" style="padding: 6px 18px; border-radius: 16px; border: none; background: transparent; color: #64748b; font-weight: 600; font-size: 0.82rem; display: flex; align-items: center; gap: 6px;">
                <i class="material-icons" style="font-size: 16px;">scanner</i> Alat Scanner
            </button>
        </div>

        {{-- Camera Active Indicator & Titles --}}
        <div style="margin-bottom: 16px;">
            <div style="display: inline-flex; align-items: center; gap: 6px; color: #0284c7; font-size: 0.72rem; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">
                <i class="material-icons" style="font-size: 14px;">photo_camera</i> KAMERA AKTIF
            </div>
            <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin: 0 0 6px 0;">Arahkan QR Code Ke Kamera</h2>
            <p style="font-size: 0.85rem; color: #64748b; margin: 0; max-width: 420px; margin: 0 auto;">Posisikan QR Code di dalam kotak pemindaian tengah kamera</p>
        </div>

        {{-- VIEWFINDER SCANNER BOX (Inside wire:ignore) --}}
        <div id="cameraSection" wire:ignore style="max-width: 420px; margin: 0 auto; position: relative;">
            {{-- Camera Select Dropdown --}}
            <div style="margin-bottom: 12px;">
                <select id="pilihKamera" class="form-control" style="width: 100%; border-radius: 10px; border: 1px solid #cbd5e1; padding: 7px 12px; font-size: 0.82rem; background: #ffffff; color: #334155; font-weight: 600;">
                    <option value="">Memuat kamera...</option>
                </select>
            </div>

            <div style="width: 100%; height: 380px; background: #000000; border-radius: 24px; position: relative; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);">
                {{-- Video Element --}}
                <video id="previewKamera" autoplay muted playsinline style="width: 100%; height: 100%; object-fit: cover; background: #000; display: block;"></video>

                {{-- Corner Guides --}}
                <div style="position: absolute; top: 24px; left: 24px; width: 36px; height: 36px; border-top: 4px solid #ffffff; border-left: 4px solid #ffffff; border-top-left-radius: 10px; pointer-events: none; z-index: 5;"></div>
                <div style="position: absolute; top: 24px; right: 24px; width: 36px; height: 36px; border-top: 4px solid #ffffff; border-right: 4px solid #ffffff; border-top-right-radius: 10px; pointer-events: none; z-index: 5;"></div>
                <div style="position: absolute; bottom: 24px; left: 24px; width: 36px; height: 36px; border-bottom: 4px solid #ffffff; border-left: 4px solid #ffffff; border-bottom-left-radius: 10px; pointer-events: none; z-index: 5;"></div>
                <div style="position: absolute; bottom: 24px; right: 24px; width: 36px; height: 36px; border-bottom: 4px solid #ffffff; border-right: 4px solid #ffffff; border-bottom-right-radius: 10px; pointer-events: none; z-index: 5;"></div>

                {{-- Laser Scan Animation Line --}}
                <div class="scan-laser-line"></div>
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
                                    <div>NUPTK: <b>{{ $scanResult['user']->nuptk ?? '-' }}</b></div>
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

    @script
    <script>
        // Live Clock Ticking Script
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

        let codeReader = null;
        let selectedDeviceId = null;
        let isProcessingScan = false;

        function getCodeReader() {
            if (!codeReader && typeof ZXing !== 'undefined') {
                codeReader = new ZXing.BrowserMultiFormatReader();
            }
            return codeReader;
        }

        function playBeepSound(isSuccess) {
            try {
                let audio = document.getElementById('audioBeep');
                if (audio) {
                    audio.currentTime = 0;
                    audio.play().catch(e => console.log("Audio play prevented:", e));
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

        $wire.on('play-beep', (event) => {
            let isSuccess = true;
            if (Array.isArray(event) && event.length > 0) {
                isSuccess = event[0].success !== false;
            } else if (event && typeof event.success !== 'undefined') {
                isSuccess = event.success !== false;
            }
            playBeepSound(isSuccess);
        });

        function initScanner() {
            const reader = getCodeReader();
            if (!reader) {
                setTimeout(initScanner, 300);
                return;
            }

            const $select = $('#pilihKamera');
            reader.getVideoInputDevices()
                .then((videoInputDevices) => {
                    $select.empty();
                    if (videoInputDevices.length > 0) {
                        videoInputDevices.forEach((element, index) => {
                            const opt = document.createElement('option');
                            opt.text = element.label || `Kamera ${index + 1}`;
                            opt.value = element.deviceId;
                            $select.append(opt);
                        });

                        let backCam = videoInputDevices.find(d => d.label.toLowerCase().includes('back') || d.label.toLowerCase().includes('environment'));
                        selectedDeviceId = backCam ? backCam.deviceId : videoInputDevices[0].deviceId;
                        $select.val(selectedDeviceId);
                        
                        startDecoding();
                    } else {
                        $select.html('<option value="">Tidak ada kamera terdeteksi</option>');
                    }
                })
                .catch((err) => {
                    console.error("Camera error:", err);
                    $select.html('<option value="">Gagal mengakses kamera</option>');
                });
        }

        function startDecoding() {
            const reader = getCodeReader();
            if (!reader || !selectedDeviceId) return;

            try {
                reader.reset();
            } catch(e) {}

            reader.decodeFromVideoDevice(selectedDeviceId, 'previewKamera', (result, err) => {
                if (result && !isProcessingScan) {
                    let text = '';
                    if (typeof result.getText === 'function') {
                        text = result.getText().trim();
                    } else if (result.text) {
                        text = result.text.trim();
                    }

                    if (text.length > 0) {
                        isProcessingScan = true;
                        console.log("QR Code Scanned:", text);
                        
                        $wire.processScan(text).then(() => {
                            setTimeout(() => {
                                isProcessingScan = false;
                            }, 2500);
                        }).catch(e => {
                            console.error("Process scan error:", e);
                            setTimeout(() => {
                                isProcessingScan = false;
                            }, 1000);
                        });
                    }
                }
            });
        }

        $(document).on('change', '#pilihKamera', function () {
            selectedDeviceId = $(this).val();
            startDecoding();
        });

        $(document).ready(function() {
            initScanner();
        });
    </script>
    @endscript
</div>
