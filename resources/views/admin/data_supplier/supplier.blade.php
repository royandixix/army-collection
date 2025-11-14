@extends('admin.layouts.app')

@section('title', 'Manajemen Supplier & Pembelian')

@section('content')
<div class="cx-main-content">

    <div class="cx-page-title d-flex justify-content-between align-items-center flex-wrap">
        <h5>Manajemen Supplier & Pembelian</h5>
    </div>

    <div class="ms-3 mb-3">
        <a href="{{ route('admin.supplier.create') }}" class="btn btn-outline-primary rounded-pill">
            <i class="ri-add-line me-1"></i> Tambah Supplier
        </a>
    </div>

    <div class="col-md-12">
        <div class="cx-card revenue-overview">
            <div class="cx-card-header d-flex justify-content-between align-items-center">
                <h4 class="cx-card-title mb-0">Daftar Supplier & Pembelian Produk</h4>
            </div>

            <div class="cx-card-content card-default">
                <div class="table-responsive">

                    <table id="supplier-table" class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Supplier</th>
                                <th>Alamat</th>
                                <th>Telepon</th>
                                <th>Produk</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($suppliers as $supplier)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $supplier->nama }}</td>
                                    <td>{{ $supplier->alamat }}</td>
                                    <td>{{ $supplier->telepon }}</td>

                                    <td>
                                        @if ($supplier->produks->isEmpty())
                                            <em>Tidak ada produk</em>
                                        @else

                                            <table class="table table-sm table-bordered mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Gambar</th>
                                                        <th>Nama Produk</th>
                                                        <th>Stok</th>
                                                        <th>Harga Beli</th>
                                                        <th>Jumlah Terjual</th>
                                                        <th>Subtotal</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach ($supplier->produks as $produk)
                                                        @php
                                                            $pembelianProduk = $produk->pembelianSuppliers->sortByDesc('created_at');
                                                            
                                                            // Harga beli terakhir, fallback ke harga produk agar tidak muncul 0
                                                            $hargaBeli = $pembelianProduk->first()?->harga_beli ?? $produk->harga;

                                                            // Total terjual
                                                            $terjual = $produk->detailTransaksis->sum('jumlah');

                                                            // Subtotal
                                                            $subtotal = $hargaBeli * $terjual;
                                                        @endphp

                                                        <tr>
                                                            <td>
                                                                @if ($produk->gambar)
                                                                    <img src="{{ asset('storage/' . $produk->gambar) }}" width="40" height="40">
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>

                                                            <td>{{ $produk->nama }}</td>
                                                            <td>{{ $produk->stok }}</td>

                                                            <td>Rp {{ number_format($hargaBeli, 0, ',', '.') }}</td>

                                                            <td>{{ $terjual }}</td>

                                                            <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>

                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                        @endif
                                    </td>

                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.supplier.edit', $supplier->id) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="ri-edit-line"></i>
                                            </a>

                                            <form action="{{ route('admin.supplier.destroy', $supplier->id) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Hapus data supplier?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">
                                                    <i class="ri-delete-bin-6-line"></i>
                                                </button>
                                            </form>
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
    $('#supplier-table').DataTable({
        language: {
            search: "_INPUT_",
            searchPlaceholder: "🔍 Cari Supplier...",
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
