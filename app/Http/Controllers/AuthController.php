<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLogin()
    {
        if (session()->has('user_id')) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Proses login dengan rate limiting.
     * Maksimal 5x percobaan per menit per IP.
     */
    public function login(Request $request)
    {
        // ===== RATE LIMITING =====
        // Kunci berdasarkan email + IP address
        $kunci = 'login.' . $request->ip();

        if (RateLimiter::tooManyAttempts($kunci, 5)) {
            $detik = RateLimiter::availableIn($kunci);
            return back()
                ->withInput($request->only('email'))
                ->with('error', "Terlalu banyak percobaan login. "
                              . "Coba lagi dalam {$detik} detik.");
        }

        // Validasi input
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
        ]);

        // Cari user
        $user = User::where('email', $request->email)->first();

        // Cek password
        if (!$user || !Hash::check($request->password, $user->password)) {
            // Tambah hitungan percobaan gagal
            RateLimiter::hit($kunci, 60); // reset setelah 60 detik

            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email atau password salah.');
        }

        // Login berhasil → reset rate limiter
        RateLimiter::clear($kunci);

        // Regenerasi session untuk mencegah session fixation attack
        $request->session()->regenerate();

        // Simpan data ke session
        session([
            'user_id'    => $user->id,
            'user_name'  => $user->name,
            'user_email' => $user->email,
        ]);

        return redirect()->route('dashboard')
                         ->with('success', 'Selamat datang, ' . $user->name . '!');
    }

    /**
     * Logout — hapus session.
     */
    public function logout(Request $request)
    {
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect()->route('login')
                         ->with('success', 'Anda berhasil logout.');
    }
}