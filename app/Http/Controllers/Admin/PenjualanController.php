<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\Pembelian;
use App\Models\BuktiPembelian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    /**
     * 📦 Halaman utama: manajemen penjualan & bukti pembelian
     */
    public function index(Request $request)
    {
        $statusFilter = $request->query('status', null);

        // Ambil penjualan
        $penjualansQuery = Penjualan::with([
            'pelanggan.user',
            'detailPenjualans.produk',
            'transaksi.detailTransaksi.produk'
        ])->latest('tanggal');

        if ($statusFilter === 'belum_bayar') {
            $penjualansQuery->where('status', 'belum_bayar');
        }

        $penjualans = $penjualansQuery->get();

        // Ambil transaksi
        $transaksisQuery = Transaksi::with([
            'user',
            'detailTransaksi.produk',
            'penjualan'
        ])->latest('created_at');

        if ($statusFilter === 'belum_bayar') {
            $transaksisQuery->where('status', 'belum_bayar');
        }

        $transaksis = $transaksisQuery->get();

        // Gabungkan penjualan + transaksi
        $items = $penjualans
            ->concat($transaksis)
            ->sortByDesc(fn($item) => $item->tanggal ?? $item->created_at)
            ->values();

        // Ambil pembelian & bukti pembelian untuk form dan tabel
        $pembelians = Pembelian::with('supplier')->get();
        $buktiPembelians = BuktiPembelian::with('pembelian.supplier')->get();

        return view('admin.manajemen_penjualan.manajemen_penjualan', compact(
            'items', 'pembelians', 'buktiPembelians'
        ));
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

        // Upload foto profil pelanggan jika ada
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
            // Hitung total harga semua produk
            $total = 0;
            foreach ($request->produk_id as $index => $produkId) {
                $produk = Produk::findOrFail($produkId);
                $jumlah = $request->jumlah[$index] ?? 1;
                $total += $produk->harga * $jumlah;
            }

            // Simpan data penjualan utama
            $penjualan = Penjualan::create([
                'pelanggan_id'      => $pelanggan->id,
                'tanggal'           => $validated['tanggal'] ?? now(),
                'total'             => $total,
                'status'            => $validated['status'],
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'user_id'           => Auth::id(),
            ]);

            // Upload bukti transfer jika ada
            if (in_array($validated['metode_pembayaran'], ['transfer', 'qris']) && $request->hasFile('bukti_tf')) {
                $penjualan->update([
                    'bukti_tf' => $this->uploadBukti($request->file('bukti_tf'))
                ]);
            }

            // Simpan detail produk & kurangi stok
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

                if ($produk->stok >= $jumlah) {
                    $produk->decrement('stok', $jumlah);
                } else {
                    throw new \Exception("Stok produk {$produk->nama} tidak mencukupi.");
                }
            }

            DB::commit();
            return redirect()->route('admin.manajemen.manajemen_penjualan')
                ->with('success', '✅ Penjualan berhasil ditambahkan & stok diperbarui.');
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
        $penjualan->pelanggan ??= new Pelanggan();
        $penjualan->bukti_tf ??= '';
        return view('admin.manajemen_penjualan.edit_manajemen_penjualan', compact('penjualan'));
    }

    /**
     * 🔧 Update data penjualan
     */
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

        $pelanggan = $penjualan->pelanggan ?? Pelanggan::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        $pelanggan->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        if ($request->hasFile('foto_profil')) {
            $path = $request->file('foto_profil')->store('foto_profil', 'public');
            $pelanggan->update(['foto_profil' => $path]);
        }

        $penjualan->update([
            'tanggal' => $request->tanggal,
            'status' => $request->status,
            'total' => $request->total,
            'metode_pembayaran' => $request->metode_pembayaran,
        ]);

        if ($request->hasFile('bukti_tf')) {
            $path = $request->file('bukti_tf')->store('bukti_tf', 'public');
            $penjualan->update(['bukti_tf' => $path]);
        }

        return redirect()->route('admin.manajemen.manajemen_penjualan')
            ->with('success', '✅ Data penjualan berhasil diperbarui.');
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
            ->with('success', '🗑️ Data penjualan berhasil dihapus.');
    }

    /**
     * 🔄 Ubah status penjualan
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

        return back()->with('success', '🔁 Status transaksi berhasil diperbarui.');
    }

    /**
     * 📂 Upload bukti pembayaran
     */
    private function uploadBukti($file)
    {
        return $file->store('bukti_tf', 'public');
    }

    /**
     * 📄 Tampilkan penjualan yang belum membayar
     */
    public function belumBayar()
    {
        $items = Penjualan::with(['pelanggan', 'detailPenjualans'])
            ->where('status', 'pending')
            ->latest('tanggal')
            ->get();

        return view('admin.manajemen_penjualan.belum_bayar', compact('items'));
    }
}
