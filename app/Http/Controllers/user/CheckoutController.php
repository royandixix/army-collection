<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Keranjang;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Pelanggan;
use App\Models\Penjualan;

class CheckoutController extends Controller
{
    public function index()
    {
        $keranjangs = Keranjang::where('user_id', Auth::id())->with('produk')->get();

        return view('user.checkout.checkout', compact('keranjangs'));
    }

    public function proses(Request $request)
    {
        $request->validate([
            'alamat' => 'required|string',
            'metode' => 'required|in:cod,transfer,qris',
        ]);

        $keranjangs = Keranjang::where('user_id', Auth::id())->with('produk')->get();

        if ($keranjangs->isEmpty()) {
            return redirect()->route('user.checkout.index')->with('error', 'Keranjang kosong.');
        }

        $user = Auth::user();

        // ✅ Update atau buat data pelanggan saat checkout
        $pelanggan = Pelanggan::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nama'   => $user->username ?? $user->name ?? 'Pengguna',
                'email'  => $user->email,
                'alamat' => $request->alamat,
                'no_hp'  => $user->no_hp,
            ]
        );

        $total = 0;

        foreach ($keranjangs as $item) {
            $total += $item->produk->harga * $item->jumlah;
        }

        // ✅ Simpan transaksi
        $transaksi = Transaksi::create([
            'user_id' => $user->id,
            'alamat'  => $request->alamat,
            'metode'  => $request->metode,
            'total'   => $total,
            'status'  => 'pending',
        ]);

        // ✅ Simpan detail transaksi
        foreach ($keranjangs as $item) {
            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'produk_id'    => $item->produk_id,
                'jumlah'       => $item->jumlah,
                'harga'        => $item->produk->harga,
            ]);
        }

        // ✅ Simpan juga ke tabel penjualans
        Penjualan::create([
            'user_id'      => $user->id,
            'pelanggan_id' => $pelanggan->id,
            'tanggal'      => now(),
            'total'        => $total,
            'status'       => 'pending',
        ]);

        // ✅ Kosongkan keranjang
        Keranjang::where('user_id', $user->id)->delete();

        return redirect()->route('user.riwayat.index')->with('success', 'Checkout berhasil.');
    }
}
