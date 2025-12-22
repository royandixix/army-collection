<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
   
    public function index()
    {
        $user = Auth::user();
        return view('user.profil.profil', compact('user'));
    }
    public function edit()
    {
        $user = Auth::user();
        return view('user.profil.edit_profil', compact('user'));
    }
    public function update(Request $request)
    {
        $user = Auth::user();

        // VALIDASI
        $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'photo'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // TAMBAHAN ALAMAT
            'alamat'    => 'required|string|min:10',
            'latitude'  => 'nullable',
            'longitude' => 'nullable',
        ]);

        if ($request->hasFile('photo')) {

            // hapus foto lama
            if ($user->img && Storage::disk('public')->exists($user->img)) {
                Storage::disk('public')->delete($user->img);
            }

            // simpan foto baru
            $photoPath = $request->file('photo')->store('profile', 'public');
            $user->img = $photoPath;
        }
        
        $user->username  = $request->name;
        $user->email     = $request->email;

        // SIMPAN ALAMAT & KOORDINAT
        $user->alamat    = $request->alamat;
        $user->latitude  = $request->latitude;
        $user->longitude = $request->longitude;

        $user->save();

        return redirect()
            ->route('user.profil')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
