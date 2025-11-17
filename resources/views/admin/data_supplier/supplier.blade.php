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
                                <th>Gambar Produk</th>
                                <th>Nama Produk</th>
                                <th>Stok</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($suppliers as $supplier)
                                @if ($supplier->produks->isEmpty())
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $supplier->nama }}</td>
                                        <td>{{ $supplier->alamat }}</td>
                                        <td>{{ $supplier->telepon }}</td>
                                        <td colspan="4" class="text-center"><em>Tidak ada produk</em></td>
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
                                @else
                                    @foreach ($supplier->produks as $index => $produk)
                                        @php
                                            $pembelianProduk = $produk->pembelianSuppliers->sortByDesc('created_at');
                                            
                                            // Harga beli terakhir, fallback ke harga produk
                                            $hargaBeli = $pembelianProduk->first()?->harga_beli ?? $produk->harga;
                                        @endphp

                                        <tr>
                                            @if ($index === 0)
                                                <td rowspan="{{ $supplier->produks->count() }}">{{ $loop->parent->iteration }}</td>
                                                <td rowspan="{{ $supplier->produks->count() }}">{{ $supplier->nama }}</td>
                                                <td rowspan="{{ $supplier->produks->count() }}">{{ $supplier->alamat }}</td>
                                                <td rowspan="{{ $supplier->produks->count() }}">{{ $supplier->telepon }}</td>
                                            @endif

                                            <td>
                                                @if ($produk->gambar)
                                                    <img src="{{ asset('storage/' . $produk->gambar) }}" 
                                                         alt="{{ $produk->nama }}"
                                                         width="40" 
                                                         height="40"
                                                         class="rounded">
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td>{{ $produk->nama }}</td>
                                            <td>{{ $produk->stok }}</td>
                                            <td>Rp {{ number_format($hargaBeli, 0, ',', '.') }}</td>

                                            @if ($index === 0)
                                                <td rowspan="{{ $supplier->produks->count() }}">
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
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
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