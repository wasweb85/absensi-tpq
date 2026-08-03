<?php

namespace App\Services;

use CodeIgniter\I18n\Time;
use App\Models\GuruModel;
use App\Models\SiswaModel;
use App\Models\PresensiGuruModel;
use App\Models\PresensiSiswaModel;
use App\Libraries\enums\TipeUser;

class AttendanceService
{
    protected SiswaModel $siswaModel;
    protected GuruModel $guruModel;
    protected PresensiSiswaModel $presensiSiswaModel;
    protected PresensiGuruModel $presensiGuruModel;
    private bool $WANotificationEnabled;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->guruModel = new GuruModel();
        $this->presensiSiswaModel = new PresensiSiswaModel();
        $this->presensiGuruModel = new PresensiGuruModel();
        $this->WANotificationEnabled = getenv('WA_NOTIFICATION') === 'true';
    }

    public function processScan(string $uniqueCode, string $waktuAbsen): array
    {
        $status = false;
        $type = TipeUser::Siswa;
        
        $result = $this->siswaModel->cekSiswa($uniqueCode);
        
        if (empty($result)) {
            $result = $this->guruModel->cekGuru($uniqueCode);
            if (!empty($result)) {
                $status = true;
                $type = TipeUser::Guru;
            } else {
                $status = false;
                $result = null;
            }
        } else {
            $status = true;
        }

        if (!$status) {
            return ['success' => false, 'message' => 'Data tidak ditemukan'];
        }

        if ($waktuAbsen === 'masuk') {
            return $this->processAbsenMasuk($type, $result);
        } elseif ($waktuAbsen === 'pulang') {
            return $this->processAbsenPulang($type, $result);
        }

        return ['success' => false, 'message' => 'Data tidak valid'];
    }

    private function processAbsenMasuk($type, $result): array
    {
        $date = Time::today()->toDateString();
        $time = Time::now()->toTimeString();
        $messageString = " sudah absen masuk pada tanggal $date jam $time";
        $data = ['data' => $result, 'waktu' => 'masuk', 'type' => $type];

        if ($type === TipeUser::Guru) {
            $idGuru = $result['id_guru'];
            $sudahAbsen = $this->presensiGuruModel->cekAbsen($idGuru, $date);

            if ($sudahAbsen) {
                $data['presensi'] = $this->presensiGuruModel->getPresensiById($sudahAbsen);
                return ['success' => false, 'message' => 'Anda sudah absen hari ini', 'data' => $data];
            }

            $this->presensiGuruModel->absenMasuk($idGuru, $date, $time);
            $messageString = $result['nama_guru'] . ' dengan NIP ' . $result['nuptk'] . $messageString;
            $data['presensi'] = $this->presensiGuruModel->getPresensiByIdGuruTanggal($idGuru, $date);
        } elseif ($type === TipeUser::Siswa) {
            $idSiswa = $result['id_siswa'];
            $idKelas = $result['id_kelas'];
            $sudahAbsen = $this->presensiSiswaModel->cekAbsen($idSiswa, $date);

            if ($sudahAbsen) {
                $data['presensi'] = $this->presensiSiswaModel->getPresensiById($sudahAbsen);
                return ['success' => false, 'message' => 'Anda sudah absen hari ini', 'data' => $data];
            }

            $this->presensiSiswaModel->absenMasuk($idSiswa, $date, $time, $idKelas);
            $messageString = 'Siswa ' . $result['nama_siswa'] . ' dengan NIS ' . $result['nis'] . $messageString;
            $data['presensi'] = $this->presensiSiswaModel->getPresensiByIdSiswaTanggal($idSiswa, $date);
        } else {
            return ['success' => false, 'message' => 'Tipe tidak valid'];
        }

        $this->sendWhatsAppNotification($result['no_hp'] ?? '', $messageString);

        return ['success' => true, 'data' => $data];
    }

    private function processAbsenPulang($type, $result): array
    {
        $date = Time::today()->toDateString();
        $time = Time::now()->toTimeString();
        $messageString = " sudah absen pulang pada tanggal $date jam $time";
        $data = ['data' => $result, 'waktu' => 'pulang', 'type' => $type];

        if ($type === TipeUser::Guru) {
            $idGuru = $result['id_guru'];
            $sudahAbsen = $this->presensiGuruModel->cekAbsen($idGuru, $date);

            if (!$sudahAbsen) {
                return ['success' => false, 'message' => 'Anda belum absen hari ini', 'data' => $data];
            }

            $this->presensiGuruModel->absenKeluar($sudahAbsen, $time);
            $messageString = $result['nama_guru'] . ' dengan NIP ' . $result['nuptk'] . $messageString;
            $data['presensi'] = $this->presensiGuruModel->getPresensiById($sudahAbsen);
        } elseif ($type === TipeUser::Siswa) {
            $idSiswa = $result['id_siswa'];
            $sudahAbsen = $this->presensiSiswaModel->cekAbsen($idSiswa, $date);

            if (!$sudahAbsen) {
                return ['success' => false, 'message' => 'Anda belum absen hari ini', 'data' => $data];
            }

            $this->presensiSiswaModel->absenKeluar($sudahAbsen, $time);
            $messageString = 'Siswa ' . $result['nama_siswa'] . ' dengan NIS ' . $result['nis'] . $messageString;
            $data['presensi'] = $this->presensiSiswaModel->getPresensiById($sudahAbsen);
        } else {
            return ['success' => false, 'message' => 'Tipe tidak valid'];
        }

        $this->sendWhatsAppNotification($result['no_hp'] ?? '', $messageString);

        return ['success' => true, 'data' => $data];
    }

    private function sendWhatsAppNotification(string $noHp, string $messageString): void
    {
        if ($this->WANotificationEnabled && !empty($noHp)) {
            $message = [
                'destination' => $noHp,
                'message' => $messageString,
                'delay' => 0
            ];
            
            $token = getenv('WHATSAPP_TOKEN');
            $provider = getenv('WHATSAPP_PROVIDER');

            if (empty($provider) || empty($token)) {
                return;
            }

            try {
                if ($provider === 'Fonnte') {
                    $whatsapp = new \App\Libraries\Whatsapp\Fonnte\Fonnte($token);
                    $whatsapp->sendMessage($message);
                }
            } catch (\Exception $e) {
                log_message('error', 'Error sending notification: ' . $e->getMessage());
            }
        }
    }
}
