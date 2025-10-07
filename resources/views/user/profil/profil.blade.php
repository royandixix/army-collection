@extends('user.layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container py-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-4">
        <h3 class="fw-bold text-dark">
            <i class="bi bi-person-circle text-primary me-2"></i> Profil Saya
        </h3>
        <p class="text-muted">Kelola informasi profil Anda</p>
    </div>

    <div class="row g-4">
        {{-- Card Profil Utama --}}
        <div class="col-lg-4">
            <div class="card profile-card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header-custom text-white text-center py-4">
                    <div class="profile-img-wrapper mx-auto position-relative">
                        <img src="{{ $user->img ? asset('storage/'.$user->img) : asset('img/default-user.png') }}"
                             alt="Foto Profil"
                             class="profile-img rounded-circle border border-4 border-white shadow"
                             width="120" height="120">
                        <div class="status-badge">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                    </div>
                </div>

                <div class="card-body text-center p-4">
                    <h4 class="fw-bold text-dark mb-1">{{ $user->username }}</h4>
                    <p class="text-muted mb-3">
                        <i class="bi bi-envelope me-1"></i>{{ $user->email }}
                    </p>

                    <div class="d-grid gap-2">
                        <a href="{{ route('user.profil.edit') }}" class="btn btn-primary btn-custom">
                            <i class="bi bi-pencil-square me-2"></i> Edit Profil
                        </a>
                        <button class="btn btn-outline-secondary btn-custom" onclick="window.history.back()">
                            <i class="bi bi-arrow-left me-2"></i> Kembali
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Informasi Detail --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-info-circle text-primary me-2"></i>Informasi Detail
                    </h5>
                </div>

                <div class="card-body p-4">
                    <div class="info-group">
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bi bi-person-badge"></i>
                            </div>
                            <div class="info-content">
                                <label class="text-muted small mb-1">Username</label>
                                <p class="fw-semibold text-dark mb-0">{{ $user->username }}</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bi bi-envelope-at"></i>
                            </div>
                            <div class="info-content">
                                <label class="text-muted small mb-1">Email</label>
                                <p class="fw-semibold text-dark mb-0">{{ $user->email }}</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            <div class="info-content">
                                <label class="text-muted small mb-1">Bergabung Sejak</label>
                                <p class="fw-semibold text-dark mb-0">
                                    {{ $user->created_at ? $user->created_at->format('d M Y') : 'N/A' }}
                                </p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div class="info-content">
                                <label class="text-muted small mb-1">Status Akun</label>
                                <p class="mb-0">
                                    <span class="badge bg-success px-3 py-2">
                                        <i class="bi bi-check-circle me-1"></i>Aktif
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Stats --}}
                    <div class="stats-container mt-4">
                        <h6 class="fw-bold text-dark mb-3">Aktivitas</h6>
                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <div class="stat-card">
                                    <div class="stat-icon bg-primary">
                                        <i class="bi bi-cart"></i>
                                    </div>
                                    <div class="stat-info">
                                        <h5 class="mb-0">0</h5>
                                        <small class="text-muted">Pesanan</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="stat-card">
                                    <div class="stat-icon bg-success">
                                        <i class="bi bi-heart"></i>
                                    </div>
                                    <div class="stat-info">
                                        <h5 class="mb-0">0</h5>
                                        <small class="text-muted">Favorit</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="stat-card">
                                    <div class="stat-icon bg-warning">
                                        <i class="bi bi-star"></i>
                                    </div>
                                    <div class="stat-info">
                                        <h5 class="mb-0">0</h5>
                                        <small class="text-muted">Review</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="stat-card">
                                    <div class="stat-icon bg-info">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                    <div class="stat-info">
                                        <h5 class="mb-0">0</h5>
                                        <small class="text-muted">Riwayat</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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

    .profile-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .profile-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
    }

    .card-header-custom {
        background: linear-gradient(135deg, #2c5364 0%, #0f2027 100%);
        padding: 2rem 1rem;
    }

    .profile-img-wrapper {
        width: 120px;
        height: 120px;
        position: relative;
        display: inline-block;
    }

    .profile-img {
        width: 120px !important;
        height: 120px !important;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .profile-img:hover {
        transform: scale(1.05);
    }

    .status-badge {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: #10b981;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .status-badge i {
        color: white;
        font-size: 0.9rem;
    }

    .btn-custom {
        padding: 0.65rem 1.5rem;
        font-weight: 600;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-primary.btn-custom {
        background: linear-gradient(135deg, #2c5364 0%, #0f2027 100%);
        border: none;
    }

    .btn-primary.btn-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(44, 83, 100, 0.3);
    }

    .btn-outline-secondary.btn-custom {
        border: 2px solid #6c757d;
        color: #6c757d;
    }

    .btn-outline-secondary.btn-custom:hover {
        background: #6c757d;
        color: white;
        border-color: #6c757d;
    }

    .info-group {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 0.75rem;
        transition: background 0.3s ease;
    }

    .info-item:hover {
        background: #e9ecef;
    }

    .info-icon {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #2c5364 0%, #0f2027 100%);
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .info-icon i {
        color: white;
        font-size: 1.2rem;
    }

    .info-content {
        flex: 1;
    }

    .stats-container {
        padding-top: 1.5rem;
        border-top: 2px dashed #e9ecef;
    }

    .stat-card {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 0.75rem;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        border-color: #2c5364;
        box-shadow: 0 4px 12px rgba(44, 83, 100, 0.1);
        transform: translateY(-3px);
    }

    .stat-icon {
        width: 45px;
        height: 45px;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-icon i {
        color: white;
        font-size: 1.2rem;
    }

    .stat-info h5 {
        font-weight: 700;
        color: #2c3e50;
    }

    .stat-info small {
        font-size: 0.75rem;
    }

    .badge {
        font-weight: 600;
        font-size: 0.85rem;
    }

    @media (max-width: 991.98px) {
        .profile-img-wrapper,
        .profile-img {
            width: 100px !important;
            height: 100px !important;
        }

        .status-badge {
            width: 24px;
            height: 24px;
        }
    }
</style>
@endpu