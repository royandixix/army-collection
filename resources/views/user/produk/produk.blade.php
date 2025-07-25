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
                    <form action="{{ url('/user/keranjang') }}" method="POST" class="mt-auto">
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


    /* Tambahan efek sticky untuk pencarian */
    .position-sticky {
   
}



</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        @if(session('success'))
        Swal.fire({
            iconHtml: '<i class="bi bi-cart-check-fill fs-2 text-success"></i>'
            , title: 'Berhasil!'
            , text: '{{ session('
            success ') }}'
            , position: 'center'
            , showConfirmButton: false
            , timer: 2200
            , timerProgressBar: true
            , background: '#ffffff'
            , color: '#333'
            , customClass: {
                popup: 'rounded-4 shadow px-4 py-3 animate__animated animate__fadeInDown'
                , title: 'fw-bold text-success mb-2'
                , htmlContainer: 'text-dark fs-6'
            }
            , hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
            , didOpen: (popup) => {
                popup.addEventListener('mouseenter', Swal.stopTimer);
                popup.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
        @endif
    });

</script>
@endpush
