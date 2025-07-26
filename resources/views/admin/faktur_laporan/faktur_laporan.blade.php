@extends('admin.layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="cx-main-content">
    <div class="cx-page-title d-flex justify-content-between align-items-center flex-wrap mb-4">
        <h4 class="mb-0">📊 Laporan Penjualan & Pelanggan</h4>
    </div>

    <div class="cx-card card-default">
        <div class="cx-card-header">
            <h5 class="mb-0">📋 Data Penjualan</h5>
        </div>

        <div class="cx-card-content card-default">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="laporan-table">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Nomor HP</th>
                            <th>Alamat</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Jenis</th>
                            <th>Metode Pembayaran</th>
                            <th>Cetak</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penjualans as $index => $penjualan)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ optional($penjualan->pelanggan)->nama ?? '-' }}</td>
                            <td>{{ optional(optional($penjualan->pelanggan)->user)->email ?? '-' }}</td>
                            <td>{{ optional($penjualan->pelanggan)->no_hp ?? '-' }}</td>
                            <td>{{ optional($penjualan->pelanggan)->alamat ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d-m-Y') }}</td>
                            <td>Rp {{ number_format($penjualan->total, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                        $penjualan->status == 'lunas' ? 'success' : 
                                        ($penjualan->status == 'pending' ? 'warning' : 'danger') 
                                    }}">
                                    {{ strtoupper($penjualan->status) }}
                                </span>
                            </td>
                            <td>{{ $penjualan->jenis_transaksi ?? '-' }}</td>
                            <td>
                                @php
                                $transaksi = $penjualan->transaksi;
                                $metode = optional($transaksi)->metode;
                                @endphp

                                @if ($metode)
                                <a href="#" class="badge bg-{{ 
                                            $metode === 'cod' ? 'success' : 
                                            ($metode === 'transfer' ? 'primary' : 
                                            ($metode === 'qris' ? 'warning text-dark' : 'secondary')) 
                                        }} text-decoration-none" data-bs-toggle="modal" data-bs-target="#detailTransaksiModal" data-nama="{{ optional($penjualan->pelanggan)->nama }}" data-metode="{{ strtoupper($metode) }}" data-total="Rp {{ number_format($transaksi->total ?? 0, 0, ',', '.') }}" data-tanggal="{{ optional($transaksi)->created_at?->format('d-m-Y H:i') }}">
                                    {{ strtoupper($metode) }}
                                </a>
                                @else
                                <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.laporan.faktur_pdf', $penjualan->id) }}" class="btn btn-sm btn-danger" target="_blank">

                                    <i class="ri-file-pdf-line"></i> Cetak PDF
                                </a>
                                
                                
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted">Tidak ada data penjualan.</td>
                        </tr>
                        @endforelse

                    </tbody>


                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal dummy agar tidak error saat klik badge -->
<div class="modal fade" id="detailTransaksiModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p><strong>Nama:</strong> <span id="modal-nama"></span></p>
                <p><strong>Metode:</strong> <span id="modal-metode"></span></p>
                <p><strong>Total:</strong> <span id="modal-total"></span></p>
                <p><strong>Tanggal:</strong> <span id="modal-tanggal"></span></p>
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
    $(document).ready(function() {
        $('#laporan-table').DataTable({
            pageLength: 10
            , language: {
                searchPlaceholder: "🔍 Cari..."
                , zeroRecords: "Data tidak ditemukan"
                , lengthMenu: "Tampilkan _MENU_ entri"
                , info: "Menampilkan _START_ - _END_ dari _TOTAL_ data"
                , paginate: {
                    previous: "⬅️"
                    , next: "➡️"
                }
            }
        });

        // Handler untuk modal detail transaksi
        $('#detailTransaksiModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            $('#modal-nama').text(button.data('nama'));
            $('#modal-metode').text(button.data('metode'));
            $('#modal-total').text(button.data('total'));
            $('#modal-tanggal').text(button.data('tanggal'));
        });
    });

</script>
@endpush
