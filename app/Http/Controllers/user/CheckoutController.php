<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Keranjang;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Pelanggan;
use App\Models\Penjualan;

class CheckoutController extends Controller
{
    /**
     * Tampilkan halaman checkout.
     */
    public function index()
    {
        $keranjangs = Keranjang::where('user_id', Auth::id())->with('produk')->get();
        return view('user.checkout.checkout', compact('keranjangs'));
    }

    /**
     * Proses checkout dengan validasi dan penyimpanan.
     */
    public function proses(Request $request)
    {
        // ✅ Validasi input
        $request->validate([
            'alamat' => 'required|string|min:5|max:255',
            'metode' => 'required|in:cod,transfer,qris',
        ], [
            'alamat.required' => 'Alamat pengiriman wajib diisi.',
            'alamat.min' => 'Alamat terlalu pendek.',
            'metode.required' => 'Pilih metode pembayaran.',
            'metode.in' => 'Metode tidak valid.',
        ]);

        $user = Auth::user();

        // ✅ Ambil isi keranjang
        $keranjangs = Keranjang::where('user_id', $user->id)->with('produk')->get();

        if ($keranjangs->isEmpty()) {
            return redirect()->route('user.checkout.index')->with('error', 'Keranjang kosong.');
        }

        // ✅ Simpan atau update data pelanggan
        $pelanggan = Pelanggan::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nama'   => $user->username ?? $user->name ?? 'Pengguna',
                'email'  => $user->email ?? '-',
                'alamat' => $request->alamat,
                'no_hp'  => $user->no_hp ?? '-',
            ]
        );

        // ✅ Hitung total belanja
        $total = $keranjangs->sum(function ($item) {
            return $item->produk->harga * $item->jumlah;
        });

        // ✅ Simpan data penjualan
        $penjualan = Penjualan::create([
            'user_id'      => $user->id,
            'pelanggan_id' => $pelanggan->id,
            'tanggal'      => now(),
            'total'        => $total,
            'status'       => 'pending',
        ]);

        // ✅ Simpan transaksi dan hubungkan dengan penjualan
        $transaksi = Transaksi::create([
            'user_id'      => $user->id,
            'penjualan_id' => $penjualan->id, // penting agar relasi $penjualan->transaksi tidak null
            'alamat'       => $request->alamat,
            'metode'       => $request->metode,
            'total'        => $total,
            'status'       => 'pending',
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

        // ✅ Kosongkan keranjang setelah checkout
        Keranjang::where('user_id', $user->id)->delete();

        return redirect()->route('user.riwayat.index')->with('success', 'Checkout berhasil!');
    }
}
