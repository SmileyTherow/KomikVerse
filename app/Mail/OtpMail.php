<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;
    public $user;

    public function __construct($code, User $user)
    {
        $this->code = $code;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Kode Verifikasi OTP - Sistem Penyewaan Komik')
                    ->markdown('emails.otp')
                    ->with([
                        'code' => $this->code,
                        'name' => $this->user->name,
                    ]);
    }
}