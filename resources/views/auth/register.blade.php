<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register - Army Collection</title>

    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4/animate.min.css" />

    <style>
        body { font-family: 'Inter', sans-serif; }
        .glow-focus:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(99,102,241,.3);
            border-color: #6366f1;
        }
    </style>
</head>

<body class="bg-gray-100">

<section class="flex flex-col md:flex-row min-h-screen">

    <!-- IMAGE -->
    <div class="w-full md:w-1/2 h-64 md:h-auto bg-cover bg-center relative"
         style="background-image: url('/img/pexels-chaikong2511-104764.jpg');">
        <div class="absolute inset-0 bg-black/70 flex flex-col items-center justify-center text-center px-6">
            <h1 class="text-white text-4xl font-extrabold">Army Collection</h1>
            <p class="text-gray-200 mt-2">Buat akun & mulai belanja</p>
        </div>
    </div>

    <!-- FORM -->
    <div class="w-full md:w-1/2 flex items-center justify-center px-6 bg-white">
        <div class="w-full max-w-md space-y-5">

            <h2 class="text-2xl font-bold text-gray-800 text-center">Registrasi</h2>

            <form method="POST"
                  action="{{ route('register') }}"
                  enctype="multipart/form-data"
                  class="space-y-4">

                @csrf

                <input type="text" name="username" placeholder="Username" required
                    class="w-full px-4 py-2 border rounded-lg bg-gray-100 glow-focus">

                <input type="email" name="email" placeholder="Email" required
                    class="w-full px-4 py-2 border rounded-lg bg-gray-100 glow-focus">

                <input type="text" name="no_hp" placeholder="No HP" required
                    class="w-full px-4 py-2 border rounded-lg bg-gray-100 glow-focus">

                <input type="password" name="password" placeholder="Password" required
                    class="w-full px-4 py-2 border rounded-lg bg-gray-100 glow-focus">

                <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required
                    class="w-full px-4 py-2 border rounded-lg bg-gray-100 glow-focus">

                <!-- FOTO PROFIL -->
                <div>
                    <label class="text-sm text-gray-600">Foto Profil (opsional)</label>
                    <input type="file" name="profile_image" accept="image/*"
                        class="w-full text-sm mt-1">
                </div>

                <!-- ALAMAT -->
                <div>
                    <label class="text-sm text-gray-600">Alamat</label>
                    <input type="text" id="alamat" name="alamat" required
                        class="w-full px-4 py-2 border rounded-lg bg-gray-100 glow-focus">

                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">

                    <button type="button" id="detectLocation"
                        class="mt-2 text-sm bg-green-500 text-white px-3 py-1 rounded">
                        📍 Lokasi Saat Ini
                    </button>

                    <div id="suggestions" class="bg-white border rounded mt-1"></div>
                </div>

                <button class="w-full bg-indigo-600 text-white py-2 rounded-lg">
                    Daftar
                </button>
            </form>

            <p class="text-center text-sm">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-indigo-600">Login</a>
            </p>

        </div>
    </div>
</section>

<script>
const alamatInput = document.getElementById('alamat');
const suggestions = document.getElementById('suggestions');

// AUTOCOMPLETE
alamatInput.addEventListener('input', function () {
    if (this.value.length < 3) return suggestions.innerHTML = '';

    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${this.value}`)
        .then(r => r.json())
        .then(data => {
            suggestions.innerHTML = '';
            data.forEach(p => {
                const d = document.createElement('div');
                d.textContent = p.display_name;
                d.className = 'px-3 py-2 hover:bg-gray-200 cursor-pointer';
                d.onclick = () => {
                    alamatInput.value = p.display_name;
                    latitude.value = p.lat;
                    longitude.value = p.lon;
                    suggestions.innerHTML = '';
                };
                suggestions.appendChild(d);
            });
        });
});

// GPS
document.getElementById('detectLocation').onclick = () => {
    navigator.geolocation.getCurrentPosition(pos => {
        latitude.value = pos.coords.latitude;
        longitude.value = pos.coords.longitude;

        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude.value}&lon=${longitude.value}`)
            .then(r => r.json())
            .then(d => alamatInput.value = d.display_name || '');
    });
};
</script>

</body>
</html>
