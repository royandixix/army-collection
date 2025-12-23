<?php

namespace App\Http\Controllers\Admin;

use App\Notifications\PengirimanDikirim;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->query('status', null);

        $penjualans = Penjualan::with(['pelanggan.user', 'detailPenjualans.produk'])
            ->when($statusFilter === 'belum_bayar', fn($q) => $q->where('status', 'belum_bayar'))
            ->latest('tanggal')->get();

        $transaksis = Transaksi::with(['user', 'detailTransaksi.produk', 'penjualan'])
            ->when($statusFilter === 'belum_bayar', fn($q) => $q->where('status', 'belum_bayar'))
            ->latest('created_at')->get();

        $items = $penjualans->concat($transaksis)
            ->sortByDesc(fn($i) => $i->tanggal ?? $i->created_at)
            ->map(function($i){
                $i->display_name = $i->pelanggan->nama ?? $i->pelanggan->user->username ?? $i->user->username ?? 'Tidak diketahui';
                $defaultImg = asset('img/default-user.png');
                $i->display_foto = $i->pelanggan->foto_profil ?? $i->pelanggan?->user->img ?? $i->user?->img ? asset('storage/' . ($i->pelanggan->foto_profil ?? $i->pelanggan?->user->img ?? $i->user->img)) : $defaultImg;
                $i->details = $i->detailPenjualans ?? collect();
                $i->total_jumlah = $i->details->sum('jumlah');
                $i->buktiPembayaran = $i->bukti_tf ? asset('storage/'.$i->bukti_tf) : null;
                $i->statusKirim = $i->status_pengiriman ?? 'pending';
                $i->buktiKirim = $i->bukti_pengiriman ? asset('storage/'.$i->bukti_pengiriman) : null;
                $i->buktiDiterima = $i->bukti_diterima ? asset('storage/'.$i->bukti_diterima) : null;
                return $i;
            })->values();

        return view('admin.manajemen_penjualan.manajemen_penjualan', compact('items'));
    }

    public function create()
    {
        $pelanggans = Pelanggan::all();
        $produks = Produk::all();
        return view('admin.manajemen_penjualan.tambah_manajemen_penjualan', compact('pelanggans','produks'));
    }

    public function storeManual(Request $request)
    {
        $validated = $request->validate([
            'nama'=>'required|string|max:255','email'=>'required|email|max:255',
            'tanggal'=>'nullable|date','status'=>'required|in:pending,lunas,batal',
            'metode_pembayaran'=>'required|in:cod,transfer,qris',
            'bukti_tf'=>'nullable|file|mimes:jpg,jpeg,png|max:20480',
            'produk_id'=>'required|array|min:1','jumlah'=>'required|array|min:1'
        ]);

        $fotoPath = $request->hasFile('foto_profil') ? $request->file('foto_profil')->store('foto_profil','public') : null;
        $pelanggan = Pelanggan::firstOrCreate(['email'=>$validated['email']],[
            'nama'=>$validated['nama'],'alamat'=>$request->alamat,'no_hp'=>$request->no_hp,'user_id'=>null
        ]);
        if($fotoPath) $pelanggan->update(['foto_profil'=>$fotoPath]);

        DB::beginTransaction();
        try {
            $total = 0;
            foreach($request->produk_id as $i=>$pid){
                $produk = Produk::findOrFail($pid);
                $jumlah = $request->jumlah[$i]??1;
                $total += $produk->harga*$jumlah;
            }

            $penjualan = Penjualan::create([
                'pelanggan_id'=>$pelanggan->id,
                'tanggal'=>$validated['tanggal']??now(),
                'total'=>$total,
                'status'=>$validated['status'],
                'metode_pembayaran'=>$validated['metode_pembayaran'],
                'user_id'=>Auth::id()
            ]);

            if(in_array($validated['metode_pembayaran'],['transfer','qris']) && $request->hasFile('bukti_tf')){
                $penjualan->update(['bukti_tf'=>$request->file('bukti_tf')->store('bukti_tf','public')]);
            }

            foreach($request->produk_id as $i=>$pid){
                $produk = Produk::findOrFail($pid);
                $jumlah = $request->jumlah[$i]??1;
                DB::table('detail_penjualans')->insert([
                    'penjualan_id'=>$penjualan->id,'produk_id'=>$pid,'jumlah'=>$jumlah,
                    'subtotal'=>$produk->harga*$jumlah,'created_at'=>now(),'updated_at'=>now()
                ]);
                if($produk->stok>=$jumlah) $produk->decrement('stok',$jumlah);
                else throw new \Exception("Stok produk {$produk->nama} tidak mencukupi.");
            }
            DB::commit();
            return redirect()->route('admin.manajemen.manajemen_penjualan')->with('success','✅ Penjualan berhasil ditambahkan & stok diperbarui.');
        }catch(\Throwable $e){
            DB::rollBack();
            return back()->with('error','Gagal menyimpan data: '.$e->getMessage());
        }
    }

    public function edit($id)
    {
        $penjualan = Penjualan::with('pelanggan')->findOrFail($id);
        $penjualan->pelanggan ??= new Pelanggan();
        return view('admin.manajemen_penjualan.edit_manajemen_penjualan', compact('penjualan'));
    }

    public function update(Request $request,$id)
    {
        $penjualan = Penjualan::with('pelanggan')->findOrFail($id);
        $request->validate([
            'nama'=>'required|string|max:255','email'=>'required|email|max:255',
            'no_hp'=>'nullable|string|max:20','alamat'=>'nullable|string|max:500',
            'tanggal'=>'required|date','status'=>'required|in:pending,diproses,selesai,batal,lunas',
            'total'=>'required|numeric|min:0','metode_pembayaran'=>'required|in:cod,transfer,qris',
            'foto_profil'=>'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bukti_tf'=>'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $pelanggan = $penjualan->pelanggan ?? Pelanggan::create([
            'nama'=>$request->nama,'email'=>$request->email,'no_hp'=>$request->no_hp,'alamat'=>$request->alamat
        ]);

        $pelanggan->update([
            'nama'=>$request->nama,'email'=>$request->email,'no_hp'=>$request->no_hp,'alamat'=>$request->alamat
        ]);

        if($request->hasFile('foto_profil')) $pelanggan->update(['foto_profil'=>$request->file('foto_profil')->store('foto_profil','public')]);

        $penjualan->update([
            'tanggal'=>$request->tanggal,'status'=>$request->status,
            'total'=>$request->total,'metode_pembayaran'=>$request->metode_pembayaran
        ]);

        if($request->hasFile('bukti_tf')) $penjualan->update(['bukti_tf'=>$request->file('bukti_tf')->store('bukti_tf','public')]);

        return redirect()->route('admin.manajemen.manajemen_penjualan')->with('success','✅ Data penjualan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $penjualan = Penjualan::findOrFail($id);
        if($penjualan->bukti_tf && Storage::disk('public')->exists($penjualan->bukti_tf)) Storage::disk('public')->delete($penjualan->bukti_tf);
        $penjualan->delete();
        return redirect()->route('admin.manajemen.manajemen_penjualan')->with('success','🗑️ Data penjualan berhasil dihapus.');
    }

    public function ubahStatus(Request $request,$id)
    {
        $penjualan = Penjualan::findOrFail($id);
        $request->validate(['status'=>'required|in:pending,diproses,selesai,batal,lunas']);
        $penjualan->update(['status'=>$request->status]);
        return back()->with('success','🔁 Status transaksi berhasil diperbarui.');
    }

    public function updatePengiriman(Request $request,$id)
    {
        $penjualan = Penjualan::findOrFail($id);
        $penjualan->status_pengiriman = $request->status_pengiriman;
        if($request->hasFile('bukti_pengiriman')) $penjualan->bukti_pengiriman = $request->file('bukti_pengiriman')->store('bukti_pengiriman','public');
        $penjualan->save();
        if($penjualan->pelanggan?->user) $penjualan->pelanggan->user->notify(new PengirimanDikirim($penjualan));
        return back()->with('success','✅ Status pengiriman & bukti berhasil diperbarui.');
    }

    public function updateBuktiDiterima(Request $request,$id)
    {
        $request->validate(['bukti_diterima'=>'required|image|mimes:jpg,jpeg,png|max:2048']);
        $penjualan = Penjualan::findOrFail($id);
        $penjualan->bukti_diterima = $request->file('bukti_diterima')->store('bukti_diterima','public');
        $penjualan->save();
        return back()->with('success','✅ Bukti diterima berhasil diupload.');
    }

    public function lihatBuktiDiterima($id)
    {
        $penjualan = Penjualan::findOrFail($id);
        return view('admin.penjualan.bukti_diterima', compact('penjualan'));
    }
}
