<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    /**
     * Tampilkan semua pelanggan.
     */
    public function index()
    {
        $pelanggans = Pelanggan::all();
        return view('admin.manajemen_pelanggan.manajemen_pelanggan', compact('pelanggans'));
    }

    /**
     * Form tambah pelanggan.
     */
    public function create()
    {
        return view('admin.manajemen_pelanggan.create');
    }

    /**
     * Simpan data pelanggan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'   => 'required|string|max:255',
            'email'  => 'required|email|unique:pelanggans,email',
            'alamat' => 'nullable|string|max:500',
            'no_hp'  => 'nullable|string|max:20',
        ], [
            'nama.required'  => 'Nama pelanggan wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.unique'   => 'Email ini sudah digunakan.',
            'no_hp.max'      => 'Nomor HP tidak boleh lebih dari 20 karakter.',
        ]);

        Pelanggan::create($validated);

        return redirect()->route('admin.manajemen.manajemen_pelanggan')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    /**
     * Form edit pelanggan.
     */
    public function edit($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('admin.manajemen_pelanggan.edit', compact('pelanggan'));
    }

    /**
     * Simpan update data pelanggan.
     */
    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $validated = $request->validate([
            'nama'   => 'required|string|max:255',
            'email'  => 'required|email|unique:pelanggans,email,' . $id,
            'alamat' => 'nullable|string|max:500',
            'no_hp'  => 'nullable|string|max:20',
        ], [
            'nama.required'  => 'Nama pelanggan wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.unique'   => 'Email ini sudah digunakan.',
            'no_hp.max'      => 'Nomor HP tidak boleh lebih dari 20 karakter.',
        ]);

        $pelanggan->update($validated);

        return redirect()->route('admin.manajemen.manajemen_pelanggan')
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    /**
     * Hapus pelanggan.
     */
    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();

        return redirect()->route('admin.manajemen.manajemen_pelanggan')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }
}
