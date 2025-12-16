@extends('admin.layouts.app')

@section('title', '10 Produk Terlaris Bulan Ini')

@section('content')
<div class="cx-main-content">
    <div class="container-fluid">
        <h4 class="mb-4">10 Produk Terlaris Bulan {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}</h4>

        <div class="mb-3">
            <a href="{{ route('admin.laporan.produk.terlaris.cetak') }}" class="btn btn-danger" target="_blank">
                <i class="fas fa-file-pdf"></i> Cetak PDF
            </a>
        </div>

        <div class="cx-card card-default">
            <div class="cx-card-content card-default">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                {{-- <th>Kategori</th> --}}
                                <th>Harga</th>
                                <th>Stok</th>
                                {{-- <th>Jumlah Terjual</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produks as $index => $produk)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $produk->nama }}</td>
                                    {{-- <td>{{ $produk->kategori->nama ?? '-' }}</td> --}}
                                    <td>Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                                    <td>{{ $produk->stok >= 0 ? $produk->stok : 0 }}</td>
                                    {{-- <td class="text-center">
                                        <span class="badge bg-success">{{ $produk->jumlah_terjual ?? 0 }}</span>
                                    </td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Tidak ada data produk terlaris bulan ini.</td>
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
