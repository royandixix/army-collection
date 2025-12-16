<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Supplier;
use App\Models\PembelianSupplier;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ProdukController extends Controller
{
    // ============================
    // INDEX / LIST PRODUK
    // ============================
    public function index()
{
    // Ambil semua produk sekaligus dengan relasi
    $produks = Produk::with(['kategori', 'pembelianSuppliers', 'detailTransaksis'])->get();
    $kategoris = Kategori::all();

    // Mapping kartu stok
    $kartuStok = $produks->map(function ($produk) {
        // total barang masuk dari pembelian supplier
        $barangMasuk = $produk->pembelianSuppliers->sum('jumlah');

        // total barang keluar dari penjualan / transaksi
        $barangKeluar = $produk->detailTransaksis->sum('jumlah');

        // stok sekarang di tabel produk
        $sisa = $produk->stok;

        return [
            'nama'   => $produk->nama,
            'masuk'  => $barangMasuk,
            'keluar' => $barangKeluar,
            'sisa'   => $sisa,
        ];
    });

    return view('admin.manajemen_produk.manajemen_produk', compact('produks', 'kategoris', 'kartuStok'));
}


    // ============================
    // CREATE PRODUK
    // ============================
    public function create()
    {
        $kategoris = Kategori::all();
        $suppliers = Supplier::all();
        return view('admin.manajemen_produk.tambah_manajemen_produk', compact('kategoris', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->merge(['price' => str_replace('.', '', $request->price)]);

        $request->validate([
            'name' => 'required|string|max:255',
            'kategori_id' => 'nullable|exists:kategoris,id',
            'kategori_baru' => 'nullable|string|max:255',
            'supplier_id' => 'required|exists:suppliers,id',
            'deskripsi' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|max:2048',
        ]);

        $kategori_id = $request->kategori_baru
            ? Kategori::firstOrCreate(['name' => $request->kategori_baru])->id
            : $request->kategori_id;

        $data = [
            'nama' => $request->name,
            'kategori_id' => $kategori_id,
            'supplier_id' => $request->supplier_id,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->price,
            'stok' => $request->stock,
        ];

        if ($request->hasFile('image')) {
            $data['gambar'] = $request->file('image')->store('produk', 'public');
        }

        Produk::create($data);

        return redirect()->route('admin.manajemen.manajemen_produk')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    // ============================
    // EDIT & UPDATE PRODUK
    // ============================
    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        $kategoris = Kategori::all();
        $suppliers = Supplier::all();
        return view('admin.manajemen_produk.edit_manajemen_produk', compact('produk', 'kategoris', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $request->merge(['price' => str_replace('.', '', $request->price)]);

        $request->validate([
            'name' => 'required|string|max:255',
            'kategori_id' => 'nullable|exists:kategoris,id',
            'kategori_baru' => 'nullable|string|max:255',
            'supplier_id' => 'required|exists:suppliers,id',
            'deskripsi' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|max:2048',
        ]);

        $kategori_id = $request->kategori_baru
            ? Kategori::firstOrCreate(['name' => $request->kategori_baru])->id
            : $request->kategori_id;

        $data = [
            'nama' => $request->name,
            'kategori_id' => $kategori_id,
            'supplier_id' => $request->supplier_id,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->price,
            'stok' => $request->stock,
        ];

        if ($request->hasFile('image')) {
            if ($produk->gambar && Storage::disk('public')->exists($produk->gambar)) {
                Storage::disk('public')->delete($produk->gambar);
            }
            $data['gambar'] = $request->file('image')->store('produk', 'public');
        }

        $produk->update($data);

        return redirect()->route('admin.manajemen.manajemen_produk')
            ->with('success', 'Produk berhasil diupdate.');
    }

    // ============================
    // DELETE PRODUK
    // ============================
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $selisihHari = Carbon::parse($produk->created_at)->diffInDays(now());

        if ($selisihHari > 7) {
            return redirect()->route('admin.manajemen.manajemen_produk')
                ->with('error', 'Produk lama (lebih dari 1 minggu) tidak dapat dihapus.');
        }

        if ($produk->gambar && Storage::disk('public')->exists($produk->gambar)) {
            Storage::disk('public')->delete($produk->gambar);
        }

        $produk->delete();

        return redirect()->route('admin.manajemen.manajemen_produk')
            ->with('success', 'Produk berhasil dihapus.');
    }

    // ============================
    // KARTU STOK
    // ============================
    public function kartuStok()
    {
        $produks = Produk::with(['pembelianSuppliers', 'detailTransaksis'])->get();

        $kartuStok = $produks->map(function ($produk) {
            return [
                'nama'   => $produk->nama,
                'masuk'  => $produk->pembelianSuppliers->sum('jumlah'),
                'keluar' => $produk->detailTransaksis->sum('jumlah'),
                'sisa'   => $produk->stok,
            ];
        });

        return view('admin.manajemen_produk.kartu_stok', compact('kartuStok'));
    }

    // ============================
    // KARTU STOK PDF
    // ============================
    public function kartuStokPdf()
    {
        $produks = Produk::with(['pembelianSuppliers', 'detailTransaksis'])->get();

        $kartuStok = $produks->map(function ($produk) {
            return [
                'nama'   => $produk->nama,
                'masuk'  => $produk->pembelianSuppliers->sum('jumlah'),
                'keluar' => $produk->detailTransaksis->sum('jumlah'),
                'sisa'   => $produk->stok,
            ];
        });

        $pdf = Pdf::loadView('admin.manajemen_produk.kartu_stok_pdf', compact('kartuStok'));

        return $pdf->stream('kartu_stok.pdf');
    }
}
