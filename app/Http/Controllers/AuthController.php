<?php

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
            'role.required'     => 'Role wajib dipilih.',
     
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->intended('/admin/dashboard')->with('success', 'Selamat datang, Admin!');
            } elseif ($user->role === 'user') {
                return redirect()->intended('/user/produk')->with('success', 'Selamat datang, ' . $user->username . '!');
            }

            Auth::logout();
            return redirect('/login')->withErrors(['username' => 'Role pengguna tidak dikenali.']);
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
            'password' => 'required', // ⛔ tidak perlu min atau confirmed
            'role'     => 'required|in:admin,user',
            'profile_image' => 'nullable|image',
        ]);

        $imagePath = null;
        if ($request->hasFile('profile_image')) {
            $folder = 'profile_images/' . $validated['role'];
            $imagePath = $request->file('profile_image')->store($folder, 'public');
        }

        $user = User::create([
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'no_hp'    => $validated['no_hp'],
            'password' => bcrypt($validated['password']),
            'role'     => $validated['role'],
            'img'      => $imagePath,
        ]);

        if ($user->role === 'user') {
            Pelanggan::create([
                'user_id' => $user->id,
                'nama'    => $user->username,
                'email'   => $user->email,
                'no_hp'   => $user->no_hp,
            ]);
        }

        return redirect()->route('login')->with('success', 'Registrasi berhasil. Silakan login.');
    }

    public function index()
    {
        $users = User::all();
        return view('admin.manajemen_pengguna.manajemen_pengguna', compact('users'));
    }

    public function create()
    {
        return view('admin.manajemen_pengguna.tambah_manajemen_pengguna');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'nullable|in:admin,user',
            'status'   => 'required|in:active,inactive',
            'password' => 'required', // ⛔ tanpa konfirmasi
            'no_hp'    => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'role'     => $validated['role'] ?? 'user',
            'status'   => $validated['status'],
            'password' => bcrypt($validated['password']),
            'no_hp'    => $validated['no_hp'] ?? null,
        ]);

        if ($user->role === 'user') {
            Pelanggan::create([
                'user_id' => $user->id,
                'nama'    => $user->username,
                'email'   => $user->email,
                'no_hp'   => $user->no_hp,
            ]);
        }

        return redirect()->route('admin.manajemen.manajemen_pengguna')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.manajemen_pengguna.edit_manajemen_pengguna', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $id,
            'role'     => 'nullable|string|in:admin,user',
            'status'   => 'required|in:active,inactive',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.edit', $user->id)
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'user') {
            $user->pelanggan()->delete();
        }

        $user->delete();

        return redirect()->route('admin.manajemen.manajemen_pengguna')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
