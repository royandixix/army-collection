@extends('admin.layouts.app')
@section('title', 'Tambah Produk')

@section('content')
<div class="cx-main-content">
    <div class="card shadow rounded-2 border-0">
        <div class="card-body p-4">
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Tambah Produk</h4>

            </div>

            <form action="{{ route('admin.manajemen.manajemen_produk_store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Nama Produk --}}
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Produk</label>
                    <input type="text" name="name" id="name" class="form-control form-control-lg" value="{{ old('name') }}" required>
                </div>



                <div class="mb-3">
                    <label for="kategori_id" class="form-label">Kategori</label>
                    <select name="kategori_id" id="kategori_id" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->name }}
                        </option>
                        @endforeach
                    </select>
                </div>




                {{-- Harga --}}
                <div class="mb-3">
                    <label for="price" class="form-label">Harga</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text">Rp</span>
                        <input type="text" name="price" id="price" class="form-control" value="{{ old('price') }}" required>
                    </div>
                </div>

                {{-- Stok --}}
                <div class="mb-3">
                    <label for="stock" class="form-label">Stok</label>
                    <input type="number" name="stock" id="stock" class="form-control form-control-lg" value="{{ old('stock') }}" required>
                </div>

                {{-- Gambar Produk --}}
                <div class="mb-3">
                    <label for="image" class="form-label">Gambar Produk</label>
                    <input type="file" name="image" id="image" class="form-control form-control-lg" accept="image/*">
                </div>

                {{-- Deskripsi --}}
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control form-control-lg" rows="4">{{ old('deskripsi') }}</textarea>
                </div>


                <!-- Tombol Submit -->
                <div class="mt-4 text-end d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.manajemen.manajemen_produk') }}" class="btn btn-outline-secondary rounded-2 d-inline-flex align-items-center gap-2">
                        <i class="ri-arrow-go-back-line"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary rounded-2 d-inline-flex align-items-center gap-2">
                        <i class="ri-save-line"></i> Simpan Produk
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

{{-- Styles --}}
@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet" />
@endpush

{{-- Scripts --}}
@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Format harga ke format Rupiah
    document.addEventListener("DOMContentLoaded", function() {
        const priceInput = document.getElementById('price');
        priceInput.addEventListener('input', function(e) {
            let angka = e.target.value.replace(/\D/g, '');
            if (angka.length > 9) angka = angka.substring(0, 9);
            e.target.value = new Intl.NumberFormat('id-ID').format(angka);
        });
    });

    // Inisialisasi DataTable
    $(document).ready(function() {
        $('#produk-table').DataTable();
    });

    // Inisialisasi Toast dari SweetAlert
    const Toast = Swal.mixin({
        toast: true
        , position: 'top'
        , showConfirmButton: false
        , timer: 3000
        , timerProgressBar: true
        , background: '#fff'
        , color: '#333'
        , iconColor: '#4f46e5'
        , customClass: {
            popup: 'rounded-xl shadow-md text-sm px-4 py-3 mt-4'
        }
        , didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    // Tampilkan notifikasi jika ada session
    @if(session('success'))
    Toast.fire({
        icon: 'success'
        , title: @js(session('success'))
    });
    @endif

    @if(session('error'))
    Toast.fire({
        icon: 'error'
        , title: @js(session('error'))
    });
    @endif

    @if($errors->any())
    Toast.fire({
        icon: 'warning'
        , title: 'Periksa form kamu'
        , text: @js($errors->first())
    });
    @endif

    // Hapus produk dengan konfirmasi
    $('.delete-btn').on('click', function(e) {
        e.preventDefault();
        const id = $(this).data('id');

        Swal.fire({
            title: 'Hapus Produk?'
            , text: "Tindakan ini tidak dapat dibatalkan."
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#d33'
            , cancelButtonColor: '#3085d6'
            , confirmButtonText: 'Ya, hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `/admin/manajemen/produk/${id}/delete`;
            }
        });
    });

</script>
@endpush
