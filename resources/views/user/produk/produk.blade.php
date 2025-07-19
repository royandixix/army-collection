@extends('user.layouts.app')

@section('title', 'Halaman Produk')

@section('content')
<div class="container py-5">

    {{-- Header --}}
    <div class="text-center mb-5">
        <h2 class="fw-bold text-dark">Produk Kami</h2>
        <p class="text-muted fs-5">Temukan produk terbaik yang kamu cari dengan harga terbaik!</p>
    </div>

    {{-- Search --}}
    <div class="mb-5 d-flex justify-content-center">
        <form action="{{ route('user.produk.index') }}" method="GET" class="d-flex w-100" style="max-width: 540px;">
            <input type="text" name="search" class="form-control rounded-start shadow-sm" placeholder="Cari produk..." value="{{ request('search') }}" aria-label="Cari produk">
            <button class="btn btn-gradient rounded-end" type="submit" aria-label="Cari">
                <i class="bi bi-search fs-5"></i>
            </button>
        </form>
    </div>

    {{-- Produk Slider --}}
    <div class="position-relative">
        <button class="btn slider-btn prev" aria-label="Scroll Left">
            <i class="bi bi-chevron-left fs-5"></i>
        </button>

        <div class="product-slider-wrapper overflow-hidden">
            <div class="d-flex product-slider">
                @forelse ($produks as $produk)
                <div class="card product-card mx-2 flex-shrink-0 shadow-sm">
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
                @empty
                <div class="text-muted text-center w-100 py-5">
                    <i class="bi bi-box-seam fs-1 mb-3 d-block"></i> Produk tidak ditemukan
                </div>
                @endforelse
            </div>
        </div>

        <button class="btn slider-btn next" aria-label="Scroll Right">
            <i class="bi bi-chevron-right fs-5"></i>
        </button>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />
<style>
    body {
        background: #f8f9fa;
        font-family: 'Poppins', sans-serif;
    }

    .product-slider-wrapper {
        margin: 0 2.5rem;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        scroll-behavior: smooth;
    }

    .product-slider {
        display: flex;
        gap: 1rem;
    }

    .product-card {
        flex: 0 0 auto;
        width: 260px;
        scroll-snap-align: start;
        border: none;
        border-radius: 1rem;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.07);
    }

    .product-image {
        object-fit: cover;
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
    }

    .shadow-image {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        border-radius: 1rem 1rem 0 0;
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

    .slider-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        width: 44px;
        height: 44px;
        background: #2c5364;
        color: #fff;
        border: none;
        border-radius: 50%;
        box-shadow: 0 5px 14px rgba(0, 0, 0, 0.15);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .slider-btn:hover {
        background: #1b2b3a;
    }

    .slider-btn.prev {
        left: 0.5rem;
    }

    .slider-btn.next {
        right: 0.5rem;
    }

    @media (max-width: 576px) {
        .slider-btn {
            width: 36px;
            height: 36px;
        }

        .product-card {
            width: 200px;
        }
    }

</style>
@endpush

@push('scripts')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // === Produk Slider Logic ===
            const sliderWrapper = document.querySelector('.product-slider-wrapper');
            const sliderProd = document.querySelector('.product-slider');
            const nextBtn = document.querySelector('.slider-btn.next');
            const prevBtn = document.querySelector('.slider-btn.prev');
            const card = sliderProd?.querySelector('.product-card');
            const cardWidth = card ? card.offsetWidth + 16 : 276;

            if (nextBtn && prevBtn && sliderWrapper && sliderProd) {
                nextBtn.addEventListener('click', () => {
                    sliderWrapper.scrollBy({ left: cardWidth, behavior: 'smooth' });
                });

                prevBtn.addEventListener('click', () => {
                    sliderWrapper.scrollBy({ left: -cardWidth, behavior: 'smooth' });
                });

                function updateButtonVisibility() {
                    const maxScroll = sliderProd.scrollWidth - sliderWrapper.clientWidth;
                    nextBtn.style.display = sliderWrapper.scrollLeft >= maxScroll - 5 ? 'none' : 'flex';
                    prevBtn.style.display = sliderWrapper.scrollLeft <= 5 ? 'none' : 'flex';
                }

                sliderWrapper.addEventListener('scroll', updateButtonVisibility);
                window.addEventListener('resize', updateButtonVisibility);
                updateButtonVisibility();
            }

            // === SweetAlert2 Success Message ===
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
