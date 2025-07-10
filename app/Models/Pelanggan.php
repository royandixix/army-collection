<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggans';

    protected $fillable = [
        'nama',
        'alamat',
        'no_hp',
        'email',
    ];

    // Relasi: 1 pelanggan bisa punya banyak penjualan
    public function penjualans()
    {
        return $this->hasMany(Penjualan::class);
    }
}
