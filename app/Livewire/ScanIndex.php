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
    public $unique_code = '';
    
    // Result
    public $scanResult = null;
    public $scanMessage = '';
    public $scanSuccess = false;

    public function updatedUniqueCode($value)
    {
        if (strlen($value) > 0) {
            $this->processScan();
        }
    }

    public function processScan($codeParam = null)
    {
        $code = trim((string) ($codeParam ?: $this->unique_code));
        $this->unique_code = ''; // reset for next scan

        if (empty($code)) return;

        $date = Carbon::today()->toDateString();
        $time = Carbon::now()->format('H:i:s');
        $isGuru = false;
        $personData = null;

        // Extract numeric ID if code starts with 'Siswa-' or 'Guru-'
        $siswaIdFromPrefix = null;
        if (preg_match('/^Siswa-(\d+)$/i', $code, $m)) {
            $siswaIdFromPrefix = (int) $m[1];
        }

        $guruIdFromPrefix = null;
        if (preg_match('/^Guru-(\d+)$/i', $code, $m)) {
            $guruIdFromPrefix = (int) $m[1];
        }

        // 1. Cek Siswa
        $siswa = Siswa::with('kelas')
            ->where(function ($q) use ($code, $siswaIdFromPrefix) {
                $q->where('nis', $code)
                  ->orWhere('rfid_code', $code)
                  ->orWhere('unique_code', $code);

                if ($siswaIdFromPrefix) {
                    $q->orWhere('id_siswa', $siswaIdFromPrefix);
                } elseif (is_numeric($code)) {
                    $q->orWhere('id_siswa', (int) $code);
                }
            })
            ->first();

        if ($siswa) {
            $personData = $siswa;
        } else {
            // 2. Cek Guru
            $guru = Guru::where(function ($q) use ($code, $guruIdFromPrefix) {
                $q->where('nuptk', $code)
                  ->orWhere('rfid_code', $code)
                  ->orWhere('unique_code', $code);

                if ($guruIdFromPrefix) {
                    $q->orWhere('id_guru', $guruIdFromPrefix);
                } elseif (is_numeric($code)) {
                    $q->orWhere('id_guru', (int) $code);
                }
            })
            ->first();

            if ($guru) {
                $isGuru = true;
                $personData = $guru;
            }
        }

        if (!$personData) {
            $this->scanSuccess = false;
            $this->scanMessage = 'Data tidak ditemukan! Pastikan QR Code valid.';
            $this->scanResult = null;
            $this->dispatch('play-beep', ['success' => false]);
            return;
        }

        $type = $isGuru ? 'guru' : 'siswa';
        $user = $personData;

        $this->processAbsenMasuk($type, $user);
    }

    private function processAbsenMasuk($type, $user)
    {
        $date = Carbon::today()->toDateString();
        $time = Carbon::now()->format('H:i:s');

        if ($type === 'guru') {
            $idGuru = $user->id_guru;
            $sudahAbsen = PresensiGuru::where('id_guru', $idGuru)->where('tanggal', $date)->first();

            if ($sudahAbsen) {
                $this->scanSuccess = false;
                $this->scanMessage = 'Sudah Absen Hari Ini (' . $user->nama_guru . ' - jam ' . $sudahAbsen->jam_masuk . ')';
                $this->scanResult = ['type' => $type, 'user' => $user, 'presensi' => $sudahAbsen];
                $this->dispatch('play-beep', ['success' => false]);
                return;
            }

            $presensiResult = PresensiGuru::create([
                'id_guru' => $idGuru,
                'tanggal' => $date,
                'jam_masuk' => $time,
                'id_kehadiran' => 1, // 1 = Hadir
                'keterangan' => 'Hadir via Scan QR Code'
            ]);

            $messageString = $user->nama_guru . ' dengan NIP ' . ($user->nuptk ?? '-') . " sudah absen pada tanggal $date jam $time";
        } else {
            $idSiswa = $user->id_siswa;
            $sudahAbsen = PresensiSiswa::where('id_siswa', $idSiswa)->where('tanggal', $date)->first();

            if ($sudahAbsen) {
                $this->scanSuccess = false;
                $this->scanMessage = 'Sudah Absen Hari Ini (' . $user->nama_siswa . ' - jam ' . $sudahAbsen->jam_masuk . ')';
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
                'keterangan' => 'Hadir via Scan QR Code'
            ]);

            $messageString = 'Siswa ' . $user->nama_siswa . ' dengan NIS ' . ($user->nis ?? '-') . " sudah absen pada tanggal $date jam $time";
        }

        $this->scanSuccess = true;
        $this->scanMessage = 'Berhasil absen pada jam ' . $time;
        $this->scanResult = ['type' => $type, 'user' => $user, 'presensi' => $presensiResult];
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

    public function render()
    {
        return view('livewire.scan-index')->layout('layouts.scan', ['title' => 'Absensi QR Code']);
    }
}
