<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BuktiPembelian;
use Illuminate\Support\Facades\Storage;

class BuktiPembelianController extends Controller
{
    // Tampilkan form upload
    public function create($pembelian_id)
    {
        // Ganti relasi 'penjualan' dengan 'pembelian'
        $bukti = BuktiPembelian::with('pembelian.supplier')
            ->where('pembelian_id', $pembelian_id)
            ->first();

        return view('user.riwayat.upload_bukti', compact('bukti', 'pembelian_id'));
    }

    // Simpan file upload
    public function store(Request $request, $pembelian_id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:3072', // max 3 MB
        ]);

        $file = $request->file('bukti_pembayaran');
        $path = $file->store('bukti_pembelian', 'public');

        // Jika sudah ada bukti sebelumnya, hapus file lama
        $existingBukti = BuktiPembelian::where('pembelian_id', $pembelian_id)->first();
        if ($existingBukti && Storage::disk('public')->exists($existingBukti->file)) {
            Storage::disk('public')->delete($existingBukti->file);
        }

        // Update atau buat baru
        BuktiPembelian::updateOrCreate(
            ['pembelian_id' => $pembelian_id],
            [
                'file' => $path,
                'status_pengiriman' => 'pending'
            ]
        );

        return redirect()->back()->with('success', 'Bukti berhasil diupload');
    }
}
