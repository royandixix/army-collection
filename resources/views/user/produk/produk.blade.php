@extends('user.layouts.app')

@section('title', 'Halaman Produk')

@section('content')
<div class="container py-5">

    {{-- Header --}}
    <div class="mb-4">
        <h3 class="fw-bold text-dark animate__animated animate__fadeInDown">
            <i class="bi bi-shop-window text-primary me-2"></i> Produk Kami
        </h3>
        <p class="text-muted fs-6 animate__animated animate__fadeInUp">
            Jelajahi produk berkualitas dengan harga terbaik!
        </p>
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
                    <p class="text-success mb-3 fs-5">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>

                    <a href="{{ url('/user/produk/' . $produk->id) }}" class="btn btn-outline-gradient btn-sm w-100 mb-2 d-flex align-items-center justify-content-start gap-2">
                        <i class="bi bi-eye fs-6"></i> <span>Lihat</span>
                    </a>

                    {{-- Tombol Tambah ke Keranjang --}}
                    <form action="{{ route('user.keranjang.tambah') }}" method="POST" class="mt-auto">
                        @csrf
                        <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                        <button type="submit" class="btn btn-outline-gradient btn-sm w-100 d-flex align-items-center justify-content-start gap-2">
                            <i class="bi bi-cart-plus fs-6"></i> <span>Tambah</span>
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
        background: linear-gradient(45deg, #556836, #457b9d);
        color: #fff;
        font-size: 0.8rem;
        padding: 0.35rem 0.75rem;
        font-weight: 600;
        border-radius: 0.6rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .btn-gradient {
        background: linear-gradient(90deg, #ffffff, #ffffff, #fbfbfb);
        color: #000000;
        border: none;
        font-weight: 600;
        transition: filter 0.3s ease;
        box-shadow: 0 4px 12px rgba(12, 30, 44, 0.3);
    }

    .btn-gradient:hover {
        filter: brightness(1.15);
        color: #000000;
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


    .btn-sm {
        padding: 0.4rem 0.75rem;
        font-size: 0.85rem;
        border-radius: 0.5rem;
    }

    .btn-gradient.btn-sm,
    .btn-outline-gradient.btn-sm {
        font-weight: 500;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .btn-gradient.btn-sm:hover {
        filter: brightness(1.1);
    }

    .btn-outline-gradient.btn-sm:hover {
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

    form input::placeholder {
        font-size: 0.9rem;
        color: #999;
    }

    form input:focus {
        box-shadow: none;
        border-color: #0d6efd;
    }

    form .btn-primary {
        background: linear-gradient(135deg, #0d6efd, #3f83f8);
        border: none;
        transition: all 0.2s ease;
    }

    form .btn-primary:hover {
        background: #0b5ed7;
        box-shadow: 0 3px 10px rgba(0, 123, 255, 0.25);
    }

    /* Custom SweetAlert Styling */
    .swal2-popup.swal-custom-success {
        border-radius: 20px !important;
        padding: 2rem 1.5rem !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15) !important;
        border: none !important;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%) !important;
    }

    .swal2-icon.swal2-success {
        border-color: #10b981 !important;
        color: #10b981 !important;
        width: 70px !important;
        height: 70px !important;
        margin: 1.5rem auto 1rem !important;
    }

    .swal2-icon.swal2-success .swal2-success-ring {
        border: 4px solid rgba(16, 185, 129, 0.2) !important;
    }

    .swal2-icon.swal2-success [class^='swal2-success-line'] {
        background-color: #10b981 !important;
    }

    .swal-custom-icon {
        width: 80px !important;
        height: 80px !important;
        margin: 1rem auto 1.5rem !important;
        border-radius: 50%;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        animation: scaleIn 0.5s ease-out;
    }

    @keyframes scaleIn {
        0% {
            transform: scale(0);
            opacity: 0;
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    @keyframes checkmark {
        0% {
            stroke-dashoffset: 100;
        }
        100% {
            stroke-dashoffset: 0;
        }
    }

    .swal-checkmark {
        width: 45px;
        height: 45px;
        stroke: white;
        stroke-width: 3;
        stroke-dasharray: 100;
        stroke-dashoffset: 100;
        animation: checkmark 0.6s ease-out 0.2s forwards;
    }

    .swal2-title {
        font-size: 1.75rem !important;
        font-weight: 700 !important;
        color: #1f2937 !important;
        margin: 0.5rem 0 !important;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .swal2-html-container {
        font-size: 1.05rem !important;
        color: #6b7280 !important;
        margin: 0.5rem 0 1.5rem !important;
        font-weight: 500 !important;
    }

    .swal2-timer-progress-bar {
        background: linear-gradient(90deg, #10b981 0%, #059669 100%) !important;
        height: 4px !important;
    }

    @keyframes slideInDown {
        from {
            transform: translate(-50%, -100%);
            opacity: 0;
        }
        to {
            transform: translate(-50%, 0);
            opacity: 1;
        }
    }

    @keyframes slideOutUp {
        from {
            transform: translate(-50%, 0);
            opacity: 1;
        }
        to {
            transform: translate(-50%, -100%);
            opacity: 0;
        }
    }

    .swal2-show {
        animation: slideInDown 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
    }

    .swal2-hide {
        animation: slideOutUp 0.3s ease-out !important;
    }

    /* Backdrop blur effect */
    .swal2-container {
        backdrop-filter: blur(4px);
        background-color: rgba(0, 0, 0, 0.4) !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        @if(session('success'))
        Swal.fire({
            html: `
                <div class="swal-custom-icon">
                    <svg class="swal-checkmark" viewBox="0 0 52 52">
                        <path fill="none" d="M14 27l7.5 7.5L38 18"/>
                    </svg>
                </div>
                <div style="margin-top: 1rem;">
                    <h2 style="font-size: 1.75rem; font-weight: 700; color: #1f2937; margin: 0.5rem 0;">
                        Berhasil Ditambahkan!
                    </h2>
                    <p style="font-size: 1.05rem; color: #6b7280; margin: 0.5rem 0 0 0; font-weight: 500;">
                        {{ session('success') }}
                    </p>
                </div>
            `,
            position: 'top',
            showConfirmButton: false,
            timer: 2800,
            timerProgressBar: true,
            customClass: {
                popup: 'swal-custom-success',
                timerProgressBar: 'swal2-timer-progress-bar'
            },
            showClass: {
                popup: 'swal2-show'
            },
            hideClass: {
                popup: 'swal2-hide'
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