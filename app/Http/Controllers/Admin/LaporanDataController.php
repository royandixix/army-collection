<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use App\Models\Pembelian; // pastikan model ini ada
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanDataController extends Controller
{
    /**
     * Laporan Penjualan
     */
    public function penjualan()
    {
        $penjualans = Penjualan::with(['pelanggan.user', 'transaksi.detailTransaksi.produk'])
            ->latest()
            ->get();

        return view('admin.laporan.laporan_penjualan.laporan_penjualan', compact('penjualans'));
    }

    /**
     * Cetak PDF Laporan Penjualan
     */
    public function cetakPenjualan()
    {
        $penjualans = Penjualan::with(['pelanggan.user', 'transaksi.detailTransaksi.produk'])
            ->latest()
            ->get();

        $pdf = Pdf::loadView(
            'admin.laporan.laporan_penjualan.penjualan_pdf',
            compact('penjualans')
        )->setPaper('a4', 'landscape');

        return $pdf->stream('laporan_penjualan.pdf');
    }

    /**
     * Laporan Pembelian
     */
    public function pembelian()
    {
        $pembelians = Pembelian::with('supplier') // kalau ada relasi supplier
            ->latest()
            ->get();

        return view('admin.laporan.laporan_pembelian.laporan_pembelian', compact('pembelians'));
    }

    /**
     * Cetak PDF Laporan Pembelian
     */
    public function cetakPembelian()
    {
        $pembelians = Pembelian::with('supplier')->get();

        $pdf = Pdf::loadView(
            'admin.laporan.laporan_pembelian.pembelian_pdf',
            compact('pembelians')
        )->setPaper('a4', 'landscape');

        return $pdf->stream('laporan_pembelian.pdf');
    }
}
