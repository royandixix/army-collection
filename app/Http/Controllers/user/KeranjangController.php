<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Keranjang;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;

class KeranjangController extends Controller
{
    // Menampilkan halaman keranjang & checkout
    public function index()
    {
        $keranjang = Keranjang::where('user_id', Auth::id())->with('produk.kategori')->get();
        return view('user.keranjang.keranjang', compact('keranjang'));
    }

    // Update jumlah item keranjang (AJAX)
    public function updateJumlah(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:keranjangs,id',
            'jumlah' => 'required|integer|min:1'
        ]);

        $item = Keranjang::where('user_id', Auth::id())->where('id', $request->id)->firstOrFail();
        $item->update(['jumlah' => $request->jumlah]);

        $subtotal = $item->produk->harga * $item->jumlah;

        return response()->json(['subtotal' => $subtotal]);
    }

    // Hapus item dari keranjang
    public function destroy($id)
{
    $item = Keranjang::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
    $item->delete();

    // return JSON agar Ajax sukses
    return response()->json(['success' => true, 'message' => 'Produk berhasil dihapus dari keranjang.']);
}


    // Proses checkout
    public function prosesCheckout(Request $request)
    {
        $request->validate([
            'alamat' => 'required|string|min:5|max:255',
            'metode' => 'required|in:cod,transfer,qris',
            'bukti_tf' => 'nullable|image'
        ]);

        $user = Auth::user();
        $keranjang = Keranjang::where('user_id', $user->id)->with('produk')->get();

        if ($keranjang->isEmpty()) {
            return redirect()->route('user.keranjang.index')->with('error', 'Keranjang kosong.');
        }

        // Simpan data pelanggan
        $pelanggan = Pelanggan::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nama' => $user->username ?? 'Pengguna',
                'email' => $user->email,
                'alamat' => $request->alamat,
                'no_hp' => $user->no_hp ?? '-',
            ]
        );

        $total = $keranjang->sum(fn($item) => $item->produk->harga * $item->jumlah);

        // ✅ Buat penjualan & isi metode_pembayaran juga
        $penjualan = Penjualan::create([
            'user_id' => $user->id,
            'pelanggan_id' => $pelanggan->id,
            'tanggal' => now(),
            'total' => $total,
            'status' => 'pending',
            'metode_pembayaran' => $request->metode, // <-- tambahkan baris ini
        ]);

        // Buat transaksi
        $transaksi = Transaksi::create([
            'user_id' => $user->id,
            'penjualan_id' => $penjualan->id,
            'alamat' => $request->alamat,
            'metode' => $request->metode,
            'total' => $total,
            'status' => 'pending',
        ]);

        // Upload bukti transfer jika ada
if ($request->hasFile('bukti_tf')) {
    $path = $request->file('bukti_tf')->store('bukti_tf', 'public');

    // Simpan ke tabel transaksi (kolom: bukti_tf)
    $transaksi->update(['bukti_tf' => $path]);

    // Simpan ke tabel penjualan (kolom: bukti_pembayaran)
    $penjualan->update(['bukti_pembayaran' => $path]);
}


        // Simpan detail transaksi
        foreach ($keranjang as $item) {
            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'produk_id' => $item->produk_id,
                'jumlah' => $item->jumlah,
                'harga' => $item->produk->harga,
                'subtotal' => $item->produk->harga * $item->jumlah,
            ]);
        }

        // Kosongkan keranjang
        Keranjang::where('user_id', $user->id)->delete();

        return redirect()->route('user.riwayat.index')->with([
            'checkout_success' => true,
            'total' => $total,
            'penjualan_id' => $penjualan->id,
        ]);
    }
}
