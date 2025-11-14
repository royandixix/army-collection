@extends('admin.layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="cx-main-content">
    <div class="container-fluid">
        <!-- Judul & tombol cetak -->
        <div class="cx-page-title d-flex justify-content-between align-items-center flex-wrap mb-4">
            <h4 class="mb-0">Laporan Penjualan</h4>

            <!-- Form filter bulan & tahun + cetak -->
            <form action="{{ route('admin.laporan.penjualan.cetak') }}" method="GET" target="_blank" class="d-flex align-items-center gap-2 flex-wrap">
                <select name="bulan" class="form-select form-select-sm" style="width: auto;">
                    <option value="">Semua Bulan</option>
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                        </option>
                    @endfor
                </select>

                <select name="tahun" class="form-select form-select-sm" style="width: auto;">
                    <option value="">Semua Tahun</option>
                    @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>

                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="bi bi-printer"></i> Cetak PDF
                </button>
            </form>
        </div>

        <!-- Card tabel -->
        <div class="cx-card card-default shadow-sm">
            <div class="cx-card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Penjualan</h5>
                <input type="text" id="global-search" class="form-control form-control-sm w-auto"
                       placeholder="🔍 Cari penjualan...">
            </div>

            <div class="cx-card-content p-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="penjualan-table">
                        <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama User</th>
                            <th>Nama Produk</th>
                            <th>Tanggal</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $no = 1; @endphp
                        @forelse ($items as $item)
                            @php
                                $isPenjualan = $item instanceof \App\Models\Penjualan;
                                $details = $isPenjualan ? $item->detailPenjualans : $item->detailTransaksi;
                                $userName = $isPenjualan 
                                            ? ($item->pelanggan->nama ?? $item->pelanggan->user->username ?? '-') 
                                            : ($item->user->username ?? '-');
                            @endphp

                            @if ($details && $details->count() > 0)
                                @foreach ($details as $detail)
                                    @php
                                        $hargaSatuan = $isPenjualan ? ($detail->produk->harga ?? 0) : ($detail->harga ?? 0);
                                        $total = ($detail->jumlah ?? 0) * $hargaSatuan;
                                        $tanggal = $item->tanggal ?? $item->created_at;
                                    @endphp
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $userName }}</td>
                                        <td>{{ $detail->produk->nama ?? '-' }}</td>
                                        <td>{{ $tanggal?->format('d/m/Y') ?? '-' }}</td>
                                        <td>{{ $detail->jumlah ?? 0 }}</td>
                                        <td>Rp {{ number_format($hargaSatuan, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    Tidak ada data penjualan atau transaksi.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<style>
    #global-search {
        margin-bottom: 10px;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f5f9;
    }

    table.dataTable th, table.dataTable td {
        vertical-align: middle;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    const table = $('#penjualan-table').DataTable({
        pageLength: 10,
        responsive: true,
        searching: false, // matikan search default DataTables
        language: {
            zeroRecords: "Data tidak ditemukan",
            lengthMenu: "Tampilkan _MENU_ entri",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ penjualan",
            infoEmpty: "Tidak ada data",
            paginate: {
                previous: "⬅",
                next: "➡"
            }
        }
    });

    // Pencarian global via input sendiri
    $('#global-search').on('keyup', function() {
        table.search(this.value).draw();
    });
});
</script>
@endpush
