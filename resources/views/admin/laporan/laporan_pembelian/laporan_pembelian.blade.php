@extends('admin.layouts.app')

@section('title', 'Laporan Pembelian')

@section('content')
    <div class="cx-main-content">
        <div class="container-fluid">
            <div class="cx-page-title d-flex justify-content-between align-items-center flex-wrap mb-4">
                <h4 class="mb-0">Laporan Pembelian</h4>
                <a href="{{ route('admin.laporan.pembelian.cetak') }}" target="_blank" class="btn btn-danger">
                    <i class="bi bi-printer"></i> Cetak PDF
                </a>
            </div>

            <div class="cx-card card-default">
                <div class="cx-card-header">
                    <h5 class="mb-0">Data Pembelian</h5>
                </div>

                <div class="cx-card-content">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="pembelian-table">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Supplier</th>
                                    <th>Alamat</th>
                                    <th>Telepon</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
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
            {{ isset($pembelian->tanggal) 
                ? \Carbon\Carbon::parse($pembelian->tanggal)->format('d/m/Y') 
                : '-' 
            }}
        </td>
        <td>Rp {{ number_format($pembelian->total ?? 0, 0, ',', '.') }}</td>
        <td>{{ $pembelian->jenis ?? '-' }}</td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center">Belum ada data</td>
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
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(function() {
            $('#pembelian-table').DataTable({
                pageLength: 10,
                language: {
                    searchPlaceholder: "🔍 Cari pembelian...",
                    zeroRecords: "Data tidak ditemukan",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ pembelian",
                    paginate: {
                        previous: "⬅️",
                        next: "➡️"
                    }
                }
            });
        });
    </script>
@endpush
