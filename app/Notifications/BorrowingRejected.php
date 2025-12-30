<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Borrowing;

class BorrowingRejected extends Notification
{
    use Queueable;

    protected Borrowing $borrowing;

    public function __construct(Borrowing $borrowing)
    {
        $this->borrowing = $borrowing;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $comic = $this->borrowing->comic;

        return (new MailMessage)
            ->subject('Permintaan Peminjaman Ditolak: ' . ($comic->title ?? 'Komik'))
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line("Mohon maaf, permintaan peminjaman untuk komik \"".($comic->title ?? '-')."\" telah ditolak oleh admin.")
            ->line('Jika Anda merasa ini keliru, silakan menghubungi admin.')
            ->line('Terima kasih.');
    }
}
