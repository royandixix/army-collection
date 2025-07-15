<div class="d-flex">
    {{-- Sidebar --}}
    <nav id="sidebarMenu" class="sidebar shadow-sm">
        {{-- Header --}}
        <div class="sidebar-header text-center py-3 border-bottom">
            <a href="{{ url('/user/produk') }}" class="text-decoration-none fw-bold fs-5 text-success">
                <i class="bi bi-shop me-2"></i> Army Collection
            </a>
        </div>

        {{-- Navigasi Menu --}}
        <ul class="nav flex-column mt-4">
            @php
            $navItems = [
            ['name' => 'Beranda', 'url' => '/user/produk', 'icon' => 'bi-house'],
            ['name' => 'Keranjang', 'url' => '/user/keranjang', 'icon' => 'bi-cart'],
            ['name' => 'Checkout', 'url' => '/user/checkout', 'icon' => 'bi-bag-check'],
            ['name' => 'Riwayat', 'url' => '/user/riwayat', 'icon' => 'bi-clock-history'],
            ];
            @endphp

            @foreach ($navItems as $item)
            @php
            $active = request()->is(ltrim($item['url'], '/')) ? 'active' : '';
            @endphp
            <li class="nav-item mb-2">
                <a href="{{ url($item['url']) }}" class="nav-link d-flex align-items-center px-3 py-2 rounded-3 sidebar-link {{ $active ? 'bg-success text-white shadow-sm' : 'text-dark sidebar-hover' }}">
                    <i class="bi {{ $item['icon'] }} me-2"></i> {{ $item['name'] }}
                </a>
            </li>
            @endforeach
        </ul>

        {{-- User Dropdown --}}
        <div class="mt-auto pt-3 border-top user-dropdown">
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center gap-2 text-decoration-none text-dark dropdown-toggle fw-semibold" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle fs-5 text-success"></i>
                    <span>{{ Auth::user()->username }}</span>
                </a>

                <ul class="dropdown-menu shadow-sm border-0 rounded-3 mt-2" aria-labelledby="dropdownUser">
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2" href="{{ url('/user/profil') }}">
                            <i class="bi bi-gear"></i> Profil
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider my-1">
                    </li>
                    <li>
                        <a href="#" id="logoutBtn" class="dropdown-item d-flex align-items-center gap-2 text-danger fw-semibold">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                        <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
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

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function toggleSidebar() {
        document.getElementById('sidebarMenu').classList.toggle('active');
    }

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3200,
        timerProgressBar: true,
        background: '#ffffff',
        color: '#1e293b',
        iconColor: '#198754',
        backdrop: false,
        customClass: {
            popup: 'rounded-xl shadow-sm text-sm px-4 py-3 border border-gray-200 mt-3'
        },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    // Flash Messages
    @if(session('success'))
        Toast.fire({
            icon: 'success',
            title: @js(session('success')),
            iconColor: '#16a34a'
        });
    @endif

    @if(session('error'))
        Toast.fire({
            icon: 'error',
            title: @js(session('error')),
            iconColor: '#dc2626'
        });
    @endif

    @if($errors->any())
        Toast.fire({
            icon: 'warning',
            title: 'Periksa form kamu',
            text: @js($errors->first()),
            iconColor: '#f59e0b'
        });
    @endif

    // Logout Confirmation
    document.addEventListener('DOMContentLoaded', function () {
        const logoutBtn = document.getElementById('logoutBtn');
        const logoutForm = document.getElementById('logoutForm');

        if (logoutBtn) {
            logoutBtn.addEventListener('click', function (e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Yakin ingin logout?',
                    text: 'Kamu akan keluar dari akun ini.',
                    icon: 'warning',
                    iconColor: '#f97316',
                    showCancelButton: true,
                    confirmButtonText: 'Logout',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    background: '#ffffff',
                    backdrop: 'rgba(0,0,0,0.3)',
                    buttonsStyling: false,
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-xl p-4 shadow-sm',
                        title: 'fw-bold fs-5',
                        htmlContainer: 'text-muted mb-3',
                        confirmButton: 'btn btn-success px-4 ms-2 fw-semibold swal2-confirm-space',
                        cancelButton: 'btn btn-secondary px-4  fw-semibold swal2-cancel-space'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        logoutForm.submit();
                    }
                });
            });
        }
    });
</script>

{{-- Custom Styles --}}
<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #f9f9f9;
    }

    /* Sidebar */
    .sidebar {
        width: 240px;
        min-height: 100vh;
        background: #ffffff;
        padding: 1.2rem 1rem;
        display: flex;
        flex-direction: column;
        position: sticky;
        top: 0;
        border-right: 1px solid #e0e0e0;
        transition: all 0.3s ease-in-out;
    }

    .sidebar-link {
        font-size: 0.95rem;
        font-weight: 500;
        transition: background 0.2s ease-in-out;
    }

    .sidebar-link:hover {
        background-color: #e0f8ec;
        color: #198754;
    }

    .sidebar-link.active {
        background-color: #198754;
        color: #fff !important;
    }

    .sidebar-hover:hover {
        background-color: #f0fdf4;
        color: #198754;
    }

    /* Content Area */
    .content-area {
        background: #ffffff;
        border-radius: 0.75rem;
        box-shadow: 0 0 12px rgba(0, 0, 0, 0.05);
    }

    /* User Dropdown */
    .user-dropdown .dropdown-menu .dropdown-item:hover {
        background-color: #e9f5ee;
        color: #000;
    }

    .user-dropdown .dropdown-toggle:hover {
        color: #198754;
    }

    /* Responsive Sidebar */
    @media (max-width: 768px) {
        .sidebar {
            position: fixed;
            left: -260px;
            z-index: 1050;
            height: 100%;
        }

        .sidebar.active {
            left: 0;
        }
    }

    /* SweetAlert: Blur background */
    body.swal2-shown>*:not(.swal2-container) {
        filter: blur(4px);
        transition: filter 0.3s ease-in-out;
    }

    .swal2-popup {
        margin-top: 1.5rem !important;
        padding-top: 1.25rem !important;
    }

    .swal2-popup .swal2-confirm,
    .swal2-popup .swal2-cancel {
        border-radius: 0.5rem !important;
        font-weight: 600;
        padding: 0.5rem 1.25rem;
        box-shadow: none;
    }

    .swal2-popup .swal2-confirm {
        background-color: #198754 !important;
        color: #fff !important;
    }

    .swal2-popup .swal2-cancel {
        background-color: #6c757d !important;
        color: #fff !important;
    }

</style>
