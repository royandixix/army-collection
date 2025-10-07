<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\Pembelian;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanDataController extends Controller
{
    /**
     * LAPORAN PRODUK
     */
    public function produk()
    {
        $produks = Produk::orderBy('created_at', 'desc')->get();
        return view('admin.laporan.laporan_produk.laporan_produk', compact('produks'));
    }

    public function cetakProduk()
    {
        $produks = Produk::orderBy('created_at', 'desc')->get();
        $pdf = Pdf::loadView(
            'admin.laporan.laporan_produk.produk_pdf',
            compact('produks')
        )->setPaper('a4', 'landscape');

        return $pdf->stream('laporan_produk.pdf');
    }

    /**
     * LAPORAN PELANGGAN
     */
    public function pelanggan()
    {
        $pelanggans = Pelanggan::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.laporan.laporan_pelanggan.laporan_pelanggan', compact('pelanggans'));
    }

    public function cetakPelanggan()
    {
        $pelanggans = Pelanggan::with('user')->orderBy('created_at', 'desc')->get();
        $pdf = Pdf::loadView(
            'admin.laporan.laporan_pelanggan.pelanggan_pdf',
            compact('pelanggans')
        )->setPaper('a4', 'landscape');

        return $pdf->stream('laporan_pelanggan.pdf');
    }

    /**
     * LAPORAN PEMBELIAN
     * (Gabungan antara pembelian admin dan transaksi user)
     */
    public function pembelian()
{
    $pembelians = Pembelian::with('supplier')->get();
    $penjualans = Penjualan::with(['pelanggan.user'])->get();

    $laporanGabungan = collect();

    // Pembelian Supplier
    foreach ($pembelians as $p) {
        $laporanGabungan->push((object)[
            'supplier' => $p->supplier->nama ?? '-',
            'alamat'   => $p->supplier->alamat ?? '-',
            'telepon'  => $p->supplier->telepon ?? '-',
            'tanggal'  => $p->tanggal,
            'total'    => $p->total,
            'jenis'    => 'Pembelian Supplier',
        ]);
    }

    // Transaksi User
    foreach ($penjualans as $pj) {
        $laporanGabungan->push((object)[
            'supplier' => $pj->pelanggan->user->username ?? 'Pelanggan',
            'alamat'   => $pj->pelanggan->alamat ?? '-',
            'telepon'  => $pj->pelanggan->no_hp ?? '-',
            'tanggal'  => $pj->tanggal,
            'total'    => $pj->total,
            'jenis'    => 'Transaksi User',
        ]);
    }

    return view('admin.laporan.laporan_pembelian.laporan_pembelian', [
        'pembelians' => $laporanGabungan
    ]);
}


    public function cetakPembelian()
{
    $pembelians = Pembelian::with('supplier')->get();
    $penjualans = Penjualan::with(['pelanggan.user'])
        ->select('id', 'pelanggan_id', 'tanggal', 'total')
        ->get();

    $laporanGabungan = collect();

    // Pembelian Supplier
    foreach ($pembelians as $p) {
        $laporanGabungan->push((object)[
            'supplier' => $p->supplier->nama ?? '-',
            'alamat'   => $p->supplier->alamat ?? '-',
            'telepon'  => $p->supplier->telepon ?? '-',
            'tanggal'  => $p->tanggal,
            'total'    => $p->total,
            'jenis'    => 'Pembelian Supplier',
        ]);
    }

    // Transaksi User
    foreach ($penjualans as $pj) {
        $laporanGabungan->push((object)[
            'supplier' => $pj->pelanggan->user->username ?? 'Pelanggan',
            'alamat'   => $pj->pelanggan->alamat ?? '-',
            'telepon'  => $pj->pelanggan->no_hp ?? '-',
            'tanggal'  => $pj->tanggal,
            'total'    => $pj->total,
            'jenis'    => 'Transaksi User',
        ]);
    }

    $pdf = Pdf::loadView(
        'admin.laporan.laporan_pembelian.pembelian_pdf',
        ['pembelians' => $laporanGabungan]
    )->setPaper('a4', 'landscape');

    return $pdf->stream('laporan_pembelian.pdf');
}


    /**
     * LAPORAN PENJUALAN
     */
    public function penjualan()
    {
        $penjualans = Penjualan::with(['pelanggan.user', 'transaksi.detailTransaksi.produk'])
            ->latest()
            ->get();

        return view('admin.laporan.laporan_penjualan.laporan_penjualan', compact('penjualans'));
    }

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
}
