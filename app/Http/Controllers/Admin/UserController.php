<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Tampilkan daftar semua pengguna.
     */
    public function index()
    {
        $users = User::with('pelanggan')
            ->where('role', 'user') // hanya tampilkan user
            ->get();

        return view('admin.manajemen_pengguna.manajemen_pengguna', compact('users'));
    }

    /**
     * Tampilkan form edit pengguna berdasarkan ID.
     */
    public function edit($id)
    {
        $user = User::with('pelanggan')->findOrFail($id);

        return view('admin.manajemen_pengguna.edit_manajemen_pengguna', compact('user'));
    }

    /**
     * Update data pengguna berdasarkan ID.
     */
    public function update(Request $request, $id)
    {
        $user = User::with('pelanggan')->findOrFail($id);

        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email'    => 'required|email',
            'no_hp'    => 'nullable|string|max:20',
            'role'     => 'nullable|string',
            'status'   => 'required|in:active,inactive',
            'img'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048|dimensions:min_width=100,min_height=100,max_width=3000,max_height=3000',
        ]);

        $user->username = $validated['username'];
        $user->email    = $validated['email'];
        $user->role     = $validated['role'] ?? null;
        $user->status   = $validated['status'];

        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/profile_images/admin', $filename);
            $user->img = $filename;
        } elseif (!$user->img) {
            $user->img = 'default.jpg';
        }

        $user->save();

        if ($user->pelanggan) {
            $user->pelanggan->no_hp = $validated['no_hp'] ?? null;
            $user->pelanggan->save();
        } else {
            $user->pelanggan()->create([
                'no_hp'  => $validated['no_hp'] ?? null,
                'nama'   => $user->username,
                'email'  => $user->email,
            ]);
        }

        return redirect()->route('admin.manajemen.manajemen_pengguna')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Hapus pengguna berdasarkan ID.
     */
    public function destroy($id)
    {
        $user = User::with('pelanggan')->findOrFail($id);

        // Cegah user menghapus dirinya sendiri
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'Kamu tidak bisa menghapus akunmu sendiri.');
        }

        // Hapus relasi pelanggan
        if ($user->pelanggan) {
            $user->pelanggan->delete();
        }

        // Hapus gambar profil jika bukan default
        if ($user->img && $user->img !== 'default.jpg') {
            Storage::delete('public/profile_images/admin/' . $user->img);
        }

        // Hapus user
        $user->delete();

        return redirect()->route('admin.manajemen.manajemen_pengguna')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
