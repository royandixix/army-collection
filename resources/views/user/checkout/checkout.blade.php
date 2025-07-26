@extends('user.layouts.app')

@section('title', 'Halaman Checkout')

@section('content')
<div class="container mt-5">
    <div class="animate__animated animate__fadeInDown animate__faster">
        <h4 class="mb-4 d-flex align-items-center gap-2 text-dark">
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
                        <tr>
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
                            <td class="text-end text-primary">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th colspan="3" class="text-end">Total</th>
                        <th class="text-end text-primary fs-5">Rp {{ number_format($total, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="card shadow border-0 rounded-4">
            <div class="card-body bg-white">
                <form id="checkoutForm" action="{{ route('user.checkout.proses') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat Pengiriman</label>
                        <textarea name="alamat" id="alamat" class="form-control custom-input" rows="3" placeholder="Masukkan alamat lengkap..." required></textarea>
                        <small class="text-muted d-block mt-1">📍 Lokasi Anda akan diisi otomatis jika diizinkan.</small>
                    </div>

                    <div class="mb-3">
                        <label for="metode" class="form-label">Metode Pembayaran</label>
                        <select name="metode" id="metode" class="form-select custom-input" required onchange="toggleQRIS()">
                            <option value="">-- Pilih Metode Pembayaran --</option>
                            <option value="cod">Bayar di Tempat (COD)</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>

                    <div id="qrisContainer" class="mb-4" style="display: none;">
                        <label class="form-label">Scan QRIS</label>
                        <div class="text-center">
                            <img src="{{ asset('images/qiris/qiris.jpeg') }}" alt="QRIS" class="img-fluid rounded shadow" style="max-width: 250px;"
                                onerror="this.onerror=null;this.src='https://via.placeholder.com/250x250.png?text=QRIS';">
                            <p class="text-muted mt-2 small">Scan menggunakan e-wallet atau m-banking Anda.</p>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn checkout-btn">Proses Checkout</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection

@push('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    .table th, .table td {
        vertical-align: middle !important;
    }
    .checkout-btn {
        background-color: #4e54c8;
        color: white;
        font-weight: 400;
        padding: 6px 16px;
        font-size: 14px;
        border-radius: 6px;
        border: none;
        transition: 0.3s ease;
    }
    .checkout-btn:hover {
        background-color: #3b40a4;
        box-shadow: 0 6px 16px rgba(78, 84, 200, 0.2);
    }
    .custom-input {
        border-radius: 8px;
        font-size: 14px;
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
    @media (max-width: 576px) {
        .checkout-btn {
            width: 100%;
        }
        .table td {
            font-size: 13px;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function toggleQRIS() {
        const metode = document.getElementById('metode').value;
        document.getElementById('qrisContainer').style.display = metode === 'qris' ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', function () {
        toggleQRIS();

        // Geolokasi otomatis
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;

                fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.display_name) {
                            document.getElementById('alamat').value = data.display_name;
                        }
                    });
            }, function(error) {
                console.warn('Lokasi ditolak atau error:', error);
            });
        }

        // ✅ SweetAlert ketika proses checkout ditekan
        const form = document.getElementById('checkoutForm');
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Memproses...',
                text: 'Mohon tunggu sebentar.',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                    setTimeout(() => {
                        form.submit();
                    }, 1200);
                }
            });
        });

        // ✅ SweetAlert jika checkout berhasil (dari session)
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
        @endif
    });
</script>
@endpush
