<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Transaksi;
use App\Models\BuktiPembelian;
use App\Models\Pembelian;
use App\Models\Supplier;

class RiwayatController extends Controller
{
    /**
     * 📄 Halaman Riwayat Transaksi
     */
    public function index()
    {
        $transaksis = Transaksi::with([
            'detailTransaksi.produk',
            'penjualan'
        ])
        ->where('user_id', Auth::id())
        ->latest()
        ->get();

        return view('user.riwayat.riwayat', compact('transaksis'));
    }

    /**
     * 📤 Upload bukti pembayaran
     */
    public function uploadBuktiSubmit(Request $request, $id)
    {
        $request->validate([
            'bukti_tf' => 'required|image|mimes:jpg,jpeg,png|max:3072',
        ]);

        $transaksi = Transaksi::with('penjualan')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // 🔒 Validasi keamanan
        if ($transaksi->status !== 'pending') {
            return back()->with('error', 'Transaksi sudah diproses, tidak bisa upload bukti.');
        }

        if (!in_array($transaksi->metode, ['transfer', 'qris'])) {
            return back()->with('error', 'Metode ini tidak memerlukan bukti pembayaran.');
        }

        // 🗑️ Hapus bukti lama (jika ada)
        if ($transaksi->bukti_tf && Storage::disk('public')->exists($transaksi->bukti_tf)) {
            Storage::disk('public')->delete($transaksi->bukti_tf);
        }

        // 📂 Upload bukti baru
        $path = $request->file('bukti_tf')->store('bukti_tf', 'public');

        // 💾 Update transaksi
        $transaksi->update([
            'bukti_tf' => $path,
            'status'   => 'diproses',
        ]);

        // 🔄 Sinkron ke penjualan
        if ($transaksi->penjualan) {
            $transaksi->penjualan->update([
                'bukti_tf' => $path,
                'status'   => 'diproses',
            ]);
        }

        // 📌 Buat record Pembelian dan BuktiPembelian
        $supplier = Supplier::where('nama', 'Supplier Default')->first();
        if (!$supplier) {
            // buat supplier default jika belum ada
            $supplier = Supplier::create([
                'nama' => 'Supplier Default',
            ]);
        }

        $pembelian = Pembelian::create([
            'supplier_id' => $supplier->id,
            'tanggal'     => now(),
            'total'       => $transaksi->total,
        ]);

        BuktiPembelian::create([
            'pembelian_id'      => $pembelian->id,
            'file'              => $path,
            'status_pengiriman' => 'pending',
        ]);

        return redirect()
            ->route('user.riwayat.index')
            ->with('success', '✅ Bukti pembayaran berhasil diupload dan dicatat.');
    }
}
