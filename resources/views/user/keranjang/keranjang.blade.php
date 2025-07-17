@extends('user.layouts.app')

@section('title', 'Halaman Keranjang')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4 fw-semibold text-success">Keranjang Belanja</h4>

    @if($keranjang->isEmpty())
        <div class="alert alert-info text-center">
            <i class="bi bi-cart-x fs-4"></i><br>
            Keranjang kamu masih kosong.
        </div>
    @else
        <form action="{{ route('user.checkout') }}" method="POST">
            @csrf
            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="keranjangTable">
                    <thead class="table-success text-center">
                        <tr>
                            <th>#</th>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach($keranjang as $index => $item)
                            @php
                                $subtotal = $item->produk->harga * $item->jumlah;
                                $total += $subtotal;
                            @endphp
                            <tr data-id="{{ $item->id }}">
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ asset('storage/' . $item->produk->gambar) }}" alt="{{ $item->produk->nama }}" width="60" class="rounded shadow-sm">
                                        <div>
                                            <div class="fw-semibold">{{ $item->produk->nama }}</div>
                                            <small class="text-muted">{{ $item->produk->kategori }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end harga" data-harga="{{ $item->produk->harga }}">
                                    Rp {{ number_format($item->produk->harga, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <div class="input-group justify-content-center" style="max-width: 120px; margin: auto;">
                                        <button type="button" class="btn btn-sm btn-outline-secondary kurang">-</button>
                                        <input type="text" name="jumlah[{{ $item->id }}]" class="form-control form-control-sm text-center jumlah" value="{{ $item->jumlah }}" min="1">
                                        <button type="button" class="btn btn-sm btn-outline-secondary tambah">+</button>
                                    </div>
                                </td>
                                <td class="text-end subtotal" data-subtotal="{{ $subtotal }}">
                                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('user.keranjang.hapus', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus item ini dari keranjang?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Hapus item"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="4" class="text-end">Total</th>
                            <th class="text-end" id="grandTotal">Rp {{ number_format($total, 0, ',', '.') }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-success px-4 py-2 fw-semibold shadow-sm">
                    <i class="bi bi-bag-check"></i> Checkout
                </button>
            </div>
        </form>
    @endif
</div>
@endsection

@push('style')
<style>
    table th,
    table td {
        vertical-align: middle !important;
    }

    .table th {
        background-color: #f0fdf4;
    }

    .table img {
        object-fit: cover;
        height: 60px;
        width: 60px;
    }

    .input-group input {
        width: 40px;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const formatRupiah = (angka) => {
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    };

    const updateSubtotal = (row) => {
        const harga = parseInt(row.querySelector('.harga').dataset.harga);
        const jumlahInput = row.querySelector('.jumlah');
        let jumlah = parseInt(jumlahInput.value);

        if (isNaN(jumlah) || jumlah < 1) {
            jumlah = 1;
            jumlahInput.value = 1;
        }

        const subtotal = harga * jumlah;
        row.querySelector('.subtotal').innerText = formatRupiah(subtotal);
        row.querySelector('.subtotal').dataset.subtotal = subtotal;

        updateTotal();
    };

    const updateTotal = () => {
        let total = 0;
        document.querySelectorAll('.subtotal').forEach((el) => {
            total += parseInt(el.dataset.subtotal);
        });
        document.getElementById('grandTotal').innerText = formatRupiah(total);
    };

    document.querySelectorAll('.tambah').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const row = e.target.closest('tr');
            const input = row.querySelector('.jumlah');
            input.value = parseInt(input.value) + 1;
            updateSubtotal(row);
        });
    });

    document.querySelectorAll('.kurang').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const row = e.target.closest('tr');
            const input = row.querySelector('.jumlah');
            input.value = Math.max(1, parseInt(input.value) - 1);
            updateSubtotal(row);
        });
    });

    document.querySelectorAll('.jumlah').forEach(input => {
        input.addEventListener('input', (e) => {
            const row = e.target.closest('tr');
            updateSubtotal(row);
        });
    });
});
</script>
@endpush
