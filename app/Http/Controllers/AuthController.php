<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 🔐 Tampilkan form login
    public function showLogin()
    {
        return view('auth.login');
    }

    // 🔐 Proses login pakai username
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user(); // Ambil user yang berhasil login

            // Redirect berdasarkan role
            if ($user->role === 'admin') {
                return redirect()->intended('/admin/dashboard')->with('success', 'Selamat datang, Admin!');
            } elseif ($user->role === 'user') {
                return redirect()->intended('/user/produk')->with('success', 'Selamat datang, ' . $user->username . '!');
            }

            // Default jika role tidak dikenali
            Auth::logout();
            return redirect('/login')->withErrors([
                'username' => 'Role pengguna tidak dikenali.',
            ]);
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }


    // 🚪 Proses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // 📝 Tampilkan form register
    public function showRegister()
    {
        return view('auth.register');
    }

    // 📝 Proses register (dengan upload profile_image opsional)
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username'       => 'required|string|max:255|unique:users',
            'email'          => 'required|email|unique:users',
            'password'       => 'required|confirmed',
            'role'           => 'required|string|in:admin,user',
            'profile_image'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('profile_image')) {
            $role = $validated['role'];
            $folder = 'profile_images/' . $role;
            $imagePath = $request->file('profile_image')->store($folder, 'public');
        }

        User::create([
            'username'      => $validated['username'],
            'email'         => $validated['email'],
            'password'      => bcrypt($validated['password']),
            'role'          => $validated['role'],
            'img'           => $imagePath,
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil. Silakan login.');
    }

    // 🎯 Tampilkan semua pengguna (admin only)
    public function index()
    {
        $users = User::all();
        return view('admin.manajemen_pengguna.manajemen_pengguna', compact('users'));
    }

    // ➕ Form tambah pengguna
    public function create()
    {
        return view('admin.manajemen_pengguna.tambah_manajemen_pengguna');
    }

    // 💾 Simpan pengguna baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'nullable|string',
            'status'   => 'required|in:active,inactive',
            'password' => 'required',
        ]);

        User::create([
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'role'     => $validated['role'],
            'status'   => $validated['status'],
            'password' => bcrypt($validated['password']),
        ]);

        return redirect()->route('admin.manajemen.manajemen_pengguna')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    // ✏️ Edit pengguna
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.manajemen_pengguna.edit_manajemen_pengguna', compact('user'));
    }

    // 🔄 Update data pengguna
    public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $validated = $request->validate([
        'username' => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email,' . $id,
        'role'     => 'nullable|string',
        'status'   => 'required|in:active,inactive',
    ]);

    $user->update($validated);

    // Redirect ke halaman edit agar SweetAlert bisa muncul
    return redirect()->route('admin.users.edit', $user->id)
        ->with('success', 'Data pengguna berhasil diperbarui.');
}


    // 🗑️ Hapus pengguna
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.manajemen.manajemen_pengguna')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
