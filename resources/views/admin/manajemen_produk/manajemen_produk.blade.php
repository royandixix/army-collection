@extends('admin.layouts.app')

@section('title', 'Manajemen Produk & Kartu Stok')

@section('content')
    <div class="cx-main-content">

        <!-- Page Title -->
        <div class="cx-page-title d-flex justify-content-between align-items-center flex-wrap mb-3">
            <h4>Manajemen Produk & Kartu Stok</h4>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3" id="tabMenu" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="produk-tab" data-bs-toggle="tab" data-bs-target="#produk" type="button"
                    role="tab">Daftar Produk</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="stok-tab" data-bs-toggle="tab" data-bs-target="#stok" type="button"
                    role="tab">Kartu Stok</button>
            </li>
        </ul>

        <div class="tab-content" id="tabContent">
            <!-- ================= Daftar Produk ================= -->
            <div class="tab-pane fade show active" id="produk" role="tabpanel">
                <!-- Filter & Search -->
                <div class="row mb-3 ms-3">
                    <div class="col-md-4">
                        <label for="kategoriFilter" class="form-label">Filter berdasarkan Kategori:</label>
                        <select id="kategoriFilter" class="form-select">
                            <option value="">-- Semua Kategori --</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->name }}">{{ $kategori->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label for="global-search" class="form-label">Pencarian Cepat:</label>
                        <input type="text" id="global-search" class="form-control form-control-lg"
                            placeholder="Cari berdasarkan Nama, Kategori, Harga, atau Stok...">
                    </div>
                </div>

                <!-- Products Table -->
                <div class="col-md-12">
                    <div class="cx-card revenue-overview">
                        <div class="cx-card-header d-flex justify-content-between align-items-center">
                            <h4 class="cx-card-title mb-0">Daftar Produk</h4>
                            <a href="{{ route('admin.manajemen.manajemen_produk_create') }}"
                                class="btn btn-outline-primary rounded-pill d-inline-flex align-items-center">
                                <i class="ri-add-line me-1"></i> Tambah Produk
                            </a>
                        </div>
                        <div class="cx-card-content card-default">
                            <div class="table-responsive">
                                <table id="produk-table" class="table table-striped table-hover align-middle">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Produk</th>
                                            <th>Kategori</th>
                                            <th>Harga</th>
                                            <th>Stok</th>
                                            <th>Gambar</th>
                                            <th>Deskripsi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($produks as $produk)
                                            @php
                                                $hariSejakDibuat = \Carbon\Carbon::parse(
                                                    $produk->created_at,
                                                )->diffInDays(now());
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $produk->nama }}
                                                    @if ($hariSejakDibuat <= 7)
                                                        <span class="badge bg-success ms-1">Baru</span>
                                                    @endif
                                                </td>
                                                <td>{{ $produk->kategori->name ?? '-' }}</td>
                                                <td>Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                                                <td>{{ $produk->stok }}</td>
                                                <td>
                                                    @if ($produk->gambar)
                                                        <img src="{{ asset('storage/' . $produk->gambar) }}" width="50"
                                                            height="50" alt="Gambar Produk">
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>{{ $produk->deskripsi }}</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin.manajemen.manajemen_produk_edit', $produk->id) }}"
                                                            class="btn btn-sm btn-outline-primary" title="Edit">
                                                            <i class="ri-edit-line"></i>
                                                        </a>
                                                        @if ($hariSejakDibuat <= 7)
                                                            <a href="#"
                                                                class="btn btn-sm btn-outline-danger delete-btn"
                                                                data-id="{{ $produk->id }}" title="Hapus">
                                                                <i class="ri-delete-bin-6-line"></i>
                                                            </a>
                                                        @else
                                                            <button class="btn btn-sm btn-outline-secondary"
                                                                title="Tidak dapat dihapus" disabled>
                                                                <i class="ri-lock-line"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= Kartu Stok ================= -->
            <div class="tab-pane fade" id="stok" role="tabpanel">
                <div class="mb-3">
                    <a href="{{ route('admin.manajemen.kartu_stok_pdf') }}" class="btn btn-outline-success mb-2">
                        <i class="ri-file-pdf-line me-1"></i> Cetak PDF
                    </a>
                </div>
                <div class="cx-card">
                    <div class="cx-card-content card-default">
                        <div class="table-responsive">
                            <table id="kartu-stok-table" class="table table-striped table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Produk</th>
                                        <th>Barang Masuk</th>
                                        <th>Barang Keluar</th>
                                        <th>Sisa Stok</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kartuStok as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item['nama'] }}</td>
                                            <td>{{ $item['masuk'] }}</td>
                                            <td>{{ $item['keluar'] }}</td>
                                            <td>{{ $item['sisa'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@push('styles')
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet" />
@endpush

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Daftar Produk
            const produkTable = $('#produk-table').DataTable({
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "🔍 Cari produk...",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    zeroRecords: "Tidak ditemukan data",
                    info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                    infoEmpty: "Tidak ada data",
                    paginate: {
                        next: "➡️",
                        previous: "⬅️"
                    }
                },
                pageLength: 10,
                responsive: true,
            });
            $('#kategoriFilter').on('change', function() {
                produkTable.column(2).search(this.value).draw();
            });
            $('#global-search').on('keyup', function() {
                produkTable.search(this.value).draw();
            });

            // Kartu Stok
            $('#kartu-stok-table').DataTable({
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "🔍 Cari produk...",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    zeroRecords: "Tidak ditemukan data",
                    info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                    infoEmpty: "Tidak ada data",
                    paginate: {
                        next: "➡️",
                        previous: "⬅️"
                    }
                },
                pageLength: 10,
                responsive: true,
            });

            // Toast
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
            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: @js(session('success'))
                });
            @endif
            @if (session('error'))
                Toast.fire({
                    icon: 'error',
                    title: @js(session('error'))
                });
            @endif
            @if ($errors->any())
                Toast.fire({
                    icon: 'warning',
                    title: 'Form belum lengkap',
                    text: @js($errors->first())
                });
            @endif

            // Delete produk
            $('.delete-btn').on('click', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data produk tidak bisa dikembalikan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#3b82f6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `/admin/manajemen/produk/${id}/delete`;
                    }
                });
            });
        });
    </script>
@endpush
