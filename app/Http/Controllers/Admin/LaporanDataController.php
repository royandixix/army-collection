<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\Pembelian;
use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class LaporanDataController extends Controller
{
    /**
     * LAPORAN PRODUK
     */
    public function produk()
    {
        $produks = Produk::with(['kategori', 'detailPenjualans'])->get();
        return view('admin.laporan.laporan_produk.laporan_produk', compact('produks'));
    }

    public function cetakProduk()
    {
        $produks = Produk::with(['kategori', 'detailPenjualans'])->orderBy('created_at', 'desc')->get();
        $pdf = Pdf::loadView('admin.laporan.laporan_produk.produk_pdf', compact('produks'))
            ->setPaper('a4', 'landscape');
        return $pdf->stream('laporan_produk.pdf');
    }

    /**
     * LAPORAN PRODUK TERLARIS BULAN INI
     */
    public function produkTerlaris()
    {
        $bulan = now()->month;
        $tahun = now()->year;

        $produks = Produk::with('kategori')
            ->withSum(['detailPenjualans as jumlah_terjual' => function ($q) use ($bulan, $tahun) {
                $q->whereHas('penjualan', fn($p) => $p->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun));
            }], 'jumlah')
            ->withSum(['detailTransaksis as jumlah_terjual_transaksi' => function ($q) use ($bulan, $tahun) {
                $q->whereHas('transaksi', fn($t) => $t->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun));
            }], 'jumlah')
            ->get()
            ->map(function ($produk) {
                $produk->jumlah_terjual_total = ($produk->jumlah_terjual ?? 0) + ($produk->jumlah_terjual_transaksi ?? 0);
                return $produk;
            })
            ->sortByDesc('jumlah_terjual_total')
            ->take(10);

        return view('admin.laporan.laporan_produk.produk_terlaris', compact('produks', 'bulan', 'tahun'));
    }

    public function cetakProdukTerlaris()
    {
        $bulan = now()->month;
        $tahun = now()->year;

        $produks = Produk::with('kategori')
            ->withSum(['detailPenjualans as jumlah_terjual' => function ($q) use ($bulan, $tahun) {
                $q->whereHas('penjualan', fn($p) => $p->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun));
            }], 'jumlah')
            ->withSum(['detailTransaksis as jumlah_terjual_transaksi' => function ($q) use ($bulan, $tahun) {
                $q->whereHas('transaksi', fn($t) => $t->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun));
            }], 'jumlah')
            ->get()
            ->map(function ($produk) {
                $produk->jumlah_terjual_total = ($produk->jumlah_terjual ?? 0) + ($produk->jumlah_terjual_transaksi ?? 0);
                return $produk;
            })
            ->sortByDesc('jumlah_terjual_total')
            ->take(10);

        $pdf = Pdf::loadView('admin.laporan.laporan_produk.produk_terlaris_pdf', compact('produks', 'bulan', 'tahun'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('produk_terlaris.pdf');
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
        $pdf = Pdf::loadView('admin.laporan.laporan_pelanggan.pelanggan_pdf', compact('pelanggans'))
            ->setPaper('a4', 'landscape');
        return $pdf->stream('laporan_pelanggan.pdf');
    }

    /**
     * LAPORAN PEMBELIAN
     */
    public function pembelian()
    {
        $pembelians = Pembelian::with('supplier')->get();
        $penjualans = Penjualan::with(['pelanggan.user'])->get();

        $laporanGabungan = collect();

        foreach ($pembelians as $p) {
            $laporanGabungan->push((object) [
                'supplier' => $p->supplier->nama ?? '-',
                'alamat'   => $p->supplier->alamat ?? '-',
                'telepon'  => $p->supplier->telepon ?? '-',
                'tanggal'  => $p->tanggal,
                'total'    => $p->total,
                'jenis'    => 'Pembelian Supplier',
            ]);
        }

        foreach ($penjualans as $pj) {
            $laporanGabungan->push((object) [
                'supplier' => $pj->pelanggan->nama ?? $pj->pelanggan->user->username ?? '-',
                'alamat'   => $pj->pelanggan->alamat ?? '-',
                'telepon'  => $pj->pelanggan->no_hp ?? '-',
                'tanggal'  => $pj->tanggal,
                'total'    => $pj->total,
                'jenis'    => 'Transaksi User',
            ]);
        }

        return view('admin.laporan.laporan_pembelian.laporan_pembelian', [
            'pembelians' => $laporanGabungan,
        ]);
    }

    public function cetakPembelian()
    {
        $pembelians = Pembelian::with('supplier')->get();
        $penjualans = Penjualan::with(['pelanggan.user'])->select('id', 'pelanggan_id', 'tanggal', 'total')->get();

        $laporanGabungan = collect();

        foreach ($pembelians as $p) {
            $laporanGabungan->push((object) [
                'supplier' => $p->supplier->nama ?? '-',
                'alamat'   => $p->supplier->alamat ?? '-',
                'telepon'  => $p->supplier->telepon ?? '-',
                'tanggal'  => $p->tanggal,
                'total'    => $p->total,
                'jenis'    => 'Pembelian Supplier',
            ]);
        }

        foreach ($penjualans as $pj) {
            $laporanGabungan->push((object) [
                'supplier' => $pj->pelanggan->nama ?? $pj->pelanggan->user->username ?? '-',
                'alamat'   => $pj->pelanggan->alamat ?? '-',
                'telepon'  => $pj->pelanggan->no_hp ?? '-',
                'tanggal'  => $pj->tanggal,
                'total'    => $pj->total,
                'jenis'    => 'Transaksi User',
            ]);
        }

        $pdf = Pdf::loadView('admin.laporan.laporan_pembelian.pembelian_pdf', [
            'pembelians' => $laporanGabungan,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('laporan_pembelian.pdf');
    }

    /**
     * LAPORAN PENJUALAN (dengan filter per bulan)
     */
    public function penjualan(Request $request)
    {
        $bulan = $request->get('bulan');

        $penjualans = Penjualan::with(['pelanggan.user', 'detailPenjualans.produk']);
        $transaksis = Transaksi::with(['user', 'detailTransaksi.produk']);

        if ($bulan) {
            $penjualans->whereMonth('tanggal', $bulan);
            $transaksis->whereMonth('created_at', $bulan);
        }

        $items = $penjualans->get()->concat($transaksis->get())
            ->sortByDesc(fn($item) => $item->tanggal ?? $item->created_at)
            ->values();

        return view('admin.laporan.laporan_penjualan.laporan_penjualan', compact('items', 'bulan'));
    }

    public function cetakPenjualan(Request $request)
    {
        $bulan = $request->get('bulan');

        $penjualans = Penjualan::with(['pelanggan.user', 'detailPenjualans.produk']);
        $transaksis = Transaksi::with(['user', 'detailTransaksi.produk']);

        if ($bulan) {
            $penjualans->whereMonth('tanggal', $bulan);
            $transaksis->whereMonth('created_at', $bulan);
        }

        $items = $penjualans->get()->concat($transaksis->get())
            ->sortByDesc(fn($item) => $item->tanggal ?? $item->created_at)
            ->values();

        $pdf = Pdf::loadView('admin.laporan.laporan_penjualan.penjualan_pdf', compact('items', 'bulan'))
            ->setPaper('a4', 'landscape');

        $namaBulan = $bulan ? Carbon::create()->month($bulan)->translatedFormat('F') : 'Semua';
        return $pdf->stream("laporan_penjualan_{$namaBulan}.pdf");
    }

    public function supplier()
    {
        $suppliers = \App\Models\Supplier::latest()->get();
        return view('admin.laporan.laporan_supplier.laporan_supplier', compact('suppliers'));
    }

    public function cetakSupplier()
    {
        $suppliers = \App\Models\Supplier::latest()->get();
        $pdf = Pdf::loadView('admin.laporan.laporan_supplier.supplier_pdf', compact('suppliers'))
            ->setPaper('a4', 'portrait');
        return $pdf->stream('laporan_supplier.pdf');
    }
}
