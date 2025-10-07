@extends('user.layouts.app')

@section('title', 'Keranjang & Checkout')

@section('content')
<div class="container py-5 animate__animated animate__fadeInUp animate__faster">

    <div class="mb-4 d-flex align-items-center gap-3">
        <i class="bi bi-cart-check-fill fs-2 text-primary"></i>
        <h2 class="fw-bold m-0">Keranjang & Checkout</h2>
    </div>

    @if ($keranjang->isEmpty())
        <div class="alert alert-light text-center py-5 shadow-sm rounded">
            <i class="bi bi-cart-x fs-1 text-secondary"></i>
            <p class="fs-5 mt-3 text-muted">Keranjang kamu masih kosong.</p>
            <a href="{{ route('user.produk.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-1"></i> Mulai Belanja
            </a>
        </div>
    @else
        <form id="checkoutForm" action="{{ route('user.keranjang.checkout') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- List Keranjang --}}
            <ul class="list-group shadow-sm rounded animate__animated animate__fadeIn animate__fast mb-4">
                @php $total = 0; @endphp
                @foreach ($keranjang as $item)
                    @php
                        $subtotal = $item->produk->harga * $item->jumlah;
                        $total += $subtotal;
                    @endphp
                    <li class="list-group-item d-flex flex-wrap align-items-center justify-content-between py-3" data-id="{{ $item->id }}">
                        <div class="d-flex align-items-center gap-3">
                            <!-- Checkbox Pilihan Produk -->
                            <input type="checkbox" name="pilih[]" value="{{ $item->id }}" class="form-check-input pilih-item me-2">

                            <img src="{{ $item->produk->gambar ? asset('storage/' . $item->produk->gambar) : asset('images/no-image.png') }}"
                                alt="{{ $item->produk->nama }}" class="rounded"
                                style="width: 80px; height: 80px; object-fit: cover;">
                            <div>
                                <h6 class="mb-1">{{ $item->produk->nama }}</h6>
                                <small class="text-muted">{{ $item->produk->kategori->name ?? '-' }}</small>
                                <div class="fw-semibold text-primary mt-1">
                                    Rp {{ number_format($item->produk->harga, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2 mt-3 mt-md-0">
                            <div class="input-group input-group-sm" style="width: 120px;">
                                <button type="button" class="btn btn-outline-secondary kurang">−</button>
                                <input type="text" name="jumlah[{{ $item->id }}]" class="form-control text-center jumlah" value="{{ $item->jumlah }}">
                                <button type="button" class="btn btn-outline-secondary tambah">+</button>
                            </div>
                            <div class="text-muted ms-3 subtotal" data-subtotal="{{ $subtotal }}">
                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                            </div>
                            <button type="button" class="btn btn-outline-danger btn-sm btn-hapus ms-3" data-id="{{ $item->id }}">
                                <i class="bi bi-cart-x-fill"></i>
                            </button>
                        </div>
                    </li>
                @endforeach
            </ul>

            {{-- Total --}}
            <div class="d-flex justify-content-end align-items-center gap-3 flex-wrap mb-4">
                <h4 class="m-0">Total:</h4>
                <h4 class="text-primary fw-bold mb-0" id="grandTotal">Rp 0</h4>
            </div>

            {{-- Alamat --}}
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat Pengiriman</label>
                <textarea name="alamat" id="alamat" class="form-control custom-input" rows="3" placeholder="Masukkan alamat lengkap..." required>{{ old('alamat') }}</textarea>
                <small class="text-muted d-block mt-1">📍 Lokasi Anda akan diisi otomatis jika diizinkan.</small>
                @error('alamat')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Metode Pembayaran --}}
            <div class="mb-3">
                <label for="metode" class="form-label">Metode Pembayaran</label>
                <select name="metode" id="metode" class="form-select custom-input" required onchange="toggleMetode()">
                    <option value="">-- Pilih Metode Pembayaran --</option>
                    <option value="cod" {{ old('metode') == 'cod' ? 'selected' : '' }}>Bayar di Tempat (COD)</option>
                    <option value="transfer" {{ old('metode') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                    <option value="qris" {{ old('metode') == 'qris' ? 'selected' : '' }}>QRIS</option>
                </select>
                @error('metode')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Preview metode pembayaran --}}
            <div id="metodeContainer" class="mb-4 text-center" style="display:none;">
                <img id="metodeImage" src="" alt="Metode Pembayaran" class="img-fluid rounded shadow" style="max-width: 250px;">
                <p id="metodeText" class="text-muted mt-2 small"></p>
            </div>

            {{-- Upload Bukti Transfer --}}
            <div id="buktiContainer" style="display:none;">
                <div class="mb-3">
                    <label for="bukti_tf" class="form-label">Upload Bukti Transfer</label>
                    <input type="file" name="bukti_tf" id="bukti_tf" class="form-control custom-input" accept="image/*">
                    <small class="text-muted">Upload screenshot / foto bukti pembayaran (jpg, png, max 2MB)</small>
                    @error('bukti_tf')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn checkout-btn">
                    <span class="spinner-border spinner-border-sm me-2 d-none" id="loadingSpinner"></span>
                    Proses Checkout
                </button>
            </div>
        </form>
    @endif
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<style>
    .checkout-btn {
        background: linear-gradient(135deg, #0d6efd, #3f83f8);
        color: #fff;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .checkout-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
    }

    .custom-input {
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s ease-in-out;
    }

    .custom-input:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
        transition: background 0.2s ease-in-out;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function formatRupiah(val) {
    return 'Rp ' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function toggleMetode() {
    const metode = $('#metode').val();
    const container = $('#metodeContainer');
    const img = $('#metodeImage');
    const text = $('#metodeText');
    const bukti = $('#buktiContainer');
    const copyBtn = $('#copyRekBtn');

    if (metode === 'qris') {
        container.show();
        img.attr('src', "{{ asset('images/qiris/qr_123.jpeg') }}");
        text.html(`
            <strong>Scan QRIS</strong> dan upload bukti pembayaran.<br>
            <small>QR berlaku untuk Dana, OVO, Gopay, dll.</small>
        `);
        bukti.show();
        copyBtn.hide();
    } else if (metode === 'transfer') {
        container.show();
        img.attr('src', "{{ asset('images/bank/transfer.webp') }}");
        text.html(`
            <strong>Transfer ke Bank BRI</strong><br>
            <ul>
                <li>No. Rekening: <span id="nomorRek">218101004389533 BRI</span></li>
                <li>Army Collection</li>
            </ul>
            Upload bukti pembayaran setelah transfer.
        `);
        bukti.show();
        copyBtn.show();
    } else {
        container.hide();
        bukti.hide();
        copyBtn.hide();
    }
}

$(function() {
    toggleMetode();
    $('#metode').change(toggleMetode);

    // Tombol copy nomor rekening
    $('#copyRekBtn').click(function() {
        const rek = $('#nomorRek').text();
        navigator.clipboard.writeText(rek).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Nomor rekening berhasil dicopy.'
            });
        }).catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Tidak dapat menyalin nomor rekening.'
            });
        });
    });

    // Update subtotal & total
    function updateSubtotal(li, qty) {
        const id = li.data('id');
        $.post("{{ route('user.keranjang.update') }}", {
            _token: '{{ csrf_token() }}',
            id,
            jumlah: qty
        })
        .done(function(res) {
            li.find('.jumlah').val(qty);
            li.find('.subtotal').data('subtotal', res.subtotal)
                .text(formatRupiah(res.subtotal));
            updateTotal();
        })
        .fail(function() {
            Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Tidak dapat memperbarui jumlah produk.' });
        });
    }

    function updateTotal() {
        let total = 0;
        $('.pilih-item:checked').each(function() {
            const li = $(this).closest('[data-id]');
            total += parseInt(li.find('.subtotal').data('subtotal'));
        });
        $('#grandTotal').text(formatRupiah(total));
    }

    $('.pilih-item').on('change', updateTotal);
    $('.tambah').click(function() {
        const li = $(this).closest('[data-id]');
        updateSubtotal(li, parseInt(li.find('.jumlah').val()) + 1);
    });

    $('.kurang').click(function() {
        const li = $(this).closest('[data-id]');
        updateSubtotal(li, Math.max(1, parseInt(li.find('.jumlah').val()) - 1));
    });

    $('.jumlah').on('input', function() {
        const li = $(this).closest('[data-id]');
        const qty = parseInt($(this).val());
        if (!isNaN(qty) && qty >= 1) updateSubtotal(li, qty);
        else {
            $(this).addClass('animate__animated animate__shakeX');
            setTimeout(() => $(this).removeClass('animate__animated animate__shakeX'), 600);
        }
    });

    // Hapus produk dari keranjang
    $('.btn-hapus').click(function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Hapus Produk Ini?',
            text: "Produk akan dihapus dari keranjang.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/user/keranjang/" + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: () => location.reload(),
                    error: () => Swal.fire({ icon: 'error', title: 'Gagal', text: 'Produk gagal dihapus!' })
                });
            }
        });
    });

    // Geo lokasi otomatis
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(async function(position) {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;
            try {
                const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}`);
                const data = await res.json();
                if (data && data.display_name) $('#alamat').val(data.display_name);
            } catch {
                $('#alamat').attr('placeholder', 'Masukkan alamat lengkap secara manual...');
            }
        });
    }

    // Form checkout
    $('#checkoutForm').on('submit', function(e) {
        if ($('.pilih-item:checked').length === 0) {
            e.preventDefault();
            Swal.fire({ icon: 'warning', title: 'Oops!', text: 'Pilih setidaknya satu produk untuk checkout.' });
            return false;
        }
        $('.pilih-item:not(:checked)').each(function() {
            const li = $(this).closest('[data-id]');
            li.find('input, select, textarea').prop('disabled', true);
        });
        $('#loadingSpinner').removeClass('d-none');
        $('.checkout-btn').prop('disabled', true);
    });

    @if (session('checkout_success'))
        Swal.fire({
            icon: 'success',
            title: 'Checkout Berhasil!',
            html: `<p>Pesanan berhasil diproses.</p>
                   <p><strong>Total:</strong> Rp {{ number_format(session('total'), 0, ',', '.') }}</p>
                   <p><strong>ID Pesanan:</strong> #{{ session('penjualan_id') }}</p>`,
            confirmButtonText: 'Lanjut',
            allowOutsideClick: false
        }).then(() => window.location.href = "{{ route('user.riwayat.index') }}");
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            timer: 3500,
            showConfirmButton: false
        });
    @endif
});
</script>
@endpush
