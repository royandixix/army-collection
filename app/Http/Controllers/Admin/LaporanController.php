<?php

namespace App\Http\Controllers\Admin;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Penjualan;
use App\Http\Controllers\Controller;

class LaporanController extends Controller
{
    /**
     * Tampilkan halaman laporan penjualan
     */
    public function index()
{
    // Ambil semua penjualan dengan relasi lengkap
    $penjualans = Penjualan::with([
        'pelanggan.user',
        'transaksi.detailTransaksi.produk'
    ])->latest()->get();

    return view('admin.faktur_laporan.faktur_laporan', compact('penjualans'));
}


    /**
     * Tampilkan faktur cetak
     */
    public function show($id)
    {
        $penjualan = Penjualan::with([
            'pelanggan.user',
            'transaksi'
        ])->findOrFail($id);

        return view('admin.faktur_laporan.cetak', compact('penjualan'));
    }

    /**
     * Cetak seluruh faktur PDF
     */
    public function cetakSemua()
    {
        $penjualans = Penjualan::with([
            'pelanggan.user',
            'transaksi.detailTransaksi.produk'
        ])->get();

        $pdf = Pdf::loadView('admin.faktur_laporan.semua_pdf', compact('penjualans'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('faktur-semua-penjualan.pdf');
    }
}
