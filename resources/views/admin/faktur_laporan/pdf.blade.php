@extends('admin.layouts.app')

@section('title', 'Faktur Penjualan')

@section('content')
<div class="cx-main-content">
    <h4>🧾 Faktur Penjualan</h4>

    <p><strong>Nama:</strong> {{ $penjualan->pelanggan->nama ?? '-' }}</p>
    <p><strong>Email:</strong> {{ $penjualan->pelanggan->user->email ?? '-' }}</p>
    <p><strong>No HP:</strong> {{ $penjualan->pelanggan->no_hp ?? '-' }}</p>
    <p><strong>Alamat:</strong> {{ $penjualan->pelanggan->alamat ?? '-' }}</p>
    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d-m-Y') }}</p>
    <p><strong>Status:</strong> {{ strtoupper($penjualan->status) }}</p>
    <p><strong>Total:</strong> Rp {{ number_format($penjualan->total, 0, ',', '.') }}</p>
</div>
@endsection
