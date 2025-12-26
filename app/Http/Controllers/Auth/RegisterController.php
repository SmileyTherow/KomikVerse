<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function show()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        // create user (not verified yet)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'is_admin' => false,
            'gender' => $request->gender ?? null,
            'bio' => $request->bio ?? null,
        ]);

        // try to create & send OTP (service will cleanup previous and enforce throttle)
        try {
            $this->otpService->createAndSend($user);
        } catch (\Throwable $e) {
            // Account is created but OTP sending failed — still continue to verify page with message
            // Ensure user is NOT logged in
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return redirect()->route('otp.verify.show')
                ->with('status', 'Akun dibuat. Gagal mengirim OTP lewat email: ' . $e->getMessage());
        }

        // Ensure user is NOT logged in (prevent auto-login)
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('otp.verify.show')
            ->with('status', 'Akun dibuat. Kode OTP telah dikirim ke email. Silakan verifikasi.');
    }
}
