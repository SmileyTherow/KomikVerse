<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\Otp;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
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

        // generate OTP (6 digits)
        $code = random_int(100000, 999999);
        $codeHash = Hash::make((string)$code);

        $otp = Otp::create([
            'user_id' => $user->id,
            'code_hash' => $codeHash,
            'expires_at' => Carbon::now()->addMinutes(15),
            'attempts' => 0,
        ]);

        // send email with OTP
        Mail::to($user->email)->send(new OtpMail($code, $user));

        return redirect()->route('otp.verify.show')
            ->with('status', 'Akun dibuat. Kode OTP telah dikirim ke email. Silakan verifikasi.');
    }
}