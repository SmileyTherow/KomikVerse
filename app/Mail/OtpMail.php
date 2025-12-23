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
        return $this->subject('Kode OTP KomikVerse')
            ->view('emails.otp')
            ->with([
                'code' => $this->code,
                'expires_at' => $this->expiresAt,
            ]);
    }
}
