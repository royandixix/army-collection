@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mt-4" role="alert">
        <strong class="font-bold">Berhasil!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

<div class="cx-main-content">

    <!-- Page Title -->
    <div class="cx-page-title d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h5 class="mb-2">Selamat datang {{ Auth::user()->username ?? 'Guest' }}</h5>

          
        </div>
        <div class="cx-tools d-flex gap-2 align-items-center">
            <a href="javascript:void(0)" class="refresh" data-bs-toggle="tooltip" title="Refresh">
                <i class="ri-refresh-line"></i>
            </a>
        </div>
    </div>

    <!-- Global Search Input -->
    <div class="mb-3">
        <input type="text" id="global-search" class="form-control form-control-lg" placeholder="Cari berdasarkan ID, Nama, Tanggal, Team, atau Status...">
    </div>

    <div class=" mb-4 m">
        <h5>Manjemen Pengguna</h5>
    </div>

    <!-- Deals Table -->
    <div class="col-md-12">
        <div class="cx-card revenue-overview">
            <div class="cx-card-header d-flex justify-content-between align-items-center">
                <h4 class="cx-card-title mb-0"></h4>
            </div>

            <div class="cx-card-content card-default">
                <div class="table-responsive">
                    <table id="deal-table" class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Team</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $deals = [
                                    ['id' => 2650, 'name' => 'Monsy Pvt.', 'img' => '1.jpg', 'date' => '15/01/2023', 'team' => 'Zara nails', 'status' => 'ACTIVE'],
                                    ['id' => 1945, 'name' => 'Miletone Gems.', 'img' => '9.jpg', 'date' => '06/05/2021', 'team' => 'Sarah Moanees', 'status' => 'PENDING'],
                                    ['id' => 1865, 'name' => 'Lightbeam Pvt.', 'img' => '10.jpg', 'date' => '01/01/2021', 'team' => 'Anne Ortha', 'status' => 'ACTIVE'],
                                ];
                            @endphp

                            @foreach($penjualans as $deal)
                                <tr>
                                    <td>{{ $deal['id'] }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="assets/img/clients/{{ $deal['img'] }}" class="rounded-circle" width="40" height="40" alt="Client">
                                            <span>{{ $deal['name'] }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $deal['date'] }}</td>
                                    <td>{{ $deal['team'] }}</td>
                                    <td>
                                        <span class="badge bg-{{ strtolower($deal['status']) === 'active' ? 'success' : 'warning' }}">
                                            {{ strtoupper($deal['status']) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="#" class="btn btn-sm btn-outline-primary edit-btn" data-id="{{ $deal['id'] }}" data-bs-toggle="tooltip" title="Edit">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-outline-danger delete-btn" data-id="{{ $deal['id'] }}" data-bs-toggle="tooltip" title="Hapus">
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
</div>

<!-- Toast Notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
    <div id="liveToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage">Action executed</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
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

    <script>
        $(document).ready(function () {
            const table = $('#deal-table').DataTable({
                dom: 't',
                responsive: true,
                paging: false,
                info: false
            });

            // Global Search
            $('#global-search').on('keyup', function () {
                table.search(this.value).draw();
            });

            // Toast handler
            const toastLive = document.getElementById('liveToast');
            const toastMessage = document.getElementById('toastMessage');
            const toast = new bootstrap.Toast(toastLive);

            $('.edit-btn').on('click', function (e) {
                e.preventDefault();
                const id = $(this).data('id');
                toastMessage.textContent = `Edit diklik untuk ID ${id}`;
                toast.show();
            });

            $('.delete-btn').on('click', function (e) {
                e.preventDefault();
                const id = $(this).data('id');
                toastMessage.textContent = `Hapus diklik untuk ID ${id}`;
                toast.show();
            });

            // Tooltip
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
        });
    </script>
@endpush
