<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;

class RiwayatController extends Controller
{
    public function index()
    {
        // Ambil semua transaksi user beserta detail produk dan penjualan
        $transaksis = Transaksi::with(['detailTransaksi.produk', 'penjualan'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.riwayat.riwayat', compact('transaksis'));
    }
}
