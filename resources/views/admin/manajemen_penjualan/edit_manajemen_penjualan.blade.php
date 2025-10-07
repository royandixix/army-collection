@extends('admin.layouts.app')

@section('title', 'Edit Manajemen Penjualan')

@section('content')
<div class="cx-main-content">
    <!-- Judul halaman -->
    <div class="cx-page-title d-flex justify-content-between align-items-center flex-wrap mb-3">
        <h4 class="mb-0">Edit Transaksi Penjualan</h4>
    </div>

    <!-- Form edit penjualan -->
    <div class="cx-card card-default p-4">
        <form action="{{ route('admin.manajemen.manajemen_penjualan_update', $penjualan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Pelanggan (readonly) -->
            <div class="mb-3">
                <label for="pelanggan_id" class="form-label">Pelanggan</label>
                <select name="pelanggan_id" id="pelanggan_id" class="form-select" disabled>
                    <option value="{{ $penjualan->pelanggan_id }}">
                        {{ $penjualan->pelanggan->nama ?? 'Pelanggan' }}
                    </option>
                </select>
            </div>

            <!-- Tanggal -->
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal Transaksi</label>
                <input type="date" name="tanggal" id="tanggal" 
                       class="form-control @error('tanggal') is-invalid @enderror" 
                       value="{{ old('tanggal', $penjualan->tanggal->format('Y-m-d')) }}" required>
                @error('tanggal')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Total -->
            <div class="mb-3">
                <label for="total" class="form-label">Total (Rp)</label>
                <input type="number" name="total" id="total" 
                       class="form-control @error('total') is-invalid @enderror" 
                       value="{{ old('total', $penjualan->total) }}" required>
                @error('total')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label for="status" class="form-label">Status Pembayaran</label>
                <select name="status" id="status" 
                        class="form-select @error('status') is-invalid @enderror" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="lunas" {{ old('status', $penjualan->status) == 'lunas' ? 'selected' : '' }}>LUNAS</option>
                    <option value="pending" {{ old('status', $penjualan->status) == 'pending' ? 'selected' : '' }}>PENDING</option>
                    <option value="batal" {{ old('status', $penjualan->status) == 'batal' ? 'selected' : '' }}>BATAL</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Metode Pembayaran -->
            <div class="mb-3">
                <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                <select name="metode_pembayaran" id="metode_pembayaran" 
                        class="form-select @error('metode_pembayaran') is-invalid @enderror" required>
                    <option value="">-- Pilih Metode Pembayaran --</option>
                    <option value="cod" {{ old('metode_pembayaran', $penjualan->metode_pembayaran) == 'cod' ? 'selected' : '' }}>COD</option>
                    <option value="qris" {{ old('metode_pembayaran', $penjualan->metode_pembayaran) == 'qris' ? 'selected' : '' }}>QIRIS</option>
                    <option value="transfer" {{ old('metode_pembayaran', $penjualan->metode_pembayaran) == 'transfer' ? 'selected' : '' }}>Transfer</option>
                </select>
                @error('metode_pembayaran')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Tombol Submit -->
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="ri-check-line me-1"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.manajemen.manajemen_penjualan') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: '#f9f9f9',
        color: '#1e293b',
        iconColor: '#10b981',
        customClass: {
            popup: 'rounded-xl shadow-md text-sm px-4 py-3 mt-4 border border-gray-200'
        },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
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
</script>
@endpush
