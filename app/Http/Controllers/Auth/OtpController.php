<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\OtpVerifyRequest;
use App\Http\Requests\ResendOtpRequest;
use App\Models\User;
use App\Services\OtpService;
use Carbon\Carbon;

class OtpController extends Controller
{
    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

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

        try {
            $this->otpService->verify($user, $request->code);
        } catch (\Throwable $e) {
            return back()->withErrors(['code' => $e->getMessage()]);
        }

        // success: mark user verified and delete otps
        $user->email_verified_at = Carbon::now();
        $user->save();

        // cleanup old otps
        \App\Models\Otp::where('user_id', $user->id)->delete();

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

        try {
            $this->otpService->createAndSend($user);
        } catch (\Throwable $e) {
            return back()->withErrors(['email' => $e->getMessage()]);
        }

        return back()->with('status', 'Kode OTP baru telah dikirim ke email.');
    }
}
