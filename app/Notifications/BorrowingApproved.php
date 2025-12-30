<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Borrowing;

class BorrowingApproved extends Notification
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
        $due = $this->borrowing->due_at ? $this->borrowing->due_at->toDayDateTimeString() : 'N/A';

        return (new MailMessage)
            ->subject('Peminjaman Disetujui: ' . $comic->title)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line("Peminjaman komik \"{$comic->title}\" telah disetujui.")
            ->line("Tanggal pinjam: " . $this->borrowing->borrowed_at->toDayDateTimeString())
            ->line("Batas pengembalian: {$due}")
            ->line('Silakan kembalikan komik tepat waktu. Terima kasih!');
    }
}
