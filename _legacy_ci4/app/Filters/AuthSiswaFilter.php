<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthSiswaFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // if ($uri->getSegment(1) == 'admin') {
        //     return; // Admin boleh lewat tanpa dicek sesi siswanya
        // }
        // Cek apakah ada session 'is_siswa_logged_in' (Tanda pengenal siswa)
        if (!session()->get('is_siswa_logged_in')) {
            // Jika tidak ada tanda pengenal, tendang balik ke halaman login utama
            return redirect()->to('/login');
        }
        $uri = service('uri');

    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Bagian ini biarkan kosong
        // (Biasanya dipakai untuk log aktivitas setelah halaman dimuat)
    }
}