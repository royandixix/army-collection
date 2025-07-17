<?php


namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Penjualan;

class RiwayatController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $riwayats = Penjualan::where('user_id', $userId)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('user.riwayat.riwayat', compact('riwayats'));
    }
}
