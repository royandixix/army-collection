@extends('admin.layouts.app')

@section('title', 'Tambah Pelanggan')

@section('content')
<div class="cx-main-content">
    <!-- Header Halaman -->
    <div class="cx-page-title d-flex justify-content-between align-items-center flex-wrap mb-3">
        <h4 class="mb-0">Tambah Pelanggan</h4>
        <a href="{{ route('admin.manajemen.manajemen_pelanggan') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="ri-arrow-left-line me-1"></i> Kembali
        </a>
    </div>

    <!-- Card Form -->
    <div class="col-md-10 mx-auto">
        <div class="cx-card card-default shadow-sm border-0 rounded-4">
            <div class="cx-card-content p-4">
                <form action="{{ route('admin.manajemen.manajemen_pelanggan_store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <!-- Nama -->
                        <div class="col-md-6">
                            <label for="username" class="form-label fw-semibold">Nama Pelanggan</label>
                            <input type="text" name="username" id="username" 
                                   class="form-control form-control-lg rounded-pill @error('username') is-invalid @enderror"
                                   placeholder="Masukkan nama pelanggan..." value="{{ old('username') }}" required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" id="email" 
                                   class="form-control form-control-lg rounded-pill @error('email') is-invalid @enderror"
                                   placeholder="Masukkan email pelanggan..." value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nomor HP -->
                        <div class="col-md-6">
                            <label for="no_hp" class="form-label fw-semibold">Nomor HP</label>
                            <input type="text" name="no_hp" id="no_hp" 
                                   class="form-control form-control-lg rounded-pill @error('no_hp') is-invalid @enderror"
                                   placeholder="Masukkan nomor HP pelanggan..." value="{{ old('no_hp') }}" required>
                            @error('no_hp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class=" "></div>

                        <!-- Alamat -->
                        <div class="col-md-6">
                            <label for="alamat" class="form-label fw-semibold">Alamat</label>
                            <textarea name="alamat" id="alamat" rows="2" 
                                      class="form-control rounded-4 @error('alamat') is-invalid @enderror"
                                      placeholder="Masukkan alamat lengkap pelanggan..." required>{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Metode Pembayaran -->
                        <div class="col-md-6">
                            <label for="metode_pembayaran" class="form-label fw-semibold">Metode Pembayaran</label>
                            <select name="metode_pembayaran" id="metode_pembayaran" 
                                    class="form-select form-select-lg rounded-pill @error('metode_pembayaran') is-invalid @enderror" required>
                                <option value="" disabled selected>Pilih metode pembayaran...</option>
                                <option value="cod" {{ old('metode_pembayaran') == 'cod' ? 'selected' : '' }}>COD</option>
                                <option value="transfer" {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                <option value="qris" {{ old('metode_pembayaran') == 'qris' ? 'selected' : '' }}>QRIS</option>
                            </select>
                            @error('metode_pembayaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Total Transaksi (opsional) -->
                        <div class="col-md-6">
                            <label for="total_transaksi" class="form-label fw-semibold">Total Transaksi (Opsional)</label>
                            <input type="number" name="total_transaksi" id="total_transaksi" 
                                   class="form-control form-control-lg rounded-pill @error('total_transaksi') is-invalid @enderror"
                                   placeholder="Masukkan total transaksi..." value="{{ old('total_transaksi') }}">
                            @error('total_transaksi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-end mt-4">
                        <button type="reset" class="btn btn-light rounded-pill px-4 me-2">
                            <i class="ri-refresh-line me-1"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="ri-save-line me-1"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Toast Notifikasi Sukses & Error
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        iconColor: '#10b981',
        background: '#fff',
        color: '#333',
        customClass: { popup: 'rounded-xl shadow text-sm px-4 py-3 mt-4 bg-white' },
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
</script>
@endpush
