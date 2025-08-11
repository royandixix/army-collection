@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="cx-main-content">
    <div class="container-fluid mt-4">
        <!-- Kartu Statistik -->
        <div class="row g-4 mb-4">
            @php
                $cards = [
                    ['label' => 'Total Penjualan', 'value' => 'Rp ' . number_format($totalPenjualan ?? 0, 0, ',', '.'), 'icon' => 'ri-money-dollar-circle-line', 'color' => 'primary', 'note' => '+5% dari minggu lalu', 'noteClass' => 'text-success'],
                    ['label' => 'Total Pelanggan', 'value' => $jumlahPelanggan ?? 0, 'icon' => 'ri-user-3-line', 'color' => 'info', 'note' => 'Data terbaru', 'noteClass' => 'text-info'],
                    ['label' => 'Transaksi Pending', 'value' => $pendingTransaksi ?? 0, 'icon' => 'ri-time-line', 'color' => 'warning', 'note' => 'Perlu konfirmasi', 'noteClass' => 'text-muted'],
                    ['label' => 'Produk Tersedia', 'value' => $jumlahProduk ?? 0, 'icon' => 'ri-box-3-line', 'color' => 'success', 'note' => 'Cek stok', 'noteClass' => 'text-muted'],
                ];
            @endphp

            @foreach ($cards as $card)
            <div class="col-md-3">
                <div class="card card-hover border-0 shadow-sm h-100 rounded-4 transition-all">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="icon-circle bg-{{ $card['color'] }} bg-opacity-10 rounded-circle p-3">
                            <i class="{{ $card['icon'] }} text-{{ $card['color'] }} fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small mb-1">{{ $card['label'] }}</div>
                            <div class="h5 fw-bold mb-1">{{ $card['value'] }}</div>
                            <small class="{{ $card['noteClass'] }}"><i class="ri-information-line"></i> {{ $card['note'] }}</small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Grafik Penjualan -->
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">📊 Grafik Penjualan Mingguan</h5>
            </div>
            <div class="card-body">
                <canvas id="salesChart" height="120"></canvas>
            </div>
        </div>

        <!-- Tabel Penjualan -->
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <h5 class="mb-2 mb-md-0">📈 Riwayat Penjualan Terbaru</h5>
                <input type="text" id="global-search" class="form-control form-control-sm w-100 w-md-50" placeholder="🔍 Cari transaksi...">
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="deal-table" class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($penjualans as $p)
                            <tr>
                                <td>{{ $p->id }}</td>
                                <td>{{ $p->pelanggan->nama ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d M Y') }}</td>
                                <td>
                                    <span class="badge rounded-pill bg-{{ $p->status === 'active' ? 'success' : 'warning' }} px-3 py-1 text-uppercase fw-semibold">
                                        {{ $p->status }}
                                    </span>
                                </td>
                                <td>Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada data penjualan</td>
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
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet" />
<style>
    .card-hover {
        transition: all 0.3s ease;
    }

    .card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 0 12px rgba(0,0,0,0.08);
    }

    .icon-circle {
        transition: transform 0.3s ease;
    }

    .card-hover:hover .icon-circle {
        transform: rotate(8deg);
    }

    table.dataTable tbody tr:hover {
        background-color: #f9f9f9;
    }

    .card h5, .card .h5 {
        font-weight: 600;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        const table = $('#deal-table').DataTable({
            responsive: true,
            paging: false,
            info: false,
            ordering: false
        });

        $('#global-search').on('keyup', function () {
            table.search(this.value).draw();
        });

        // Grafik Penjualan (dummy data, bisa diganti)
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                datasets: [{
                    label: 'Total Penjualan',
                    data: [1200000, 1500000, 800000, 2000000, 2500000, 2200000, 1800000],
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#0d6efd'
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => 'Rp ' + value.toLocaleString()
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
