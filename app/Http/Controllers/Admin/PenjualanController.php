<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Pelanggan;

class PenjualanController extends Controller
{
    /**
     * 🔍 Tampilkan semua transaksi penjualan
     */
    public function index()
    {
        $penjualans = Penjualan::with('pelanggan')->latest()->get();
        return view('admin.manajemen_penjualan.manajemen_penjualan', compact('penjualans'));
    }

    /**
     * ➕ Tampilkan form tambah transaksi
     */
    public function create()
    {
        $pelanggans = Pelanggan::all();
        return view('admin.manajemen_penjualan.tambah_manajemen_penjualan', compact('pelanggans'));
    }

    /**
     * 💾 Simpan transaksi baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'tanggal'      => 'required|date',
            'total'        => 'required|integer|min:1',
            'status'       => 'required|in:lunas,pending,batal',
        ], [
            'pelanggan_id.required' => 'Pelanggan wajib dipilih.',
            'pelanggan_id.exists'   => 'Pelanggan tidak ditemukan.',
            'tanggal.required'      => 'Tanggal transaksi wajib diisi.',
            'tanggal.date'          => 'Format tanggal tidak valid.',
            'total.required'        => 'Total pembayaran wajib diisi.',
            'total.integer'         => 'Total harus berupa angka bulat.',
            'total.min'             => 'Total tidak boleh 0.',
            'status.required'       => 'Status pembayaran wajib dipilih.',
            'status.in'             => 'Status harus salah satu dari: lunas, pending, atau batal.',
        ]);

        Penjualan::create($validated);

        return redirect()->route('admin.manajemen.manajemen_penjualan')
                         ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    /**
     * 📄 Detail transaksi penjualan
     */
    public function show($id)
    {
        $penjualan = Penjualan::with('pelanggan')->findOrFail($id);
        return view('admin.manajemen_penjualan.show', compact('penjualan'));
    }

    /**
     * ✏️ Tampilkan form edit
     */
    public function edit($id)
    {
        $penjualan = Penjualan::findOrFail($id);
        $pelanggans = Pelanggan::all();

        return view('admin.manajemen_penjualan.edit', compact('penjualan', 'pelanggans'));
    }

    /**
     * 🔄 Update transaksi
     */
    public function update(Request $request, $id)
    {
        $penjualan = Penjualan::findOrFail($id);

        $validated = $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'tanggal'      => 'required|date',
            'total'        => 'required|integer|min:1',
            'status'       => 'required|in:lunas,pending,batal',
        ], [
            'pelanggan_id.required' => 'Pelanggan wajib dipilih.',
            'pelanggan_id.exists'   => 'Pelanggan tidak ditemukan.',
            'tanggal.required'      => 'Tanggal transaksi wajib diisi.',
            'tanggal.date'          => 'Format tanggal tidak valid.',
            'total.required'        => 'Total pembayaran wajib diisi.',
            'total.integer'         => 'Total harus berupa angka bulat.',
            'total.min'             => 'Total tidak boleh 0.',
            'status.required'       => 'Status pembayaran wajib dipilih.',
            'status.in'             => 'Status harus salah satu dari: lunas, pending, atau batal.',
        ]);

        $penjualan->update($validated);

        return redirect()->route('admin.manajemen.manajemen_penjualan')
                         ->with('success', 'Transaksi berhasil diperbarui.');
    }

    /**
     * 🗑️ Hapus transaksi
     */
    public function destroy($id)
    {
        $penjualan = Penjualan::findOrFail($id);
        $penjualan->delete();

        return redirect()->route('admin.manajemen.manajemen_penjualan')
                         ->with('success', 'Transaksi berhasil dihapus.');
    }
}
