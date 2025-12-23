<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $fillable = [
    'pelanggan_id',
    'user_id',
    'tanggal',
    'total',
    'status',
    'metode_pembayaran',
    'bukti_tf',
    'status_pengiriman',
    'bukti_pengiriman',
    'bukti_diterima', // kolom baru
];



    protected $casts = [
        'tanggal' => 'datetime',
    ];

    // === RELASI ===
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Ubah menjadi
    public function transaksi()
    {
        return $this->hasOne(Transaksi::class, 'penjualan_id');
    }

    // app/Models/Penjualan.php
    public function detailPenjualans()
    {
        return $this->hasMany(DetailPenjualan::class);
    }
}
