<div class="min-h-screen bg-slate-100 flex flex-col justify-center py-10 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-2xl">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">
                Absensi TPQ (Scan QR Code)
            </h2>
            <p class="mt-2 text-sm text-slate-500 font-medium">
                Arahkan QR Code Kartu Siswa / Guru ke Kamera untuk Absensi
            </p>
        </div>
    </div>

    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-2xl">
        <div class="bg-white py-6 px-4 shadow-xl rounded-2xl sm:px-10 border border-slate-200">
            
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100">
                <div class="flex items-center space-x-3">
                    <span class="flex h-3 w-3 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $waktu == 'masuk' ? 'bg-emerald-400' : 'bg-amber-400' }} opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 {{ $waktu == 'masuk' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                    </span>
                    <h3 class="text-xl font-bold {{ $waktu == 'masuk' ? 'text-emerald-700' : 'text-amber-700' }}">
                        Mode Absen {{ ucfirst($waktu) }}
                    </h3>
                </div>
                <button 
                    wire:click="setWaktu('{{ $waktu == 'masuk' ? 'pulang' : 'masuk' }}')"
                    class="px-4 py-2 rounded-xl text-xs font-bold text-white transition-all shadow-md active:scale-95 {{ $waktu == 'masuk' ? 'bg-amber-500 hover:bg-amber-600 shadow-amber-200' : 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-200' }}">
                    Ganti ke Absen {{ $waktu == 'masuk' ? 'Pulang' : 'Masuk' }}
                </button>
            </div>

            <!-- RESULT NOTIFICATION CARD -->
            @if($scanResult)
                <div class="mb-6 p-5 rounded-2xl border-2 transition-all duration-300 shadow-lg {{ $scanStatus == 'success' ? 'bg-emerald-50 border-emerald-300 text-emerald-900' : 'bg-rose-50 border-rose-300 text-rose-900' }}">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 mt-1">
                            @if($scanStatus == 'success')
                                <div class="w-12 h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-md">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            @else
                                <div class="w-12 h-12 rounded-full bg-rose-500 text-white flex items-center justify-center shadow-md">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-lg font-bold tracking-tight {{ $scanStatus == 'success' ? 'text-emerald-800' : 'text-rose-800' }}">
                                {{ $scanResult['message'] }}
                            </h4>
                            @if(isset($scanResult['nama']))
                                <p class="text-base font-extrabold mt-1 text-slate-800">
                                    {{ $scanResult['nama'] }} 
                                    @if(isset($scanResult['role']))
                                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $scanResult['role'] == 'Guru / Ustadz' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                            {{ $scanResult['role'] }}
                                        </span>
                                    @endif
                                </p>
                            @endif
                            @if(isset($scanResult['kelas']))
                                <p class="text-xs font-medium text-slate-600 mt-0.5">Kelas: {{ $scanResult['kelas'] }}</p>
                            @endif
                            @if(isset($scanResult['info']))
                                <p class="text-sm font-semibold mt-1.5 {{ $scanStatus == 'success' ? 'text-emerald-700' : 'text-rose-700' }}">
                                    {{ $scanResult['info'] }}
                                </p>
                            @endif
                        </div>
                        <button wire:click="resetResult" class="text-slate-400 hover:text-slate-600 p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
            @endif

            <!-- SCANNER SECTION (wire:ignore prevents DOM re-render from resetting camera state) -->
            <div wire:ignore x-data="scannerSetup()" x-init="initScanner()" class="space-y-6">
                <!-- Camera UI -->
                <div class="border border-slate-200 rounded-xl p-4 bg-slate-50 text-center">
                    <div class="mb-3">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Pilih Kamera</label>
                        <select id="pilihKamera" class="block w-full max-w-xs mx-auto pl-3 pr-10 py-2 text-sm border-slate-300 focus:outline-none focus:ring-purple-500 focus:border-purple-500 rounded-lg bg-white" @change="changeCamera($event.target.value)">
                            <option>Memuat kamera...</option>
                        </select>
                    </div>
                    <div class="relative max-w-sm mx-auto aspect-square bg-slate-900 rounded-xl overflow-hidden flex items-center justify-center shadow-inner border-2 border-slate-700">
                        <h4 x-show="isSearching" class="absolute text-white font-bold z-10 animate-pulse text-sm">Mencari Kamera...</h4>
                        <video id="previewKamera" class="w-full h-full object-cover" x-show="!isSearching"></video>
                        <!-- Scanner Overlay -->
                        <div x-show="!isSearching" class="absolute inset-0 pointer-events-none border-2 border-purple-500 opacity-60 rounded-lg m-8 flex items-center justify-center">
                            <div x-show="isProcessing" class="bg-purple-950/80 text-white font-bold text-xs px-3 py-1.5 rounded-full animate-bounce">
                                Memproses QR Code...
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 text-center border-t border-slate-100 pt-4">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-bold text-purple-600 hover:text-purple-700">
                    &larr; Kembali ke Dashboard
                </a>
            </div>

        </div>
    </div>

    <!-- ZXing library -->
    <script type="text/javascript" src="/assets/js/plugins/zxing/zxing.min.js"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('playAudio', (eventData) => {
                let type = 'success';
                if (typeof eventData === 'string') {
                    type = eventData;
                } else if (Array.isArray(eventData) && eventData.length > 0) {
                    type = eventData[0];
                } else if (eventData && eventData.type) {
                    type = eventData.type;
                }
                playBeepSound(type);
            });
        });

        function playBeepSound(type) {
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) return;
                const ctx = new AudioCtx();

                if (type === 'success') {
                    // High double beep for success (880Hz)
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(880, ctx.currentTime);
                    gain.gain.setValueAtTime(0.3, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.25);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start();
                    osc.stop(ctx.currentTime + 0.25);
                } else {
                    // Low error warning tone (220Hz)
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = 'sawtooth';
                    osc.frequency.setValueAtTime(220, ctx.currentTime);
                    gain.gain.setValueAtTime(0.3, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.4);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start();
                    osc.stop(ctx.currentTime + 0.4);
                }
            } catch(e) {
                console.log("Audio playback error:", e);
            }
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('scannerSetup', () => ({
                isSearching: false,
                codeReader: null,
                selectedDeviceId: null,
                isProcessing: false,
                lastCode: null,

                initScanner() {
                    if (typeof ZXing !== 'undefined') {
                        this.codeReader = new ZXing.BrowserMultiFormatReader();
                        this.startCamera();
                    }
                },

                changeCamera(deviceId) {
                    this.selectedDeviceId = deviceId;
                    if(this.codeReader) {
                        this.codeReader.reset();
                        this.startCamera();
                    }
                },

                startCamera() {
                    if (!this.codeReader) return;
                    this.isSearching = true;
                    this.codeReader.listVideoInputDevices()
                        .then(videoInputDevices => {
                            const sourceSelect = document.getElementById('pilihKamera');
                            if(videoInputDevices.length >= 1) {
                                sourceSelect.innerHTML = '';
                                videoInputDevices.forEach((element) => {
                                    const sourceOption = document.createElement('option');
                                    sourceOption.text = element.label || `Kamera ${sourceSelect.length + 1}`;
                                    sourceOption.value = element.deviceId;
                                    sourceSelect.appendChild(sourceOption);
                                });
                                
                                if(!this.selectedDeviceId) {
                                    this.selectedDeviceId = videoInputDevices[videoInputDevices.length > 1 ? 1 : 0].deviceId;
                                }
                                sourceSelect.value = this.selectedDeviceId;
                                
                                this.isSearching = false;
                                this.codeReader.decodeFromVideoDevice(this.selectedDeviceId, 'previewKamera', (result, err) => {
                                    if (result && !this.isProcessing) {
                                        let text = result.getText().trim();
                                        if (text) {
                                            this.processCode(text);
                                        }
                                    }
                                });
                            } else {
                                sourceSelect.innerHTML = '<option>Kamera tidak ditemukan</option>';
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            this.isSearching = false;
                        });
                },

                processCode(code) {
                    if (!code || this.isProcessing) return;
                    this.isProcessing = true;
                    this.lastCode = code;

                    // Direct Livewire call
                    $wire.processQrCode(code).then(() => {
                        // Keep lock for 3 seconds to avoid duplicate immediate re-scanning of the same card
                        setTimeout(() => {
                            this.isProcessing = false;
                        }, 3000);
                    }).catch(err => {
                        console.error("Livewire error:", err);
                        setTimeout(() => {
                            this.isProcessing = false;
                        }, 1500);
                    });
                }
            }));
        });
    </script>
</div>
