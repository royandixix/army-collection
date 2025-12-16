@extends('user.layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container py-5">

    @if(session('success'))
        <div class="alert alert-success rounded-3 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">

        {{-- PROFIL KIRI --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header-custom text-center py-4 text-white">
                    <img src="{{ $user->img ? asset('storage/'.$user->img) : asset('img/default-user.png') }}"
                         class="rounded-circle border border-4 border-white"
                         width="120" height="120" style="object-fit:cover">
                </div>

                <div class="card-body text-center">
                    <h4 class="fw-bold">{{ $user->username }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>

                    <a href="{{ route('user.profil.edit') }}" class="btn btn-primary w-100 mb-2">
                        Edit Profil
                    </a>
                </div>
            </div>
        </div>

        {{-- DETAIL --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body">

                    <h5 class="fw-bold mb-3">Informasi Detail</h5>

                    {{-- USER INFO --}}
                    <div class="mb-3">
                        <label class="text-muted small">Username</label>
                        <p class="fw-semibold">{{ $user->username }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Email</label>
                        <p class="fw-semibold">{{ $user->email }}</p>
                    </div>

                    {{-- ALAMAT --}}
                    <div class="mb-3">
                        <label class="text-muted small">Alamat</label>
                        <p class="fw-semibold">
                            {{ $user->alamat ?? 'Alamat belum diisi' }}
                        </p>
                    </div>

                    {{-- KOORDINAT --}}
                    @if($user->latitude && $user->longitude)
                        <div class="mb-3">
                            <label class="text-muted small">Lokasi</label>
                            <p class="fw-semibold">
                                Lat: {{ $user->latitude }} <br>
                                Long: {{ $user->longitude }}
                            </p>

                            <a target="_blank"
                               href="https://www.google.com/maps?q={{ $user->latitude }},{{ $user->longitude }}"
                               class="btn btn-outline-primary btn-sm">
                                📍 Lihat di Maps
                            </a>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="text-muted small">Bergabung Sejak</label>
                        <p class="fw-semibold">
                            {{ $user->created_at->format('d M Y') }}
                        </p>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
.card-header-custom{
    background: linear-gradient(135deg,#2c5364,#0f2027);
}
</style>
@endpush
