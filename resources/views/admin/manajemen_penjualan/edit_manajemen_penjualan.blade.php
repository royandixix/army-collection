@extends('admin.layouts.app')

@section('title', 'Edit Penjualan & Profil Pelanggan')

@section('content')
<div class="cx-main-content">
    <div class="cx-page-title mb-4">
        <h4>Edit Penjualan & Profil Pelanggan</h4>
    </div>

    <div class="col-md-12">
        <div class="cx-card">
            <div class="cx-card-content card-default">
                <form action="{{ route('admin.manajemen.manajemen_penjualan_update', $penjualan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- ================== DATA PELANGGAN ================== --}}
                    <h5 class="mb-3 text-primary border-bottom pb-2">Data Pelanggan</h5>

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Pelanggan</label>
                        <input type="text" name="nama" id="nama" class="form-control"
                            value="{{ old('nama', $penjualan->pelanggan?->nama ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Pelanggan</label>
                        <input type="email" name="email" id="email" class="form-control"
                            value="{{ old('email', $penjualan->pelanggan?->email ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="no_hp" class="form-label">Nomor HP</label>
                        <input type="text" name="no_hp" id="no_hp" class="form-control"
                            value="{{ old('no_hp', $penjualan->pelanggan?->no_hp ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea name="alamat" id="alamat" rows="3" class="form-control">{{ old('alamat', $penjualan->pelanggan?->alamat ?? '') }}</textarea>
                    </div>

                    @php
                        $defaultFoto = asset('img/default-user.png');
                        $fotoProfil = $penjualan->pelanggan && $penjualan->pelanggan->foto_profil
                            ? asset('storage/' . $penjualan->pelanggan->foto_profil)
                            : $defaultFoto;

                        $defaultBukti = asset('img/no-image.png');
                        $buktiUrl = $penjualan->bukti_tf
                            ? asset('storage/' . $penjualan->bukti_tf)
                            : $defaultBukti;
                    @endphp

                    <div class="mb-3">
                        <label for="foto_profil" class="form-label">Foto Profil Pelanggan</label>
                        <input type="file" name="foto_profil" id="foto_profil" class="form-control" accept="image/*">
                        <div class="mt-2">
                            <img id="preview-foto" src="{{ $fotoProfil }}" width="120" class="rounded shadow-sm border">
                        </div>
                    </div>

                    {{-- ================== DATA PENJUALAN ================== --}}
                    <h5 class="mb-3 text-primary border-bottom pb-2 mt-4">Data Penjualan</h5>

                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal Transaksi</label>
                        <input type="datetime-local" name="tanggal" id="tanggal" class="form-control"
                            value="{{ \Carbon\Carbon::parse($penjualan->tanggal)->format('Y-m-d\TH:i') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="pending" {{ $penjualan->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="diproses" {{ $penjualan->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ $penjualan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="batal" {{ $penjualan->status == 'batal' ? 'selected' : '' }}>Batal</option>
                            <option value="lunas" {{ $penjualan->status == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="total" class="form-label">Total (Rp)</label>
                        <input type="number" name="total" id="total" class="form-control"
                            value="{{ old('total', $penjualan->total ?? 0) }}" min="0" required>
                    </div>

                    <div class="mb-3">
                        <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                        <select name="metode_pembayaran" id="metode_pembayaran" class="form-select" required>
                            <option value="cod" {{ $penjualan->metode_pembayaran == 'cod' ? 'selected' : '' }}>COD</option>
                            <option value="transfer" {{ $penjualan->metode_pembayaran == 'transfer' ? 'selected' : '' }}>Transfer</option>
                            <option value="qris" {{ $penjualan->metode_pembayaran == 'qris' ? 'selected' : '' }}>QRIS</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="bukti_tf" class="form-label">Bukti Pembayaran</label>
                        <input type="file" name="bukti_tf" id="bukti_tf" class="form-control" accept="image/*">
                        <div class="mt-2">
                            <img id="preview-img" src="{{ $buktiUrl }}" width="200" class="rounded shadow-sm border">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Update Data</button>
                        <a href="{{ route('admin.manajemen.manajemen_penjualan') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
