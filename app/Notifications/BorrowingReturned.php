<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Borrowing;

class BorrowingReturned extends Notification
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
        $status = $this->borrowing->status === Borrowing::STATUS_TERLAMBAT ? 'Terlambat' : 'Tepat waktu';

        return (new MailMessage)
            ->subject('Konfirmasi Pengembalian: ' . $comic->title)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line("Pengembalian komik \"{$comic->title}\" telah diproses oleh admin.")
            ->line("Status pengembalian: {$status}")
            ->line('Terima kasih sudah menggunakan layanan kami.');
    }
}
