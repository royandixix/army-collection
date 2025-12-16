<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Army Collection</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white p-6 rounded shadow-md w-full max-w-md">
    <h2 class="text-xl font-bold mb-4 text-center">Lupa Password</h2>
    <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
        @csrf
        <input type="email" name="email" placeholder="Masukkan email" required
               class="w-full px-4 py-2 border rounded glow-focus">
        <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded">Kirim Link Reset</button>
    </form>
</div>

<script>
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Sukses!',
        text: '{{ session('success') }}',
    });
    @endif

    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: '{{ session('error') }}',
    });
    @endif
</script>
</body>
</html>
