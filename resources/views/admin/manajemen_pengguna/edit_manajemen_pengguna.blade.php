@extends('admin.layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
<div class="cx-main-content">

    <!-- Judul Halaman -->
    <div class="cx-page-title d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div>
            <h5 class="mb-2">Edit Pengguna</h5>
        </div>
    </div>

    <!-- Kartu Formulir -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" id="form-edit-user">
                        @csrf
                        @method('PUT')

                        <!-- Username -->
                        <div class="row mb-3">
                            <label for="username" class="col-sm-2 col-form-label fw-semibold text-end">Username</label>
                            <div class="col-sm-10">
                                <input type="text" name="username" id="username" class="form-control" value="{{ old('username', $user->username) }}" required>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="row mb-3">
                            <label for="email" class="col-sm-2 col-form-label fw-semibold text-end">Email</label>
                            <div class="col-sm-10">
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>

                        <!-- Role -->
                        <div class="row mb-3">
                            <label for="role" class="col-sm-2 col-form-label fw-semibold text-end">Tim / Role</label>
                            <div class="col-sm-10">
                                <input type="text" name="role" id="role" class="form-control" value="{{ old('role', $user->role) }}">
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="row mb-4">
                            <label for="status" class="col-sm-2 col-form-label fw-semibold text-end">Status</label>
                            <div class="col-sm-10">
                                <select name="status" id="status" class="form-select">
                                    <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive" {{ $user->status === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex justify-content-between mt-4">

                            <div class="text-end">
                                <button type="button" id="btn-submit-confirm" class="btn btn-outline-primary rounded-2 d-inline-flex align-items-center gap-2">
                                    <i class="ri-save-line"></i> Simpan Produk
                                </button>
                            </div>

                            <a href="{{ route('admin.manajemen.manajemen_pengguna') }}" class="btn btn-outline-secondary">
                                <i class="ri-arrow-go-back-line"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
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
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    @if(session('success'))
    Toast.fire({
        icon: 'success',
        title: @js(session('success'))
    });
    @endif

    @if(session('error'))
    Toast.fire({
        icon: 'error',
        title: @js(session('error'))
    });
    @endif

    @if($errors->any())
    Toast.fire({
        icon: 'warning',
        title: 'Periksa form kamu',
        text: @js($errors->first())
    });
    @endif

    // Konfirmasi sebelum submit
    document.getElementById('btn-submit-confirm')?.addEventListener('click', function () {
        Swal.fire({
            title: 'Yakin simpan perubahan?',
            text: 'Perubahan data akan disimpan permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, simpan!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'btn btn-primary me-2',
                cancelButton: 'btn btn-outline-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-edit-user').submit();
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: 'Dibatalkan',
                    text: 'Perubahan tidak disimpan.',
                    icon: 'info',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'btn btn-secondary'
                    }
                });
            }
        });
    });
</script>
@endpush
