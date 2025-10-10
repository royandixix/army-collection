<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class PelangganController extends Controller
{
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

        // Gabungkan keduanya (collection)
        $pelanggans = $userPelanggan->concat($manualPelanggan);

        // Kirim ke view
        return view('admin.manajemen_pelanggan.manajemen_pelanggan', compact('pelanggans'));
    }


    public function create()
    {
        return view('admin.manajemen_pelanggan.tambah_manajemen_pelanggan');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email',
            'alamat' => 'nullable|string|max:500',
            'no_hp'  => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'username' => $validated['nama'],
            'email'    => $validated['email'],
            'password' => Hash::make('password123'),
            'role'     => 'user',
            'status'   => 'active',
        ]);

        $user->pelanggan()->create([
            'nama'   => $validated['nama'],
            'email'  => $validated['email'],
            'alamat' => $validated['alamat'] ?? null,
            'no_hp'  => $validated['no_hp'] ?? null,
            'jumlah_transaksi_manual' => 0, // default
        ]);

        return redirect()->route('admin.manajemen.manajemen_pelanggan')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pelanggan = User::with(['pelanggan', 'transaksis'])->find($id);

        if (!$pelanggan) {
            $pelanggan = Pelanggan::with('penjualans')->findOrFail($id);
        }

        $transaksiTerakhir = null;
        if (!empty($pelanggan->transaksis) && $pelanggan->transaksis->isNotEmpty()) {
            $transaksiTerakhir = $pelanggan->transaksis->sortByDesc('created_at')->first();
        } elseif (!empty($pelanggan->penjualans) && $pelanggan->penjualans->isNotEmpty()) {
            $transaksiTerakhir = $pelanggan->penjualans->sortByDesc('tanggal')->first();
        }

        $jumlahTransaksi = $pelanggan->jumlah_transaksi_manual ?? collect([
            optional($pelanggan->transaksis)->count(),
            optional($pelanggan->penjualans)->count(),
        ])->filter()->sum();

        return view('admin.manajemen_pelanggan.edit_manajemen_pelanggan', compact(
            'pelanggan',
            'transaksiTerakhir',
            'jumlahTransaksi'
        ));
    }

    public function update(Request $request, $id)
    {
        $user = User::with('pelanggan', 'transaksis')->find($id);
        $pelanggan = null;

        if ($user) {
            $pelanggan = $user->pelanggan;
        } else {
            $pelanggan = Pelanggan::findOrFail($id);
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user?->id)],
            'alamat' => 'nullable|string|max:500',
            'no_hp' => 'nullable|string|max:20',
            'jumlah_transaksi' => 'nullable|numeric|min:0',
        ]);

        // Update data pelanggan/user
        if ($user) {
            $user->update([
                'username' => $validated['nama'],
                'email' => $validated['email'],
            ]);
            if ($pelanggan) {
                $pelanggan->update([
                    'nama' => $validated['nama'],
                    'email' => $validated['email'],
                    'alamat' => $validated['alamat'] ?? $pelanggan->alamat,
                    'no_hp' => $validated['no_hp'] ?? $pelanggan->no_hp,
                    'jumlah_transaksi_manual' => $validated['jumlah_transaksi'] ?? $pelanggan->jumlah_transaksi_manual,
                ]);
            }
        } else {
            $pelanggan->update([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'alamat' => $validated['alamat'] ?? $pelanggan->alamat,
                'no_hp' => $validated['no_hp'] ?? $pelanggan->no_hp,
                'jumlah_transaksi_manual' => $validated['jumlah_transaksi'] ?? $pelanggan->jumlah_transaksi_manual,
            ]);
        }

        return redirect()->route('admin.manajemen.manajemen_pelanggan')
            ->with('success', 'Data pelanggan berhasil diperbarui tanpa mengubah Penjualan.');
    }



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
