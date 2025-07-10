<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Tampilkan daftar semua pengguna.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::all()->map(function ($user) {
            return [
                'id'       => $user->id,
                'username' => $user->username,
                'img'      => $user->img ?? 'default.jpg',
                'date'     => $user->created_at->format('Y-m-d'),
                'team'     => $user->role ?? 'N/A',
                'status'   => $user->status ?? 'inactive',
            ];
        });

        return view('admin.manajemen_pengguna.manajemen_pengguna', compact('users'));
    }

    /**
     * Tampilkan form edit pengguna berdasarkan ID.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.manajemen_pengguna.edit_manajemen_pengguna', compact('user'));
    }

    /**
     * Update data pengguna berdasarkan ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email'    => 'required|email',
            'role'     => 'nullable|string',
            'status'   => 'required|in:active,inactive',
            'img'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // validasi file gambar
        ], [
            'username.required' => 'Nama pengguna wajib diisi.',
            'username.string'   => 'Nama pengguna harus berupa teks.',
            'username.max'      => 'Nama pengguna tidak boleh lebih dari 255 karakter.',
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'role.string'       => 'Peran harus berupa teks.',
            'status.required'   => 'Status pengguna wajib dipilih.',
            'status.in'         => 'Status harus bernilai active atau inactive.',
            'img.image'         => 'File harus berupa gambar.',
            'img.mimes'         => 'Format gambar harus jpg, jpeg, atau png.',
            'img.max'           => 'Ukuran gambar maksimal 2MB.',
        ]);

        // Update data user
        $user->update($validated);

        // Proses upload gambar jika ada
        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/profile_images/admin', $filename);
            $user->img = $filename;
            $user->save();
        }

        return redirect()->route('admin.manajemen.manajemen_pengguna')
                         ->with('success', 'Data pengguna berhasil diperbarui.');
    }
}
