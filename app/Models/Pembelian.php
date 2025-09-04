<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Supplier; // ← tambahkan ini

class Pembelian extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'tanggal',
        'total',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
