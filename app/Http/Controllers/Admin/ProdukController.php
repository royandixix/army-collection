<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    // ✅ Tampilkan semua produk
    public function index()
    {
        $produks = Produk::with('kategori')->get();
        $kategoris = Kategori::all();
        return view('admin.manajemen_produk.manajemen_produk', compact('produks', 'kategoris'));
    }

    // ✅ Tampilkan form tambah produk
    public function create()
    {
        $kategoris = Kategori::all();
        return view('admin.manajemen_produk.tambah_manajemen_produk', compact('kategoris'));
    }

    // ✅ Simpan data produk baru
    public function store(Request $request)
    {
        // Ubah harga jadi integer (hilangkan titik)
        $request->merge([
            'price' => str_replace('.', '', $request->price)
        ]);

        $request->validate([
            'name' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'deskripsi' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|max:2048',
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'price.required' => 'Harga produk wajib diisi.',
            'stock.required' => 'Stok produk wajib diisi.',
            'image.image' => 'File harus berupa gambar.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $data = [
            'nama' => $request->name,
            'kategori_id' => $request->kategori_id,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->price,
           'stok' => $request->input('stock'), // ✅ FIELD yang benar
        ];

        if ($request->hasFile('image')) {
            $data['gambar'] = $request->file('image')->store('produk', 'public');
        }

        Produk::create($data);

        return redirect()->route('admin.manajemen.manajemen_produk')->with('success', 'Produk berhasil ditambahkan.');
    }

    // ✅ Tampilkan form edit produk
    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        $kategoris = Kategori::all();
        return view('admin.manajemen_produk.edit_manajemen_produk', compact('produk', 'kategoris'));
    }

    // ✅ Update data produk
    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->merge([
            'price' => str_replace('.', '', $request->price),
        ]);

        $request->validate([
            'name' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'deskripsi' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|max:2048',
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'price.required' => 'Harga wajib diisi.',
            'stock.required' => 'Stok wajib diisi.',
            
        ]);

        $data = [
            'nama' => $request->name,
            'kategori_id' => $request->kategori_id,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->price,
            'stok' => $request->input('stock'), // ✅ FIELD yang benar
        ];

        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($produk->gambar && Storage::disk('public')->exists($produk->gambar)) {
                Storage::disk('public')->delete($produk->gambar);
            }
            $data['gambar'] = $request->file('image')->store('produk', 'public');
        }

        $produk->update($data);

        return redirect()->route('admin.manajemen.manajemen_produk')->with('success', 'Produk berhasil diupdate.');
    }

    // ✅ Hapus produk
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        if ($produk->gambar && Storage::disk('public')->exists($produk->gambar)) {
            Storage::disk('public')->delete($produk->gambar);
        }

        $produk->delete();

        return redirect()->route('admin.manajemen.manajemen_produk')->with('success', 'Produk berhasil dihapus.');
    }

    

    
}
