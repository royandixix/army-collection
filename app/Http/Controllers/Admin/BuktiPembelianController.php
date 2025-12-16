<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BuktiPembelian;
use Illuminate\Http\Request;

class BuktiPembelianController extends Controller
{
    /**
     * Tampilkan daftar bukti pembelian.
     */
    public function index()
    {
        // Ambil semua bukti pembelian beserta relasi pembelian dan supplier
        $buktiPembelians = BuktiPembelian::with(['pembelian.supplier'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.bukti_pembelian.index', compact('buktiPembelians'));
    }

    /**
     * Update status pengiriman bukti pembelian.
     */
    public function updateStatus(Request $request, $id)
    {
        $bukti = BuktiPembelian::findOrFail($id);

        $request->validate([
            'status_pengiriman' => 'required|in:pending,dikirim,diterima',
        ]);

        $bukti->status_pengiriman = $request->status_pengiriman;
        $bukti->save();

        return redirect()->route('admin.bukti_pembelian.index')
            ->with('success', 'Status pengiriman berhasil diperbarui.');
    }

    /**
     * Download file bukti pembelian.
     */
    public function download($id)
    {
        $bukti = BuktiPembelian::findOrFail($id);

        $filePath = storage_path('app/public/' . $bukti->file);

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download($filePath);
    }
}
