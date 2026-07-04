<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BorrowingStatusUpdatedNotification extends Notification
{
    use Queueable;

    private $pesan;
    private $url;

    public function __construct($pesan, $url)
    {
        $this->pesan = $pesan;
        $this->url = $url;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => $this->pesan,
            'url' => $this->url,
        ];
    }
}