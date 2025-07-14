<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukUserController extends Controller
{
    /**
     * Tampilkan semua data
     */

    public function index()
    {
        $produks = Produk::latest()->get(); 
        return view('user.produk.produk', compact('produks'));
    } 
}
