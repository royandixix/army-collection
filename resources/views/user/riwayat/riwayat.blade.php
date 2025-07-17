@extends('user.layouts.app')

@section('title', 'Halaman Riwayat')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4 fw-semibold text-success">Riwayat Transaksi</h4>

    @if($riwayats->isEmpty())
        <div class="alert alert-info text-center">
            <i class="bi bi-clock-history fs-4"></i><br>
            Belum ada riwayat transaksi.
        </div>
    @else
        {{-- Pencarian --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <input type="text" id="searchInput" class="form-control shadow-sm" placeholder="Cari berdasarkan tanggal atau status...">
            </div>
        </div>

        {{-- Tabel riwayat --}}
        <div class="table-responsive">
            <table class="table table-bordered align-middle shadow-sm" id="riwayatTable">
                <thead class="table-success text-center">
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayats as $i => $riwayat)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($riwayat->tanggal)->translatedFormat('d F Y') }}</td>
                            <td class="text-end">Rp {{ number_format($riwayat->total, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <span class="badge 
                                    @if($riwayat->status == 'selesai') bg-success
                                    @elseif($riwayat->status == 'diproses') bg-warning text-dark
                                    @elseif($riwayat->status == 'batal') bg-danger
                                    @else bg-secondary
                                    @endif
                                ">
                                    {{ ucfirst($riwayat->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

@push('style')
<style>
    .table th,
    .table td {
        vertical-align: middle !important;
    }

    #searchInput {
        border-radius: 8px;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('searchInput');
        const table = document.getElementById('riwayatTable').getElementsByTagName('tbody')[0];

        searchInput.addEventListener('keyup', function () {
            const filter = this.value.toLowerCase();
            const rows = table.getElementsByTagName('tr');

            Array.from(rows).forEach(row => {
                const tanggal = row.cells[1].innerText.toLowerCase();
                const status = row.cells[3].innerText.toLowerCase();

                if (tanggal.includes(filter) || status.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
@endpush
