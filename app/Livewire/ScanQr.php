<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\PresensiSiswa;
use App\Models\PresensiGuru;
use Carbon\Carbon;
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

        $code = trim((string) $uniqueCode);
        if (empty($code)) {
            $this->scanStatus = 'error';
            $this->scanResult = [
                'message' => 'Gagal Membaca Kode',
                'info' => 'Kode QR Code tidak terbaca.'
            ];
            $this->dispatch('playAudio', 'error');
            return;
        }

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
                $q->where('niup', $code)
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
            $this->scanStatus = 'error';
            $this->scanResult = [
                'message' => 'Data Tidak Ditemukan!',
                'info' => 'Kode "' . $code . '" tidak terdaftar pada database Siswa maupun Guru.'
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
                    'message' => 'Sudah Absen Masuk Hari Ini',
                    'nama' => $personData->nama_guru,
                    'role' => 'Guru / Ustadz',
                    'info' => 'Tercatat masuk pukul ' . $sudahAbsen->jam_masuk
                ];
                $this->dispatch('playAudio', 'error');
                return;
            }

            PresensiGuru::create([
                'id_guru' => $personData->id_guru,
                'tanggal' => $date,
                'id_kehadiran' => 1, // Hadir
                'jam_masuk' => $time,
                'jam_keluar' => null,
                'keterangan' => 'Hadir via Scan QR Code'
            ]);

            $this->scanStatus = 'success';
            $this->scanResult = [
                'message' => 'Berhasil Absen Masuk!',
                'nama' => $personData->nama_guru,
                'role' => 'Guru / Ustadz',
                'info' => 'Waktu Masuk: ' . $time
            ];

        } else {
            // Siswa
            $sudahAbsen = PresensiSiswa::where('id_siswa', $personData->id_siswa)
                ->whereDate('tanggal', $date)
                ->first();

            if ($sudahAbsen) {
                $this->scanStatus = 'error';
                $this->scanResult = [
                    'message' => 'Sudah Absen Masuk Hari Ini',
                    'nama' => $personData->nama_siswa,
                    'role' => 'Siswa',
                    'kelas' => $personData->kelas ? $personData->kelas->tingkat . ' ' . $personData->kelas->index_kelas : '-',
                    'info' => 'Tercatat masuk pukul ' . $sudahAbsen->jam_masuk
                ];
                $this->dispatch('playAudio', 'error');
                return;
            }

            PresensiSiswa::create([
                'id_siswa' => $personData->id_siswa,
                'id_kelas' => $personData->id_kelas,
                'tanggal' => $date,
                'id_kehadiran' => 1, // Hadir
                'jam_masuk' => $time,
                'jam_keluar' => null,
                'keterangan' => 'Hadir via Scan QR Code'
            ]);

            $this->scanStatus = 'success';
            $this->scanResult = [
                'message' => 'Berhasil Absen Masuk!',
                'nama' => $personData->nama_siswa,
                'role' => 'Siswa',
                'kelas' => $personData->kelas ? $personData->kelas->tingkat . ' ' . $personData->kelas->index_kelas : '-',
                'info' => 'Waktu Masuk: ' . $time
            ];
        }

        $this->dispatch('playAudio', 'success');
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
                    'message' => 'Belum Absen Masuk Hari Ini',
                    'nama' => $personData->nama_guru,
                    'role' => 'Guru / Ustadz',
                    'info' => 'Silakan melakukan absen masuk terlebih dahulu.'
                ];
                $this->dispatch('playAudio', 'error');
                return;
            }

            if ($sudahAbsen->jam_keluar) {
                $this->scanStatus = 'error';
                $this->scanResult = [
                    'message' => 'Sudah Absen Pulang Hari Ini',
                    'nama' => $personData->nama_guru,
                    'role' => 'Guru / Ustadz',
                    'info' => 'Tercatat pulang pukul ' . $sudahAbsen->jam_keluar
                ];
                $this->dispatch('playAudio', 'error');
                return;
            }

            $sudahAbsen->update(['jam_keluar' => $time]);

            $this->scanStatus = 'success';
            $this->scanResult = [
                'message' => 'Berhasil Absen Pulang!',
                'nama' => $personData->nama_guru,
                'role' => 'Guru / Ustadz',
                'info' => 'Waktu Pulang: ' . $time
            ];

        } else {
            // Siswa
            $sudahAbsen = PresensiSiswa::where('id_siswa', $personData->id_siswa)
                ->whereDate('tanggal', $date)
                ->first();

            if (!$sudahAbsen) {
                $this->scanStatus = 'error';
                $this->scanResult = [
                    'message' => 'Belum Absen Masuk Hari Ini',
                    'nama' => $personData->nama_siswa,
                    'role' => 'Siswa',
                    'kelas' => $personData->kelas ? $personData->kelas->tingkat . ' ' . $personData->kelas->index_kelas : '-',
                    'info' => 'Silakan melakukan absen masuk terlebih dahulu.'
                ];
                $this->dispatch('playAudio', 'error');
                return;
            }

            if ($sudahAbsen->jam_keluar) {
                $this->scanStatus = 'error';
                $this->scanResult = [
                    'message' => 'Sudah Absen Pulang Hari Ini',
                    'nama' => $personData->nama_siswa,
                    'role' => 'Siswa',
                    'kelas' => $personData->kelas ? $personData->kelas->tingkat . ' ' . $personData->kelas->index_kelas : '-',
                    'info' => 'Tercatat pulang pukul ' . $sudahAbsen->jam_keluar
                ];
                $this->dispatch('playAudio', 'error');
                return;
            }

            $sudahAbsen->update(['jam_keluar' => $time]);

            $this->scanStatus = 'success';
            $this->scanResult = [
                'message' => 'Berhasil Absen Pulang!',
                'nama' => $personData->nama_siswa,
                'role' => 'Siswa',
                'kelas' => $personData->kelas ? $personData->kelas->tingkat . ' ' . $personData->kelas->index_kelas : '-',
                'info' => 'Waktu Pulang: ' . $time
            ];
        }

        $this->dispatch('playAudio', 'success');
    }

    public function render()
    {
        return view('livewire.scan-qr')->layout('layouts.guest'); // Use guest layout (no sidebar)
    }
}

