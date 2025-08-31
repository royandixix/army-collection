@extends('admin.layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="cx-main-content">
    <div class="container-fluid">
        <div class="cx-page-title d-flex justify-content-between align-items-center flex-wrap mb-4">
            <h4 class="mb-0">Laporan Penjualan</h4>
            <a href="{{ route('admin.laporan.penjualan.cetak') }}" target="_blank" class="btn btn-danger">
                <i class="bi bi-printer"></i> Cetak PDF
            </a>
            
        </div>

        <div class="cx-card card-default">
            <div class="cx-card-header">
                <h5 class="mb-0">Data Penjualan</h5>
            </div>

            <div class="cx-card-content">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="penjualan-table">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Pelanggan</th>
                                <th>Produk</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($penjualans as $index => $penjualan)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $penjualan->pelanggan->user->username ?? '-' }}</td>
                                    <td>
                                        @if($penjualan->transaksi && $penjualan->transaksi->detailTransaksi)
                                            <ul class="mb-0 ps-3">
                                                @foreach($penjualan->transaksi->detailTransaksi as $detail)
                                                    <li>{{ $detail->produk->nama ?? '-' }} (x{{ $detail->qty }})</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>Rp {{ number_format($penjualan->total, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($penjualan->status == 'lunas') bg-success
                                            @elseif($penjualan->status == 'pending') bg-warning
                                            @elseif($penjualan->status == 'batal') bg-danger
                                            @else bg-secondary
                                            @endif">
                                            {{ ucfirst($penjualan->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $penjualan->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Tidak ada data penjualan.</td>
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
        $('#penjualan-table').DataTable({
            pageLength: 10,
            language: {
                searchPlaceholder: "🔍 Cari penjualan...",
                zeroRecords: "Data tidak ditemukan",
                lengthMenu: "Tampilkan _MENU_ entri",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ penjualan",
                paginate: { previous: "⬅️", next: "➡️" }
            }
        });
    });
</script>
@endpush
