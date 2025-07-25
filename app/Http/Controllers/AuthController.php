<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
            'username'       => 'required|string|max:255|unique:users',
            'email'          => 'required|email|unique:users',
            'no_hp'          => 'required|string|max:20',
            'password'       => 'required|confirmed|min:6',
            'role'           => 'required|string|in:admin,user',
            'profile_image'  => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ], [
            'username.required'      => 'Nama pengguna wajib diisi.',
            'username.unique'        => 'Username sudah digunakan.',
            'email.required'         => 'Email wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
            'email.unique'           => 'Email sudah digunakan.',
            'no_hp.required'         => 'Nomor HP wajib diisi.',
            'password.required'      => 'Password wajib diisi.',
            'password.confirmed'     => 'Konfirmasi password tidak cocok.',
            'password.min'           => 'Password minimal 6 karakter.',
            'role.required'          => 'Peran wajib dipilih.',
            'role.in'                => 'Peran harus berupa admin atau user.',
            'profile_image.image'    => 'File harus berupa gambar.',
            'profile_image.mimes'    => 'Gambar harus berformat jpg, jpeg, atau png.',
            'profile_image.max'      => 'Ukuran gambar maksimal 5MB.',
        ]);

        // Upload image jika ada
        $imagePath = null;
        if ($request->hasFile('profile_image')) {
            $folder = 'profile_images/' . $validated['role'];
            $imagePath = $request->file('profile_image')->store($folder, 'public');
        }

        // Simpan user
        $user = User::create([
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'no_hp'    => $validated['no_hp'],
            'password' => bcrypt($validated['password']),
            'role'     => $validated['role'],
            'img'      => $imagePath,
        ]);

        // Jika user adalah pelanggan, simpan ke tabel pelanggans
        if ($user->role === 'user') {
            Pelanggan::create([
                'user_id' => $user->id,
                'nama'    => $user->username,
                'email'   => $user->email,
                'no_hp'   => $user->no_hp,
                'alamat'  => null,
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
            'role'     => 'nullable|string|in:admin,user',
            'status'   => 'required|in:active,inactive',
            'password' => 'required',
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

        // Buat data pelanggan jika role adalah user
        if ($user->role === 'user') {
            Pelanggan::create([
                'user_id' => $user->id,
                'nama'    => $user->username,
                'email'   => $user->email,
                'no_hp'   => $user->no_hp,
                'alamat'  => null,
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

        // Hapus pelanggan jika role = user
        if ($user->role === 'user') {
            $user->pelanggan()->delete();
        }

        $user->delete();

        return redirect()->route('admin.manajemen.manajemen_pengguna')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
