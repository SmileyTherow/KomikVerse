<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\OtpVerifyRequest;
use App\Http\Requests\ResendOtpRequest;
use App\Models\User;
use App\Models\Otp;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Hash;
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

        $otp = Otp::where('user_id', $user->id)
                  ->orderBy('created_at', 'desc')
                  ->first();

        if (! $otp) {
            return back()->withErrors(['code' => 'Kode OTP tidak ditemukan. Silakan minta resend.']);
        }

        if (Carbon::now()->gt($otp->expires_at)) {
            return back()->withErrors(['code' => 'Kode OTP telah kadaluarsa. Silakan kirim ulang.']);
        }

        if (! Hash::check($request->code, $otp->code_hash)) {
            $otp->increment('attempts');
            return back()->withErrors(['code' => 'Kode OTP salah.']);
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

        // optional: limit resend frequency — contoh sederhana: hapus existing dan kirim baru
        Otp::where('user_id', $user->id)->delete();

        $code = random_int(100000, 999999);
        $codeHash = Hash::make((string)$code);

        $otp = Otp::create([
            'user_id' => $user->id,
            'code_hash' => $codeHash,
            'expires_at' => Carbon::now()->addMinutes(15),
            'attempts' => 0,
        ]);

        Mail::to($user->email)->send(new OtpMail($code, $user));

        return back()->with('status', 'Kode OTP baru telah dikirim ke email.');
    }
}