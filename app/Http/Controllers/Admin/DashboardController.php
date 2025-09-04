<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Pelanggan;
use App\Models\Produk;                  

class DashboardController extends Controller
{
    public function index()
    {
        $penjualans = Penjualan::with('pelanggan')->latest()->take(10)->get();

        return view('admin.dashboard', [
            'penjualans' => $penjualans,
            'totalPenjualan' => Penjualan::sum('total'),
            'jumlahPelanggan' => Pelanggan::count(),
            'pendingTransaksi' => Penjualan::where('status', 'pending')->count(),
            'jumlahProduk' => Produk::count(),
        ]);
    }
}
