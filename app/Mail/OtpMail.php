<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $code;
    public $expiresAt;

    public function __construct(string $code, $expiresAt = null)
    {
        $this->code = $code;
        $this->expiresAt = $expiresAt;
    }

    public function build()
    {
        // Gunakan markdown agar komponen mail::message tersedia untuk view yang memakai @component('mail::message')
        return $this->subject('Kode OTP inkomi')
                    ->markdown('emails.otp')
                    ->with([
                        'code' => $this->code,
                        'expiresAt' => $this->expiresAt,
                    ]);
    }
}
