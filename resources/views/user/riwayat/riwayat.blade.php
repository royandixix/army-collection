@extends('user.layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="container py-5 animate__animated animate__fadeInUp animate__faster">

    {{-- HEADER --}}
    <div class="mb-4 d-flex align-items-center gap-3">
        <i class="bi bi-receipt-cutoff fs-2 text-primary"></i>
        <h2 class="fw-bold m-0">Riwayat Transaksi</h2>
    </div>

    {{-- TIDAK ADA TRANSAKSI --}}
    @if($transaksis->isEmpty())
        <div class="alert alert-light text-center py-5 shadow-sm rounded">
            <i class="bi bi-inbox fs-1 text-secondary"></i>
            <p class="fs-5 mt-3 text-muted">Belum ada riwayat transaksi.</p>
            <a href="{{ route('user.produk.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-cart me-1"></i> Mulai Belanja
            </a>
        </div>
    @else

        {{-- SEARCH BAR --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="searchInput" class="form-control border-start-0" 
                           placeholder="Cari tanggal transaksi...">
                </div>
            </div>
        </div>

        {{-- LIST TRANSAKSI --}}
        <div class="row gy-4">
            @foreach($transaksis as $transaksi)
                <div class="col-md-6 riwayat-item"
                     data-tanggal="{{ optional($transaksi->penjualan)->tanggal 
                        ? strtolower(\Carbon\Carbon::parse($transaksi->penjualan->tanggal)->translatedFormat('d F Y')) 
                        : '' }}">

                    <div class="card shadow-sm border-0 rounded-4 h-100 overflow-hidden">
                        <div class="card-body">

                            {{-- TANGGAL --}}
                            <div class="mb-3 pb-3 border-bottom">
                                <h6 class="fw-semibold mb-1">
                                    <i class="bi bi-calendar-event text-primary me-1"></i>
                                    {{ optional($transaksi->penjualan)->tanggal 
                                        ? \Carbon\Carbon::parse($transaksi->penjualan->tanggal)->translatedFormat('l, d F Y') 
                                        : '-' }}
                                </h6>
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ optional($transaksi->penjualan)->tanggal 
                                        ? \Carbon\Carbon::parse($transaksi->penjualan->tanggal)->format('H:i') 
                                        : '-' }} WIB
                                </small>
                            </div>

                            {{-- DETAIL PRODUK --}}
                            <div class="mb-3">
                                @foreach($transaksi->detailTransaksi as $item)
                                    <div class="d-flex justify-content-between align-items-start py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                        <div class="flex-grow-1">
                                            <strong class="d-block">{{ $item->produk->nama ?? '-' }}</strong>
                                            <small class="text-muted">
                                                {{ $item->jumlah }} x Rp {{ number_format($item->harga, 0, ',', '.') }}
                                            </small>
                                        </div>
                                        <div class="fw-semibold text-nowrap ms-3">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- ALAMAT --}}
                            <div class="mb-2">
                                <small class="text-muted d-block mb-1">
                                    <i class="bi bi-geo-alt me-1"></i> Alamat Pengiriman
                                </small>
                                <div class="small">{{ $transaksi->alamat }}</div>
                            </div>

                            {{-- METODE PEMBAYARAN --}}
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">
                                    <i class="bi bi-credit-card me-1"></i> Metode Pembayaran
                                </small>
                                <span class="badge bg-primary-subtle text-primary text-uppercase">{{ $transaksi->metode }}</span>
                            </div>

                            {{-- BUKTI PEMBAYARAN --}}
                            <div class="mb-3">
                                <small class="text-muted d-block mb-2">
                                    <i class="bi bi-receipt me-1"></i> Bukti Pembayaran
                                </small>

                                @if($transaksi->bukti_tf)
                                    {{-- Tombol lihat bukti jika sudah ada upload --}}
                                    <button class="btn btn-sm btn-outline-primary viewBuktiBtn" 
                                            data-image="{{ asset('storage/'.$transaksi->bukti_tf) }}">
                                        <i class="bi bi-eye me-1"></i> Lihat Bukti
                                    </button>

                                @elseif($transaksi->metode === 'transfer')
                                    {{-- Form upload bukti transfer --}}
                                    <form action="{{ route('user.riwayat.upload', $transaksi->id) }}" method="POST" enctype="multipart/form-data" class="text-center mt-2">
                                        @csrf
                                        <img src="{{ asset('images/bank/transfer.webp') }}" class="img-fluid mb-2" style="max-width:200px;">
                                        <div><strong>No. Rek:</strong> <span class="nomorRek">218101004389533</span></div>
                                        <button type="button" class="btn btn-sm btn-outline-primary mt-1 copyRekBtn">
                                            <i class="bi bi-clipboard"></i> Salin No. Rek
                                        </button>
                                        <div class="mt-2">
                                            <input type="file" name="bukti_tf" required>
                                            <button type="submit" class="btn btn-sm btn-warning mt-1">
                                                <i class="bi bi-cloud-upload me-1"></i> Upload Bukti
                                            </button>
                                        </div>
                                    </form>

                                @elseif($transaksi->metode === 'qris')
                                    {{-- Form upload bukti QRIS --}}
                                    <form action="{{ route('user.riwayat.upload', $transaksi->id) }}" method="POST" enctype="multipart/form-data" class="text-center mt-2">
                                        @csrf
                                        <img src="{{ asset('images/qiris/qr_123.jpeg') }}" class="img-fluid mb-2" style="max-width:200px;">
                                        <div>Scan QRIS untuk bayar</div>
                                        <div class="mt-2">
                                            <input type="file" name="bukti_tf" required>
                                            <button type="submit" class="btn btn-sm btn-warning mt-1">
                                                <i class="bi bi-cloud-upload me-1"></i> Upload Bukti
                                            </button>
                                        </div>
                                    </form>

                                @else
                                    <span class="badge bg-success-subtle text-success">
                                        <i class="bi bi-cash me-1"></i> Bayar di Tempat
                                    </span>
                                @endif
                            </div>

                            {{-- TOTAL --}}
                            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                <span class="fw-semibold">Total Pembayaran</span>
                                <h5 class="fw-bold text-primary m-0">
                                    Rp {{ number_format(optional($transaksi->penjualan)->total, 0, ',', '.') }}
                                </h5>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- MODAL VIEW BUKTI --}}
<div class="modal fade" id="viewImageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Bukti Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="viewImage" class="img-fluid w-100" alt="Bukti Pembayaran">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {

    // Lihat bukti pembayaran sesuai transaksi
    $('.viewBuktiBtn').on('click', function() {
        const img = $(this).data('image'); // ambil dari data-image
        $('#viewImage').attr('src', img);
        new bootstrap.Modal($('#viewImageModal')).show();
    });

    // Copy nomor rekening
    $('.copyRekBtn').on('click', function() {
        const rek = $(this).siblings('.nomorRek').text();
        navigator.clipboard.writeText(rek)
            .then(() => alert('Nomor rekening berhasil disalin!'))
            .catch(() => alert('Gagal menyalin nomor rekening.'));
    });

    // Search filter berdasarkan tanggal
    $('#searchInput').on('input', function() {
        const term = $(this).val().toLowerCase();
        $('.riwayat-item').each(function() {
            const tanggal = $(this).data('tanggal');
            $(this).toggle(tanggal.includes(term));
        });
    });

    // Notifikasi berhasil upload bukti
    @if(session('success'))
        alert('{{ session("success") }}');
    @endif

});
</script>
@endpush
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {

    // Lihat bukti pembayaran sesuai transaksi dengan loading spinner
    $('.viewBuktiBtn').on('click', function() {
        const imgSrc = $(this).data('image');
        const $img = $('#viewImage');
        $img.attr('src', '');
        
        // Tampilkan modal dengan spinner
        const modal = new bootstrap.Modal($('#viewImageModal'));
        modal.show();
        $img.after('<div class="spinner-border text-primary" id="imgLoader" role="status"><span class="visually-hidden">Loading...</span></div>');

        // Load gambar
        const img = new Image();
        img.onload = function() {
            $img.attr('src', imgSrc);
            $('#imgLoader').remove();
        }
        img.src = imgSrc;
    });

    // Copy nomor rekening dengan SweetAlert
    $('.copyRekBtn').on('click', function() {
        const rek = $(this).closest('div').find('.nomorRek').text();
        navigator.clipboard.writeText(rek)
            .then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Nomor rekening berhasil disalin.',
                    timer: 1500,
                    showConfirmButton: false
                });
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Gagal menyalin nomor rekening.',
                });
            });
    });

    // Search filter berdasarkan tanggal
    $('#searchInput').on('input', function() {
        const term = $(this).val().toLowerCase();
        $('.riwayat-item').each(function() {
            const tanggal = $(this).data('tanggal').toLowerCase();
            $(this).toggle(!term || tanggal.includes(term));
        });
    });

    // Notifikasi berhasil upload bukti dengan SweetAlert
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: '{{ session("success") }}',
            timer: 2000,
            showConfirmButton: false
        });
    @endif

});
</script>
@endpush

