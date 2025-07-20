@extends('user.layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm rounded-4">
        <div class="card-body text-center">
            <img src="{{ $user->profile_photo_path ? asset('storage/'.$user->profile_photo_path) : asset('img/default-user.png') }}"
                 alt="Foto Profil"
                 class="rounded-circle mb-3"
                 width="100" height="100"
                 style="object-fit: cover;">

            <h5 class="fw-semibold">{{ $user->name }}</h5>
            <p class="text-muted">{{ $user->email }}</p>

            <a href="{{ route('user.profil.edit') }}" class="btn btn-outline-primary mt-2">
                <i class="bi bi-pencil me-1"></i> Edit Profil
            </a>
        </div>
    </div>
</div>
@endsection
