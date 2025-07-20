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
                   class="nav-link d-flex align-items-center gap-3 sidebar-link {{ $active }}">
                    <div class="icon-circle {{ $active ? 'active-icon' : '' }}">
                        <i class="bi {{ $item['icon'] }}"></i>
                    </div>
                    <span class="menu-label">{{ $item['name'] }}</span>
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
        background: linear-gradient(135deg, #f8f9fa, #ffffff);
        color: #333;
        padding: 1rem 0.75rem;
        z-index: 1030;
        overflow-y: auto;
        border-right: 1px solid #e9ecef;
        box-shadow: 3px 0 8px rgba(0, 0, 0, 0.05);
    }

    .nav-item + .nav-item {
        border-top: 1px solid #f1f1f1;
    }

    .sidebar-link {
        color: #495057;
        font-weight: 500;
        border-radius: 0.65rem;
        padding: 0.6rem 0.9rem;
        transition: all 0.25s ease-in-out;
        display: flex;
        align-items: center;
        position: relative;
    }

    .sidebar-link:hover {
        background-color: #e9f2ff;
        color: #0d6efd;
        transform: translateX(5px);
        box-shadow: 0 2px 6px rgba(0, 123, 255, 0.1);
    }

    .sidebar-link.active {
        background-color: #dbeafe;
        color: #0d6efd;
        font-weight: 600;
        box-shadow: inset 4px 0 0 #0d6efd;
    }

    .icon-circle {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background-color: #e2e6ea;
        color: #495057;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s ease, color 0.3s ease;
        font-size: 1.2rem;
    }

    .sidebar-link:hover .icon-circle {
        background-color: #cfe2ff;
        color: #0d6efd;
    }

    .sidebar-link.active .icon-circle,
    .icon-circle.active-icon {
        background-color: #0d6efd;
        color: white;
    }

    .menu-label {
        font-size: 15px;
        transition: color 0.3s ease;
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
