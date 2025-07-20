<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfilController extends Controller
{
    // 🧍 Menampilkan halaman profil user
    public function index()
    {
        $user = Auth::user();
        return view('user.profil.profil', compact('user'));
    }

    // ✏️ Form edit profil
    public function edit()
    {
        $user = Auth::user();
        return view('user.profil.edit_profil', compact('user'));
    }

    // 💾 Simpan perubahan profil
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi data input
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Upload foto jika ada
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->img && Storage::disk('public')->exists($user->img)) {
                Storage::disk('public')->delete($user->img);
            }

            $photoPath = $request->file('photo')->store('profile', 'public');
            $user->img = $photoPath;
        }

        // Simpan perubahan
        $user->username = $request->name; // DB kamu pakai 'username'
        $user->email = $request->email;
        $user->save(); // ✅ method ini valid karena $user instance dari model User

        return redirect()->route('user.profil')->with('success', 'Profil berhasil diperbarui.');
    }
}
