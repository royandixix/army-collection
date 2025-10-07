<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;

class PenjualanController extends Controller
{
    /**
     * ✅ Tampilkan daftar penjualan & transaksi (digabung unik)
     */
    public function index()
{
    // Ambil Penjualan yang punya pelanggan atau user
    $penjualans = Penjualan::with(['pelanggan', 'user'])
        ->whereHas('pelanggan')
        ->orWhereHas('user')
        ->latest()
        ->get();

    // Ambil Transaksi yang punya user
    $transaksis = Transaksi::with('user')
        ->whereHas('user')
        ->latest()
        ->get();

    // Gabungkan semua, urut berdasarkan tanggal/created_at
    $items = $penjualans->concat($transaksis)
        ->sortByDesc(function($item) {
            return $item->tanggal ?? $item->created_at;
        })
        ->values();

    return view('admin.manajemen_penjualan.manajemen_penjualan', compact('items'));
}




    /**
     * ✅ Form tambah penjualan manual
     */
    public function create()
    {
        $pelanggans = Pelanggan::all();
        $penjualans = Penjualan::with('pelanggan')->latest()->get();

        return view('admin.manajemen_penjualan.tambah_manajemen_penjualan', compact('pelanggans', 'penjualans'));
    }

    /**
     * ✅ Simpan data penjualan (dari pelanggan yang sudah ada)
     */
    public function store(Request $request)
    {
        $validated = $this->validatePenjualan($request);

        Penjualan::create([
            'pelanggan_id' => $validated['pelanggan_id'] ?? null,
            'tanggal' => $validated['tanggal'],
            'total' => $validated['total'],
            'status' => $validated['status'],
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.manajemen.manajemen_penjualan')
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    /**
     * ✅ Simpan data penjualan manual (tanpa pelanggan sebelumnya)
     */
    public function storeManual(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pelanggans,email',
            'tanggal' => 'required|date',
            'total' => 'required|integer|min:1',
            'status' => 'required|in:lunas,pending,batal',
            'metode_pembayaran' => 'required|in:cod,qris,transfer',
        ]);

        $pelanggan = Pelanggan::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
        ]);

        Penjualan::create([
            'pelanggan_id' => $pelanggan->id,
            'tanggal' => $validated['tanggal'],
            'total' => $validated['total'],
            'status' => $validated['status'],
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.manajemen.manajemen_penjualan')
            ->with('success', 'Transaksi manual berhasil disimpan.');
    }

    /**
     * ✅ Edit data penjualan
     */
    public function edit($id)
    {
        $penjualan = Penjualan::findOrFail($id);
        $pelanggans = Pelanggan::all();

        return view('admin.manajemen_penjualan.edit_manajemen_penjualan', compact('penjualan', 'pelanggans'));
    }

    /**
     * ✅ Update data penjualan
     */
    public function update(Request $request, $id)
    {
        $penjualan = Penjualan::findOrFail($id);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'total' => 'required|integer|min:1',
            'status' => 'required|in:lunas,pending,batal',
            'metode_pembayaran' => 'required|in:cod,qris,transfer',
        ]);

        $data = $validated;

        if ($request->filled('pelanggan_id')) {
            $data['pelanggan_id'] = $request->pelanggan_id;
        }

        $penjualan->update($data);

        return redirect()->route('admin.manajemen.manajemen_penjualan')
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    /**
     * ✅ Hapus data penjualan
     */
    public function destroy($id)
    {
        $penjualan = Penjualan::findOrFail($id);
        $penjualan->delete();

        return redirect()->route('admin.manajemen.manajemen_penjualan')
            ->with('success', 'Transaksi berhasil dihapus.');
    }

    /**
     * ✅ Ubah status transaksi penjualan
     */
    public function ubahStatus(Request $request, $id)
    {
        $penjualan = Penjualan::find($id);

        if (!$penjualan) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
        }

        $request->validate([
            'status' => 'required|in:pending,diproses,selesai,batal,lunas',
        ]);

        $penjualan->status = $request->status;
        $penjualan->save();

        // Mapping ke status transaksi agar enum tidak error
        $mappedStatus = match ($request->status) {
            'lunas' => 'selesai',
            'pending' => 'pending',
            'batal' => 'batal',
            default => 'diproses',
        };

        if (method_exists($penjualan, 'transaksi')) {
            $penjualan->transaksi()->update(['status' => $mappedStatus]);
        }

        return redirect()->back()->with('success', 'Status transaksi berhasil diperbarui.');
    }

    /**
     * ✅ Validasi input penjualan
     */
    protected function validatePenjualan(Request $request)
    {
        return $request->validate([
            'pelanggan_id' => 'nullable|exists:pelanggans,id',
            'tanggal' => 'required|date',
            'total' => 'required|integer|min:1',
            'status' => 'required|in:lunas,pending,batal',
            'metode_pembayaran' => 'required|in:cod,qris,transfer',
        ]);
    }
}
