@extends('user.layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container py-5 animate__animated animate__fadeInUp animate__faster">
  <div class="mb-4 d-flex align-items-center gap-3">
    <i class="bi bi-cart-check-fill fs-2 text-primary"></i>
    <h2 class="fw-bold m-0">Keranjang Belanja</h2>
  </div>

  @if($keranjang->isEmpty())
    <div class="alert alert-light text-center py-5 shadow-sm rounded">
      <i class="bi bi-cart-x fs-1 text-secondary"></i>
      <p class="fs-5 mt-3 text-muted">Keranjang kamu masih kosong.</p>
      <a href="{{ route('user.produk.index') }}" class="btn btn-outline-primary">
        <i class="bi bi-arrow-left me-1"></i> Mulai Belanja
      </a>
    </div>
  @else
    <form action="{{ route('user.checkout.proses') }}" method="POST">
      @csrf
      <ul class="list-group shadow-sm rounded animate__animated animate__fadeIn animate__fast">
        @php $total = 0; @endphp
        @foreach($keranjang as $item)
          @php
            $subtotal = $item->produk->harga * $item->jumlah;
            $total += $subtotal;
          @endphp
          <li class="list-group-item d-flex flex-wrap align-items-center justify-content-between py-3" data-id="{{ $item->id }}">
            <div class="d-flex align-items-center gap-3">
              <img src="{{ asset('storage/' . $item->produk->gambar) }}" alt="{{ $item->produk->nama }}" class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
              <div>
                <h6 class="mb-1">{{ $item->produk->nama }}</h6>
                <small class="text-muted">{{ $item->produk->kategori->name ?? '-' }}</small>
                <div class="fw-semibold text-primary mt-1">Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</div>
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
              <form id="form-hapus-{{ $item->id }}" action="{{ route('user.keranjang.hapus', $item->id) }}" method="POST" class="d-none">
                @csrf
                @method('DELETE')
              </form>
              <button type="button" class="btn btn-outline-danger btn-sm btn-hapus ms-3">
                <i class="bi bi-cart-x-fill"></i>
              </button>
            </div>
          </li>
        @endforeach
      </ul>

     
      <div class="d-flex justify-content-end align-items-center gap-3 mt-4 flex-wrap">
        <h4 class="m-0">Total:</h4>
        <h4 class="text-primary fw-bold mb-0" id="grandTotal">Rp {{ number_format($total, 0, ',', '.') }}</h4>
        <button type="submit" class="btn checkout-btn position-relative">
          <span class="btn-text"><i class="bi bi-bag-check-fill me-1"></i> Checkout Sekarang</span>
          <span class="spinner-border spinner-border-sm position-absolute end-0 me-3 d-none" role="status"></span>
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
  @media (max-width: 768px) {
    .checkout-btn { width: 100%; text-align: center; }
    .d-flex.justify-content-end { flex-direction: column; align-items: stretch; gap: 0.5rem; }
  }
  .swal2-popup {
    font-family: 'Inter', sans-serif;
    font-size: 14px;
  }
  .list-group-item:hover {
    background-color: #f8f9fa;
    transition: background 0.2s ease-in-out;
  }
  .input-group-sm .btn {
    font-weight: bold;
    list-style: none;
  }

  
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  $(document).on('click', '.btn-hapus', function() {
    const id = $(this).closest('[data-id]').data('id');
    Swal.fire({
      title: '<div class="fs-5 fw-semibold mb-1">Hapus Produk Ini?</div>',
      html: '<div class="text-muted small">Produk akan dihapus dari keranjang belanja kamu.</div>',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: '<i class="bi bi-cart-x me-1"></i> Hapus',
      cancelButtonText: 'Batal',
      buttonsStyling: false,
      customClass: {
        popup: 'swal2-popup shadow rounded-4 px-4 pt-4 pb-3',
        title: 'mb-0',
        confirmButton: 'btn btn-danger px-4 py-2 fw-semibold me-2',
        cancelButton: 'btn btn-outline-secondary px-4 py-2 fw-semibold ms-2',
      },
      background: '#ffffff',
      backdrop: 'rgba(0, 0, 0, 0.4)',
      iconColor: '#dc3545',
    }).then((result) => {
      if (result.isConfirmed) {
        $('#form-hapus-' + id).submit();
      }
    });
  });

  @if(session('success') || session('error'))
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: '{{ session('success') ? 'success' : 'error' }}',
      title: '{{ session('success') ?? session('error') }}',
      showConfirmButton: false,
      timer: 2500,
      timerProgressBar: true,
      background: '{{ session('success') ? "#e6ffed" : "#ffe4e6" }}',
      iconColor: '{{ session('success') ? "#16a34a" : "#dc2626" }}',
      customClass: {
        popup: 'shadow rounded-3 px-3 py-2 fw-semibold',
        title: 'fs-6 text-dark',
      }
    });
  @endif

  $(function() {
    function formatRupiah(val) {
      return 'Rp ' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function updateSubtotal(li, qty) {
      const id = li.data('id');
      $.post("{{ route('user.keranjang.update') }}", {
        _token: '{{ csrf_token() }}',
        id,
        jumlah: qty
      }, function(res) {
        li.find('.jumlah').val(qty);
        li.find('.subtotal').data('subtotal', res.subtotal).text(formatRupiah(res.subtotal));
        updateTotal();
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
      if (!isNaN(qty) && qty >= 1) {
        updateSubtotal(li, qty);
      } else {
        $(this).addClass('animate__animated animate__shakeX');
        setTimeout(() => $(this).removeClass('animate__animated animate__shakeX'), 600);
      }
    });

    $('form[action="{{ route('user.checkout.proses') }}"]').submit(function() {
      const btn = $(this).find('.checkout-btn');
      btn.prop('disabled', true);
      btn.find('.btn-text').text('Memproses...');
      btn.find('.spinner-border').removeClass('d-none');
    });
  });
</script>
@endpush
