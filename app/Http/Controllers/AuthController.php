<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->intended('/admin/dashboard')->with('success', 'Selamat datang, Admin!');
            }

            // default semua user adalah 'user'
            return redirect()->intended('/user/produk')->with('success', 'Selamat datang, ' . $user->username . '!');
        }

        return back()->withErrors(['username' => 'Username atau password salah.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Berhasil logout!');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email'    => 'required|email|unique:users',
            'no_hp'    => 'required|string|max:20',
            'password' => 'required|confirmed',
            'profile_image' => 'nullable|image',
            'role'     => 'required|in:user,admin', // ✅ tambahkan validasi role
        ]);

        $imagePath = null;
        if ($request->hasFile('profile_image')) {
            $folder = $validated['role'] === 'admin' ? 'profile_images/admin' : 'profile_images/user';
            $imagePath = $request->file('profile_image')->store($folder, 'public');
        }

        $user = User::create([
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'no_hp'    => $validated['no_hp'],
            'password' => bcrypt($validated['password']),
            'role'     => $validated['role'], // ✅ ambil dari form
            'img'      => $imagePath,
        ]);

        Pelanggan::create([
            'user_id' => $user->id,
            'nama'    => $user->username,
            'email'   => $user->email,
            'no_hp'   => $user->no_hp,
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil. Silakan login.');
    }


    // metode admin/manajemen pengguna tetap bisa digunakan seperti sebelumnya
}
