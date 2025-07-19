@extends('user.layouts.app')

@section('title', 'Halaman Checkout')

@section('content')
< class="container mt-5 animate__animated animate__fadeInUp">
    <h4 class="mb-4 fw-bold text-success d-flex align-items-center gap-2 animate__animated animate__fadeInDown">
        <i class="bi bi-bag-check-fill fs-3 text-success"></i>
        <span class="fs-3">Checkout</span>
    </h4>

    @if($keranjangs->isEmpty())
        <div class="alert alert-warning text-center animate__animated animate__fadeIn">
            <i class="bi bi-exclamation-triangle-fill fs-4 text-warning"></i><br>
            Keranjang Anda kosong.
        </div>
    @else
        <div class="table-responsive mb-4 animate__animated animate__fadeIn animate__delay-1s">
            <table class="table table-bordered align-middle shadow-sm rounded overflow-hidden">
                <thead class="table-success text-center">
                    <tr>
                        <th>Produk</th>
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
                        <tr>
                            <td>{{ $item->produk->nama }}</td>
                            <td class="text-end">Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $item->jumlah }}</td>
                            <td class="text-end">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th colspan="3" class="text-end">Total</th>
                        <th class="text-end text-success fs-5">Rp {{ number_format($total, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="conte-card no-underline alert-info mdi-kickstarter">
            <div class=" after:">
                <script>
                    const data = 1;
                    const x = 10;
                    const y = 5;
                </script>
            </div>
        </div>
        <div class=" main-bg">
            <div class="main=bg *:">
                <di class=" contents bg-inherit mdi-image-auto-adjust">
                    <p class=" main-bg " style=" color : red ">
                        halo halaman keranjang
                    </p>
                </di>
            </div>
        </div>

        {{-- Form checkout --}}
        <div class="card shadow-sm border-0 rounded-4 animate__animated animate__fadeInUp animate__delay-2s">
            <div class="card-body">
                <form action="{{ route('user.checkout.proses') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="alamat" class="form-label fw-semibold">Alamat Pengiriman</label>
                        <textarea name="alamat" id="alamat" class="form-control rounded-3" rows="3" placeholder="Masukkan alamat lengkap..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="metode" class="form-label fw-semibold">Metode Pembayaran</label>
                        <select name="metode" id="metode" class="form-select rounded-3" required>
                            <option value="">-- Pilih Metode Pembayaran --</option>
                            <option value="cod">Bayar di Tempat (COD)</option>
                            <option value="transfer">Transfer Bank</option>
                        </select>
                    </div>
                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-success fw-semibold px-4 py-2 checkout-btn">
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
<!-- Animate.css CDN for Animations -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    .table th, .table td {
        vertical-align: middle !important;
    }

    .table th {
        background-color: #f0fdf4;
    }

    .checkout-btn {
        transition: all 0.3s ease-in-out;
        box-shadow: 0 4px 10px rgba(0, 128, 0, 0.15);
        border-radius: 10px;
        font-size: 16px;
    }

    .checkout-btn:hover {
        transform: scale(1.03);
        box-shadow: 0 6px 14px rgba(0, 128, 0, 0.25);
    }

    .form-control,
    .form-select {
        border-radius: 8px;
    }

    textarea::placeholder {
        color: #adb5bd;
        font-style: italic;
    }

    .animate__delay-1s {
        animation-delay: 0.3s;
    }

    .animate__delay-2s {
        animation-delay: 0.6s;
    }
</style>
@endpush
