@extends('admin.layouts.app')
@section('title', 'Tambah Produk')

@section('content')
    <div class="cx-main-content">
        <div class="card shadow rounded-2 border-0">
            <div class="card-body p-4">
                <div class="mb-4 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Tambah Produk</h4>
                </div>

                <form action="{{ route('admin.manajemen.manajemen_produk_store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    {{-- Nama Produk --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Produk</label>
                        <input type="text" name="name" id="name" class="form-control form-control-lg"
                            value="{{ old('name') }}" required>
                    </div>

                    {{-- Supplier --}}
                    <div class="mb-3">
                        <label for="supplier_id" class="form-label">Supplier</label>
                        <select name="supplier_id" id="supplier_id" class="form-select" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Kategori --}}
                    <div class="mb-3">
                        <label for="kategori_id" class="form-label">Kategori</label>
                        <select name="kategori_id" id="kategori_id" class="form-select">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}"
                                    {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->name }}
                                </option>
                            @endforeach
                        </select>

                        <small class="text-muted">Atau ketik nama kategori baru:</small>
                        <input type="text" name="kategori_baru" id="kategori_baru" class="form-control mt-1"
                            placeholder="Kategori baru">
                    </div>


                    {{-- Harga (Perubahan di sini) --}}
                    <div class="mb-3">
                        <label for="price_display" class="form-label">Harga</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text">Rp</span>
                            {{-- Input untuk tampilan yang diformat --}}
                            <input type="text" id="price_display" class="form-control"
                                value="{{ old('price') ? number_format(old('price'), 0, ',', '.') : '' }}" required
                                inputmode="numeric">
                            {{-- Input tersembunyi untuk menyimpan nilai numerik yang dikirim ke backend --}}
                            <input type="hidden" name="price" id="price_hidden" value="{{ old('price') }}">
                        </div>
                    </div>

                    {{-- Stok --}}
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stok</label>
                        <input type="number" name="stock" id="stock" class="form-control form-control-lg"
                            value="{{ old('stock') }}" required>
                    </div>

                    {{-- Gambar Produk --}}
                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar Produk</label>
                        <input type="file" name="image" id="image" class="form-control form-control-lg"
                            accept="image/*">
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control form-control-lg" rows="4">{{ old('deskripsi') }}</textarea>
                    </div>

                    {{-- Tombol --}}
                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.manajemen.manajemen_produk') }}"
                            class="btn btn-outline-secondary rounded-2">
                            <i class="ri-arrow-go-back-line"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary rounded-2">
                            <i class="ri-save-line"></i> Simpan Produk
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
    <link href="htps://cnd"
@endpush
@push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // FUNGSI UNTUK FORMAT MATA UANG (RP)
        document.addEventListener('DOMContentLoaded', function() {
            const priceDisplay = document.getElementById('price_display');
            const priceHidden = document.getElementById('price_hidden');

            // Fungsi untuk memformat angka menjadi format mata uang
            function formatRupiah(angka, prefix) {
                let number_string = angka.replace(/[^,\d]/g, '').toString(),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                // Tambahkan titik jika yang diinput sudah menjadi angka ribuan
                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return prefix == undefined ? rupiah : (rupiah ? rupiah : '');
            }

            // Event listener saat input harga berubah
            priceDisplay.addEventListener('keyup', function(e) {
                // Hapus semua karakter non-digit kecuali koma (untuk desimal, meskipun harga biasanya bulat)
                let cleanedValue = this.value.replace(/[^0-9]/g, '');

                // Format tampilan input
                this.value = formatRupiah(cleanedValue);

                // Simpan nilai numerik murni (tanpa format titik atau koma) ke input tersembunyi
                priceHidden.value = cleanedValue;
            });

            // Pastikan nilai awal dari old('price') juga diformat saat halaman dimuat
            if (priceHidden.value) {
                priceDisplay.value = formatRupiah(priceHidden.value.toString());
            }
        });


        // Inisialisasi SweetAlert Toast
        const Toast = Swal.mixin({
            toast: true
            , position: 'top-end'
            , showConfirmButton: false
            , timer: 3500
            , timerProgressBar: true
            , background: '#f8fafc', // abu-abu terang
            color: '#1e293b', // abu-abu gelap
            iconColor: '#0d9488', // teal-500
            customClass: {
                popup: 'rounded-xl shadow-md border border-slate-200 px-4 py-3 text-sm'
            , }
            , didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // SweetAlert untuk session flash
        @if (session('success'))
        Toast.fire({
            icon: 'success'
            , title: @js(session('success'))
            , iconColor: '#16a34a', // green-600
        });
        @endif

        @if (session('error'))
        Toast.fire({
            icon: 'error'
            , title: @js(session('error'))
            , iconColor: '#dc2626', // red-600
        });
        @endif

        @if ($errors->any())
        Toast.fire({
            icon: 'warning'
            , title: 'Periksa form kamu'
            , text: @js($errors->first())
            , iconColor: '#f59e0b', // amber-500
        });
        @endif

        // Konfirmasi hapus produk (catatan: bagian ini seharusnya tidak ada di halaman "Tambah Produk",
        // tapi saya biarkan di sini jika memang digunakan di seluruh admin layout)
        document.querySelectorAll('.delete-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.dataset.id;
                Swal.fire({
                    title: 'Yakin hapus produk ini?'
                    , text: 'Data akan dihapus permanen dan tidak bisa dikembalikan.'
                    , icon: 'warning'
                    , showCancelButton: true
                    , confirmButtonColor: '#dc2626'
                    , // merah
                    cancelButtonColor: '#94a3b8'
                    , // slate
                    confirmButtonText: '<i class="ri-delete-bin-2-line"></i> Ya, hapus'
                    , cancelButtonText: 'Batal'
                    , background: '#ffffff'
                    , color: '#1e293b'
                    , iconColor: '#f97316'
                    , // orange-500
                    customClass: {
                        popup: 'rounded-lg shadow-lg border border-slate-200 p-4'
                        , confirmButton: 'btn btn-danger me-2'
                        , cancelButton: 'btn btn-outline-secondary'
                    }
                    , buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `/admin/manajemen/produk/${id}/delete`;
                    }
                });
            });
        });
    </script>
@endpush