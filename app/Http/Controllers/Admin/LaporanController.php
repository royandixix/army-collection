<?php

namespace App\Http\Controllers\Admin;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\Penjualan;

class LaporanController extends Controller
{
    /**
     * Tampilkan halaman laporan penjualan
     */
    public function index()
    {
        // Ambil semua penjualan dengan relasi pelanggan->user dan transaksi
        $penjualans = Penjualan::with([
            'pelanggan.user',
            'transaksi'
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




    public function cetakPdf($id)
    {
        $penjualan = Penjualan::with(['pelanggan.user', 'transaksi.detailTransaksi.produk'])->findOrFail($id);

        $pdf = Pdf::loadView('admin.faktur_laporan.faktur_pdf', compact('penjualan'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('faktur-penjualan-' . $penjualan->id . '.pdf');
    }
}
