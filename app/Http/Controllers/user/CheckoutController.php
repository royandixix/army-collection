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
use App\Models\Pembelian;
use App\Models\Supplier;

class CheckoutController extends Controller
{
    public function index()
    {
        $keranjangs = Keranjang::where('user_id', Auth::id())->with('produk')->get();
        return view('user.checkout.checkout', compact('keranjangs'));
    }

    public function proses(Request $request)
    {
        // Validasi input
        $request->validate([
            'alamat' => 'required|string|min:5|max:255',
            'metode' => 'required|in:cod,transfer,qris',
        ]);

        $user = Auth::user();

        // Ambil data keranjang
        $keranjangs = Keranjang::where('user_id', $user->id)->with('produk')->get();
        if ($keranjangs->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang kosong.');
        }

        // Simpan atau update data pelanggan
        $pelanggan = Pelanggan::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nama'   => $user->username ?? $user->name ?? 'Pengguna',
                'email'  => $user->email ?? '-',
                'alamat' => $request->alamat,
                'no_hp'  => $user->no_hp ?? '-',
            ]
        );

        // Hitung total
        $total = $keranjangs->sum(fn($item) => $item->produk->harga * $item->jumlah);

        // Simpan data penjualan
        $penjualan = Penjualan::create([
            'user_id'      => $user->id,
            'pelanggan_id' => $pelanggan->id,
            'tanggal'      => now(),
            'total'        => $total,
            'status'       => 'pending',
        ]);

        // Simpan data transaksi
        $transaksi = Transaksi::create([
            'user_id'      => $user->id,
            'penjualan_id' => $penjualan->id,
            'alamat'       => $request->alamat,
            'metode'       => $request->metode,
            'total'        => $total,
            'status'       => 'pending',
        ]);

        // Simpan detail transaksi (per produk)
        foreach ($keranjangs as $item) {
            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'produk_id'    => $item->produk_id,
                'quantity'     => $item->jumlah, // ⚠ sesuaikan migration
                'harga'        => $item->produk->harga,
                'subtotal'     => $item->jumlah * $item->produk->harga,
            ]);
        }

        // Tambahkan ke tabel pembelian
        $supplier = Supplier::firstOrCreate(
            ['nama' => $pelanggan->nama],
            [
                'alamat' => $pelanggan->alamat,
                'no_hp'  => $pelanggan->no_hp, // ⚠ sesuaikan kolom
            ]
        );

        Pembelian::create([
            'supplier_id' => $supplier->id,
            'tanggal'     => now(),
            'total'       => $total,
        ]);

        // Kosongkan keranjang
        Keranjang::where('user_id', $user->id)->delete();

        // Redirect ke halaman checkout dengan session flash
        return redirect()->route('user.checkout.index')->with([
            'checkout_success' => true,
            'total'            => $total,
            'penjualan_id'     => $penjualan->id,
        ]);
    }
}
