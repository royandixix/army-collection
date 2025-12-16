<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pelanggan;
use App\Models\UserAlamat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // =========================
    // TAMPILAN LOGIN
    // =========================
    public function showLogin()
    {
        return view('auth.login');
    }

    // =========================
    // PROSES LOGIN
    // =========================
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('username', 'password'))) {
            $request->session()->regenerate();

            if (auth()->user()->role === 'admin') {
                return redirect('/admin/dashboard')->with('success', 'Selamat datang, Admin!');
            }

            return redirect('/user/produk')->with('success', 'Selamat datang, ' . auth()->user()->username . '!');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }

    // =========================
    // LOGOUT
    // =========================
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Berhasil logout.');
    }

    // =========================
    // TAMPILAN REGISTER
    // =========================
    public function showRegister()
    {
        return view('auth.register');
    }

    // =========================
    // PROSES REGISTER
    // =========================
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|unique:users',
            'email'    => 'required|email|unique:users',
            'no_hp'    => 'required',
            'alamat'   => 'required',
            'latitude' => 'nullable',
            'longitude'=> 'nullable',
            'password' => 'required',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        $imagePath = $request->file('profile_image')
            ? $request->file('profile_image')->store('profile_images/user', 'public')
            : null;

        // Buat user
        $user = User::create([
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'no_hp'    => $validated['no_hp'],
            'password' => bcrypt($validated['password']),
            'role'     => 'user',
            'img'      => $imagePath,
        ]);

        // Alamat default
        UserAlamat::create([
            'user_id'    => $user->id,
            'label'      => 'Rumah',
            'alamat'     => $validated['alamat'],
            'latitude'   => $validated['latitude'] ?? null,
            'longitude'  => $validated['longitude'] ?? null,
            'is_default' => true,
        ]);

        // Data pelanggan
        Pelanggan::create([
            'user_id'     => $user->id,
            'nama'        => $user->username,
            'email'       => $user->email,
            'no_hp'       => $user->no_hp,
            'alamat'      => $validated['alamat'],
            'foto_profil' => $imagePath,
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil. Silakan login.');
    }

    // =========================
    // TAMPILAN FORM MANUAL RESET PASSWORD
    // =========================
    public function showManualResetForm()
    {
        return view('auth.manual-reset-password');
    }

    // =========================
    // PROSES RESET PASSWORD MANUAL
    // =========================
    public function manualReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed', // Hapus min:6
        ]);

        $user = User::where('email', $request->email)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->route('login')->with('success', 'Password berhasil diubah secara manual. Silakan login.');
    }
}
