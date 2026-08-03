<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\PresensiSiswa;
use App\Models\PresensiGuru;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On;

class ScanQr extends Component
{
    public $waktu = 'masuk';
    public $scanResult = null; // To store result message/data
    public $scanStatus = null; // 'success' or 'error'

    // We can switch time via the UI
    public function setWaktu($waktu)
    {
        $this->waktu = $waktu;
        $this->resetResult();
    }

    public function resetResult()
    {
        $this->scanResult = null;
        $this->scanStatus = null;
    }

    #[On('processQrCode')]
    public function processQrCode($uniqueCode)
    {
        $this->resetResult();

        $date = Carbon::today()->toDateString();
        $time = Carbon::now()->toTimeString();
        $isGuru = false;
        $personData = null;

        // Cek Siswa
        $siswa = Siswa::where('rfid', $uniqueCode)
            ->orWhere('qr_code', $uniqueCode)
            ->first();

        if ($siswa) {
            $personData = $siswa;
        } else {
            // Cek Guru
            $guru = Guru::where('rfid', $uniqueCode)
                ->orWhere('qr_code', $uniqueCode)
                ->first();
            
            if ($guru) {
                $isGuru = true;
                $personData = $guru;
            }
        }

        if (!$personData) {
            $this->scanStatus = 'error';
            $this->scanResult = [
                'message' => 'Data tidak ditemukan! Pastikan QR Code atau RFID valid.'
            ];
            $this->dispatch('playAudio', 'error');
            return;
        }

        // Proses Kehadiran
        if ($this->waktu == 'masuk') {
            $this->handleAbsenMasuk($isGuru, $personData, $date, $time);
        } else {
            $this->handleAbsenPulang($isGuru, $personData, $date, $time);
        }
    }

    private function handleAbsenMasuk($isGuru, $personData, $date, $time)
    {
        if ($isGuru) {
            $sudahAbsen = PresensiGuru::where('id_guru', $personData->id_guru)
                ->whereDate('tanggal', $date)
                ->first();

            if ($sudahAbsen) {
                $this->scanStatus = 'error';
                $this->scanResult = [
                    'message' => 'Anda sudah absen hari ini.',
                    'nama' => $personData->nama_guru,
                    'info' => 'Waktu absen: ' . $sudahAbsen->jam_masuk
                ];
                $this->dispatch('playAudio', 'error');
                return;
            }

            PresensiGuru::create([
                'id_guru' => $personData->id_guru,
                'tanggal' => $date,
                'id_kehadiran' => 1,
                'jam_masuk' => $time,
                'jam_keluar' => null,
                'keterangan' => ''
            ]);

            $this->scanStatus = 'success';
            $this->scanResult = [
                'message' => 'Berhasil absen masuk!',
                'nama' => $personData->nama_guru,
                'info' => 'Waktu: ' . $time
            ];

        } else {
            // Siswa
            $sudahAbsen = PresensiSiswa::where('id_siswa', $personData->id_siswa)
                ->whereDate('tanggal', $date)
                ->first();

            if ($sudahAbsen) {
                $this->scanStatus = 'error';
                $this->scanResult = [
                    'message' => 'Anda sudah absen hari ini.',
                    'nama' => $personData->nama_siswa,
                    'info' => 'Waktu absen: ' . $sudahAbsen->jam_masuk
                ];
                $this->dispatch('playAudio', 'error');
                return;
            }

            PresensiSiswa::create([
                'id_siswa' => $personData->id_siswa,
                'id_kelas' => $personData->id_kelas,
                'tanggal' => $date,
                'id_kehadiran' => 1,
                'jam_masuk' => $time,
                'jam_keluar' => null,
                'keterangan' => ''
            ]);

            $this->scanStatus = 'success';
            $this->scanResult = [
                'message' => 'Berhasil absen masuk!',
                'nama' => $personData->nama_siswa,
                'info' => 'Waktu: ' . $time
            ];
        }

        $this->dispatch('playAudio', 'success');
        // Notifikasi WA dapat ditambahkan di sini
    }

    private function handleAbsenPulang($isGuru, $personData, $date, $time)
    {
        if ($isGuru) {
            $sudahAbsen = PresensiGuru::where('id_guru', $personData->id_guru)
                ->whereDate('tanggal', $date)
                ->first();

            if (!$sudahAbsen) {
                $this->scanStatus = 'error';
                $this->scanResult = [
                    'message' => 'Anda belum absen masuk hari ini.',
                    'nama' => $personData->nama_guru
                ];
                $this->dispatch('playAudio', 'error');
                return;
            }

            $sudahAbsen->update(['jam_keluar' => $time]);

            $this->scanStatus = 'success';
            $this->scanResult = [
                'message' => 'Berhasil absen pulang!',
                'nama' => $personData->nama_guru,
                'info' => 'Waktu keluar: ' . $time
            ];

        } else {
            // Siswa
            $sudahAbsen = PresensiSiswa::where('id_siswa', $personData->id_siswa)
                ->whereDate('tanggal', $date)
                ->first();

            if (!$sudahAbsen) {
                $this->scanStatus = 'error';
                $this->scanResult = [
                    'message' => 'Anda belum absen masuk hari ini.',
                    'nama' => $personData->nama_siswa
                ];
                $this->dispatch('playAudio', 'error');
                return;
            }

            $sudahAbsen->update(['jam_keluar' => $time]);

            $this->scanStatus = 'success';
            $this->scanResult = [
                'message' => 'Berhasil absen pulang!',
                'nama' => $personData->nama_siswa,
                'info' => 'Waktu keluar: ' . $time
            ];
        }

        $this->dispatch('playAudio', 'success');
    }

    public function render()
    {
        return view('livewire.scan-qr')->layout('layouts.guest'); // Use guest layout (no sidebar)
    }
}
