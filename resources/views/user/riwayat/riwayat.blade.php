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
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Cari tanggal transaksi...">
                </div>
            </div>
        </div>

        {{-- LIST TRANSAKSI --}}
        <div class="row gy-4">
            @foreach($transaksis as $transaksi)
                <div class="col-md-6 riwayat-item" data-tanggal="{{ optional($transaksi->penjualan)->tanggal ? strtolower(\Carbon\Carbon::parse($transaksi->penjualan->tanggal)->translatedFormat('d F Y')) : '' }}">
                    <div class="card shadow-sm border-0 rounded-4 h-100 overflow-hidden">
                        <div class="card-body">

                            {{-- TANGGAL --}}
                            <div class="mb-3 pb-3 border-bottom">
                                <h6 class="fw-semibold mb-1">
                                    <i class="bi bi-calendar-event text-primary me-1"></i>
                                    {{ optional($transaksi->penjualan)->tanggal ? \Carbon\Carbon::parse($transaksi->penjualan->tanggal)->translatedFormat('l, d F Y') : '-' }}
                                </h6>
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ optional($transaksi->penjualan)->tanggal ? \Carbon\Carbon::parse($transaksi->penjualan->tanggal)->format('H:i') : '-' }} WIB
                                </small>
                            </div>

                            {{-- DETAIL PRODUK --}}
                            <div class="mb-3">
                                @foreach($transaksi->detailTransaksi as $item)
                                    <div class="d-flex justify-content-between align-items-start py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                        <div class="flex-grow-1">
                                            <strong class="d-block">{{ $item->produk->nama ?? '-' }}</strong>
                                            <small class="text-muted">{{ $item->jumlah }} x Rp {{ number_format($item->harga,0,',','.') }}</small>
                                        </div>
                                        <div class="fw-semibold text-nowrap ms-3">Rp {{ number_format($item->subtotal,0,',','.') }}</div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- ALAMAT --}}
                            <div class="mb-2">
                                <small class="text-muted d-block mb-1"><i class="bi bi-geo-alt me-1"></i> Alamat Pengiriman</small>
                                <div class="small">{{ $transaksi->alamat }}</div>
                            </div>

                            {{-- METODE PEMBAYARAN --}}
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1"><i class="bi bi-credit-card me-1"></i> Metode Pembayaran</small>
                                <span class="badge bg-primary-subtle text-primary text-uppercase">{{ $transaksi->metode }}</span>
                            </div>

                            {{-- BUKTI PEMBAYARAN --}}
                            @if($transaksi->bukti_tf)
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1"><i class="bi bi-receipt me-1"></i> Bukti Pembayaran</small>
                                    <img src="{{ asset('storage/'.$transaksi->bukti_tf) }}" class="img-fluid rounded shadow-sm" style="max-width:100%;">
                                </div>
                            @elseif($transaksi->metode === 'transfer' || $transaksi->metode === 'qris')
                                <form action="{{ route('user.riwayat.upload', $transaksi->id) }}" method="POST" enctype="multipart/form-data" class="mb-3">
                                    @csrf
                                    <input type="file" name="bukti_tf" required>
                                    <button type="submit" class="btn btn-sm btn-warning mt-1"><i class="bi bi-cloud-upload me-1"></i> Upload Bukti</button>
                                </form>
                            @endif

                            {{-- BUKTI DITERIMA --}}
                            @if(optional($transaksi->penjualan)?->status_pengiriman && in_array(optional($transaksi->penjualan)->status_pengiriman, ['diterima','selesai']))
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1"><i class="bi bi-check2-circle me-1"></i> Bukti Diterima</small>
                                    @if(optional($transaksi->penjualan)->bukti_diterima)
                                        <img src="{{ asset('storage/'.optional($transaksi->penjualan)->bukti_diterima) }}" class="img-fluid rounded shadow-sm" style="max-width:100%;">
                                    @else
                                        <form action="{{ route('user.riwayat.upload_diterima', optional($transaksi->penjualan)->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="file" name="bukti_diterima" required>
                                            <button type="submit" class="btn btn-sm btn-success mt-1"><i class="bi bi-cloud-upload me-1"></i> Upload Bukti Diterima</button>
                                        </form>
                                    @endif
                                </div>
                            @endif

                            {{-- TOTAL --}}
                            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                <span class="fw-semibold">Total Pembayaran</span>
                                <h5 class="fw-bold text-primary m-0">Rp {{ number_format(optional($transaksi->penjualan)->total,0,',','.') }}</h5>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function(){

    // Copy nomor rekening
    $('.copyRekBtn').click(function(){
        const rek = $(this).closest('div').find('.nomorRek').text();
        navigator.clipboard.writeText(rek)
            .then(()=>Swal.fire({icon:'success',title:'Berhasil!',text:'Nomor rekening berhasil disalin.',timer:1500,showConfirmButton:false}))
            .catch(()=>Swal.fire({icon:'error',title:'Gagal!',text:'Gagal menyalin nomor rekening.'}));
    });

    // Search filter
    $('#searchInput').on('input',function(){
        const term = $(this).val().toLowerCase();
        $('.riwayat-item').each(function(){
            const tanggal = $(this).data('tanggal').toLowerCase();
            $(this).toggle(!term || tanggal.includes(term));
        });
    });

    // Notifikasi sukses
    @if(session('success'))
        Swal.fire({icon:'success',title:'Sukses!',text:'{{ session("success") }}',timer:2000,showConfirmButton:false});
    @endif
});
</script>
@endpush
