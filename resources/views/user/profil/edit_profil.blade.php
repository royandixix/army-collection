@extends('user.layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="container py-5">
    {{-- Header --}}
    <div class="mb-4">
        <h3 class="fw-bold text-dark">
            <i class="bi bi-pencil-square text-primary me-2"></i> Edit Profil
        </h3>
        <p class="text-muted">Perbarui informasi profil Anda</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-gradient text-white text-center py-4 rounded-top-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-person-gear me-2"></i>Pengaturan Profil
                    </h5>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('user.profil.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Preview Foto --}}
                        <div class="mb-4 text-center">
                            <div class="position-relative d-inline-block">
                                <img id="previewImg" 
                                     src="{{ $user->img ? asset('storage/' . $user->img) : asset('img/default-user.png') }}"
                                     alt="Foto Profil" 
                                     class="rounded-circle border border-4 border-light shadow-lg" 
                                     width="150" 
                                     height="150"
                                     style="object-fit: cover;">
                                <label for="photo" class="upload-overlay">
                                    <i class="bi bi-camera-fill"></i>
                                </label>
                            </div>
                            <p class="text-muted mt-2 mb-0 small">Klik ikon kamera untuk mengganti foto</p>
                        </div>

                        {{-- Input Nama --}}
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">
                                <i class="bi bi-person-badge text-primary me-2"></i>Nama
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       class="form-control border-start-0 @error('name') is-invalid @enderror"
                                       value="{{ old('name', $user->username ?? $user->name) }}" 
                                       placeholder="Masukkan nama Anda"
                                       required>
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Input Email --}}
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">
                                <i class="bi bi-envelope-at text-primary me-2"></i>Email
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       class="form-control border-start-0 @error('email') is-invalid @enderror"
                                       value="{{ old('email', $user->email) }}" 
                                       placeholder="email@example.com"
                                       required>
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Input Foto --}}
                        <div class="mb-4">
                            <label for="photo" class="form-label fw-semibold">
                                <i class="bi bi-image text-primary me-2"></i>Foto Profil
                            </label>
                            <input type="file" 
                                   name="photo" 
                                   id="photo" 
                                   class="form-control @error('photo') is-invalid @enderror" 
                                   accept="image/*"
                                   onchange="previewImage(event)">
                            @error('photo')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Format: JPG, JPEG, PNG | Maksimal: 2MB
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex gap-2 justify-content-end mt-4 pt-3 border-top">
                            <a href="{{ route('user.profil') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-x-circle me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Info Card --}}
            <div class="card border-0 bg-light mt-4">
                <div class="card-body">
                    <h6 class="fw-bold text-dark mb-3">
                        <i class="bi bi-lightbulb text-warning me-2"></i>Tips
                    </h6>
                    <ul class="mb-0 ps-3">
                        <li class="text-muted mb-2">Gunakan foto profil yang jelas dan profesional</li>
                        <li class="text-muted mb-2">Pastikan email Anda aktif untuk notifikasi</li>
                        <li class="text-muted">Nama akan ditampilkan di seluruh platform</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
<style>
    body {
        background: #f8f9fa;
        font-family: 'Poppins', sans-serif;
    }

    .bg-gradient {
        background: linear-gradient(135deg, #2c5364 0%, #0f2027 100%);
    }

    .card {
        transition: box-shadow 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12) !important;
    }

    .upload-overlay {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #2c5364 0%, #0f2027 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }

    .upload-overlay:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3);
    }

    .upload-overlay i {
        color: white;
        font-size: 1.2rem;
    }

    #previewImg {
        transition: transform 0.3s ease;
    }

    #previewImg:hover {
        transform: scale(1.05);
    }

    .input-group-text {
        border: 1px solid #dee2e6;
    }

    .form-control {
        border: 1px solid #dee2e6;
        padding: 0.65rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #2c5364;
        box-shadow: 0 0 0 0.2rem rgba(44, 83, 100, 0.15);
    }

    .border-start-0:focus {
        border-left: 1px solid #2c5364 !important;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2c5364 0%, #0f2027 100%);
        border: none;
        font-weight: 600;
        padding: 0.65rem 1.5rem;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(44, 83, 100, 0.3);
    }

    .btn-outline-secondary {
        border: 2px solid #6c757d;
        color: #6c757d;
        font-weight: 600;
        padding: 0.65rem 1.5rem;
        transition: all 0.3s ease;
    }

    .btn-outline-secondary:hover {
        background: #6c757d;
        color: white;
        border-color: #6c757d;
    }

    .invalid-feedback {
        font-size: 0.875rem;
    }

    .form-text {
        font-size: 0.85rem;
        color: #6c757d;
    }

    .form-label {
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .bg-light.card {
        border-radius: 1rem;
    }

    .bg-light.card ul li {
        font-size: 0.9rem;
    }
</style>
@endpush

@push('scripts')
<script>
    // Preview image before upload
    function previewImage(event) {
        const file = event.target.files[0];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
        }
        
        if (file) {
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush