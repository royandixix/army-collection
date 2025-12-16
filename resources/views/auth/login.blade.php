<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Army Collection</title>

  @vite('resources/css/app.css')
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4/animate.min.css" />

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
      box-shadow: 0 0 0  4px rgba(99, 102, 241, 0.3);
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
      margin-left: 0.5rem;
      vertical-align: middle;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }
  </style>
</head>

<body class="bg-gray-100">

  <section class="flex flex-col md:flex-row min-h-screen">
    <!-- Gambar -->
    <div class="w-full md:w-1/2 h-64 md:h-auto bg-cover bg-center relative" style="background-image: url('/img/pexels-chaikong2511-104764.jpg');">
      <div class="absolute inset-0 bg-gradient-to-br from-black/70 via-gray-900/70 to-black/60 flex flex-col items-center justify-center text-center px-6 py-10 md:py-16 space-y-4">
        <h1 class="text-white text-3xl md:text-5xl font-extrabold tracking-wide drop-shadow-md fade-in">Army Collection</h1>
        <p class="text-gray-200 text-sm md:text-lg max-w-md leading-relaxed fade-in">
          Selamat datang kembali di <span class="text-green-400 font-semibold">Army Collection</span>.
        </p>
      </div>
    </div>

    <!-- Form Login -->
    <div class="w-full md:w-1/2 flex items-center justify-center px-4 sm:px-8 py-10 bg-white">
      <div class="w-full max-w-md space-y-6 fade-in">
        <div class="text-center">
          <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-1">Login ke akunmu</h2>
          <p class="text-sm text-gray-500">Masukkan username dan password kamu</p>
        </div>

        <form id="loginForm" method="POST" action="{{ route('login.post') }}" class="space-y-4">
          @csrf

          <div>
            <label class="block text-sm font-medium text-gray-700">Username</label>
            <input type="text" name="username" value="{{ old('username') }}" required
              class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 glow-focus" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" required
              class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 glow-focus" />
          </div>

          <div class="flex justify-between text-sm">
            <label class="flex items-center gap-2 text-gray-600">
              <input type="checkbox" name="remember" class="rounded text-indigo-500" />
              Ingat saya
            </label>
           <a href="{{ route('password.manual') }}" class="text-indigo-600 hover:underline">Lupa password?</a>


          </div>

          <button type="submit"
            class="btn-animate w-full bg-indigo-600 hover:bg-indigo-500 text-white font-semibold py-2.5 rounded-lg transition">
            Login
          </button>
        </form>

        <p class="text-center text-sm text-gray-600">
          Belum punya akun?
          <a href="{{ route('register') }}" class="text-indigo-600 hover:underline font-medium">Daftar Sekarang</a>
        </p>
      </div>
    </div>
  </section>

  <!-- SweetAlert2 Toast Notification -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    const Toast = Swal.mixin({
      toast: true,
      position: 'top',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      background: '#fff',
      color: '#333',
      iconColor: '#4f46e5',
      customClass: {
        popup: 'rounded-xl shadow-md text-sm px-4 py-3 mt-4'
      },
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
    });

    @if (session('success'))
      Toast.fire({
        icon: 'success',
        title: {!! json_encode(session('success')) !!}
      });
    @endif

    @if (session('error'))
      Toast.fire({
        icon: 'error',
        title: {!! json_encode(session('error')) !!}
      });
    @endif

    @if ($errors->any())
      Toast.fire({
        icon: 'warning',
        title: 'Periksa kembali!',
        text: {!! json_encode($errors->first()) !!}
      });
    @endif
  </script>
</body>
</html>
