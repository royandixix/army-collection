<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register - Army Collection</title>

  @vite('resources/css/app.css')
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet" />

  <style>
    body {
      font-family: 'Inter', sans-serif;
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .fade-in {
      animation: fadeInUp 0.6s ease-out both;
    }

    .btn-animate:active {
      transform: scale(0.96);
    }

    .glow-focus:focus {
      outline: none;
      box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.3);
      border-color: #6366f1;
      transition: box-shadow 0.3s ease, border 0.3s ease;
    }

    .btn-loading::after {
      content: '';
      border: 2px solid #fff;
      border-top-color: transparent;
      border-radius: 50%;
      width: 16px;
      height: 16px;
      animation: spin 0.6s linear infinite;
      display: inline-block;
      margin-left: 8px;
      vertical-align: middle;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }
  </style>
</head>

<body class="bg-gray-100">

  <section class="flex flex-col md:flex-row min-h-screen">

    <!-- Gambar (atas untuk mobile, kiri untuk desktop) -->
    <div class="w-full md:w-1/2 h-64 md:h-auto bg-cover bg-center relative"
         style="background-image: url('/img/Blog-post.jpg');">
      <div class="absolute inset-0 bg-gradient-to-br from-black/70 via-gray-900/70 to-black/60
                  flex flex-col items-center justify-center text-center px-6 py-10 md:py-16 space-y-4">
        <h1 class="text-white text-3xl md:text-5xl font-extrabold tracking-wide drop-shadow-md fade-in">
          Army Collection
        </h1>
        <p class="text-gray-200 text-sm md:text-lg max-w-md leading-relaxed fade-in">
          Buat akunmu dan mulai jelajahi koleksi army terbaik bersama <span class="text-green-400 font-semibold">Army Collection</span>.
        </p>
      </div>
    </div>

    <!-- Form Registrasi -->
    <div class="w-full md:w-1/2 flex items-center justify-center px-4 sm:px-8 py-10 bg-white">
      <div class="w-full max-w-md space-y-6 fade-in">

        <div class="text-center">
          <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-1">Buat Akun Baru</h2>
          <p class="text-sm text-gray-500">Lengkapi data di bawah ini untuk mendaftar</p>
        </div>

        <!-- Alert error -->
        @if ($errors->any())
          <ul class="text-sm text-red-600 list-disc pl-5">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        @endif

        <!-- Form -->
        <form id="registerForm" method="POST" action="{{ route('register') }}" class="space-y-4">
          @csrf

          <div>
            <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" required
              class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 glow-focus" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
              class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 glow-focus" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" required minlength="6"
              class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 glow-focus" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required minlength="6"
              class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 glow-focus" />
          </div>

          <button type="submit"
            class="btn-animate w-full bg-indigo-600 hover:bg-indigo-500 text-white font-semibold py-2.5 rounded-lg transition">
            Daftar
          </button>
        </form>

        <p class="text-center text-sm text-gray-600">
          Sudah punya akun?
          <a href="{{ route('login') }}" class="text-indigo-600 hover:underline font-medium">Login di sini</a>
        </p>
      </div>
    </div>
  </section>

  <!-- Script validasi -->
  <script>
    document.getElementById("registerForm").addEventListener("submit", function (e) {
      const form = this;
      const required = form.querySelectorAll("input[required]");
      let valid = true;

      required.forEach(input => {
        if (!input.value.trim()) valid = false;
      });

      if (!valid) {
        alert("Semua kolom harus diisi!");
        e.preventDefault();
        return;
      }

      const btn = form.querySelector("button[type='submit']");
      btn.disabled = true;
      btn.textContent = "Memproses...";
      btn.classList.add("opacity-70", "cursor-not-allowed", "btn-loading");
    });
  </script>
</body>
</html>
