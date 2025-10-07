<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pelanggan_id',
        'tanggal',
        'total',
        'status',
        'metode_pembayaran',
        'user_id',
        'bukti_pembayaran',
        'bukti_tf',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'penjualan_id');
    }

    // 🔥 Tambahkan accessor ini di sini (bukan di Transaksi)
    public function getMetodePembayaranAttribute($value)
    {
        return $value ?? $this->attributes['metode'] ?? null;
    }
}
