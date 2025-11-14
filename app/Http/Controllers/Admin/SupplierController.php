<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    /**
     * Tampilkan daftar supplier
     */
    public function index()
    {
        $suppliers = Supplier::with([
            'produks.pembelianSuppliers', // stok masuk
            'produks.detailTransaksis'    // stok terjual
        ])->latest()->get();

        return view('admin.data_supplier.supplier', compact('suppliers'));
    }

    /**
     * Form tambah supplier
     */
    public function create()
    {
        return view('admin.data_supplier.tambah_supplier');
    }

    /**
     * Simpan supplier baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:20',
        ]);

        Supplier::create($request->all());

        return redirect()->route('admin.supplier.index')
            ->with('success', 'Supplier berhasil ditambahkan!');
    }

    /**
     * Form edit supplier
     */
    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('admin.data_supplier.edit_supplier', compact('supplier'));
    }

    /**
     * Update
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:20',
        ]);

        Supplier::findOrFail($id)->update($request->all());

        return redirect()->route('admin.supplier.index')
            ->with('success', 'Supplier berhasil diperbarui!');
    }

    /**
     * Hapus
     */
    public function destroy($id)
    {
        Supplier::findOrFail($id)->delete();

        return back()->with('success', 'Supplier berhasil dihapus!');
    }

    // suplier 
    public function supplier()
    {
        $suppliers = Supplier::orderBy('created_at', 'desc')->get();

        return view('admin.laporan.laporan_supplier.laporan_supplier', compact('suppliers'));
    }

    public function cetakSupplier()
    {
        $suppliers = Supplier::orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('admin.laporan.laporan_supplier.supplier_pdf', compact('suppliers'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('laporan_supplier.pdf');
    }
}
