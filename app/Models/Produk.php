<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'kategori_id',
        'deskripsi', // ✅ Tambah ini
        'harga',
        'stok',
        'gambar',
    ];
    

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

   
}
