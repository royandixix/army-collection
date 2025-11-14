<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ProdukController extends Controller
{
    // Tampilkan semua produk
    public function index()
    {
        $produks = Produk::with(['kategori', 'supplier'])->get();
        $kategoris = Kategori::all();
        return view('admin.manajemen_produk.manajemen_produk', compact('produks', 'kategoris'));
    }

    // Form tambah produk
    public function create()
    {
        $kategoris = Kategori::all();
        $suppliers = Supplier::all();
        return view('admin.manajemen_produk.tambah_manajemen_produk', compact('kategoris', 'suppliers'));
    }

    // Simpan produk baru
    public function store(Request $request)
    {
        // Hapus titik dari harga
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

        // Buat kategori baru jika diisi
        if ($request->kategori_baru) {
            $kategori = Kategori::firstOrCreate(['name' => $request->kategori_baru]);
            $kategori_id = $kategori->id;
        } else {
            $kategori_id = $request->kategori_id;
        }

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

        return redirect()->route('admin.manajemen.manajemen_produk')->with('success', 'Produk berhasil ditambahkan.');
    }

    // Form edit produk
    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        $kategoris = Kategori::all();
        $suppliers = Supplier::all();
        return view('admin.manajemen_produk.edit_manajemen_produk', compact('produk', 'kategoris', 'suppliers'));
    }

    // Update produk
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

        // Buat kategori baru jika diisi
        if ($request->kategori_baru) {
            $kategori = Kategori::firstOrCreate(['name' => $request->kategori_baru]);
            $kategori_id = $kategori->id;
        } else {
            $kategori_id = $request->kategori_id;
        }

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

        return redirect()->route('admin.manajemen.manajemen_produk')->with('success', 'Produk berhasil diupdate.');
    }

    // Hapus produk
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

        return redirect()->route('admin.manajemen.manajemen_produk')->with('success', 'Produk berhasil dihapus.');
    }

    
}
