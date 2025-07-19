@extends('user.layouts.app')

@section('title', 'Halaman Produk')

@section('content')
<div class="container py-5">

    {{-- Header --}}
    <div class="text-center mb-5">
        <h2 class="fw-bold text-dark animate__animated animate__fadeInDown">Produk Kami</h2>
        <p class="text-muted fs-5 animate__animated animate__fadeInUp">Temukan produk terbaik yang kamu cari dengan harga terbaik!</p>
    </div>

    {{-- Search --}}
    <div class="mb-5 d-flex justify-content-center">
        <form action="{{ route('user.produk.index') }}" method="GET" class="d-flex w-100 animate__animated animate__fadeIn" style="max-width: 540px;">
            <input type="text" name="search" class="form-control rounded-start shadow-sm" placeholder="Cari produk..." value="{{ request('search') }}">
            <button class="btn btn-gradient rounded-end" type="submit" aria-label="Cari">
                <i class="bi bi-search fs-5"></i>
            </button>
        </form>
    </div>

    {{-- Produk Grid --}}
    <div class="row g-4">
        @forelse ($produks as $produk)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 d-flex">
                <div class="card product-card shadow-sm w-100 animate__animated animate__fadeInUp">
                    <div class="ratio ratio-1x1 position-relative">
                        <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama }}" class="w-100 h-100 object-fit-cover product-image shadow-image" loading="lazy">
                    </div>
                    <div class="card-body d-flex flex-column pt-3">
                        <span class="badge bg-kategori mb-2">{{ $produk->kategori?->name }}</span>
                        <h6 class="fw-semibold text-truncate mb-1" title="{{ $produk->nama }}">{{ $produk->nama }}</h6>
                        <p class="text-success fw-bold mb-3 fs-5">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                        <a href="{{ url('/user/produk/' . $produk->id) }}" class="btn btn-outline-gradient w-100 mb-3">
                            <i class="bi bi-eye"></i> Lihat Detail
                        </a>
                        <form action="{{ url('/user/keranjang') }}" method="POST" class="mt-auto">
                            @csrf
                            <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                            <button class="btn btn-gradient w-100" type="submit">
                                <i class="bi bi-cart-plus"></i> Tambah
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5 w-100 text-muted animate__animated animate__fadeIn">
                <i class="bi bi-box-seam fs-1 mb-3 d-block"></i> Produk tidak ditemukan
            </div>
        @endforelse
    </div>

</div>
@endsection

@push('styles')
<!-- Eksternal CDN -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<!-- Custom Styles -->
<style>
    body {
        background: #f8f9fa;
        font-family: 'Poppins', sans-serif;
    }

    .product-card {
        border: none;
        border-radius: 1rem;
        overflow: hidden;
        background: #fff;
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .product-image {
        object-fit: cover;
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
    }

    .shadow-image {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .badge.bg-kategori {
        background: linear-gradient(45deg, #a8dadc, #457b9d);
        color: #fff;
        font-size: 0.8rem;
        padding: 0.35rem 0.75rem;
        font-weight: 600;
        border-radius: 0.6rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .btn-gradient {
        background: linear-gradient(90deg, #0f2027, #203a43, #2c5364);
        color: #fff;
        border: none;
        font-weight: 600;
        transition: filter 0.3s ease;
        box-shadow: 0 4px 12px rgba(12, 30, 44, 0.3);
    }

    .btn-gradient:hover {
        filter: brightness(1.15);
        color: #fff;
    }

    .btn-outline-gradient {
        border: 2px solid #2c5364;
        color: #2c5364;
        font-weight: 600;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .btn-outline-gradient:hover {
        background-color: #2c5364;
        color: #fff;
    }

    /* Responsive tweaks for product cards */
    @media (max-width: 1399.98px) {
        .col-xl-2 {
            flex: 0 0 auto;
            width: 20%;
        }
    }

    @media (max-width: 1199.98px) {
        .col-lg-3 {
            flex: 0 0 auto;
            width: 25%;
        }
    }

    @media (max-width: 991.98px) {
        .col-md-4 {
            flex: 0 0 auto;
            width: 33.3333%;
        }
    }

    @media (max-width: 767.98px) {
        .col-sm-6 {
            flex: 0 0 auto;
            width: 50%;
        }
    }

    @media (max-width: 575.98px) {
        .col-12 {
            flex: 0 0 auto;
            width: 100%;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        @if(session('success'))
        Swal.fire({
            iconHtml: '<i class="bi bi-cart-check-fill fs-2 text-success"></i>',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            position: 'center',
            showConfirmButton: false,
            timer: 2200,
            timerProgressBar: true,
            background: '#ffffff',
            color: '#333',
            customClass: {
                popup: 'rounded-4 shadow px-4 py-3 animate__animated animate__fadeInDown',
                title: 'fw-bold text-success mb-2',
                htmlContainer: 'text-dark fs-6'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            },
            didOpen: (popup) => {
                popup.addEventListener('mouseenter', Swal.stopTimer);
                popup.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
        @endif
    });
</script>
@endpush
