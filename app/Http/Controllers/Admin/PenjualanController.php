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
        $penjualans = Penjualan::with(['pelanggan', 'user'])
            ->whereHas('user', function ($q) {
                $q->where('role', 'admin');
            })
            ->latest()
            ->get();

        $transaksis = Transaksi::with(['user.pelanggan'])
            ->whereHas('user', function ($q) {
                $q->where('role', 'user');
            })
            ->latest()
            ->get();

        return view('admin.manajemen_penjualan.manajemen_penjualan', compact('penjualans', 'transaksis'));
    }



    public function create()
    {
        $pelanggans = Pelanggan::all();
        return view('admin.manajemen_penjualan.tambah_manajemen_penjualan', compact('pelanggans'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatePenjualan($request);

        Penjualan::create([
            'pelanggan_id' => $validated['pelanggan_id'],
            'tanggal' => $validated['tanggal'],
            'total' => $validated['total'],
            'status' => $validated['status'],
            'metode_pembayaran' => $request->metode_pembayaran,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.manajemen.manajemen_penjualan')
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $penjualan = Penjualan::findOrFail($id);
        $pelanggans = Pelanggan::all();

        return view('admin.manajemen_penjualan.edit_manajemen_penjualan', compact('penjualan', 'pelanggans'));
    }

    public function update(Request $request, $id)
    {
        $penjualan = Penjualan::findOrFail($id);
        $validated = $this->validatePenjualan($request);

        $penjualan->update([
            'pelanggan_id' => $validated['pelanggan_id'],
            'tanggal' => $validated['tanggal'],
            'total' => $validated['total'],
            'status' => $validated['status'],
            'metode_pembayaran' => $request->metode_pembayaran,
        ]);

        return redirect()->route('admin.manajemen.manajemen_penjualan')
            ->with('success', 'Transaksi berhasil diperbarui.');
    }



    public function destroy($id)
    {
        $penjualan = Penjualan::findOrFail($id);
        $penjualan->delete();

        return redirect()->route('admin.manajemen.manajemen_penjualan')
            ->with('success', 'Transaksi berhasil dihapus.');
    }

    public function ubahStatus(Request $request, $id)
    {
        $penjualan = Penjualan::find($id);
    
        if (!$penjualan) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
        }
    
        $request->validate([
            'status' => 'required|in:pending,diproses,selesai,batal,lunas',
        ]);
    
        // Update status penjualan
        $penjualan->status = $request->status;
        $penjualan->save();
    
        // Sinkron ke semua transaksi terkait
        $penjualan->transaksi()->update(['status' => $request->status]);
    
        return redirect()->back()->with('success', 'Status transaksi berhasil diperbarui.');
    }
    




    protected function validatePenjualan(Request $request)
    {
        return $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'tanggal' => 'required|date',
            'total' => 'required|integer|min:1',
            'status' => 'required|in:lunas,pending,batal',
            'metode_pembayaran' => 'required|in:cod,qiris,transfer',
        ], [
            'pelanggan_id.required' => 'Pelanggan wajib dipilih.',
            'tanggal.required' => 'Tanggal transaksi wajib diisi.',
            'total.required' => 'Total pembayaran wajib diisi.',
            'status.required' => 'Status pembayaran wajib dipilih.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
        ]);
    }
}
