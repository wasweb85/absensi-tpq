<?php

namespace App\Controllers;

use Myth\Auth\Controllers\AuthController as MythAuthController;
use App\Models\SiswaModel;

class AuthController extends MythAuthController
{
    public function attemptLogin()
    {
        $login = $this->request->getPost('login');
        $password = $this->request->getPost('password');

        if ($login && $password) {
            $siswaModel = new SiswaModel();
            
            // Check if user is a student:
            // The user might enter their NIS or their Name in the 'login' field, 
            // and their NIS in the 'password' field.
            $siswa = $siswaModel->groupStart()
                                ->where('nis', $login)
                                ->orWhere('nama_siswa', $login)
                                ->groupEnd()
                                ->where('nis', $password)
                                ->first();

            if ($siswa) {
                // If matched, log in as Siswa
                session()->set([
                    'is_siswa_logged_in' => true,
                    'siswa_id'   => $siswa['id_siswa'],
                    'nama_siswa' => $siswa['nama_siswa'],
                    'nis'        => $siswa['nis'],
                    'id_kelas'   => $siswa['id_kelas']
                ]);
                
                return redirect()->to('/siswa/dashboard');
            }
        }

        // If it's not a student, continue with default Myth\Auth login logic
        return parent::attemptLogin();
    }
}
