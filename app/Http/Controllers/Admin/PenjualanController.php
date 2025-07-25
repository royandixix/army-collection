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
     * 🔍 Tampilkan semua transaksi penjualan (Admin & User)
     */
    public function index()
    {
        $penjualans = Penjualan::with(['pelanggan', 'user'])->latest()->get();
        $transaksis = Transaksi::with(['user.pelanggan'])->latest()->get();

        return view('admin.manajemen_penjualan.manajemen_penjualan', compact('penjualans', 'transaksis'));
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
        $validated = $this->validatePenjualan($request);

        Penjualan::create([
            'pelanggan_id'       => $validated['pelanggan_id'],
            'tanggal'            => $validated['tanggal'],
            'total'              => $validated['total'],
            'status'             => $validated['status'],
            'metode_pembayaran'  => $request->metode_pembayaran,
            'user_id'            => Auth::id(),
        ]);

        return redirect()->route('admin.manajemen.manajemen_penjualan')
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    /**
     * ✏️ Tampilkan form edit
     */
    public function edit($id)
    {
        $penjualan = Penjualan::findOrFail($id);
        $pelanggans = Pelanggan::all();

        return view('admin.manajemen_penjualan.edit_manajemen_penjualan', compact('penjualan', 'pelanggans'));
    }

    /**
     * 🔄 Update transaksi
     */
    public function update(Request $request, $id)
    {
        $penjualan = Penjualan::findOrFail($id);
        $validated = $this->validatePenjualan($request);

        $penjualan->update([
            'pelanggan_id'       => $validated['pelanggan_id'],
            'tanggal'            => $validated['tanggal'],
            'total'              => $validated['total'],
            'status'             => $validated['status'],
            'metode_pembayaran'  => $request->metode_pembayaran,
        ]);

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

    /**
     * 🟡 Ubah status transaksi
     */
    public function ubahStatus(Request $request, $id)
    {
        $item = Penjualan::find($id) ?? Transaksi::find($id);

        if (!$item) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
        }

        $request->validate([
            'status' => $item instanceof Penjualan
                ? 'required|in:lunas,pending,batal'
                : 'required|in:pending,diproses,selesai,batal',
        ]);

        $item->status = $request->status;
        $item->save();

        return redirect()->back()->with('success', 'Status transaksi berhasil diperbarui.');
    }

    /**
     * ✅ Validasi data transaksi
     */
    protected function validatePenjualan(Request $request)
    {
        return $request->validate([
            'pelanggan_id'       => 'required|exists:pelanggans,id',
            'tanggal'            => 'required|date',
            'total'              => 'required|integer|min:1',
            'status'             => 'required|in:lunas,pending,batal',
            'metode_pembayaran'  => 'required|in:cod,qiris,transfer',
        ], [
            'pelanggan_id.required'      => 'Pelanggan wajib dipilih.',
            'tanggal.required'           => 'Tanggal transaksi wajib diisi.',
            'total.required'             => 'Total pembayaran wajib diisi.',
            'status.required'            => 'Status pembayaran wajib dipilih.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
        ]);
    }
}
