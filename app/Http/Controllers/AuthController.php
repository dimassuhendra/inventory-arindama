<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        // Mengirimkan session error untuk memicu modal SweetAlert2
        return back()->with('loginError', 'Akses Ditolak! Akun tidak ditemukan atau password salah.');
    }

    public function logout(Request $request)
    {
        // Opsional: Catat aktivitas logout sebelum session dihancurkan
        if (Auth::check()) {
            \App\Models\ActivityLogs::log("User Logout", Auth::user());
        }

        Auth::logout();

        // Menghapus session agar tidak bisa digunakan kembali
        $request->session()->invalidate();

        // Me-reset token CSRF agar aman dari serangan session fixation
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}