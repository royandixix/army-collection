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
}
