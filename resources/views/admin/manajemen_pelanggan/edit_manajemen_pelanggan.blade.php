@extends('admin.layouts.app')

@section('title', 'Edit Pelanggan')

@section('content')
<div class="cx-main-content">
    <div class="cx-page-title d-flex justify-content-between align-items-center flex-wrap mb-3">
        <h4 class="mb-0">Edit Data Pelanggan</h4>
        <a href="{{ route('admin.manajemen.manajemen_pelanggan') }}" class="btn btn-sm btn-secondary rounded-pill">
            ← Kembali
        </a>
    </div>

    <div class="cx-card card-default p-4">
        <form action="{{ route('admin.manajemen.manajemen_pelanggan_update', $pelanggan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <h5 class="fw-bold mb-3 border-bottom pb-2 text-primary">Data Pelanggan</h5>
            <div class="row">

                {{-- NAMA --}}
                <div class="col-md-6 mb-3">
                    <label for="nama" class="form-label fw-semibold">Nama</label>
                    <input type="text" name="nama" id="nama" class="form-control"
                        value="{{ old('nama', $pelanggan->username ?? $pelanggan->nama) }}" required>
                </div>

                {{-- EMAIL --}}
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" id="email" class="form-control"
                        value="{{ old('email', $pelanggan->email) }}" required>
                </div>

                {{-- NOMOR HP --}}
                <div class="col-md-6 mb-3">
                    <label for="no_hp" class="form-label fw-semibold">Nomor HP</label>
                    <input type="text" name="no_hp" id="no_hp" class="form-control"
                        value="{{ old('no_hp', optional($pelanggan->pelanggan)->no_hp ?? $pelanggan->no_hp ?? '') }}">
                </div>

                {{-- ALAMAT --}}
                <div class="col-md-6 mb-3">
                    <label for="alamat" class="form-label fw-semibold">Alamat</label>
                    <input type="text" name="alamat" id="alamat" class="form-control"
                        value="{{ old('alamat', optional($pelanggan->pelanggan)->alamat ?? $pelanggan->alamat ?? '') }}">
                </div>

                {{-- JUMLAH TRANSAKSI --}}
@php
    // Ambil jumlah transaksi saat ini
    $jumlahTransaksiDefault = (optional($pelanggan->transaksis)->count() ?? 0)
                            + (optional($pelanggan->penjualans)->count() ?? 0);

    // Jika ada old value dari validasi sebelumnya, gunakan itu
    $jumlahTransaksi = old('jumlah_transaksi', $jumlahTransaksiDefault);
@endphp

<div class="col-md-6 mb-3">
    <label for="jumlah_transaksi" class="form-label fw-semibold">Jumlah Transaksi</label>
    <input type="number" name="jumlah_transaksi" id="jumlah_transaksi" class="form-control"
        value="{{ $jumlahTransaksi }}" min="0">
</div>



                {{-- METODE PEMBAYARAN --}}
                @php
                    $transaksiTerakhir = collect(optional($pelanggan->transaksis))->sortByDesc('created_at')->first();
                    $penjualanTerakhir = collect(optional($pelanggan->penjualans))->sortByDesc('tanggal')->first();

                    $metode = optional($transaksiTerakhir)->metode
                              ?? optional($penjualanTerakhir)->metode_pembayaran
                              ?? '';

                    $metodeOptions = ['transfer', 'cod', 'qris'];
                @endphp
                <div class="col-md-6 mb-3">
                    <label for="metode_pembayaran" class="form-label fw-semibold">Metode Pembayaran</label>
                    <select name="metode_pembayaran" id="metode_pembayaran" class="form-select">
                        <option value="" {{ $metode == '' ? 'selected' : '' }}>-- Pilih Metode --</option>
                        @foreach($metodeOptions as $option)
                            <option value="{{ $option }}" {{ strtolower($metode) == $option ? 'selected' : '' }}>
                                {{ strtoupper($option) }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="mt-4 d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-primary rounded-pill px-4">
                    <i class="ri-save-3-line me-1"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.manajemen.manajemen_pelanggan') }}"
                   class="btn btn-outline-secondary rounded-pill px-4">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    iconColor: '#10b981',
});
@if(session('success'))
    Toast.fire({ icon: 'success', title: @js(session('success')) });
@endif
@if(session('error'))
    Toast.fire({ icon: 'error', title: @js(session('error')) });
@endif
@if($errors->any())
    Toast.fire({ icon: 'warning', title: 'Periksa kembali form!' });
@endif
</script>
@endpush
