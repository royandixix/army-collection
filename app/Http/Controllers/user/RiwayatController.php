<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;

class RiwayatController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with(['detailTransaksi.produk', 'penjualan'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.riwayat.riwayat', compact('transaksis'));
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$transaksi) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
        }

        // Hapus detail transaksi dulu
        $transaksi->detailTransaksi()->delete();

        // Hapus transaksi
        $transaksi->delete();

        return redirect()->back()->with('success', 'Transaksi berhasil dihapus.');
    }


    
}
