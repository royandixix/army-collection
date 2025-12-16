@extends('admin.layouts.app')

@section('title', 'Laporan Produk')

@section('content')
<div class="cx-main-content">
    <div class="container-fluid">
        <div class="cx-page-title d-flex justify-content-between align-items-center flex-wrap mb-4">
            <h4 class="mb-0">Laporan Produk</h4>
            <a href="{{ route('admin.laporan.produk.cetak') }}" target="_blank" class="btn btn-danger">
                <i class="bi bi-printer"></i> Cetak PDF
            </a>
        </div>

        <div class="cx-card card-default">
            <div class="cx-card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Produk</h5>
            </div>

            <div class="cx-card-content card-default">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="produk-table">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Jumlah Terjual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produks as $index => $produk)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $produk->nama }}</td>
                                    <td>{{ $produk->kategori->name ?? '-' }}</td>
                                    <td>Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                                    <td>{{ $produk->stok }}</td>
                                    <td>{{ $produk->detailPenjualans->sum('jumlah') ?? 0 }}</td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Tidak ada data produk.</td>
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
            $('#produk-table').DataTable({
                pageLength: 10,
                language: {
                    searchPlaceholder: "🔍 Cari produk...",
                    zeroRecords: "Data tidak ditemukan",
                    lengthMenu: "Tampilkan MENU entri",
                    info: "Menampilkan START - END dari TOTAL produk",
                    paginate: {
                        previous: "⬅",
                        next: "➡"
                    }
                }
            });
        });
    </script>
@endpush 