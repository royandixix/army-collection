<?php

namespace App\Http\Controllers\Admin;

use App\Models\penjualan;
use App\Http\Controllers\Controller;
use Dom\Comment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $penjualans = penjualan::with('penjualan')->latest()->get();
        return view('admin.dashboard',compact('penjualans'));
    }
}
