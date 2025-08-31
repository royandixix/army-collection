@extends('admin.layouts.app')

@section('title', 'Laporan Pelanggan')

@section('content')
<div class="cx-main-content">
    <div class="container-fluid">
        <div class="cx-page-title d-flex justify-content-between align-items-center flex-wrap mb-4">
            <h4 class="mb-0">Laporan Pelanggan</h4>
            <a href="{{ route('admin.laporan.pelanggan.cetak') }}" target="_blank" class="btn btn-danger">
                <i class="bi bi-printer"></i> Cetak PDF
            </a>
        </div>

        <div class="cx-card card-default">
            <div class="cx-card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Pelanggan</h5>
            </div>

            <div class="cx-card-content card-default">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="pelanggan-table">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>No HP</th>
                                <th>Alamat</th>
                                <th>Jumlah Transaksi</th>
                                <th>Bergabung</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pelanggans as $index => $user)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $user->pelanggan->nama ?? '-' }}</td>
                                    <td>{{ $user->email ?? '-' }}</td>
                                    <td>{{ $user->pelanggan->no_hp ?? '-' }}</td>
                                    <td>{{ $user->pelanggan->alamat ?? '-' }}</td>
                                    <td>{{ $user->transaksis_count ?? 0 }}</td>
                                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Tidak ada data pelanggan.</td>
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
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(function() {
            $('#pelanggan-table').DataTable({
                pageLength: 10,
                language: {
                    searchPlaceholder: "🔍 Cari pelanggan...",
                    zeroRecords: "Data tidak ditemukan",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ pelanggan",
                    paginate: {
                        previous: "⬅️",
                        next: "➡️"
                    }
                }
            });
        });
    </script>
@endpush
