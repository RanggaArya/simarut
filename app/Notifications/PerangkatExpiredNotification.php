<?php

namespace App\Notifications;

use App\Models\Perangkat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PerangkatExpiredNotification extends Notification
{
    // use Queueable;

    public function __construct(public Perangkat $perangkat)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $p = $this->perangkat;

        return (new MailMessage)
            ->subject('Perangkat Expired: ' . $p->nama_perangkat)
            ->greeting('Halo,')
            ->line('Perangkat berikut telah melewati masa pakai:')
            ->line('Nama           : ' . $p->nama_perangkat)
            ->line('Nomor Inventaris: ' . ($p->nomor_inventaris ?? '-'))
            ->line('Kategori       : ' . ($p->kategori->nama_kategori ?? '-'))
            ->line('Tahun Pengadaan: ' . ($p->tahun_pengadaan ?? '-'))
            ->line('Tahun Expired  : ' . ($p->tahun_expired ?? '-'))
            ->line('Harga perangkat telah otomatis diubah menjadi Rp 0.')
            ->line('Silakan cek sistem inventaris untuk tindakan lebih lanjut.');
    }
}
