<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class PelangganController extends Controller
{
    /**
     * Tampilkan daftar pelanggan.
     */
    public function index()
    {
        // Ambil user dengan role 'user' beserta relasinya
        $userPelanggan = User::where('role', 'user')
            ->with(['pelanggan.penjualans', 'transaksis'])
            ->get();

        // Ambil pelanggan manual yang tidak punya user_id
        $manualPelanggan = Pelanggan::whereNull('user_id')
            ->with('penjualans')
            ->get();

        // Gabungkan keduanya
        $pelanggans = $userPelanggan->concat($manualPelanggan);

        return view('admin.manajemen_pelanggan.manajemen_pelanggan', compact('pelanggans'));
    }

    /**
     * Form tambah pelanggan baru.
     */
    public function create()
    {
        return view('admin.manajemen_pelanggan.tambah_manajemen_pelanggan');
    }

    /**
     * Simpan pelanggan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email',
            'alamat' => 'nullable|string|max:500',
            'no_hp'  => 'nullable|string|max:20',
        ]);

        // Buat user
        $user = User::create([
            'username' => $validated['nama'],
            'email'    => $validated['email'],
            'password' => Hash::make('password123'),
            'role'     => 'user',
            'status'   => 'active',
        ]);

        // Buat relasi pelanggan
        $user->pelanggan()->create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'alamat' => $validated['alamat'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
            'jumlah_transaksi_manual' => 0,
        ]);

        return redirect()->route('admin.manajemen.manajemen_pelanggan')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    /**
     * Form edit pelanggan.
     */
    public function edit($id)
    {
        // Cek apakah ID adalah user
        $user = User::with(['pelanggan', 'transaksis'])->find($id);

        if ($user) {
            $pelanggan = $user->pelanggan;
        } else {
            // Pelanggan manual
            $pelanggan = Pelanggan::with('penjualans')->find($id);
        }

        if (!$pelanggan) {
            return redirect()->route('admin.manajemen.manajemen_pelanggan')
                ->with('error', 'Pelanggan tidak ditemukan.');
        }

        $penjualans = collect($pelanggan->penjualans ?? []);
        $transaksis = collect(optional($user)->transaksis ?? []);

        $jumlahTransaksi = ($pelanggan->jumlah_transaksi_manual ?? 0)
            + $penjualans->count()
            + $transaksis->count();

        return view('admin.manajemen_pelanggan.edit_manajemen_pelanggan', compact(
            'pelanggan',
            'user',
            'jumlahTransaksi'
        ));
    }

    /**
     * Update data pelanggan.
     */
    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::with(['user', 'penjualans'])->findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('pelanggans')->ignore($pelanggan->id)],
            'alamat' => 'nullable|string|max:500',
            'no_hp' => 'nullable|string|max:20',
            'jumlah_transaksi' => 'nullable|numeric|min:0',
            'metode_pembayaran' => 'nullable|in:transfer,cod,qris',
        ]);

        // Update data pelanggan
        $pelanggan->update([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'alamat' => $validated['alamat'] ?? $pelanggan->alamat,
            'no_hp' => $validated['no_hp'] ?? $pelanggan->no_hp,
            // 'jumlah_transaksi_manual' => $validated['jumlah_transaksi'] ?? $pelanggan->jumlah_transaksi_manual,
            'jumlah_transaksi_manual' => $validated['jumlah_transaksi_manual'] ?? $pelanggan->jumlah_transaksi_manual,
        ]);

        // Update User jika ada
        if ($pelanggan->user) {
            $pelanggan->user->update([
                'username' => $validated['nama'],
                'email' => $validated['email'],
            ]);
        }

        // Update penjualan terakhir untuk metode pembayaran
        if (!empty($validated['metode_pembayaran']) && $pelanggan->penjualans->count()) {
            $penjualanTerakhir = $pelanggan->penjualans->sortByDesc('tanggal')->first();
            $penjualanTerakhir->update([
                'metode_pembayaran' => $validated['metode_pembayaran']
            ]);
        }

        return redirect()->route('admin.manajemen.manajemen_pelanggan')
            ->with('success', 'Data pelanggan dan metode pembayaran berhasil diperbarui.');
    }


    /**
     * Hapus pelanggan.
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->pelanggan()->delete();
            $user->delete();
        } else {
            Pelanggan::find($id)?->delete();
        }

        return redirect()->route('admin.manajemen.manajemen_pelanggan')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }
}
