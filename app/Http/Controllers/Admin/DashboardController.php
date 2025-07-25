<?php

namespace App\Http\Controllers\Admin;

use App\Models\Penjualan; // ← huruf P besar! (bukan penjualan)
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data penjualan lengkap dengan relasi pelanggan & user
        $penjualans = Penjualan::with(['pelanggan', 'user'])->latest()->get();

        return view('admin.dashboard', compact('penjualans'));
    }
}
