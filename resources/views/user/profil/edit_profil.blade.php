@extends('user.ayouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm rounded-4">
        <div class="card-header fw-bold">Edit Profil</div>

        <div class="card-body">
            <form method="POST" action="{{ route('user.profil.update') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="mb-3">
                    <label>Foto Profil (opsional)</label>
                    <input type="file" name="photo" class="form-control">
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('user.profil') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
