<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\User;
use App\Models\Penjualan;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanDataController extends Controller
{
    /**
     * Laporan Produk
     */
    public function produk()
    {
        $produks = Produk::with('kategori')->get();
        return view('admin.laporan.laporan_produk.laporan_produk', compact('produks'));
    }

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
     * Laporan Pelanggan
     */
    public function pelanggan()
    {
        $pelanggans = User::with(['pelanggan', 'transaksis'])
            ->where('role', 'user')
            ->withCount('transaksis')
            ->get();

        return view('admin.laporan.laporan_pelanggan.laporan_pelanggan', compact('pelanggans'));
    }

    /**
     * Cetak PDF Laporan Produk
     */
    public function cetakProduk()
    {
        $produks = Produk::with(['kategori', 'detailTransaksis'])->get();

        $pdf = Pdf::loadView('admin.laporan.laporan_produk.produk_pdf', compact('produks'))
            ->setPaper('a4', 'landscape');


        return $pdf->stream('laporan_produk.pdf');
    }


    public function cetakPenjualan()
    {
        $penjualans = \App\Models\Penjualan::with(['pelanggan.user', 'transaksi.detailTransaksi.produk'])
            ->latest()
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'admin.laporan.laporan_penjualan.penjualan_pdf',
            compact('penjualans')
        )->setPaper('a4', 'landscape');

        return $pdf->stream('laporan_penjualan.pdf');
    }



    public function cetakPelanggan()
    {
        $pelanggans = User::with(['pelanggan', 'transaksis'])
            ->where('role', 'user')
            ->withCount('transaksis')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan.laporan_pelanggan.pelanggan_pdf', compact('pelanggans'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('laporan_pelanggan.pdf');
    }
}
