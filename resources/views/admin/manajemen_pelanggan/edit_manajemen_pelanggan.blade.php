@extends('admin.layouts.app')

@section('title', 'Edit Pelanggan')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />
<style>
    /* Animasi sederhana */
    .fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .form-label span {
        font-size: 0.875rem;
    }
</style>
@endpush

@section('content')
<div class="cx-main-content fade-in">
    <div class="cx-page-title d-flex justify-content-between align-items-center flex-wrap mb-3">
        <h4 class="mb-0">Edit Data Pelanggan</h4>
        <a href="{{ route('admin.manajemen.manajemen_pelanggan') }}" class="btn btn-sm btn-secondary rounded-pill">
            ← Kembali
        </a>
    </div>

    <div class="cx-card card-default p-4 shadow-sm">
        <form id="formEditPelanggan" action="{{ route('admin.manajemen.manajemen_pelanggan_update', $pelanggan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <h5 class="fw-bold mb-3 border-bottom pb-2 text-primary">Data Pelanggan</h5>
            <div class="row">

                {{-- NAMA --}}
                <div class="col-md-6 mb-3">
                    <label for="nama" class="form-label fw-semibold">Nama</label>
                    <input type="text" name="nama" id="nama" class="form-control"
                        value="{{ old('nama', $pelanggan->nama ?? optional($user)->username) }}" required>
                </div>

                {{-- EMAIL --}}
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" id="email" class="form-control"
                        value="{{ old('email', $pelanggan->email ?? optional($user)->email) }}" required>
                </div>

                {{-- NOMOR HP --}}
                <div class="col-md-6 mb-3">
                    <label for="no_hp" class="form-label fw-semibold">Nomor HP</label>
                    <input type="text" name="no_hp" id="no_hp" class="form-control"
                        value="{{ old('no_hp', $pelanggan->no_hp ?? '') }}">
                </div>

                {{-- ALAMAT --}}
                <div class="col-md-6 mb-3">
                    <label for="alamat" class="form-label fw-semibold">Alamat</label>
                    <input type="text" name="alamat" id="alamat" class="form-control"
                        value="{{ old('alamat', $pelanggan->alamat ?? '') }}">
                </div>

                {{-- JUMLAH TRANSAKSI ASLI --}}
                @php
                    $penjualans1 = $pelanggan->pelanggan?->penjualans ?? collect();
                    $penjualans2 = $pelanggan->penjualans ?? collect();
                    $transaksis = $pelanggan->transaksis ?? collect();
                    $jumlahTransaksiAsli = $penjualans1->count() + $penjualans2->count() + $transaksis->count();
                @endphp
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">
                        Jumlah Transaksi
                        <span class="text-danger fw-normal d-block" style="cursor: default;">
                            PERINGATAN: Transaksi asli tidak dapat diedit.
                        </span>
                    </label>
                    <input type="number" class="form-control bg-light" value="{{ $jumlahTransaksiAsli }}" readonly>
                </div>

                {{-- METODE PEMBAYARAN TERAKHIR --}}
                @php
                    $penjualanTerakhir = $penjualans2->sortByDesc('tanggal')->first() ?? $transaksis->sortByDesc('created_at')->first();
                    $metodeOptions = ['transfer', 'cod', 'qris'];
                    $metode = old('metode_pembayaran', optional($penjualanTerakhir)->metode_pembayaran ?? '');
                @endphp
                <div class="col-md-6 mb-3">
                    <label for="metode_pembayaran" class="form-label fw-semibold">
                        Metode Pembayaran Terakhir
                        <span class="text-muted">(Opsional, tidak wajib diubah)</span>
                    </label>
                    <select name="metode_pembayaran" id="metode_pembayaran" class="form-select">
                        <option value="">-- Pilih Metode --</option>
                        @foreach($metodeOptions as $option)
                            <option value="{{ $option }}" {{ strtolower($metode) == $option ? 'selected' : '' }}>
                                {{ strtoupper($option) }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="mt-4 d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-primary rounded-pill px-4" id="btnSimpan">
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
document.getElementById('formEditPelanggan').addEventListener('submit', function(e) {
    e.preventDefault(); // hentikan submit default

    Swal.fire({
        title: 'Simpan perubahan?',
        text: "Transaksi asli tidak dapat diedit, hanya data pelanggan yang diperbarui.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, simpan',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        customClass: {
            confirmButton: 'btn btn-primary rounded-pill px-4',
            cancelButton: 'btn btn-outline-secondary rounded-pill px-4'
        },
        buttonsStyling: false
    }).then((result) => {
        if(result.isConfirmed) {
            this.submit(); // submit form setelah konfirmasi
        }
    });
});
</script>
@endpush
