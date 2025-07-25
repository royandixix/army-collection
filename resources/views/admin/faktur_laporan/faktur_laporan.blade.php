@extends('admin.layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="cx-main-content">
    <!-- Judul Halaman -->
    <div class="cx-page-title d-flex justify-content-between align-items-center flex-wrap">
        <h4 class="mb-0">Laporan Penjualan</h4>
    </div>

    <!-- Tabel Laporan -->
    <div class="cx-card card-default mt-4">
        <div class="cx-card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data Transaksi Penjualan</h5>
        </div>

        <div class="cx-card-content card-default">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="laporan-table">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Pelanggan</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Cetak Faktur</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penjualans as $penjualan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            {{-- Gunakan Carbon::parse() agar aman meski tanggal adalah string --}}
                            <td>{{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d-m-Y') }}</td>
                            <td>{{ $penjualan->pelanggan->nama ?? '-' }}</td>
                            <td>Rp {{ number_format($penjualan->total, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ $penjualan->status == 'lunas' ? 'success' : ($penjualan->status == 'pending' ? 'warning' : 'danger') }}">
                                    {{ strtoupper($penjualan->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.laporan.faktur_laporan_show', $penjualan->id) }}" class="btn btn-sm btn-outline-info">
                                    <i class="ri-printer-line"></i> Cetak
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div> <!-- /.table-responsive -->
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        $('#laporan-table').DataTable({
            language: {
                searchPlaceholder: "🔍 Cari laporan...",
                zeroRecords: "Data tidak ditemukan",
                lengthMenu: "Tampilkan _MENU_ entri",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                paginate: {
                    previous: "⬅️",
                    next: "➡️"
                }
            }
        });
    });
</script>
@endpush
