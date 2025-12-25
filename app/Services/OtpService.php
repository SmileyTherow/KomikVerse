<?php

namespace App\Services;

use App\Mail\OtpMail;
use App\Models\Otp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    protected int $expiryMinutes = 15;
    protected int $resendCooldownSeconds = 60; // jeda minimal antar resend (detik)
    protected int $maxResendPerHour = 5;
    protected int $maxAttempts = 5;

    /**
     * Create (cleanup previous) OTP for a user and send via email.
     * Returns the plain code (for logging/testing) or throws Exception on throttle/mail error.
     */
    public function createAndSend(User $user): string
    {
        // cooldown check
        $last = Otp::where('user_id', $user->id)->latest()->first();
        if ($last && $last->created_at && $last->created_at->gt(now()->subSeconds($this->resendCooldownSeconds))) {
            throw new \Exception('Tunggu sebelum meminta OTP baru (1 menit).');
        }

        // limit per hour
        $countLastHour = Otp::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->subHour())
            ->count();

        if ($countLastHour >= $this->maxResendPerHour) {
            throw new \Exception('Batas pengiriman OTP tercapai. Coba lagi nanti.');
        }

        // cleanup previous otps for user
        Otp::where('user_id', $user->id)->delete();

        // create via model helper (returns plain code)
        $plainCode = Otp::createForUser($user->id, $this->expiryMinutes);
        $latestOtp = Otp::where('user_id', $user->id)->latest()->first();

        // Decide sending method: sync send by default for dev; set MAIL_USE_QUEUE=true to use queue
        $useQueue = (bool) config('mail.use_queue', env('MAIL_USE_QUEUE', false));

        try {
            if ($useQueue) {
                Mail::to($user->email)->queue(new OtpMail($plainCode, $latestOtp?->expires_at));
            } else {
                Mail::to($user->email)->send(new OtpMail($plainCode, $latestOtp?->expires_at));
            }
        } catch (\Throwable $e) {
            throw new \Exception('Gagal mengirim OTP: ' . $e->getMessage());
        }

        return $plainCode;
    }

    /**
     * Verify a plain code for a user.
     * Returns true on success, throws Exception on failure.
     */
    public function verify(User $user, string $code): bool
    {
        $result = Otp::verifyCode($user->id, $code, $this->maxAttempts);

        if (! isset($result['ok']) || ! $result['ok']) {
            $message = $result['message'] ?? 'Verifikasi gagal.';
            throw new \Exception($message);
        }

        return true;
    }
}
