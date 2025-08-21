@extends('admin.layouts.app')

@section('title', 'Rekap Data Penjualan & Transaksi')

@section('content')
<div class="content-wrapper">
    <div class="container-fluid">
        <h2 class="mb-4">📊 Rekap Data</h2>

        {{-- Form Filter Tanggal --}}
        <form method="GET" action="{{ route('admin.rekap.index') }}" class="row g-3 mb-4">
            <div class="col-md-4">
                <label for="start" class="form-label">Tanggal Awal</label>
                <input type="date" id="start" name="start" class="form-control" value="{{ $start }}">
            </div>
            <div class="col-md-4">
                <label for="end" class="form-label">Tanggal Akhir</label>
                <input type="date" id="end" name="end" class="form-control" value="{{ $end }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        {{-- Ringkasan --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card text-bg-primary shadow">
                    <div class="card-body">
                        <h5 class="card-title">Total Penjualan</h5>
                        <p class="fs-4 fw-bold">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-bg-info shadow">
                    <div class="card-body">
                        <h5 class="card-title">Total Transaksi</h5>
                        <p class="fs-4 fw-bold">Rp {{ number_format($totalTransaksi, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Penjualan --}}
        <h4 class="mt-5">📌 Detail Penjualan</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped mt-3">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Pelanggan</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($penjualans as $pj)
                    <tr>
                        <td>{{ $pj->id }}</td>
                        <td>{{ $pj->pelanggan->nama ?? '-' }}</td>
                        <td>{{ $pj->tanggal }}</td>
                        <td>{{ ucfirst($pj->status) }}</td>
                        <td>Rp {{ number_format($pj->total, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data penjualan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Detail Transaksi --}}
        <h4 class="mt-5">📌 Detail Transaksi</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped mt-3">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Alamat</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksis as $tr)
                    <tr>
                        <td>{{ $tr->id }}</td>
                        <td>{{ $tr->user->username ?? '-' }}</td>
                        <td>{{ $tr->alamat }}</td>
                        <td>{{ strtoupper($tr->metode) }}</td>
                        <td>{{ ucfirst($tr->status) }}</td>
                        <td>Rp {{ number_format($tr->total, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card { border-radius: 12px; }
    .content-wrapper {
        min-height: 100vh;
        background: #f8f9fa;
        margin-left: 320px; /* 👉 tambah lagi jaraknya */
        padding: 20px;      /* 👉 biar isi konten tidak nempel */
    }
</style>
@endpush
