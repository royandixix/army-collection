@extends('user.layouts.app')

@section('title', 'Detail Produk')

@section('content')
<div class="container py-5">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white px-0">
            <li class="breadcrumb-item"><a href="{{ route('user.produk.index') }}">Produk</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $produk->nama }}</li>
        </ol>
    </nav>

    {{-- Detail Produk --}}
    <div class="row g-5">
        <div class="col-md-6">
            <div class="rounded shadow-sm overflow-hidden bg-white ratio ratio-1x1">
                <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama }}"
                    class="w-100 h-100 object-fit-cover" loading="lazy">
            </div>
        </div>

        <div class="col-md-6">
            <div class="product-detail">
                <h2 class="mb-3 text-dark">{{ $produk->nama }}</h2>

                <div class="mb-3">
                    <span class="badge bg-gradient-primary text-uppercase px-3 py-2">
                        {{ $produk->kategori?->name }}
                    </span>
                </div>

                <p class="fs-5 text-success mb-3">
                    Rp {{ number_format($produk->harga, 0, ',', '.') }}
                </p>

                <p class="text-secondary mb-4" style="white-space: pre-wrap;">
                    {!! nl2br(e($produk->deskripsi)) !!}
                </p>

                {{-- Form Tambah ke Keranjang --}}
                <form action="{{ url('/user/keranjang') }}" method="POST" class="d-flex flex-column gap-3">
                    @csrf
                    <input type="hidden" name="produk_id" value="{{ $produk->id }}">

                    <div class="d-flex align-items-center" style="max-width: 180px;">
                        <label class="me-2">Qty:</label>
                        <input type="number" name="qty" value="1" min="1" class="form-control form-control-sm" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm rounded-2 w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
                    </button>

                    <button class=" btn btn-warning btn-sm rounded-2 w-100 d-flex align-middle-center justify-center-center gap-3">
                        <i class="bi btn-outline-secondary"></i>
                        kembali
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
<style>
    body {
        background: #f4f5f7;
        font-family: 'Poppins', sans-serif;
    }

    .breadcrumb {
        background: transparent;
        font-size: 0.9rem;
    }

    .breadcrumb a {
        text-decoration: none;
        color: #0d6efd;
    }

    .product-detail h2 {
        font-size: 1.5rem;
    }

    .badge.bg-gradient-primary {
        background: linear-gradient(135deg, #0d6efd, #3f83f8);
        color: white;
        border-radius: 0.5rem;
        font-size: 0.75rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #0d6efd, #3f83f8);
        border: none;
        font-weight: normal;
        font-size: 0.85rem;
        padding: 0.45rem 1rem;
        transition: background-color 0.2s ease;
    }

    .btn-primary:hover {
        background-color: #0b5ed7;
    }

    .btn-sm {
        font-size: 0.8rem;
        padding: 0.4rem 0.75rem;
    }

    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        appearance: textfield;
    }

    .form-control:focus {
        box-shadow: none;
        border-color: #3f83f8;
    }
</style>
@endpush
