@extends('admin.layouts.app')
@section('title', 'Edit Produk')

@section('content')
<div class="cx-main-content">
    <div class="card shadow rounded-2 border-0">
        <div class="card-body p-4">
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Edit Produk</h4>
            </div>

            <form action="{{ route('admin.manajemen.manajemen_produk_update', $produk->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Nama Produk --}}
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Produk</label>
                    <input type="text" name="name" id="name" class="form-control form-control-lg"
                           value="{{ old('name', $produk->nama) }}" required>
                </div>

                {{-- Kategori --}}
                <div class="mb-3">
                    <label for="kategori_id" class="form-label">Kategori</label>
                    <select name="kategori_id" id="kategori_id" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}"
                                {{ old('kategori_id', $produk->kategori_id) == $kategori->id ? 'selected' : '' }}>
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
                        <input type="text" name="price" id="price" class="form-control"
                               value="{{ old('price', $produk->harga) }}" required>
                    </div>
                </div>

                {{-- Stok --}}
                <div class="mb-3">
                    <label for="stock" class="form-label">Stok</label>
                    <input type="number" name="stock" id="stock" class="form-control form-control-lg"
                           value="{{ old('stock', $produk->stok) }}" required>
                </div>

                {{-- Gambar Produk --}}
                <div class="mb-3">
                    <label for="image" class="form-label">Gambar Produk</label>
                    <input type="file" name="image" id="image" class="form-control form-control-lg" accept="image/*">
                    @if($produk->gambar)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $produk->gambar) }}" alt="Gambar Produk" width="100">
                        </div>
                    @endif
                </div>

                {{-- Deskripsi --}}
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control form-control-lg" rows="4">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                </div>

                {{-- Tombol --}}
                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.manajemen.manajemen_produk') }}"
                       class="btn btn-outline-secondary rounded-2 d-inline-flex align-items-center gap-2">
                        <i class="ri-arrow-go-back-line"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary rounded-2 d-inline-flex align-items-center gap-2">
                        <i class="ri-save-line"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true,
        background: '#f8fafc',
        color: '#1e293b',
        iconColor: '#0d9488',
        customClass: {
            popup: 'rounded-xl shadow-md border border-slate-200 px-4 py-3 text-sm'
        },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    @if(session('success'))
    Toast.fire({ icon: 'success', title: @js(session('success')), iconColor: '#16a34a' });
    @endif

    @if(session('error'))
    Toast.fire({ icon: 'error', title: @js(session('error')), iconColor: '#dc2626' });
    @endif

    @if($errors->any())
    Toast.fire({ icon: 'warning', title: 'Periksa form kamu', text: @js($errors->first()), iconColor: '#f59e0b' });
    @endif
</script>
@endpush
