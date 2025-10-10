<?php

namespace App\Http\Controllers\Admin;

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
    /**
     * 📦 Daftar Penjualan & Transaksi
     */
    public function index()
    {
        $penjualans = Penjualan::with(['pelanggan.user', 'transaksi.detailTransaksi'])
            ->latest('tanggal')
            ->get();

        $transaksis = Transaksi::with(['user', 'detailTransaksi'])
            ->latest('created_at')
            ->get();

        $items = $penjualans
            ->concat($transaksis)
            ->sortByDesc(fn($item) => $item->tanggal ?? $item->created_at)
            ->values();

        return view('admin.manajemen_penjualan.manajemen_penjualan', compact('items'));
    }

    /**
     * 🧩 Form tambah penjualan manual
     */
    public function create()
    {
        $pelanggans = Pelanggan::all();
        $produks = Produk::all();

        return view('admin.manajemen_penjualan.tambah_manajemen_penjualan', compact('pelanggans', 'produks'));
    }

    /**
     * 🧾 Simpan penjualan manual
     */
    public function storeManual(Request $request)
    {
        $validated = $request->validate([
            'nama'               => 'required|string|max:255',
            'email'              => 'required|email|max:255',
            'tanggal'            => 'nullable|date',
            'status'             => 'required|in:pending,lunas,batal',
            'metode_pembayaran'  => 'required|in:cod,transfer,qris',
            'bukti_tf'           => 'nullable|file|mimes:jpg,jpeg,png|max:20480',
            'produk_id'          => 'required|array|min:1',
            'jumlah'             => 'required|array',
        ]);

        // Upload foto profil pelanggan
        $fotoPath = $request->hasFile('foto_profil')
            ? $request->file('foto_profil')->store('foto_profil', 'public')
            : null;

        // Buat atau ambil pelanggan
        $pelanggan = Pelanggan::firstOrCreate(
            ['email' => $validated['email']],
            [
                'nama'    => $validated['nama'],
                'alamat'  => $request->input('alamat'),
                'no_hp'   => $request->input('no_hp'),
                'user_id' => null,
            ]
        );

        if ($fotoPath) {
            $pelanggan->update(['foto_profil' => $fotoPath]);
        }

        DB::beginTransaction();
        try {
            // Hitung total penjualan
            $total = 0;
            foreach ($request->produk_id as $index => $produkId) {
                $produk = Produk::findOrFail($produkId);
                $jumlah = $request->jumlah[$index] ?? 1;
                $total += $produk->harga * $jumlah;
            }

            // Simpan penjualan
            $penjualan = Penjualan::create([
                'pelanggan_id'      => $pelanggan->id,
                'tanggal'           => $validated['tanggal'] ?? now(),
                'total'             => $total,
                'status'            => $validated['status'],
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'user_id'           => Auth::id(),
            ]);

            // Upload bukti transfer / QRIS
            if (in_array($validated['metode_pembayaran'], ['transfer', 'qris']) && $request->hasFile('bukti_tf')) {
                $penjualan->update([
                    'bukti_tf' => $this->uploadBukti($request->file('bukti_tf'))
                ]);
            }

            // Simpan detail produk
            foreach ($request->produk_id as $index => $produkId) {
                $produk = Produk::findOrFail($produkId);
                $jumlah = $request->jumlah[$index] ?? 1;

                DB::table('detail_penjualans')->insert([
                    'penjualan_id' => $penjualan->id,
                    'produk_id'    => $produkId,
                    'jumlah'       => $jumlah,
                    'subtotal'     => $produk->harga * $jumlah,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }

            DB::commit();
            return redirect()->route('admin.manajemen.manajemen_penjualan')
                ->with('success', 'Penjualan berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * ✏️ Form edit penjualan
     */
    public function edit($id)
    {
        $penjualan = Penjualan::with(['pelanggan'])->findOrFail($id);

        // Pastikan objek pelanggan selalu ada agar Blade tidak error
        $penjualan->pelanggan ??= new \App\Models\Pelanggan();

        // Pastikan bukti_tf selalu string
        $penjualan->bukti_tf ??= '';

        return view('admin.manajemen_penjualan.edit_manajemen_penjualan', compact('penjualan'));
    }

    public function update(Request $request, $id)
    {
        $penjualan = Penjualan::with('pelanggan')->findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
            'tanggal' => 'required|date',
            'status' => 'required|in:pending,diproses,selesai,batal,lunas',
            'total' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:cod,transfer,qris',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bukti_tf' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // ========== UPDATE DATA PELANGGAN ==========
        $pelanggan = $penjualan->pelanggan;

        if (!$pelanggan) {
            // Jika pelanggan belum ada
            $pelanggan = Pelanggan::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'user_id' => null,
            ]);
            $penjualan->pelanggan_id = $pelanggan->id;
            $penjualan->save();
        } else {
            // Jika pelanggan sudah ada
            $pelanggan->update([
                'nama' => $request->nama,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
            ]);
        }

        // Upload foto profil jika ada
        if ($request->hasFile('foto_profil')) {
            $path = $request->file('foto_profil')->store('foto_profil', 'public');
            $pelanggan->update(['foto_profil' => $path]);
        }

        // ========== UPDATE DATA PENJUALAN ==========
        $penjualan->update([
            'tanggal' => $request->tanggal,
            'status' => $request->status,
            'total' => $request->total,
            'metode_pembayaran' => $request->metode_pembayaran,
        ]);

        // Upload bukti pembayaran jika ada
        if ($request->hasFile('bukti_tf')) {
            $path = $request->file('bukti_tf')->store('bukti_tf', 'public');
            $penjualan->update(['bukti_tf' => $path]);
        }

        return redirect()->route('admin.manajemen.manajemen_penjualan')
            ->with('success', 'Data penjualan berhasil diupdate.');
    }




    /**
     * 🗑️ Hapus penjualan
     */
    public function destroy($id)
    {
        $penjualan = Penjualan::findOrFail($id);

        if ($penjualan->bukti_tf && Storage::disk('public')->exists($penjualan->bukti_tf)) {
            Storage::disk('public')->delete($penjualan->bukti_tf);
        }

        $penjualan->delete();

        return redirect()->route('admin.manajemen.manajemen_penjualan')
            ->with('success', 'Data penjualan berhasil dihapus.');
    }

    /**
     * 🔄 Ubah status penjualan & sinkron transaksi
     */
    public function ubahStatus(Request $request, $id)
    {
        $penjualan = Penjualan::findOrFail($id);

        $request->validate(['status' => 'required|in:pending,diproses,selesai,batal,lunas']);

        $penjualan->update(['status' => $request->status]);

        if (method_exists($penjualan, 'transaksi')) {
            $mappedStatus = match ($request->status) {
                'lunas'   => 'selesai',
                'pending' => 'pending',
                'batal'   => 'batal',
                default   => 'diproses',
            };
            $penjualan->transaksi()->update(['status' => $mappedStatus]);
        }

        return back()->with('success', 'Status transaksi berhasil diperbarui.');
    }

    /**
     * 📂 Upload bukti pembayaran ke storage
     */
    private function uploadBukti($file)
    {
        return $file->store('bukti_tf', 'public');
    }
}
