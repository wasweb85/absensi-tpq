<?php

namespace App\Controllers;

use App\Services\AttendanceService;

class Scan extends BaseController
{
   protected AttendanceService $attendanceService;

   public function __construct()
   {
      $this->attendanceService = new AttendanceService();
   }

   public function index($t = 'Masuk')
   {
      $data = ['waktu' => $t, 'title' => 'Absensi Siswa dan Guru Berbasis QR Code'];
      return view('scan/scan', $data);
   }

   public function cekKode()
   {
      // Validasi Input Dasar (Security Improvement)
      $rules = [
          'unique_code' => 'required|string|max_length[100]',
          'waktu'       => 'required|in_list[masuk,pulang]'
      ];

      if (!$this->validate($rules)) {
          return $this->showErrorView('Data input tidak valid. Pastikan QR code dan pilihan waktu sudah benar.');
      }

      $uniqueCode = $this->request->getVar('unique_code');
      $waktuAbsen = $this->request->getVar('waktu');

      $response = $this->attendanceService->processScan($uniqueCode, $waktuAbsen);

      if (!$response['success']) {
          $data = $response['data'] ?? null;
          return $this->showErrorView($response['message'], $data);
      }

      return view('scan/scan-result', $response['data']);
   }

   public function showErrorView(string $msg = 'no error message', $data = NULL)
   {
      $errdata = $data ?? [];
      $errdata['msg'] = $msg;

      return view('scan/error-scan-result', $errdata);
   }
}
