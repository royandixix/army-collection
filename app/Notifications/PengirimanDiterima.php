<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PengirimanDiterima extends Notification
{
    use Queueable;

    protected $penjualan;

    public function __construct($penjualan)
    {
        $this->penjualan = $penjualan;
    }

    public function via($notifiable)
    {
        return ['database']; // simpan ke tabel notifications
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "Penjualan ID #{$this->penjualan->id} sudah diterima oleh user.",
            'penjualan_id' => $this->penjualan->id,
            'bukti_diterima' => $this->penjualan->bukti_diterima,
        ];
    }
}
