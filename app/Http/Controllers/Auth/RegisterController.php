<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\Otp;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class RegisterController extends Controller
{
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
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'is_admin' => false,
        ]);

        // Remove any existing otps for user (cleanup)
        Otp::where('user_id', $user->id)->delete();

        // create hashed OTP and get plain code (uses model helper)
        $plainCode = Otp::createForUser($user->id, 15);
        $latestOtp = Otp::where('user_id', $user->id)->latest()->first();

        // send email with OTP (uses the OtpMail that expects code + expires_at)
        try {
            Mail::to($user->email)->send(new OtpMail($plainCode, $latestOtp?->expires_at));
        } catch (\Throwable $e) {
            // mailer might not be configured — still proceed but inform user
            return redirect()->route('otp.verify.show')
                ->with('status', 'Akun dibuat. Gagal mengirim OTP lewat email: ' . $e->getMessage());
        }

        return redirect()->route('otp.verify.show')
            ->with('status', 'Akun dibuat. Kode OTP telah dikirim ke email. Silakan verifikasi.');
    }
}
