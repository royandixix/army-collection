<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $penjualans = Penjualan::with('pelanggan')->latest()->get();
        return view('admin.faktur_laporan.faktur_laporan', compact('penjualans'));
    }
}
