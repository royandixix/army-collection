@extends('admin.layouts.app')

@section('title', 'Tambah Manajemen Penjualan')

@section('content')
<div class="cx-main-content">
    <!-- Judul halaman -->
    <div class="cx-page-title d-flex justify-content-between align-items-center flex-wrap mb-3">
        <h4 class="mb-0">Tambah Transaksi Penjualan</h4>
        <a href="{{ route('admin.manajemen.manajemen_penjualan') }}" class="btn btn-sm btn-secondary">← Kembali</a>
    </div>

    <!-- Form tambah penjualan -->
    <div class="cx-card card-default p-4">
        <form action="{{ route('admin.manajemen.manajemen_penjualan_store') }}" method="POST">
            @csrf

            <!-- Pelanggan -->
            <div class="mb-3">
                <label for="pelanggan_id" class="form-label">Pelanggan</label>
                <select name="pelanggan_id" id="pelanggan_id" class="form-select @error('pelanggan_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Pelanggan --</option>
                    @foreach($pelanggans as $pelanggan)
                        <option value="{{ $pelanggan->id }}" {{ old('pelanggan_id') == $pelanggan->id ? 'selected' : '' }}>
                            {{ $pelanggan->nama }} ({{ $pelanggan->email }})
                        </option>
                    @endforeach
                </select>
                @error('pelanggan_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Tanggal -->
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal Transaksi</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal') ?? date('Y-m-d') }}" required>
                @error('tanggal')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Total -->
            <div class="mb-3">
                <label for="total" class="form-label">Total (Rp)</label>
                <input type="number" name="total" id="total" class="form-control @error('total') is-invalid @enderror" placeholder="Masukkan total pembayaran" value="{{ old('total') }}" required>
                @error('total')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-3">
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

            <!-- Tombol Submit -->
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="ri-check-line me-1"></i> Simpan Transaksi
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
    // Format harga ke format Rupiah
    document.addEventListener("DOMContentLoaded", function () {
        const priceInput = document.getElementById('price');
        if (priceInput) {
            priceInput.addEventListener('input', function (e) {
                let angka = e.target.value.replace(/\D/g, '');
                if (angka.length > 9) angka = angka.substring(0, 9);
                e.target.value = new Intl.NumberFormat('id-ID').format(angka);
            });
        }
    });

    // Inisialisasi DataTable
    $(document).ready(function () {
        $('#produk-table').DataTable();
    });

    // Toast Notification
    const Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: '#fff',
        color: '#333',
        iconColor: '#4f46e5',
        customClass: {
            popup: 'rounded-xl shadow-md text-sm px-4 py-3 mt-4'
        },
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

    // Hapus produk dengan konfirmasi
    $('.delete-btn').on('click', function (e) {
        e.preventDefault();
        const id = $(this).data('id');

        Swal.fire({
            title: 'Hapus Produk?',
            text: "Tindakan ini tidak dapat dibatalkan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `/admin/manajemen/produk/${id}/delete`;
            }
        });
    });
</script>
@endpush
