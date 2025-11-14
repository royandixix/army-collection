<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembelianSupplier extends Model
{
    protected $fillable = ['supplier_id', 'produk_id', 'jumlah', 'harga_beli', 'subtotal'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    /**
     * Booted event untuk otomatis update stok
     */
    protected static function booted()
    {
        static::created(function ($pembelian) {
            if ($pembelian->produk) $pembelian->produk->increment('stok', $pembelian->jumlah);
        });

        static::updated(function ($pembelian) {
            $diff = $pembelian->jumlah - $pembelian->getOriginal('jumlah');
            if ($pembelian->produk) $pembelian->produk->increment('stok', $diff);
        });

        static::deleted(function ($pembelian) {
            if ($pembelian->produk) $pembelian->produk->decrement('stok', $pembelian->jumlah);
        });
    }

}
