<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class PengirimanDikirim extends Notification
{
    use Queueable;

    protected $penjualan;

    public function __construct($penjualan)
    {
        $this->penjualan = $penjualan;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "{$this->penjualan->id} sudah dikirim oleh admin.",
            'penjualan_id' => $this->penjualan->id,
            'bukti_pengiriman' => $this->penjualan->bukti_pengiriman,
        ];
    }
}
