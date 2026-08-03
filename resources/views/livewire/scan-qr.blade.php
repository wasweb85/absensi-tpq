<div class="min-h-screen bg-gray-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-2xl">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Absensi Siswa & Guru (QR Code/RFID)
        </h2>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-2xl">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold {{ $waktu == 'masuk' ? 'text-green-600' : 'text-yellow-600' }}">
                    Absen {{ ucfirst($waktu) }}
                </h3>
                <button 
                    wire:click="setWaktu('{{ $waktu == 'masuk' ? 'pulang' : 'masuk' }}')"
                    class="px-4 py-2 rounded-md text-sm font-medium text-white {{ $waktu == 'masuk' ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }}">
                    Ganti ke Absen {{ $waktu == 'masuk' ? 'Pulang' : 'Masuk' }}
                </button>
            </div>

            <!-- RESULT MESSAGE -->
            @if($scanResult)
                <div class="mb-6 p-4 rounded-md {{ $scanStatus == 'success' ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800' }}">
                    <h4 class="text-lg font-bold">{{ $scanResult['message'] }}</h4>
                    @if(isset($scanResult['nama']))
                        <p class="font-medium mt-1">Nama: {{ $scanResult['nama'] }}</p>
                    @endif
                    @if(isset($scanResult['info']))
                        <p class="text-sm mt-1">{{ $scanResult['info'] }}</p>
                    @endif
                </div>
            @endif

            <!-- SCANNER SECTION -->
            <div x-data="scannerSetup()" x-init="initScanner()" class="space-y-6">
                <!-- Toggles -->
                <div class="flex justify-center space-x-6">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" x-model="useCamera" class="sr-only peer" @change="toggleCamera()">
                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                        <span class="ms-3 text-sm font-medium text-gray-900">Gunakan Kamera</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" x-model="useRfid" class="sr-only peer" @change="toggleRfid()">
                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                        <span class="ms-3 text-sm font-medium text-gray-900">Gunakan RFID</span>
                    </label>
                </div>

                <!-- Camera UI -->
                <div x-show="useCamera" class="border rounded-lg p-4 bg-gray-50 text-center">
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kamera</label>
                        <select id="pilihKamera" class="block w-full max-w-xs mx-auto pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm rounded-md" @change="changeCamera($event.target.value)">
                            <option>Loading cameras...</option>
                        </select>
                    </div>
                    <div class="relative max-w-sm mx-auto aspect-square bg-black rounded-lg overflow-hidden flex items-center justify-center">
                        <h4 x-show="isSearching" class="absolute text-white font-bold z-10">Mencari...</h4>
                        <video id="previewKamera" class="w-full h-full object-cover" x-show="!isSearching"></video>
                    </div>
                </div>

                <!-- RFID UI -->
                <div x-show="useRfid" class="border rounded-lg p-6 bg-gray-50 text-center">
                    <div class="mb-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium" :class="rfidFocused ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                            <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 11V9h4v2H8zm0-4V5h4v2H8z"/></svg>
                            <span x-text="rfidFocused ? 'RFID Reader: Siap' : 'RFID Reader: Tidak Fokus (Klik Disini)'"></span>
                        </span>
                    </div>
                    <input type="text" id="rfidInput" x-ref="rfidInput" class="block w-full max-w-xs mx-auto text-center font-mono border-2 focus:ring-purple-500 rounded-md sm:text-sm" :class="rfidFocused ? 'border-green-500 focus:border-green-500' : 'border-gray-300'" placeholder="Siap Scan Kartu RFID" @focus="rfidFocused = true" @blur="rfidFocused = false" @keydown.enter="processRfid($event)">
                    <p class="mt-2 text-xs text-gray-500">Pastikan input di atas memiliki garis hijau saat scan.</p>
                </div>

            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('dashboard') }}" class="text-sm font-medium text-purple-600 hover:text-purple-500">
                    &larr; Kembali ke Dashboard
                </a>
            </div>

        </div>
    </div>

    <!-- AUDIO ELEMENTS -->
    <audio id="audioSuccess" src="/assets/audio/beep.mp3"></audio>
    <audio id="audioError" src="/assets/audio/error.mp3"></audio> <!-- Assuming you have an error beep, or use the same -->

    <!-- ZXing library -->
    <script type="text/javascript" src="/assets/js/plugins/zxing/zxing.min.js"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('playAudio', (type) => {
                if(type == 'error') {
                    // Try to play error audio if exists, otherwise fallback to success audio but maybe modify it
                    let audio = document.getElementById('audioError') || document.getElementById('audioSuccess');
                    if(audio) audio.play();
                } else {
                    let audio = document.getElementById('audioSuccess');
                    if(audio) audio.play();
                }
            });
        });

        document.addEventListener('alpine:init', () => {
            Alpine.data('scannerSetup', () => ({
                useCamera: true,
                useRfid: false,
                isSearching: false,
                rfidFocused: false,
                codeReader: null,
                selectedDeviceId: null,

                initScanner() {
                    this.codeReader = new ZXing.BrowserMultiFormatReader();
                    if(this.useCamera) this.startCamera();
                },

                toggleCamera() {
                    if(this.useCamera) {
                        this.startCamera();
                    } else {
                        if(this.codeReader) this.codeReader.reset();
                    }
                },

                toggleRfid() {
                    if(this.useRfid) {
                        setTimeout(() => this.$refs.rfidInput.focus(), 100);
                    }
                },

                changeCamera(deviceId) {
                    this.selectedDeviceId = deviceId;
                    if(this.useCamera && this.codeReader) {
                        this.codeReader.reset();
                        this.startCamera();
                    }
                },

                startCamera() {
                    this.isSearching = true;
                    this.codeReader.listVideoInputDevices()
                        .then(videoInputDevices => {
                            const sourceSelect = document.getElementById('pilihKamera');
                            if(videoInputDevices.length >= 1) {
                                sourceSelect.innerHTML = '';
                                videoInputDevices.forEach((element) => {
                                    const sourceOption = document.createElement('option');
                                    sourceOption.text = element.label;
                                    sourceOption.value = element.deviceId;
                                    sourceSelect.appendChild(sourceOption);
                                });
                                
                                if(!this.selectedDeviceId) {
                                    this.selectedDeviceId = videoInputDevices[videoInputDevices.length > 1 ? 1 : 0].deviceId;
                                }
                                sourceSelect.value = this.selectedDeviceId;
                                
                                this.isSearching = false;
                                this.codeReader.decodeOnceFromVideoDevice(this.selectedDeviceId, 'previewKamera')
                                    .then(result => {
                                        this.processCode(result.text);
                                        // Reset and start again after delay
                                        setTimeout(() => {
                                            if(this.useCamera) this.startCamera();
                                        }, 2500);
                                    })
                                    .catch(err => {
                                        console.warn(err);
                                    });
                            } else {
                                sourceSelect.innerHTML = '<option>Camera not found</option>';
                            }
                        })
                        .catch(err => console.error(err));
                },

                processRfid(e) {
                    let code = e.target.value.trim();
                    if(code.length > 0) {
                        this.processCode(code);
                        e.target.value = '';
                    }
                },

                processCode(code) {
                    // Send to Livewire component
                    @this.dispatch('processQrCode', { uniqueCode: code });
                }
            }));
        });
    </script>
</div>
