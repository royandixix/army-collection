<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Keranjang;

class CheckoutController extends Controller
{
    public function index()
    {
        $keranjangs = Keranjang::where('user_id', Auth::id())->with('produk')->get();

        return view('user.checkout.checkout', compact('keranjangs'));
    }
}
