<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategoris';

    protected $fillable = [
        'name',
    ];

    // Relasi ke Produk
    public function produks()
    {
        return $this->hasMany(Produk::class, 'kategori_id');
    }

    // Relasi ke SubKategori
    public function subKategoris()
    {
        return $this->hasMany(SubKategori::class, 'kategori_id');
    }
}
