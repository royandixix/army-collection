@extends('admin.layouts.app')

@section('title', 'Laporan Pembelian')

@section('content')
<div class="cx-main-content">
    <div class="container-fluid">
        <!-- Judul & tombol cetak -->
        <div class="cx-page-title d-flex justify-content-between align-items-center flex-wrap mb-4">
            <h4 class="mb-0">Laporan Pembelian</h4>
            <a href="{{ route('admin.laporan.pembelian.cetak') }}" target="_blank" class="btn btn-danger">
                <i class="bi bi-printer"></i> Cetak PDF
            </a>
        </div>

        <!-- Card tabel -->
        <div class="cx-card card-default shadow-sm">
            <div class="cx-card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Pembelian</h5>
                <!-- Search tunggal -->
                <input type="text" id="global-search" class="form-control form-control-sm w-auto"
                       placeholder="🔍 Cari pembelian...">
            </div>

            <div class="cx-card-content p-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="pembelian-table">
                        <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Supplier</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Jenis</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($pembelians as $index => $pembelian)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $pembelian->supplier ?? '-' }}</td>
                                <td>{{ $pembelian->alamat ?? '-' }}</td>
                                <td>{{ $pembelian->telepon ?? '-' }}</td>
                                <td>
                                    {{ $pembelian->tanggal ? \Carbon\Carbon::parse($pembelian->tanggal)->format('d/m/Y') : '-' }}
                                </td>
                                <td>Rp {{ number_format($pembelian->total ?? 0, 0, ',', '.') }}</td>
                                <td>{{ $pembelian->jenis ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada data pembelian</td>
                            </tr>
                        @endforelse
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
<style>
    #global-search {
        margin-bottom: 10px;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f5f9;
    }

    table.dataTable th, table.dataTable td {
        vertical-align: middle;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    const table = $('#pembelian-table').DataTable({
        pageLength: 10,
        responsive: true,
        searching: false, // Matikan search default DataTables
        language: {
            zeroRecords: "Data tidak ditemukan",
            lengthMenu: "Tampilkan _MENU_ entri",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ pembelian",
            infoEmpty: "Tidak ada data",
            paginate: {
                previous: "⬅️",
                next: "➡️"
            }
        }
    });

    // Pencarian global via input sendiri
    $('#global-search').on('keyup', function() {
        table.search(this.value).draw();
    });
});
</script>
@endpush
