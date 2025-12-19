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
use App\Models\UserAlamat;

class KeranjangController extends Controller
{
    // 🛒 Halaman Keranjang
    public function index()
    {
        $keranjang = Keranjang::where('user_id', Auth::id())
            ->with('produk.kategori')
            ->get();

        $alamats = UserAlamat::where('user_id', Auth::id())
            ->orderByDesc('is_default')
            ->get();

        return view('user.keranjang.keranjang', compact('keranjang', 'alamats'));
    }

    // 🔄 Update jumlah (AJAX)
    public function updateJumlah(Request $request)
    {
        $request->validate([
            'id'     => 'required|exists:keranjangs,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $item = Keranjang::where('user_id', Auth::id())
            ->where('id', $request->id)
            ->firstOrFail();

        $item->update(['jumlah' => $request->jumlah]);

        return response()->json([
            'subtotal' => $item->produk->harga * $item->jumlah
        ]);
    }

    // ❌ Hapus item dari keranjang
    public function destroy($id)
    {
        Keranjang::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail()
            ->delete();

        return response()->json(['success' => true]);
    }

    // 📍 Simpan alamat baru dari modal (NEW METHOD)
    public function storeAlamat(Request $request)
    {
        $request->validate([
            'alamat' => 'required|string|min:5|max:500',
            'is_default' => 'nullable|boolean',
        ]);

        $isDefault = $request->is_default ? true : false;

        // Jika alamat ini dijadikan default, set semua alamat lain jadi false
        if ($isDefault) {
            UserAlamat::where('user_id', Auth::id())
                ->update(['is_default' => false]);
        }

        // Cek apakah alamat sudah ada
        $existingAlamat = UserAlamat::where('user_id', Auth::id())
            ->where('alamat', $request->alamat)
            ->first();

        if ($existingAlamat) {
            // Update existing address
            $existingAlamat->update(['is_default' => $isDefault]);
            $alamat = $existingAlamat;
        } else {
            // Create new address
            $alamat = UserAlamat::create([
                'user_id' => Auth::id(),
                'alamat' => $request->alamat,
                'is_default' => $isDefault,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil disimpan',
            'data' => $alamat
        ]);
    }

    // ✅ Proses Checkout (TANPA upload bukti)
    public function prosesCheckout(Request $request)
    {
        $request->validate([
            'alamat' => 'required|string|min:5|max:255',
            'metode' => 'required|in:cod,transfer,qris',
        ]);

        $user = Auth::user();

        $keranjang = Keranjang::where('user_id', $user->id)
            ->with('produk')
            ->get();

        if ($keranjang->isEmpty()) {
            return back()->with('error', 'Keranjang masih kosong.');
        }

        // 🧍 Data pelanggan
        $pelanggan = Pelanggan::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nama'   => $user->username ?? 'Pengguna',
                'email'  => $user->email,
                'alamat' => $request->alamat,
                'no_hp'  => $user->no_hp ?? '-',
            ]
        );

        // 📍 Simpan alamat user
        UserAlamat::updateOrCreate(
            [
                'user_id' => $user->id,
                'alamat'  => $request->alamat,
            ],
            ['is_default' => true]
        );

        UserAlamat::where('user_id', $user->id)
            ->where('alamat', '!=', $request->alamat)
            ->update(['is_default' => false]);

        // 💰 Hitung total
        $total = $keranjang->sum(fn ($item) =>
            $item->produk->harga * $item->jumlah
        );

        // 🧾 Penjualan
        $penjualan = Penjualan::create([
            'user_id'           => $user->id,
            'pelanggan_id'      => $pelanggan->id,
            'tanggal'           => now(),
            'total'             => $total,
            'status'            => 'pending',
            'metode_pembayaran' => $request->metode,
        ]);

        // 💳 Transaksi (BUKTI BELUM ADA)
        $transaksi = Transaksi::create([
            'user_id'      => $user->id,
            'penjualan_id' => $penjualan->id,
            'alamat'       => $request->alamat,
            'metode'       => $request->metode,
            'total'        => $total,
            'status'       => 'pending',
            'bukti_tf'     => null,
        ]);

        // 📦 Detail transaksi & kurangi stok
        foreach ($keranjang as $item) {
            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'produk_id'    => $item->produk_id,
                'jumlah'       => $item->jumlah,
                'harga'        => $item->produk->harga,
                'subtotal'     => $item->produk->harga * $item->jumlah,
            ]);

            $item->produk->decrement('stok', $item->jumlah);
        }

        // 🧹 Kosongkan keranjang
        Keranjang::where('user_id', $user->id)->delete();

        return redirect()->route('user.riwayat.index')->with([
            'checkout_success' => true,
            'total'            => $total,
            'penjualan_id'     => $penjualan->id,
        ]);
    }
}