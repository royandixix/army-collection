<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // ✅ WAJIB!
use App\Models\Keranjang;

class KeranjangController extends Controller
{
    public function index()
    {
        $userId = Auth::id(); // ✅ AMAN

        $keranjang = Keranjang::where('user_id', $userId)->with('produk')->get();

        return view('user.keranjang.keranjang', compact('keranjang'));
    }

    public function updateJumlah(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:keranjangs,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $item = \App\Models\Keranjang::find($request->id);
        $item->jumlah = $request->jumlah;
        $item->save();

        $subtotal = $item->jumlah * $item->produk->harga;

        return response()->json([
            'subtotal' => $subtotal
        ]);
    }
    public function destroy($id)
    {
        $userId = Auth::id();

        $item = Keranjang::where('id', $id)->where('user_id', $userId)->first();

        if ($item) {
            $item->delete();
            return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang.');
        }

        return redirect()->back()->with('error', 'Produk tidak ditemukan atau tidak diizinkan.');
    }
}
