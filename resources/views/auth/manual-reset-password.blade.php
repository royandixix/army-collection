<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ubah Password</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold mb-2 text-center">Ubah Password Anda</h1>
        <p class="text-gray-600 text-sm mb-6 text-center">
            Masukkan <strong>email yang sudah terdaftar</strong> dan password baru Anda. Gunakan kata sandi yang mudah diingat tapi aman.
        </p>

        @if(session('success'))
            <div class="bg-green-200 text-green-800 p-2 rounded mb-4 text-center">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="bg-red-200 text-red-800 p-2 rounded mb-4 text-center">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('password.manual.post') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block font-medium text-gray-700">Email</label>
                <input type="email" name="email" placeholder="Email terdaftar Anda" required 
                    class="mt-1 w-full border px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
                <p class="text-gray-500 text-xs mt-1">Gunakan email yang pernah digunakan saat login atau registrasi.</p>
            </div>

            <div>
                <label class="block font-medium text-gray-700">Password Baru</label>
                <input type="password" name="password" placeholder="Ketik password baru" required 
                    class="mt-1 w-full border px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
            </div>

            <div>
                <label class="block font-medium text-gray-700">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" placeholder="Ketik ulang password baru" required 
                    class="mt-1 w-full border px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
            </div>

            <button type="submit" 
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-500 transition">
                Perbarui Password
            </button>
        </form>

        <!-- Tombol Kembali ke Login -->
        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium">
                Kembali ke Halaman Login
            </a>
        </div>
    </div>
</body>
</html>
