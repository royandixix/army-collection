@extends('user.layouts.app')

@section('title', 'Halaman Checkout')

@section('content')
<div class="container mt-5">
    <div class="animate__animated animate__fadeInDown animate__faster">
        <h4 class="mb-4 fw-bold d-flex align-items-center gap-2 text-primary">
            <i class="bi bi-bag-check-fill fs-3 text-primary"></i>
            <span class="fs-3">Checkout</span>
        </h4>
    </div>

    @if($keranjangs->isEmpty())
        <div class="alert alert-warning text-center animate__animated animate__fadeIn">
            <i class="bi bi-exclamation-triangle-fill fs-4 text-warning"></i><br>
            Keranjang Anda kosong.
        </div>
    @else
        <div class="table-responsive mb-4 animate__animated animate__fadeInUp animate__delay-1s">
            <table class="table table-bordered table-hover align-middle shadow-sm rounded overflow-hidden">
                <thead class="table-light text-center text-secondary small text-uppercase">
                    <tr>
                        <th class="text-start">Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($keranjangs as $item)
                        @php
                            $subtotal = $item->produk->harga * $item->jumlah;
                            $total += $subtotal;
                        @endphp
                        <tr class="animate__animated animate__fadeInUp animate__faster">
                            <td class="d-flex align-items-center gap-3">
                                <img src="{{ asset('storage/' . $item->produk->gambar) }}" alt="{{ $item->produk->nama }}" class="rounded shadow-sm" style="width: 60px; height: 60px; object-fit: cover;">
                                <div>
                                    <div class="fw-semibold">{{ $item->produk->nama }}</div>
                                    <small class="text-muted">
                                        <i class="bi bi-tags"></i> {{ $item->produk->kategori->name ?? '-' }}
                                    </small>
                                </div>
                            </td>
                            <td class="text-end">Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $item->jumlah }}</td>
                            <td class="text-end fw-semibold text-primary">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr class="animate__animated animate__fadeInUp animate__delay-1s">
                        <th colspan="3" class="text-end">Total</th>
                        <th class="text-end text-primary fs-5">Rp {{ number_format($total, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="card shadow border-0 rounded-4 animate__animated animate__zoomIn animate__delay-2s">
            <div class="card-body bg-white">
                <form action="{{ route('user.checkout.proses') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="alamat" class="form-label fw-semibold">Alamat Pengiriman</label>
                        <textarea name="alamat" id="alamat" class="form-control custom-input" rows="3" placeholder="Masukkan alamat lengkap..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="metode" class="form-label fw-semibold">Metode Pembayaran</label>
                        <select name="metode" id="metode" class="form-select custom-input" required>
                            <option value="">-- Pilih Metode Pembayaran --</option>
                            <option value="cod">Bayar di Tempat (COD)</option>
                            <option value="transfer">Transfer Bank</option>
                        </select>
                    </div>
                    <div class="text-end mt-4">
                        <button type="submit" class="btn checkout-btn shadow-sm">
                            <i class="bi bi-credit-card me-1"></i> Proses Checkout
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection

@push('style')
<!-- Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    .table th, .table td {
        vertical-align: middle !important;
    }

    .checkout-btn {
        background: linear-gradient(135deg, #4e54c8, #8f94fb);
        color: white;
        font-weight: 600;
        padding: 0.6rem 1.6rem;
        border-radius: 12px;
        font-size: 16px;
        transition: all 0.3s ease-in-out;
        border: none;
    }

    .checkout-btn:hover {
        transform: scale(1.04);
        box-shadow: 0 8px 20px rgba(78, 84, 200, 0.3);
        opacity: 0.95;
    }

    .custom-input {
        border-radius: 10px;
        transition: all 0.2s ease-in-out;
    }

    .custom-input:focus {
        border-color: #4e54c8;
        box-shadow: 0 0 0 0.2rem rgba(142, 148, 251, 0.25);
    }

    textarea::placeholder {
        color: #adb5bd;
        font-style: italic;
    }

    .table img {
        border-radius: 10px;
    }

    @media (max-width: 576px) {
        .table td {
            font-size: 14px;
        }
        .checkout-btn {
            width: 100%;
        }
    }
</style>
@endpush
