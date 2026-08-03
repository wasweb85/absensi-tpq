<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SiswaModel;

class AuthSiswa extends BaseController
{
    public function index()
    {
        // Jika sudah login, langsung lempar ke dashboard siswa
        if (session()->get('is_siswa_logged_in')) {
            return redirect()->to('/siswa/dashboard');
        }
        
        // Tampilkan file login_siswa.php yang baru kita buat
        return view('auth/login_siswa'); 
    }

    public function login()
    {
        $siswaModel = new SiswaModel();
        $nis = $this->request->getVar('nis');
        $nama = $this->request->getVar('nama');

        // Cek Data Siswa
        $siswa = $siswaModel->where('nis', $nis)->where('nama_siswa', $nama)->first();

        if ($siswa) {
            // SET SESSION SISWA
            session()->set([
                'is_siswa_logged_in' => true,
                'siswa_id'   => $siswa['id_siswa'],
                'nama_siswa' => $siswa['nama_siswa'],
                'nis'        => $siswa['nis'],
                'id_kelas'   => $siswa['id_kelas']
            ]);
            
            // REDIRECT KE DASHBOARD SISWA (Bukan Admin)
            return redirect()->to('/siswa/dashboard');
        } else {
            session()->setFlashdata('error', 'NIS atau Nama tidak ditemukan / tidak sesuai.');
            return redirect()->to('/login-siswa')->withInput();
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}