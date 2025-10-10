@extends('admin.layouts.app')

@section('title', 'Manajemen Penjualan')

@section('content')
    <div class="cx-main-content">
        <div class="cx-page-title d-flex justify-content-between align-items-center flex-wrap">
            <div class="cx-tools d-flex gap-2 align-items-center">
                <a href="javascript:void(0)" class="refresh" data-bs-toggle="tooltip" title="Refresh">
                    <i class="ri-refresh-line"></i>
                </a>
            </div>
        </div>

        <div class="row mb-3 ms-3">
            <div class="col-md-4">
                <label for="statusFilter" class="form-label">Filter berdasarkan Status:</label>
                <select id="statusFilter" class="form-select">
                    <option value="">-- Semua Status --</option>
                    <option value="lunas">LUNAS</option>
                    <option value="pending">PENDING</option>
                    <option value="batal">BATAL</option>
                </select>
            </div>
            <div class="col-md-8">
                <label for="global-search" class="form-label">Pencarian Cepat:</label>
                <input type="text" id="global-search" class="form-control form-control-lg"
                    placeholder="Cari berdasarkan pelanggan, tanggal, total, atau barang...">
            </div>
        </div>

        <div class="mb-4 ms-3">
            <h5>Manajemen Penjualan</h5>
        </div>

        <div class="col-md-12">
            <div class="cx-card revenue-overview">
                <div class="cx-card-header d-flex justify-content-between align-items-center">
                    <h4 class="cx-card-title mb-0">Daftar Transaksi Penjualan</h4>
                </div>

                <div>
                    <a href="{{ route('admin.manajemen.manajemen_penjualan_create') }}"
                        class="btn btn-outline-primary rounded-pill d-inline-flex align-items-center mb-3">
                        <i class="ri-add-line me-1"></i>
                        Tambah Penjualan
                    </a>
                </div>

                <div class="cx-card-content card-default">
                    <div class="table-responsive">
                        <table id="penjualan-table" class="table table-striped table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Nama Barang</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Bukti Pembayaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
    @php
        $no = 1;
        $shownIds = [];
    @endphp
    @foreach ($items as $item)
        @php
            $type = $item instanceof \App\Models\Penjualan ? 'penjualan' : 'transaksi';
            $uniqueId = $type . '-' . $item->id;

            // Skip transaksi yang punya penjualan agar tidak duplikat
            if ($item instanceof \App\Models\Transaksi && $item->penjualan) {
                continue;
            }

            // Ambil nama pelanggan
            $nama = $item->pelanggan->nama 
                    ?? ($item->pelanggan?->user?->name 
                        ?? ($item->user?->name ?? 'Tidak diketahui'));
            if (!$nama || in_array($nama, $shownIds)) {
                continue; // skip jika duplikat atau tidak ada nama
            }
            $shownIds[] = $nama;

            // Ambil foto profil
            $defaultImg = asset('img/default-user.png');
            $fotoUrl = $defaultImg;

            if (!empty($item->pelanggan?->foto_profil) && file_exists(public_path('storage/' . $item->pelanggan->foto_profil))) {
                $fotoUrl = asset('storage/' . $item->pelanggan->foto_profil);
            } elseif (!empty($item->pelanggan?->user?->img) && file_exists(public_path('storage/' . $item->pelanggan->user->img))) {
                $fotoUrl = asset('storage/' . $item->pelanggan->user->img);
            } elseif (!empty($item->user?->img) && file_exists(public_path('storage/' . $item->user->img))) {
                $fotoUrl = asset('storage/' . $item->user->img);
            }

            // Ambil detail produk dengan pengecekan nullsafe
            $details = collect();
            if ($item instanceof \App\Models\Penjualan && $item->detailPenjualans?->count() > 0) {
                $details = $item->detailPenjualans;
            } elseif ($item instanceof \App\Models\Transaksi && $item->detailTransaksi?->count() > 0) {
                $details = $item->detailTransaksi;
            } elseif ($item instanceof \App\Models\Penjualan && $item->transaksi?->detailTransaksi?->count() > 0) {
                $details = $item->transaksi->detailTransaksi;
            }

            $totalJumlah = $details->sum('jumlah');
        @endphp

        <tr>
            <td>{{ $no++ }}</td>
            <td>
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ $fotoUrl }}" alt="Foto Profil" class="rounded-circle border" width="45" height="45" style="object-fit: cover;">
                    <span>{{ $nama }}</span>
                </div>
            </td>
            <td>
                <div><strong>Jumlah Total: {{ $totalJumlah }}</strong></div>
                <ul class="m-0 p-0" style="list-style:none;">
                    @foreach ($details as $detail)
                        <li>
                            <span>{{ $detail->produk?->nama ?? '-' }}</span>
                            <span style="font-weight:bold;"> x{{ $detail->jumlah }}</span>
                        </li>
                    @endforeach
                </ul>
            </td>
            <td>{{ \Carbon\Carbon::parse($item->tanggal ?? $item->created_at)->translatedFormat('l, d F Y H:i') }}</td>
            <td>Rp {{ number_format($item->total ?? 0, 0, ',', '.') }}</td>
            <td>
                @if ($item instanceof \App\Models\Penjualan)
                    <form id="status-form-{{ $uniqueId }}" action="{{ route('admin.manajemen.penjualan_ubah_status', $item->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="form-select form-select-sm status-select" data-id="{{ $uniqueId }}">
                            <option value="lunas" {{ $item->status == 'lunas' ? 'selected' : '' }}>LUNAS</option>
                            <option value="pending" {{ $item->status == 'pending' ? 'selected' : '' }}>PENDING</option>
                            <option value="batal" {{ $item->status == 'batal' ? 'selected' : '' }}>BATAL</option>
                        </select>
                    </form>
                @else
                    <span class="text-muted">{{ ucfirst($item->status ?? '-') }}</span>
                @endif
            </td>
            <td>{{ ucfirst(str_replace('_', ' ', $item->metode_pembayaran ?? '-')) }}</td>
            <td>
                @php
                    $buktiPath = $item->bukti_tf ?? ($item->transaksi?->bukti_tf ?? null);
                    if ($buktiPath) {
                        $buktiPath = preg_replace('/^storage\//', '', $buktiPath);
                    }
                @endphp
                @if ($buktiPath && file_exists(public_path('storage/' . $buktiPath)))
                    <a href="{{ asset('storage/' . $buktiPath) }}" target="_blank">
                        <img src="{{ asset('storage/' . $buktiPath) }}" width="60" height="60" class="rounded shadow-sm border" style="object-fit: cover;" alt="Bukti Pembayaran">
                    </a>
                @elseif(($item->metode_pembayaran ?? '') === 'qris')
                    <img src="{{ asset('images/qiris/qris.jpeg') }}" width="60" height="60" class="rounded shadow-sm border" style="object-fit: cover;" alt="QRIS">
                @else
                    <span class="text-muted small">Belum upload</span>
                @endif
            </td>
            <td>
                <div class="d-flex gap-2">
                    @if ($item instanceof \App\Models\Penjualan)
                        <a href="{{ route('admin.manajemen.manajemen_penjualan_edit', ['id' => $item->id]) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                            <i class="ri-edit-line"></i>
                        </a>
                        <form id="delete-form-{{ $uniqueId }}" action="{{ route('admin.manajemen.manajemen_penjualan_destroy', ['id' => $item->id]) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="{{ $uniqueId }}" title="Hapus">
                                <i class="ri-delete-bin-6-line"></i>
                            </button>
                        </form>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </div>
            </td>
        </tr>
    @endforeach
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
@endpush

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            const table = $('#penjualan-table').DataTable({
                language: {
                    search: "INPUT",
                    searchPlaceholder: "🔍 Cari penjualan...",
                    lengthMenu: "Tampilkan MENU entri",
                    zeroRecords: "Tidak ditemukan data",
                    info: "Menampilkan START hingga END dari TOTAL entri",
                    infoEmpty: "Tidak ada data",
                    paginate: {
                        next: "➡",
                        previous: "⬅"
                    }
                },
                pageLength: 10,
                responsive: true
            });

            $('#statusFilter').on('change', function() {
                table.column(5).search($(this).val()).draw();
            });

            $('#global-search').on('keyup', function() {
                table.search(this.value).draw();
            });

            $('.status-select').on('change', function() {
                const id = this.dataset.id;
                document.getElementById(`status-form-${id}`).submit();
            });

            $('.delete-btn').on('click', function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data penjualan tidak bisa dikembalikan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#3b82f6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) $(`#delete-form-${id}`).submit();
                });
            });

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#f9f9f9',
                color: '#1e293b',
                iconColor: '#10b981'
            });

            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: @js(session('success'))
                });
            @endif
        });
    </script>
@endpush
