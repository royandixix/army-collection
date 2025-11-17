@extends('admin.layouts.app')

@section('title', 'Tambah Supplier')

@section('content')
<div class="cx-main-content">

    <!-- Page Header -->
    <div class="cx-page-title d-flex justify-content-between align-items-center flex-wrap mb-4">
        <h4 class="mb-0">Tambah Supplier</h4>
        <a href="{{ route('admin.supplier.index') }}" class="btn btn-secondary rounded-pill">
            <i class="ri-arrow-go-back-line me-1"></i> Kembali
        </a>
    </div>

    <!-- Form Tambah Supplier -->
    <div class="cx-card p-4">
        <form action="{{ route('admin.supplier.store') }}" method="POST">
            @csrf

            <!-- Nama Supplier -->
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Supplier</label>
                <input type="text" name="nama" class="form-control" placeholder="Masukkan nama supplier" value="{{ old('nama') }}" required>
                @error('nama')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Alamat Supplier -->
            <div class="mb-3">
                <label class="form-label fw-bold">Alamat</label>
                <textarea name="alamat" class="form-control" placeholder="Masukkan alamat lengkap" rows="3" required>{{ old('alamat') }}</textarea>
                @error('alamat')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Telepon Supplier -->
            <div class="mb-3">
                <label class="form-label fw-bold">Telepon</label>
                <input type="text" name="telepon" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('telepon') }}" required>
                @error('telepon')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Tombol Submit -->
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary rounded-pill px-4">
                    <i class="ri-save-line me-1"></i> Simpan Supplier
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet" />
@endpush