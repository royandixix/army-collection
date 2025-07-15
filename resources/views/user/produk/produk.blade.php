@extends('user.layouts.app')

@section('title', 'Halaman Produk')

@section('content')
<div class="container mt-4">
    

    {{-- Grid Produk --}}
    <div class="row g-4">
        @forelse($produks as $produk)
            <div class="col-md-6 col-lg-4">
                <div class="card produk-card h-100 border-0 shadow-lg rounded-4 overflow-hidden position-relative">
                    {{-- Gambar Produk --}}
                    <div class="produk-image-wrapper" style="height: 220px; overflow: hidden;">
                        <img src="{{ asset('storage/' . $produk->gambar) }}" 
                             class="w-100 h-100 object-fit-cover transition-scale" 
                             alt="{{ $produk->nama }}">
                    </div>

                    {{-- Konten Produk --}}
                    <div class="card-body d-flex flex-column p-4 bg-white">
                        <h5 class="fw-semibold text-dark text-truncate mb-1" title="{{ $produk->nama }}">
                            {{ $produk->nama }}
                        </h5>
                        <p class="text-muted mb-3 fs-6">
                            Rp {{ number_format($produk->harga, 0, ',', '.') }}
                        </p>

                        {{-- Tombol Aksi --}}
                        <div class="mt-auto">
                            <a href="{{ url('/user/produk/' . $produk->id) }}" 
                               class="btn btn-outline-success w-100 mb-2 rounded-pill fw-semibold">
                                <i class="bi bi-eye me-1"></i> Lihat Detail
                            </a>
                            <form action="{{ url('/user/keranjang') }}" method="POST">
                                @csrf
                                <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                                <button type="submit" class="btn btn-success w-100 rounded-pill fw-semibold">
                                    <i class="bi bi-cart-plus me-1"></i> Tambah ke Keranjang
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted">
                <p>Belum ada produk yang tersedia saat ini 🛒</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('styles')
<style>
    .produk-card:hover {
        transform: translateY(-5px);
        transition: all 0.3s ease;
    }

    .produk-image-wrapper img {
        transition: transform 0.4s ease-in-out;
    }

    .produk-card:hover .produk-image-wrapper img {
        transform: scale(1.07);
    }

    .btn-success,
    .btn-outline-success {
        font-size: 0.95rem;
        padding: 10px 16px;
        transition: background 0.3s ease;
    }

    .btn-outline-success:hover {
        background-color: #000000;
        color: white;
        border-color: #2C6E49;
    }

    .bg-gradient {
        background: linear-gradient(135deg, #5C946E, #2C6E49);
    }

    .object-fit-cover {
        object-fit: cover;
    }

    .transition-scale {
        transition: transform 0.4s ease;
    }
</style>
@endpush
