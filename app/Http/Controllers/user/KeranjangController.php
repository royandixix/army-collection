<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Keranjang;
use App\Models\Produk;

class KeranjangController extends Controller
{
    // Tampilkan keranjang
    public function index()
    {
        if (Auth::check()) {
            // User login
            $keranjang = Keranjang::where('user_id', Auth::id())->with('produk')->get();
        } else {
            // User tidak login → gunakan session
            $sessionKeranjang = session('keranjang', []);
            $keranjang = [];

            foreach ($sessionKeranjang as $produkId => $jumlah) {
                $produk = Produk::find($produkId);
                if ($produk) {
                    $keranjang[] = (object)[
                        'id' => $produk->id,
                        'produk' => $produk,
                        'jumlah' => $jumlah,
                        'subtotal' => $produk->harga * $jumlah
                    ];
                }
            }
        }

        return view('user.keranjang.keranjang', compact('keranjang'));
    }

    // Update jumlah
    public function updateJumlah(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'jumlah' => 'required|integer|min:1',
        ]);

        if (Auth::check()) {
            $item = Keranjang::where('id', $request->id)->where('user_id', Auth::id())->first();
            if (!$item) return response()->json(['error' => 'Item tidak ditemukan'], 404);

            $item->jumlah = $request->jumlah;
            $item->save();
            $subtotal = $item->jumlah * $item->produk->harga;
        } else {
            $keranjang = session('keranjang', []);
            if (!isset($keranjang[$request->id])) return response()->json(['error' => 'Item tidak ditemukan'], 404);

            $keranjang[$request->id] = $request->jumlah;
            session(['keranjang' => $keranjang]);

            $produk = Produk::find($request->id);
            $subtotal = $produk->harga * $request->jumlah;
        }

        return response()->json(['subtotal' => $subtotal]);
    }

    // Hapus item
    public function destroy($id)
    {
        if (Auth::check()) {
            $item = Keranjang::where('id', $id)->where('user_id', Auth::id())->first();
            if ($item) {
                $item->delete();
                return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang.');
            }
            return redirect()->back()->with('error', 'Produk tidak ditemukan atau tidak diizinkan.');
        } else {
            $keranjang = session('keranjang', []);
            if (isset($keranjang[$id])) {
                unset($keranjang[$id]);
                session(['keranjang' => $keranjang]);
                return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang.');
            }
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }
    }
}
