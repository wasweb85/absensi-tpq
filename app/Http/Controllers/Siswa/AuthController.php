<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        // Jika sudah login, langsung ke dashboard siswa
        if (Auth::guard('siswa')->check()) {
            return redirect()->route('siswa.dashboard');
        }
        
        return view('auth.login_siswa'); 
    }

    public function login(Request $request)
    {
        $request->validate([
            'nis' => 'required',
            'nama' => 'required'
        ]);

        $nis = $request->input('nis');
        $nama = $request->input('nama');

        // Cek Data Siswa
        $siswa = Siswa::where('nis', $nis)->where('nama_siswa', $nama)->first();

        if ($siswa) {
            Auth::guard('siswa')->login($siswa);
            
            return redirect()->route('siswa.dashboard');
        } else {
            return back()->withInput()->with('error', 'NIS atau Nama tidak ditemukan / tidak sesuai.');
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('siswa')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('siswa.login');
    }
}
