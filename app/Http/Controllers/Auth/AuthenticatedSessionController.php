<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Note: tambahkan PHPDoc supaya analyzer tahu tipe Auth::user()
 *
 * @package App\Http\Controllers\Auth
 */
class AuthenticatedSessionController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // coba autentikasi
        if (! Auth::attempt($credentials, $request->filled('remember'))) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        /**
         * @var \App\Models\User|null $user
         */
        $user = Auth::user();

        // safety: jika somehow user null, redirect back
        if (! $user) {
            Auth::logout();
            return back()->withErrors(['email' => 'Terjadi kesalahan saat login.']);
        }

        // cek status setelah berhasil autentikasi
        $status = $user->status ?? null;

        if ($status === 'blocked') {
            // logout dan kembalikan pesan
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Akun Anda diblokir. Hubungi admin untuk aktivasi kembali.',
            ]);
        }

        if ($status === 'pending' && is_null($user->email_verified_at)) {
            // user belum verifikasi OTP/email
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Akun Anda belum terverifikasi. Silakan lakukan verifikasi OTP terlebih dahulu.',
            ]);
        }

        // Tentukan apakah user adalah admin (kompatibel dengan isAdmin() atau kolom is_admin)
        $isAdmin = false;
        if ($user instanceof \App\Models\User) {
            $isAdmin = method_exists($user, 'isAdmin') ? $user->isAdmin() : (bool) ($user->is_admin ?? false);
        }

        // arahkan sesuai role (jaga intended jika ada)
        if ($isAdmin) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}