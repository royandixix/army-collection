<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Army Collection')</title>

    {{-- Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

    {{-- Tailwind (jika dipakai) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Floating toggle button */
        .toggle-sidebar-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1050; /* Lebih tinggi dari konten lain */
            width: 60px;
            height: 60px;
            border-radius: 50%;
            padding: 0;
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            background-color: #3b82f6; /* biru cerah */
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.6);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .toggle-sidebar-btn:hover {
            background-color: #2563eb; /* biru lebih gelap */
            transform: scale(1.1);
            cursor: pointer;
        }

        /* Hanya tampil di mobile (max-width 767.98px) */
        @media (min-width: 768px) {
            .toggle-sidebar-btn {
                display: none;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="bg-light">

    {{-- Navbar --}}
    @include('user.layouts.navbar')

    <div class="d-flex" style="min-height: 100vh; overflow: hidden;">
        {{-- Sidebar --}}
        @include('user.layouts.sidebar')

        {{-- Main Content --}}
        <main class="flex-grow-1 p-4 content-area d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-semibold text-primary">@yield('title')</h4>
            </div>

            @yield('content')

            {{-- Kamu bisa hapus tombol toggle di bawah main karena sudah floating --}}
            {{-- <div class="mt-auto d-md-none text-center mb-3">
                <button class="btn btn-outline-success toggle-sidebar-btn" onclick="toggleSidebar()" aria-label="Toggle Sidebar">
                    <i class="bi bi-list me-2"></i> Menu
                </button>
            </div> --}}
        </main>
    </div>

    {{-- Floating Toggle Button --}}
    <button class="toggle-sidebar-btn d-md-none" onclick="toggleSidebar()" aria-label="Toggle Sidebar">
        <i class="bi bi-list"></i>
    </button>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebarMenu').classList.toggle('active');
        }
    </script>
    @stack('scripts')
</body>
</html>
