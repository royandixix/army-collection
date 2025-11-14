<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'alamat', 'telepon'];

    // Relasi ke produk supplier
    public function produks()
    {
        return $this->hasMany(Produk::class);
    }

    // Relasi ke pembelian supplier (stok masuk)
    public function pembelianSuppliers()
    {
        return $this->hasMany(PembelianSupplier::class);
    }

    /**
     * Relasi stok terjual melalui produk
     * Aman & pasti muncul
     */
    public function produkTerjual()
    {
        // Ambil semua detail transaksi dari setiap produk supplier
        return $this->hasManyThrough(
            \App\Models\DetailTransaksi::class, // model akhir
            \App\Models\Produk::class,          // model perantara
            'supplier_id',                       // foreign key Produk ke Supplier
            'produk_id',                         // foreign key DetailTransaksi ke Produk
            'id',                                // local key Supplier
            'id'                                 // local key Produk
        );
    }

    /**
     * Alternatif relasi stok terjual via collection
     * Bisa dipanggil seperti $supplier->produkTerjualCollection
     */
    public function getProdukTerjualCollectionAttribute()
    {
        return $this->produks->flatMap(function ($produk) {
            return $produk->detailTransaksis;
        });
    }


    

    /**
     * Total stok masuk dari supplier
     */
    public function totalStokMasuk()
    {
        return $this->pembelianSuppliers()->sum('jumlah');
    }

    /**
     * Total produk terjual dari supplier
     */
    public function totalProdukTerjual()
    {
        // Bisa pakai collection atau hasManyThrough
        return $this->produkTerjualCollection->sum('jumlah');
    }
}
