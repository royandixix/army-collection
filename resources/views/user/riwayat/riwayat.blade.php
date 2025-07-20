@extends('user.layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="container mt-4 animate__animated animate__fadeInUp">
    <h4 class="mb-4 fw-bold text-primary d-flex align-items-center gap-2 animate__animated animate__fadeInDown">
        <i class="bi bi-receipt-cutoff fs-4"></i> Riwayat Transaksi
    </h4>

    @if($riwayats->isEmpty())
        <div class="alert alert-info text-center animate__animated animate__fadeIn">
            <i class="bi bi-info-circle fs-4"></i><br>
            Belum ada riwayat transaksi.
        </div>
    @else
        {{-- Search Input --}}
        <div class="row mb-4 animate__animated animate__fadeInLeft animate__delay-1s">
            <div class="col-md-6">
                <input type="text" id="searchInput" class="form-control shadow-sm rounded-3" placeholder="Cari tanggal / status transaksi...">
            </div>
        </div>

        {{-- Riwayat Cards --}}
        <div class="row gy-3" id="riwayatContainer">
            @foreach($riwayats as $i => $riwayat)
                <div class="col-md-6 animate__animated animate__fadeInUp animate__delay-2s riwayat-item" data-tanggal="{{ strtolower(\Carbon\Carbon::parse($riwayat->tanggal)->translatedFormat('d F Y')) }}" data-status="{{ strtolower($riwayat->status) }}">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold mb-0">
                                    <i class="bi bi-calendar-check me-1 text-success"></i> {{ \Carbon\Carbon::parse($riwayat->tanggal)->translatedFormat('d F Y') }}
                                </h6>
                                <span class="badge rounded-pill status-badge
                                    @if($riwayat->status == 'selesai') bg-success
                                    @elseif($riwayat->status == 'diproses') bg-warning text-dark
                                    @elseif($riwayat->status == 'batal') bg-danger
                                    @else bg-secondary
                                    @endif
                                ">
                                    <i class="bi 
                                        @if($riwayat->status == 'selesai') bi-check-circle-fill
                                        @elseif($riwayat->status == 'diproses') bi-hourglass-split
                                        @elseif($riwayat->status == 'batal') bi-x-circle-fill
                                        @else bi-info-circle
                                        @endif
                                    me-1"></i> {{ ucfirst($riwayat->status) }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="text-muted">Total Pembayaran</span>
                                <h5 class="mb-0 text-primary">Rp {{ number_format($riwayat->total, 0, ',', '.') }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@push('style')
<!-- Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    .status-badge {
        font-size: 0.85rem;
        padding: 6px 12px;
        transition: all 0.3s ease;
    }

    .status-badge:hover {
        transform: scale(1.05);
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }

    #searchInput {
        padding: 10px 14px;
        font-size: 0.95rem;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('searchInput');
        const items = document.querySelectorAll('.riwayat-item');

        searchInput.addEventListener('keyup', function () {
            const filter = this.value.toLowerCase();

            items.forEach(item => {
                const tanggal = item.getAttribute('data-tanggal');
                const status = item.getAttribute('data-status');

                if (tanggal.includes(filter) || status.includes(filter)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
</script>
@endpush
