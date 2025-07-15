<div class="d-flex">

    {{-- Sidebar --}}
    <nav id="sidebarMenu" class="sidebar shadow-sm">
        <div class="sidebar-header text-center py-3">
            <a href="{{ url('/user/produk') }}" class="text-decoration-none fw-bold fs-5 text-dark">
                <i class="bi bi-shop me-1"></i> Army Collection
            </a>
        </div>

        {{-- Menu Navigasi --}}
        <ul class="nav flex-column mt-4">
            @php
                $navItems = [
                    ['name' => 'Beranda',   'url' => '/user/produk',    'icon' => 'bi-house'],
                    ['name' => 'Keranjang', 'url' => '/user/keranjang', 'icon' => 'bi-cart'],
                    ['name' => 'Checkout',  'url' => '/user/checkout',  'icon' => 'bi-bag-check'],
                    ['name' => 'Riwayat',   'url' => '/user/riwayat',   'icon' => 'bi-clock-history'],
                ];
            @endphp

            @foreach ($navItems as $item)
                <li class="nav-item">
                    <a href="{{ url($item['url']) }}"
                       class="nav-link px-3 py-2 rounded sidebar-link {{ request()->is(ltrim($item['url'], '/')) ? 'active' : 'text-dark' }}">
                        <i class="bi {{ $item['icon'] }} me-2"></i>{{ $item['name'] }}
                    </a>
                </li>
            @endforeach
        </ul>

        {{-- User Dropdown --}}
        <div class="mt-auto p-3 user-dropdown">
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center gap-2 text-decoration-none text-dark dropdown-toggle fw-semibold"
                   id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle fs-5"></i>
                    <span>{{ Auth::user()->username }}</span>
                </a>
                <ul class="dropdown-menu shadow-sm border-0 rounded-3 mt-2" aria-labelledby="dropdownUser">
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2" href="{{ url('/user/profil') }}">
                            <i class="bi bi-gear"></i> Profil
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <form id="logoutForm" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="button" onclick="confirmLogout()" class="dropdown-item d-flex align-items-center gap-2 text-danger fw-semibold">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Konten Utama --}}
    <main class="flex-grow-1 p-4 content-area">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-semibold text-success">@yield('title', 'Halaman')</h4>
            <button class="btn btn-outline-success d-md-none" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
        </div>

        @yield('content')
    </main>
</div>

{{-- Script --}}
<script>
    function toggleSidebar() {
        document.getElementById('sidebarMenu').classList.toggle('active');
    }

    function confirmLogout() {
        Swal.fire({
            title: 'Yakin ingin logout?',
            text: "Sesi kamu akan ditutup.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-3',
                confirmButton: 'btn btn-danger me-2',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logoutForm').submit();
            }
        });
    }
</script>

{{-- CSS --}}
<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #f9f9f9;
    }

    .sidebar {
        width: 220px;
        min-height: 100vh;
        background: #e6f2e6;
        padding: 1rem;
        display: flex;
        flex-direction: column;
        position: sticky;
        top: 0;
        transition: all 0.3s ease-in-out;
    }

    .sidebar-link {
        display: block;
        color: #333;
        font-size: 0.95rem;
        font-weight: 500;
        transition: background 0.2s ease-in-out;
    }

    .sidebar-link:hover {
        background-color: #d1e7dd;
        color: #000;
    }

    .sidebar-link.active {
        background-color: #198754;
        color: #fff !important;
    }

    .dropdown-menu {
        font-size: 0.9rem;
    }

    .content-area {
        background: #ffffff;
        border-radius: 0.5rem;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }

    @media (max-width: 768px) {
        .sidebar {
            position: fixed;
            left: -250px;
            top: 0;
            z-index: 1050;
            height: 100%;
            transition: left 0.3s ease-in-out;
        }

        .sidebar.active {
            left: 0;
        }
    }

    .user-dropdown .dropdown-menu {
        font-size: 0.95rem;
        background-color: #fff;
        transition: all 0.2s ease-in-out;
    }

    .user-dropdown .dropdown-menu .dropdown-item:hover {
        background-color: #e9f5ee;
        color: #000;
    }

    .user-dropdown .dropdown-toggle:hover {
        color: #198754;
    }
</style>

{{-- SweetAlert Toast --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: '#f9f9f9',
        color: '#1e293b',
        iconColor: '#10b981',
        customClass: {
            popup: 'rounded-xl shadow-md text-sm px-4 py-3 mt-4 border border-gray-200'
        },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    @if(session('success'))
        Toast.fire({ icon: 'success', title: @js(session('success')) });
    @endif

    @if(session('error'))
        Toast.fire({ icon: 'error', title: @js(session('error')) });
    @endif

    @if($errors->any())
        Toast.fire({
            icon: 'warning',
            title: 'Periksa form kamu',
            text: @js($errors->first())
        });
    @endif
</script>
@endpush
