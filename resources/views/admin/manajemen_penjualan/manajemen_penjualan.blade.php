@extends('admin.layouts.app')

@section('title', 'Manajemen Penjualan')

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

    <!-- Search & Filter -->
    <div class="row mb-3 ms-3">
        <div class="col-md-4">
            <label for="statusFilter" class="form-label">Filter berdasarkan Status:</label>
            <select id="statusFilter" class="form-select">
                <option value="">-- Semua Status --</option>
                <option value="lunas">LUNAS</option>
                <option value="pending">PENDING</option>
                <option value="batal">BATAL</option>
            </select>
        </div>
        <div class="col-md-8">
            <label for="global-search" class="form-label">Pencarian Cepat:</label>
            <input type="text" id="global-search" class="form-control form-control-lg" placeholder="Cari berdasarkan pelanggan, tanggal, atau total...">
        </div>
    </div>

    <div class="mb-4 ms-3">
        <h5>Manajemen Penjualan</h5>
    </div>

    <!-- Table -->
    <div class="col-md-12">
        <div class="cx-card revenue-overview">
            <div class="cx-card-header d-flex justify-content-between align-items-center">
                <h4 class="cx-card-title mb-0">Daftar Transaksi Penjualan</h4>
            </div>

            <div>
                <a href="{{ route('admin.manajemen.manajemen_penjualan_create') }}" class="btn btn-outline-primary rounded-pill d-inline-flex align-items-center mb-3">
                    <i class="ri-add-line me-1"></i>
                    Tambah Penjualan
                </a>
            </div>

            <div class="cx-card-content card-default">
                <div class="table-responsive">
                    <table id="penjualan-table" class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penjualans as $penjualan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $penjualan->tanggal }}</td>
                                <td>{{ $penjualan->pelanggan->nama ?? '-' }}</td>
                                <td>Rp {{ number_format($penjualan->total, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-{{ $penjualan->status == 'lunas' ? 'success' : ($penjualan->status == 'pending' ? 'warning' : 'danger') }}">
                                        {{ strtoupper($penjualan->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.penjualan.show', $penjualan->id) }}" class="btn btn-sm btn-outline-info">Detail</a>
                                    <a href="{{ route('admin.penjualan.edit', $penjualan->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('admin.penjualan.destroy', $penjualan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
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
        const table = $('#penjualan-table').DataTable({
            language: {
                search: "_INPUT_",
                searchPlaceholder: "🔍 Cari penjualan...",
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

        $('#statusFilter').on('change', function () {
            const selected = $(this).val();
            table.column(4).search(selected).draw();
        });

        $('#global-search').on('keyup', function () {
            table.search(this.value).draw();
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
        Toast.fire({ icon: 'success', title: @js(session('success')) });
    @endif

    @if(session('error'))
        Toast.fire({ icon: 'error', title: @js(session('error')) });
    @endif

    @if($errors->any())
        Toast.fire({ icon: 'warning', title: 'Form belum lengkap', text: @js($errors->first()) });
    @endif
</script>
@endpush
