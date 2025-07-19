@extends('user.layouts.app')

@section('title', 'Halaman Checkout')

@section('content')
<div class="container mt-5">
    <h4 class="mb-4 fw-semibold text-success">Halaman Checkout</h4>

    @if($keranjangs->isEmpty())
        <div class="alert alert-warning text-center">
            <i class="bi bi-exclamation-triangle-fill fs-4 text-warning"></i><br>
            Keranjang Anda kosong.
        </div>
    @else
        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle shadow-sm">
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

        {{-- Form checkout (alamat & metode pembayaran) --}}
        <form action="{{ route('user.checkout.proses') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat Pengiriman</label>
                <textarea name="alamat" id="alamat" class="form-control" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="metode" class="form-label">Metode Pembayaran</label>
                <select name="metode" id="metode" class="form-select" required>
                    <option value="">-- Pilih Metode Pembayaran --</option>
                    <option value="cod">Bayar di Tempat (COD)</option>
                    <option value="transfer">Transfer Bank</option>
                </select>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-success fw-semibold">
                    <i class="bi bi-credit-card"></i> Proses Checkout
                </button>
            </div>
        </form>
    @endif
</div>
@endsection

@push('style')
<style>
    .table th, .table td {
        vertical-align: middle !important;
    }

    .table th {
        background-color: #f0fdf4;
    }
</style>
@endpush
