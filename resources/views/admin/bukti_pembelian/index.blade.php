@extends('admin.layouts.app')

@section('title', 'Manajemen Bukti Pembelian')

@section('content')
<div class="cx-main-content">

    <div class="cx-page-title d-flex justify-content-between align-items-center flex-wrap">
        <h5>Manajemen Bukti Pembelian</h5>
    </div>

    <div class="col-md-12">
        <div class="cx-card revenue-overview">

            <!-- Form Upload Bukti -->
            <div class="cx-card-header d-flex justify-content-between align-items-center">
                <h4 class="cx-card-title mb-0">Upload Bukti Pembelian</h4>
            </div>
            <div class="cx-card-content card-default mb-4">
                <form action="{{ route('admin.bukti_pembelian.upload', 0) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-4">
                            <select name="pembelian_id" class="form-select" required>
                                <option value="">Pilih Pembelian</option>
                                @foreach($pembelians as $pembelian)
                                    <option value="{{ $pembelian->id }}">
                                        ID: {{ $pembelian->id }} | Supplier: {{ $pembelian->supplier->nama ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="file" name="file" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Upload Bukti</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tabel Bukti Pembelian -->
            <div class="cx-card-header d-flex justify-content-between align-items-center">
                <h4 class="cx-card-title mb-0">Daftar Bukti Pembelian</h4>
            </div>

            <div class="cx-card-content card-default">
                <div class="table-responsive">
                    <table id="bukti-pembelian-table" class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Faktur / Pembelian ID</th>
                                <th>Supplier</th>
                                <th>File</th>
                                <th>Status Pengiriman</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($buktiPembelians as $bukti)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $bukti->pembelian->id ?? '-' }}</td>
                                    <td>{{ $bukti->pembelian->supplier->nama ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('admin.bukti_pembelian.download', $bukti->id) }}" class="btn btn-sm btn-primary">
                                            <i class="ri-download-line me-1"></i> Download
                                        </a>
                                    </td>
                                    <td>
                                        @php
                                            $status = $bukti->status_pengiriman;
                                            $badge = $status === 'pending' ? 'warning text-dark' : ($status === 'dikirim' ? 'primary' : 'success');
                                        @endphp
                                        <span class="badge bg-{{ $badge }}">{{ ucfirst($status) }}</span>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.bukti_pembelian.update_status', $bukti->id) }}" method="POST" class="d-flex gap-1">
                                            @csrf
                                            <select name="status_pengiriman" class="form-select form-select-sm">
                                                <option value="pending" {{ $status=='pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="dikirim" {{ $status=='dikirim' ? 'selected' : '' }}>Dikirim</option>
                                                <option value="diterima" {{ $status=='diterima' ? 'selected' : '' }}>Diterima</option>
                                            </select>
                                            <button class="btn btn-sm btn-success">
                                                <i class="ri-check-line"></i>
                                            </button>
                                        </form>
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
@endsection

@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#bukti-pembelian-table').DataTable({
        language: {
            search: "_INPUT_",
            searchPlaceholder: "🔍 Cari Bukti Pembelian...",
            lengthMenu: "Tampilkan _MENU_ entri",
            zeroRecords: "Tidak ditemukan data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
        },
        pageLength: 10,
        responsive: true,
    });
});
</script>
@endpush
