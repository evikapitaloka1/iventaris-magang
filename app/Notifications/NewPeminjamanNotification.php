<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewPeminjamanNotification extends Notification
{
    use Queueable;

    private $pesan;
    private $url;

    // Menangkap data yang dikirim dari Controller
    public function __construct($pesan, $url)
    {
        $this->pesan = $pesan;
        $this->url = $url;
    }

    // Menentukan via apa notifikasi dikirim (wajib 'database' untuk web)
    public function via($notifiable)
    {
        return ['database'];
    }

    // Format data yang akan masuk ke kolom 'data' di tabel database (format JSON)
    public function toArray($notifiable)
    {
        return [
            'title' => $this->pesan,
            'url' => $this->url,
        ];
    }
}