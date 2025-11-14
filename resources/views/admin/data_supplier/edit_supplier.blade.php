@extends('admin.layouts.app')

@section('title', 'Edit Supplier')

@section('content')
<div class="cx-main-content">

    <!-- Page Header -->
    <div class="cx-page-title d-flex justify-content-between align-items-center flex-wrap mb-4">
        <h4 class="mb-0">Edit Supplier</h4>
        <a href="{{ route('admin.supplier.index') }}" class="btn btn-secondary rounded-pill">
            <i class="ri-arrow-go-back-line me-1"></i> Kembali
        </a>
    </div>

    <!-- Form Edit Supplier -->
    <div class="cx-card p-4">
        <form action="{{ route('admin.supplier.update', $supplier->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Nama Supplier --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Supplier</label>
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                    value="{{ old('nama', $supplier->nama) }}" required>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Alamat --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Alamat</label>
                <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3" required>{{ old('alamat', $supplier->alamat) }}</textarea>
                @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Telepon --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Telepon</label>
                <input type="text" name="telepon" class="form-control @error('telepon') is-invalid @enderror"
                    value="{{ old('telepon', $supplier->telepon) }}" required>
                @error('telepon')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tombol Submit --}}
            <button type="submit" class="btn btn-primary rounded-pill px-4">
                <i class="ri-check-line me-1"></i> Update Data
            </button>
        </form>
    </div>

</div>
@endsection
