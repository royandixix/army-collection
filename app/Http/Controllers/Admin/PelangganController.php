<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\User;
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
        $pelanggans = User::with(['pelanggan', 'transaksis'])
            ->where('role', 'user')                  // ✅ hanya role user
            ->whereHas('pelanggan')                  // ✅ ada relasi pelanggan
            ->whereHas('transaksis')                 // ✅ hanya jika punya transaksi
            ->withCount('transaksis')                // ✅ tampilkan jumlah transaksi
            ->get();
    
        return view('admin.manajemen_pelanggan.manajemen_pelanggan', compact('pelanggans'));
    }
    


    /**
     * Tampilkan form tambah pelanggan.
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

        // Simpan ke tabel users
        $user = User::create([
            'username' => $validated['nama'],
            'email'    => $validated['email'],
            'password' => Hash::make('password123'), // Password default
            'role'     => 'pelanggan',
            'status'   => 'active',
        ]);

        // Simpan ke tabel pelanggans
        $user->pelanggan()->create([
            'nama'   => $validated['nama'],  // ⬅ WAJIB agar tidak error
            'email'  => $validated['email'],
            'alamat' => $validated['alamat'] ?? null,
            'no_hp'  => $validated['no_hp'] ?? null,
        ]);

        return redirect()->route('admin.manajemen.manajemen_pelanggan')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit pelanggan.
     */
    public function edit($id)
    {
        $pelanggan = Pelanggan::with('user')->findOrFail($id);
        return view('admin.manajemen_pelanggan.edit_manajemen_pelanggan', compact('pelanggan'));
    }

    /**
     * Update data pelanggan.
     */
    public function update(Request $request, $id)
{
    $pelanggan = Pelanggan::findOrFail($id);
    $user = $pelanggan->user;

    $validated = $request->validate([
        'nama'   => 'required|string|max:255',
        'email'  => ['required','email', Rule::unique('users','email')->ignore($user->id)],
        'alamat' => 'nullable|string|max:500',
        'no_hp'  => 'nullable|string|max:20',
    ]);

    $user->update([
        'username' => $validated['nama'],
        'email'    => $validated['email'],
    ]);

    $pelanggan->update($validated);

    return redirect()->back()->with('success', 'Profil pelanggan berhasil diperbarui.');
}

    /**
     * Hapus pelanggan.
     */
    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $user = $pelanggan->user;

        $pelanggan->delete();
        $user->delete();

        return redirect()->route('admin.manajemen.manajemen_pelanggan')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }
}