<!-- Sidebar -->
<div class="cx-sidebar-overlay">
    <div class="cx-sidebar">
        <div class="cx-sidebar-head text-center py-4">
            @auth
                @if (Auth::user()->img)
                    <img src="{{ asset('storage/' . Auth::user()->img) }}" alt="Profile" width="40" height="40"
                        class="rounded-circle">
                @else
                    <img src="{{ asset('assets/img/default-profile.png') }}" alt="Default Profile" width="40"
                        height="40" class="rounded-circle">
                @endif
            @endauth
        </div>

        <div class="cx-sidebar-body">
            <ul class="cx-sb-list">
                <li class="cx-sb-item sb-drop-item">
                    <a href="{{ route('admin.dashboard') }}" class="cx-drop-toggle">
                        <i class="ri-home-3-line"></i>
                        <span class="condense">Dashboard <i class="drop-arrow ri-arrow-down-s-line"></i></span>
                    </a>
                </li>

                <li class="cx-sb-title condense"><span>Data</span></li>

                {{-- <li class="cx-sb-item sb-drop-item">
                    <a href="{{ route('admin.manajemen.manajemen_pengguna') }}" class="cx-drop-toggle">
                        <i class="ri-todo-line"></i>
                        <span class="condense">Manajemen Pengguna</span>
                    </a>
                </li> --}}

                <li class="cx-sb-item sb-drop-item">
                    <a href="{{ route('admin.manajemen.manajemen_produk') }}" class="cx-drop-toggle">
                        <i class="ri-todo-line"></i>
                        <span class="condense">Manajemen Produk</span>
                    </a>
                </li>

                <li class="cx-sb-item sb-drop-item">
                    <a href="{{ route('admin.manajemen.manajemen_penjualan') }}" class="cx-drop-toggle">
                        <i class="ri-message-3-line"></i>
                        <span class="condense">Manajemen Penjualan</span>
                    </a>
                </li>

                <li class="cx-sb-item sb-drop-item">
                    <a href="{{ route('admin.manajemen.manajemen_pelanggan') }}" class="cx-drop-toggle">
                        <i class="ri-calendar-2-line"></i>
                        <span class="condense">Manajemen Pelanggan</span>
                    </a>
                </li>

                <!-- Grup Laporan -->
                <li class="cx-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="cx-drop-toggle">
                        <i class="ri-file-chart-line"></i>
                        <span class="condense">Laporan <i class="drop-arrow ri-arrow-down-s-line"></i></span>
                    </a>
                    <ul class="cx-submenu">
                        <li>
                            <a href="{{ route('admin.laporan.faktur_laporan') }}">
                                <i class="ri-kanban-view"></i> Faktur & Laporan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.rekap.index') }}">
                                <i class="ri-bar-chart-2-line"></i> Rekap Data
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.laporan.produk') }}">
                                <i class="ri-archive-line"></i> Laporan Produk
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.laporan.penjualan') }}">
                                <i class="ri-shopping-cart-line"></i> Laporan Penjualan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.laporan.pelanggan') }}">
                                <i class="ri-user-3-line"></i> Laporan Pelanggan
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
        <div class="cx-bar-title">
            <h6>Notifications <span class="label">12</span></h6>
            <a href="javascript:void(0)" class="close-notify"><i class="ri-close-line"></i></a>
        </div>

        <div class="cx-bar-content">
            <!-- Tabs -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="alert-tab" data-bs-toggle="tab" data-bs-target="#alert"
                        type="button" role="tab">Alert</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="messages-tab" data-bs-toggle="tab" data-bs-target="#messages"
                        type="button" role="tab">Messages</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="log-tab" data-bs-toggle="tab" data-bs-target="#log" type="button"
                        role="tab">Log</button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="myTabContent">
                <!-- Alerts -->
                <div class="tab-pane fade show active" id="alert" role="tabpanel">
                    <div class="cx-alert-list">
                        <ul>
                            <li>
                                <div class="icon cx-alert"><i class="ri-alarm-warning-line"></i></div>
                                <div class="detail">
                                    <div class="title">Your final report is overdue</div>
                                    <p class="time">Just now</p>
                                    <p class="message">Please submit your quarterly report before - June 15.</p>
                                </div>
                            </li>
                            <li class="check"><a class="cx-primary-btn" href="#">View all</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Messages -->
                <div class="tab-pane fade" id="messages" role="tabpanel">
                    <div class="cx-message-list">
                        <ul>
                            <li>
                                <a href="#" class="reply">Reply</a>
                                <div class="user">
                                    <img src="assets/img/user/9.jpg" alt="user">
                                    <span class="label online"></span>
                                </div>
                                <div class="detail">
                                    <a href="#" class="name">Nama Pengguna</a>
                                    <p class="time">5:30AM, Today</p>
                                    <p class="message">Hello, I am sending some file. Please use this in landing page.
                                    </p>
                                    <span class="download-files">
                                        <span class="download">
                                            <img src="assets/img/other/1.jpg" alt="image">
                                            <a href="javascript:void(0)"><i class="ri-download-2-line"></i></a>
                                        </span>
                                    </span>
                                </div>
                            </li>
                            <li class="check"><a class="cx-primary-btn" href="#">View all</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Log -->
                <div class="tab-pane fade" id="log" role="tabpanel">
                    <div class="cx-activity-list activity-list">
                        <ul>
                            <li>
                                <span class="date-time">8 Thu <span class="time">11:30 AM - 05:10 PM</span></span>
                                <p class="title">Project Submitted from Smith</p>
                                <p class="detail">Lorem Ipsum is simply dummy text of the printing.</p>
                                <span class="download-files">
                                    <span class="download">
                                        <img src="assets/img/other/1.jpg" alt="image">
                                        <a href="javascript:void(0)"><i class="ri-download-2-line"></i></a>
                                    </span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
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
</script>
