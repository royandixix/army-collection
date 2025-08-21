<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->start ?? now()->startOfMonth()->toDateString();
        $end   = $request->end   ?? now()->endOfMonth()->toDateString();

        // Rekap Penjualan
        $penjualans = Penjualan::with('pelanggan')
            ->whereBetween('tanggal', [$start, $end])
            ->get();
        $totalPenjualan = $penjualans->sum('total');

        // Rekap Transaksi
        $transaksis = Transaksi::with('user')
            ->whereBetween('created_at', [$start, $end])
            ->get();
        $totalTransaksi = $transaksis->sum('total');

        return view('admin.rekap.rekap', compact(
            'penjualans',
            'transaksis',
            'totalPenjualan',
            'totalTransaksi',
            'start',
            'end'
        ));
    }
}
