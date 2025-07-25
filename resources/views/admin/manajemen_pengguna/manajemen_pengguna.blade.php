@extends('admin.layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="cx-main-content">
    <!-- Page Title -->
    <div class="cx-page-title d-flex justify-content-between align-items-center flex-wrap">
        <div class="cx-tools d-flex gap-2 align-items-center">
            <a href="javascript:void(0)" class="refresh" data-bs-toggle="tooltip" title="Refresh">
                <i class="ri-refresh-line"></i>
            </a>
        </div>
    </div>

    <!-- Search -->
    <div class="mb-3">
        <input type="text" id="global-search" class="form-control form-control-lg"
               placeholder="Cari berdasarkan ID, Username, Tanggal, Tim, atau Status...">
    </div>

    <div class="mb-4 ms-3">
        <h5>Manajemen Pengguna</h5>
    </div>

    <!-- Users Table -->
    <div class="col-md-12">
        <div class="cx-card revenue-overview">
            <div class="cx-card-header d-flex justify-content-between align-items-center">
                <h4 class="cx-card-title mb-0">Daftar Pengguna</h4>
            </div>

            {{-- Tombol tambah pengguna jika diperlukan --}}
            {{-- 
            <div>
                <a href="{{ route('admin.users.create') }}" class="btn btn-outline-primary rounded-pill d-inline-flex align-items-center mb-3">
                    <i class="ri-add-line me-1"></i>
                    Tambah Pengguna
                </a>
            </div> 
            --}}

            <div class="cx-card-content card-default">
                <div class="table-responsive">
                    <table id="pengguna-table" class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Foto</th>
                                <th>Username</th>
                                <th>Nomor HP</th>
                                <th>Tanggal</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                @php
                                    $fotoUrl = $user->profile_photo_url;
                                    $nama = $user->pelanggan->nama
                                        ?? $user->username
                                        ?? $user->email
                                        ?? 'Pengguna';
                                @endphp
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $fotoUrl }}" alt="Foto" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                                            <span>{{ $nama }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->pelanggan->no_hp ?? '-' }}</td>
                                    <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                    <td>{{ $user->role ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ strtolower($user->status) === 'active' ? 'success' : 'warning' }}">
                                            {{ strtoupper($user->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-outline-danger delete-btn" data-id="{{ $user->id }}" title="Hapus">
                                                <i class="ri-delete-bin-6-line"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        
                        
                    </table>
                </div> <!-- /.table-responsive -->
            </div> <!-- /.cx-card-content -->
        </div> <!-- /.cx-card -->
    </div> <!-- /.col-md-12 -->

    <!-- Toast -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
        <div id="liveToast" class="toast align-items-center text-white bg-success border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage">Action executed</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
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
        $(document).ready(function () {
            $('#pengguna-table').DataTable({
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "🔍 Cari pengguna...",
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

            $('.delete-btn').on('click', function (e) {
                e.preventDefault();
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data pengguna tidak bisa dikembalikan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#3b82f6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `/admin/users/${id}/delete`;
                    }
                });
            });
        });

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
            Toast.fire({
                icon: 'success',
                title: @js(session('success'))
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: @js(session('error'))
            });
        @endif

        @if($errors->any())
            Toast.fire({
                icon: 'warning',
                title: 'Form belum lengkap',
                text: @js($errors->first())
            });
        @endif
    </script>
@endpush
