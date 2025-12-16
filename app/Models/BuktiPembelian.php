<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuktiPembelian extends Model
{
    use HasFactory;

    protected $table = 'bukti_pembelian'; // tambahkan ini supaya Laravel tahu nama tabel sebenarnya

    protected $fillable = [
        'pembelian_id',
        'file',
        'status_pengiriman',
    ];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }
}
