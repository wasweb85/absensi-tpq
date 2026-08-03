<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\PresensiGuru;
use App\Models\PresensiSiswa;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class ScanIndex extends Component
{
    public $waktu = 'masuk'; // masuk or pulang
    public $unique_code = '';
    
    // Result
    public $scanResult = null;
    public $scanMessage = '';
    public $scanSuccess = false;

    // We'll dispatch an event to play the beep sound in JS
    public function updatedUniqueCode($value)
    {
        if (strlen($value) > 0) {
            $this->processScan();
        }
    }

    public function processScan()
    {
        $code = trim($this->unique_code);
        $this->unique_code = ''; // reset for next scan

        if (empty($code)) return;

        // Try to find Siswa first
        $siswa = Siswa::where('unique_code', $code)->orWhere('rfid_code', $code)->first();
        $guru = null;
        
        $type = 'siswa';
        $user = $siswa;

        if (!$siswa) {
            $guru = Guru::where('unique_code', $code)->orWhere('rfid_code', $code)->first();
            if ($guru) {
                $type = 'guru';
                $user = $guru;
            }
        }

        if (!$user) {
            $this->scanSuccess = false;
            $this->scanMessage = 'Data tidak ditemukan (QR/RFID tidak terdaftar)';
            $this->scanResult = null;
            $this->dispatch('play-beep', ['success' => false]);
            return;
        }

        if ($this->waktu === 'masuk') {
            $this->processAbsenMasuk($type, $user);
        } else {
            $this->processAbsenPulang($type, $user);
        }
    }

    private function processAbsenMasuk($type, $user)
    {
        $date = Carbon::today()->toDateString();
        $time = Carbon::now()->toTimeString();
        $messageString = " sudah absen masuk pada tanggal $date jam $time";

        $presensiResult = null;

        if ($type === 'guru') {
            $idGuru = $user->id_guru;
            $sudahAbsen = PresensiGuru::where('id_guru', $idGuru)->where('tanggal', $date)->first();

            if ($sudahAbsen) {
                $this->scanSuccess = false;
                $this->scanMessage = 'Anda sudah absen masuk hari ini.';
                $this->scanResult = ['type' => $type, 'user' => $user, 'presensi' => $sudahAbsen];
                $this->dispatch('play-beep', ['success' => false]);
                return;
            }

            $presensiResult = PresensiGuru::create([
                'id_guru' => $idGuru,
                'tanggal' => $date,
                'jam_masuk' => $time,
                'id_kehadiran' => 1, // 1 = Hadir
                'keterangan' => ''
            ]);

            $messageString = $user->nama_guru . ' dengan NIP ' . ($user->nuptk ?? '-') . $messageString;
        } else {
            $idSiswa = $user->id_siswa;
            $sudahAbsen = PresensiSiswa::where('id_siswa', $idSiswa)->where('tanggal', $date)->first();

            if ($sudahAbsen) {
                $this->scanSuccess = false;
                $this->scanMessage = 'Anda sudah absen masuk hari ini.';
                $this->scanResult = ['type' => $type, 'user' => $user, 'presensi' => $sudahAbsen];
                $this->dispatch('play-beep', ['success' => false]);
                return;
            }

            $presensiResult = PresensiSiswa::create([
                'id_siswa' => $idSiswa,
                'id_kelas' => $user->id_kelas,
                'tanggal' => $date,
                'jam_masuk' => $time,
                'id_kehadiran' => 1, // 1 = Hadir
                'keterangan' => ''
            ]);

            $messageString = 'Siswa ' . $user->nama_siswa . ' dengan NIS ' . ($user->nis ?? '-') . $messageString;
        }

        $this->scanSuccess = true;
        $this->scanMessage = 'Berhasil absen masuk.';
        $this->scanResult = ['type' => $type, 'user' => $user, 'presensi' => $presensiResult];
        $this->dispatch('play-beep', ['success' => true]);

        $this->sendWhatsAppNotification($user->no_hp ?? '', $messageString);
    }

    private function processAbsenPulang($type, $user)
    {
        $date = Carbon::today()->toDateString();
        $time = Carbon::now()->toTimeString();
        $messageString = " sudah absen pulang pada tanggal $date jam $time";

        if ($type === 'guru') {
            $idGuru = $user->id_guru;
            $sudahAbsen = PresensiGuru::where('id_guru', $idGuru)->where('tanggal', $date)->first();

            if (!$sudahAbsen) {
                $this->scanSuccess = false;
                $this->scanMessage = 'Anda belum absen masuk hari ini.';
                $this->scanResult = ['type' => $type, 'user' => $user];
                $this->dispatch('play-beep', ['success' => false]);
                return;
            }

            $sudahAbsen->update(['jam_keluar' => $time]);
            $messageString = $user->nama_guru . ' dengan NIP ' . ($user->nuptk ?? '-') . $messageString;
            
            $this->scanResult = ['type' => $type, 'user' => $user, 'presensi' => clone $sudahAbsen];
        } else {
            $idSiswa = $user->id_siswa;
            $sudahAbsen = PresensiSiswa::where('id_siswa', $idSiswa)->where('tanggal', $date)->first();

            if (!$sudahAbsen) {
                $this->scanSuccess = false;
                $this->scanMessage = 'Anda belum absen masuk hari ini.';
                $this->scanResult = ['type' => $type, 'user' => $user];
                $this->dispatch('play-beep', ['success' => false]);
                return;
            }

            $sudahAbsen->update(['jam_keluar' => $time]);
            $messageString = 'Siswa ' . $user->nama_siswa . ' dengan NIS ' . ($user->nis ?? '-') . $messageString;

            $this->scanResult = ['type' => $type, 'user' => $user, 'presensi' => clone $sudahAbsen];
        }

        $this->scanSuccess = true;
        $this->scanMessage = 'Berhasil absen pulang.';
        $this->dispatch('play-beep', ['success' => true]);

        $this->sendWhatsAppNotification($user->no_hp ?? '', $messageString);
    }

    private function sendWhatsAppNotification($noHp, $messageString)
    {
        $enabled = env('WA_NOTIFICATION', false);
        $token = env('WHATSAPP_TOKEN');
        $provider = env('WHATSAPP_PROVIDER', 'Fonnte');

        if ($enabled && !empty($noHp) && !empty($token) && $provider === 'Fonnte') {
            try {
                Http::withHeaders([
                    'Authorization' => $token
                ])->post('https://api.fonnte.com/send', [
                    'target' => $noHp,
                    'message' => $messageString,
                    'delay' => '0'
                ]);
            } catch (\Exception $e) {
                \Log::error('Fonnte Error: ' . $e->getMessage());
            }
        }
    }

    public function setWaktu($w)
    {
        $this->waktu = $w;
        $this->scanResult = null;
        $this->scanMessage = '';
    }

    public function render()
    {
        return view('livewire.scan-index')->layout('layouts.scan', ['title' => 'Absensi QR Code']);
    }
}
