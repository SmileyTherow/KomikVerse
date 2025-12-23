<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\OtpVerifyRequest;
use App\Http\Requests\ResendOtpRequest;
use App\Models\User;
use App\Models\Otp;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class OtpController extends Controller
{
    public function showVerifyForm()
    {
        return view('auth.otp_verify');
    }

    public function verify(OtpVerifyRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        if ($user->email_verified_at) {
            return redirect()->route('login')->with('status', 'Email sudah terverifikasi. Silakan login.');
        }

        // use model helper to verify hashed OTP
        $result = Otp::verifyCode($user->id, $request->code, 5);

        if (! $result['ok']) {
            return back()->withErrors(['code' => $result['message']]);
        }

        // success: mark user verified and delete otps
        $user->email_verified_at = Carbon::now();
        $user->save();

        Otp::where('user_id', $user->id)->delete();

        return redirect()->route('login')->with('status', 'Email berhasil diverifikasi. Silakan login.');
    }

    public function resend(ResendOtpRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        if ($user->email_verified_at) {
            return redirect()->route('login')->with('status', 'Email sudah terverifikasi. Silakan login.');
        }

        // rate limiting: simple check last OTP created -> at least 60s gap
        $last = Otp::where('user_id', $user->id)->latest()->first();
        if ($last && $last->created_at && $last->created_at->gt(now()->subSeconds(60))) {
            return back()->withErrors(['email' => 'Tunggu sebelum meminta OTP baru (1 menit).']);
        }

        // remove old otps and create new
        Otp::where('user_id', $user->id)->delete();

        $plainCode = Otp::createForUser($user->id, 15);
        $latestOtp = Otp::where('user_id', $user->id)->latest()->first();

        try {
            Mail::to($user->email)->send(new OtpMail($plainCode, $latestOtp?->expires_at));
        } catch (\Throwable $e) {
            return back()->withErrors(['email' => 'Gagal mengirim OTP: '.$e->getMessage()]);
        }

        return back()->with('status', 'Kode OTP baru telah dikirim ke email.');
    }
}
