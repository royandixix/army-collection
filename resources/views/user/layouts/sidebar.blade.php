<nav id="sidebarMenu" class="sidebar shadow-sm mt-5">
    <ul class="nav flex-column mt-4">
        @php
            $navItems = [
                ['name' => 'Produk', 'url' => '/user/produk', 'icon' => 'bi-box'],
                ['name' => 'Keranjang', 'url' => '/user/keranjang', 'icon' => 'bi-cart'],
                ['name' => 'Checkout', 'url' => '/user/checkout', 'icon' => 'bi-bag-check'],
                ['name' => 'Riwayat', 'url' => '/user/riwayat', 'icon' => 'bi-clock-history'],
            ];
        @endphp

        @foreach ($navItems as $item)
            @php $active = request()->is(ltrim($item['url'], '/')) ? 'active' : ''; @endphp
            <li class="nav-item mb-2">
                <a href="{{ url($item['url']) }}"
                   class="nav-link d-flex align-items-center px-3 py-2 sidebar-link {{ $active }}">
                    <i class="bi {{ $item['icon'] }} me-2"></i> {{ $item['name'] }}
                </a>
            </li>
        @endforeach
    </ul>
</nav>
<style>
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 240px;
        height: 100vh;
        background: #ffffff; /* Putih bersih */
        color: #333;
        padding: 1rem 0.75rem;
        z-index: 1030;
        overflow-y: auto;
        box-shadow: 3px 0 8px rgba(0, 0, 0, 0.05);
        border-right: 1px solid #e9ecef;
    }

    .sidebar-link {
        color: #333;
        font-weight: 500;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
        padding: 0.5rem 1rem;
    }

    .sidebar-link:hover {
        background: #f8f9fa;
        color: #0d6efd;
    }

    .sidebar-link.active {
        background-color: #e9ecef;
        color: #0d6efd;
        font-weight: 600;
        box-shadow: inset 3px 0 0 #0d6efd;
    }

    .sidebar .nav-item + .nav-item {
        border-top: 1px solid #f1f1f1;
    }

    .content-area {
        margin-left: 240px;
        width: calc(100% - 240px);
    }

    @media (max-width: 768px) {
        .sidebar {
            left: -240px;
            transition: left 0.3s ease;
        }

        .sidebar.active {
            left: 0;
        }

        .content-area {
            margin-left: 0;
            width: 100%;
        }
    }
</style>
