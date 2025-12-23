<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Transaksi;
use App\Models\Pembelian;
use App\Models\Supplier;
use App\Models\BuktiPembelian;

class RiwayatController extends Controller
{
    // Halaman Riwayat Transaksi
    public function index()
    {
        $transaksis = Transaksi::with(['detailTransaksi.produk','penjualan'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.riwayat.riwayat', compact('transaksis'));
    }

    // Upload Bukti Pembayaran
    public function uploadBukti(Request $request, $id)
    {
        $request->validate(['bukti_tf'=>'required|image|mimes:jpg,jpeg,png|max:3072']);

        $transaksi = Transaksi::with('penjualan')
            ->where('id',$id)->where('user_id', Auth::id())->firstOrFail();

        if($transaksi->status !== 'pending') {
            return back()->with('error','Transaksi sudah diproses, tidak bisa upload bukti.');
        }

        if(!in_array($transaksi->metode,['transfer','qris'])) {
            return back()->with('error','Metode ini tidak memerlukan bukti pembayaran.');
        }

        // Hapus bukti lama
        if($transaksi->bukti_tf && Storage::disk('public')->exists($transaksi->bukti_tf)) {
            Storage::disk('public')->delete($transaksi->bukti_tf);
        }

        $path = $request->file('bukti_tf')->store('bukti_tf','public');

        $transaksi->update(['bukti_tf'=>$path,'status'=>'diproses']);

        // Sinkron ke penjualan
        if($transaksi->penjualan) {
            $transaksi->penjualan->update(['bukti_tf'=>$path,'status'=>'diproses']);
        }

        // Buat Pembelian & BuktiPembelian
        $supplier = Supplier::firstOrCreate(['nama'=>'Supplier Default']);
        $pembelian = Pembelian::create(['supplier_id'=>$supplier->id,'tanggal'=>now(),'total'=>$transaksi->total]);
        BuktiPembelian::create(['pembelian_id'=>$pembelian->id,'file'=>$path,'status_pengiriman'=>'pending']);

        return redirect()->route('user.riwayat.index')->with('success','Bukti pembayaran berhasil diupload.');
    }

    // Upload Bukti Diterima
    public function uploadBuktiDiterima(Request $request, $id)
    {
        $penjualan = \App\Models\Penjualan::findOrFail($id);

        $request->validate(['bukti_diterima'=>'required|image|mimes:jpg,jpeg,png|max:2048']);

        $filePath = $request->file('bukti_diterima')->store('bukti_diterima','public');

        $penjualan->update(['status_pengiriman'=>'diterima','bukti_diterima'=>$filePath]);

        return redirect()->back()->with('success','Bukti diterima berhasil diupload.');
    }
}
