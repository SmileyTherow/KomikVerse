<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminMessageToUser extends Mailable
{
    use Queueable, SerializesModels;

    // jangan tambahkan type di $subject karena didefinisikan di parent tanpa type
    public $subject;
    public string $messageBody;
    public string $adminName;

    public function __construct(string $subject, string $message, string $adminName = 'Admin')
    {
        $this->subject = $subject;
        $this->messageBody = $message;
        $this->adminName = $adminName;
    }

    public function build()
    {
        // set subject Mailable dengan method subject()
        return $this->subject($this->subject)
                    ->view('emails.admin_message')
                    ->with([
                        'messageBody' => $this->messageBody,
                        'adminName' => $this->adminName,
                    ]);
    }
}
