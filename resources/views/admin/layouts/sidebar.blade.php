<!-- Sidebar -->
<div class="cx-sidebar-overlay">
    <div class="cx-sidebar">
        <div class="cx-sidebar-head text-center py-4">
            @auth
                @if (Auth::user()->img)
                    <img src="{{ asset('storage/' . Auth::user()->img) }}" 
                         alt="Profile" width="60" height="60" class="rounded-circle">
                @else
                    <img src="{{ asset('img/logo.jpg') }}" 
                         alt="Logo" width="60" height="60" class="rounded-circle">
                @endif
            @else
                <img src="{{ asset('img/logo.jpg') }}" 
                     alt="Logo" width="60" height="60" class="rounded-circle">
            @endauth
        </div>

        <div class="cx-sidebar-body">
            <ul class="cx-sb-list">
                <li class="cx-sb-item sb-drop-item">
                    <a href="{{ route('admin.dashboard') }}" class="cx-drop-toggle">
                        <i class="ri-home-3-line"></i>
                        <span class="condense">Dashboard</span>
                    </a>
                </li>

                <li class="cx-sb-title condense"><span>Data</span></li>

                <li class="cx-sb-item sb-drop-item">
                    <a href="{{ route('admin.manajemen.manajemen_produk') }}" class="cx-drop-toggle">
                        <i class="ri-todo-line"></i>
                        <span class="condense">Data Produk</span>
                    </a>
                </li>

                <li class="cx-sb-item sb-drop-item">
                    <a href="{{ route('admin.manajemen.manajemen_penjualan') }}" class="cx-drop-toggle">
                        <i class="ri-message-3-line"></i>
                        <span class="condense">Data Penjualan</span>
                    </a>
                </li>

                <li class="cx-sb-item sb-drop-item">
                    <a href="{{ route('admin.manajemen.manajemen_pelanggan') }}" class="cx-drop-toggle">
                        <i class="ri-calendar-2-line"></i>
                        <span class="condense">Data Pembelian</span>
                    </a>
                </li>

                <li class="cx-sb-item sb-drop-item">
    <a href="{{ route('admin.supplier.index') }}" class="cx-drop-toggle">
        <i class="ri-truck-line"></i>
        <span class="condense">Data Supplier</span>
    </a>
</li>


                <!-- Grup Laporan -->
                <li class="cx-sb-item sb-drop-item has-submenu">
                    <a href="javascript:void(0)" class="cx-drop-toggle">
                        <i class="ri-file-chart-line"></i>
                        <span class="condense">Data Laporan <i class="drop-arrow ri-arrow-down-s-line"></i></span>
                    </a>
                    <ul class="cx-submenu">
                        <li>
                            <a href="{{ route('admin.laporan.penjualan') }}">
                                <i class="ri-shopping-cart-line"></i> Laporan Penjualan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.laporan.pembelian') }}">
                                <i class="ri-wallet-3-line"></i> Laporan Pembelian
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.laporan.produk') }}">
                                <i class="ri-wallet-3-line"></i> Laporan Produk
                            </a>
                        </li>
                        <li>
    <a href="{{ route('admin.laporan.supplier') }}">
        <i class="ri-truck-line"></i> Laporan Supplier
    </a>
</li>

                    </ul>
                </li>
                <!-- End Grup Laporan -->
            </ul>
        </div>
    </div>

    <!-- Notify Sidebar -->
    <div class="cx-notify-bar-overlay"></div>
    <div class="cx-notify-bar">
        ...
    </div>
</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmLogout() {
        Swal.fire({
            title: 'Yakin ingin keluar?',
            text: 'Sesi kamu akan diakhiri.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="ri-logout-box-line mr-1"></i> Ya, Logout',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-xl shadow-md',
                title: 'text-lg font-bold text-gray-800',
                confirmButton: 'px-4 py-2 rounded-md text-white bg-blue-500',
                cancelButton: 'px-4 py-2 rounded-md bg-gray-200 text-gray-700',
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }

    // Script buat dropdown sidebar
    document.querySelectorAll('.has-submenu > .cx-drop-toggle').forEach(toggle => {
        toggle.addEventListener('click', function () {
            const parent = this.parentElement;
            parent.classList.toggle('open');
        });
    });
</script>

<style>
    /* Supaya submenu hidden default */
    .cx-submenu {
        display: none;
        padding-left: 1.5rem;
    }
    .has-submenu.open > .cx-submenu {
        display: block;
    }
    .drop-arrow {
        transition: transform 0.3s;
    }
    .has-submenu.open .drop-arrow {
        transform: rotate(180deg);
    }
</style>
