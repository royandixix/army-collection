<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DetailTransaksi;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'alamat',
        'metode',
        'status',
        'total',
        'penjualan_id',
        'bukti_tf'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    // Accessor agar
}