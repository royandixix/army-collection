@extends('admin.layouts.app')

@section('title', 'Manajemen Penjualan')

@section('content')
<div class="cx-main-content">

    <div class="cx-page-title d-flex justify-content-between align-items-center flex-wrap mb-3">
        <h4 class="mb-0">Tambah Transaksi Penjualan Manual</h4>
        <button class="btn btn-sm btn-light" id="btnRefresh"><i class="ri-refresh-line"></i> Refresh</button>
    </div>

    <div class="cx-card card-default p-4 mb-4">
        <h5 class="mb-3">Input Data Pelanggan & Transaksi</h5>
        <form action="{{ route('admin.manajemen.manajemen_penjualan_store_manual') }}" method="POST" class="row g-3 align-items-end">
            @csrf

            {{-- Input Nama Pelanggan --}}
            <div class="col-md-3">
                <label for="nama" class="form-label">Nama Pelanggan</label>
                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required placeholder="Masukkan nama pelanggan">
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input Email Pelanggan --}}
            <div class="col-md-3">
                <label for="email" class="form-label">Email Pelanggan</label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="Masukkan email pelanggan">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input Tanggal Transaksi --}}
            <div class="col-md-2">
                <label for="tanggal" class="form-label">Tanggal Transaksi</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal') ?? date('Y-m-d') }}" required>
                @error('tanggal')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input Total --}}
            <div class="col-md-2">
                <label for="total" class="form-label">Total (Rp)</label>
                <input type="number" name="total" id="total" class="form-control @error('total') is-invalid @enderror" placeholder="Masukkan total pembayaran" value="{{ old('total') }}" required>
                @error('total')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input Status Pembayaran --}}
            <div class="col-md-2">
                <label for="status" class="form-label">Status Pembayaran</label>
                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="lunas" {{ old('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="batal" {{ old('status') == 'batal' ? 'selected' : '' }}>Batal</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input Metode Pembayaran --}}
            <div class="col-md-3">
                <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                <select name="metode_pembayaran" id="metode_pembayaran" class="form-select @error('metode_pembayaran') is-invalid @enderror" required>
                    <option value="">-- Pilih Metode Pembayaran --</option>
                    <option value="cod" {{ old('metode_pembayaran') == 'cod' ? 'selected' : '' }}>Cash On Delivery (COD)</option>
                    <option value="qris" {{ old('metode_pembayaran') == 'qris' ? 'selected' : '' }}>QRIS</option>
                    <option value="transfer" {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                </select>
                @error('metode_pembayaran')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tombol Simpan --}}
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="ri-check-line me-1"></i> Simpan Transaksi
                </button>
            </div>
        </form>
    </div>

    {{-- Tabel data penjualan (optional) --}}
    {{-- ... --}}
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Notifikasi dan refresh
    const Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: '#fff',
        color: '#333',
        iconColor: '#4f46e5',
        customClass: { popup: 'rounded-xl shadow-md text-sm px-4 py-3 mt-4' },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    @if(session('success'))
        Toast.fire({ icon: 'success', title: @js(session('success')) });
    @endif

    @if(session('error'))
        Toast.fire({ icon: 'error', title: @js(session('error')) });
    @endif

    @if($errors->any())
        Toast.fire({ icon: 'warning', title: 'Periksa form kamu', text: @js($errors->first()) });
    @endif

    document.getElementById('btnRefresh').addEventListener('click', function(){
        location.reload();
    });
</script>
@endpush
