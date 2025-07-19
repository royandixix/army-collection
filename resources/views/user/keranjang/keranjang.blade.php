@extends('user.layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="mb-4">
    <h4 class="fw-semibold text-primary d-flex align-items-center gap-2">
        <i class="bi bi-cart-check"></i> Keranjang Belanja
    </h4>
</div>

@if($keranjang->isEmpty())
<div class="alert alert-light border border-dashed border-2 text-center py-5 rounded shadow-sm">
    <i class="bi bi-cart-x fs-1 text-secondary"></i>
    <p class="fs-5 mt-3 text-muted">Keranjang kamu masih kosong.</p>
    <a href="{{ route('user.produk.index') }}" class="btn btn-outline-primary fw-semibold shadow-sm transition-hover px-4 py-2">
        <i class="bi bi-arrow-left me-1"></i> Mulai Belanja
    </a>
</div>
@else
<form action="{{ route('user.checkout.proses') }}" method="POST">
    @csrf
    <div class="row g-4">
        @php $total = 0; @endphp
        @foreach($keranjang as $item)
        @php
        $subtotal = $item->produk->harga * $item->jumlah;
        $total += $subtotal;
        @endphp
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4" data-id="{{ $item->id }}">
                <div class="card-body d-flex gap-3 align-items-center">
                    <img src="{{ asset('storage/' . $item->produk->gambar) }}" class="rounded shadow-sm" style="width: 90px; height: 90px; object-fit: cover;">
                    <div class="flex-grow-1">
                        <div class="fw-bold">{{ $item->produk->nama }}</div>
                        <div class="text-muted small">{{ $item->produk->kategori->name ?? '-' }}</div>
                        <div class="text-primary fw-semibold mt-1">Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</div>

                        <div class="input-group mt-2" style="max-width: 150px;">
                            <button type="button" class="btn btn-sm btn-outline-secondary kurang">-</button>
                            <input type="text" name="jumlah[{{ $item->id }}]" class="form-control form-control-sm text-center jumlah" value="{{ $item->jumlah }}" min="1">
                            <button type="button" class="btn btn-sm btn-outline-secondary tambah">+</button>
                        </div>

                        <div class="mt-2 subtotal" data-subtotal="{{ $subtotal }}">
                            Subtotal: <strong class="text-dark">Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <form action="{{ route('user.keranjang.hapus', $item->id) }}" method="POST" onsubmit="return confirm('Hapus item ini dari keranjang?')" class="ms-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>



    <div class="mt-5 text-end">
        <h5>Total Belanja: <span class="text-primary fw-bold" id="grandTotal">Rp {{ number_format($total, 0, ',', '.') }}</span></h5>
        <button type="submit" class="btn checkout-btn">
            <i class="bi bi-bag-check-fill me-1"></i> Checkout Sekarang
        </button>
    </div>
</form>
@endif
@endsection

@push('styles')
<style>
    .transition-hover {
        transition: all 0.3s ease-in-out;
    }

    .transition-hover:hover {
        transform: scale(1.04);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }

    .checkout-btn {
        background-color: #fff;
        border: 2px solid #0d6efd;
        color: #0d6efd;
        padding: 10px 24px;
        font-weight: 600;
        font-size: 15px;
        border-radius: 10px;
        transition: all 0.3s ease-in-out;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .checkout-btn:hover {
        background-color: #0d6efd;
        color: #fff;
        transform: scale(1.03);
        box-shadow: 0 0.5rem 1rem rgba(13, 110, 253, 0.2);
    }

    .btn-outline-secondary:hover,
    .btn-outline-danger:hover {
        background-color: #f1f1f1;
        color: #000;
    }

</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        function formatRupiah(angka) {
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function updateSubtotal(row, jumlahBaru) {
            let id = row.data('id');
            $.ajax({
                url: "{{ route('user.keranjang.update') }}"
                , method: 'POST'
                , data: {
                    _token: '{{ csrf_token() }}'
                    , id: id
                    , jumlah: jumlahBaru
                }
                , success: function(res) {
                    row.find('.jumlah').val(jumlahBaru);
                    row.find('.subtotal').html(
                        'Subtotal: <strong class="text-dark">' + formatRupiah(res.subtotal) + '</strong>'
                    );
                    row.find('.subtotal').data('subtotal', res.subtotal);
                    updateTotal();
                }
            });
        }

        function updateTotal() {
            let total = 0;
            $('.subtotal').each(function() {
                total += parseInt($(this).data('subtotal'));
            });
            $('#grandTotal').text(formatRupiah(total));
        }

        $('.tambah').click(function() {
            let row = $(this).closest('.card');
            let input = row.find('.jumlah');
            let jumlah = parseInt(input.val()) + 1;
            updateSubtotal(row, jumlah);
        });

        $('.kurang').click(function() {
            let row = $(this).closest('.card');
            let input = row.find('.jumlah');
            let jumlah = Math.max(1, parseInt(input.val()) - 1);
            updateSubtotal(row, jumlah);

        });



        $('.jumlah').on('input', function() {
            let row = $(this).closest('.card');
            let jumlah = parseInt($(this).val());
            if (!isNaN(jumlah) && jumlah >= 1) {
                updateSubtotal(row, jumlah);
            }
        });
    });

</script>
@endpush
