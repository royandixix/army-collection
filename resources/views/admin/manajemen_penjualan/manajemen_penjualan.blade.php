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
                    <option value="belum_bayar">BELUM BAYAR</option>
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


                <a href="{{ route('admin.manajemen.penjualan.belum_bayar') }}" class="btn btn-warning">
                    Penjualan Belum Bayar
                </a>



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
                                @php $no = 1; @endphp
                                @foreach ($items as $item)
                                    @php
                                        $type = $item instanceof \App\Models\Penjualan ? 'penjualan' : 'transaksi';
                                        $uniqueId = $type . '-' . $item->id;

                                        // Skip transaksi jika sudah ada penjualan terkait
                                        if ($item instanceof \App\Models\Transaksi && $item->penjualan) {
                                            continue;
                                        }

                                        // Nama pelanggan
                                        $nama =
                                            $item->pelanggan->nama ??
                                            ($item->pelanggan?->user?->name ??
                                                ($item->user?->name ?? 'Tidak diketahui'));

                                        // Foto profil
                                        $defaultImg = asset('img/default-user.png');
                                        $fotoUrl = $defaultImg;
                                        if (
                                            !empty($item->pelanggan?->foto_profil) &&
                                            Storage::disk('public')->exists($item->pelanggan->foto_profil)
                                        ) {
                                            $fotoUrl = asset('storage/' . $item->pelanggan->foto_profil);
                                        } elseif (
                                            !empty($item->pelanggan?->user?->img) &&
                                            Storage::disk('public')->exists($item->pelanggan->user->img)
                                        ) {
                                            $fotoUrl = asset('storage/' . $item->pelanggan->user->img);
                                        } elseif (
                                            !empty($item->user?->img) &&
                                            Storage::disk('public')->exists($item->user->img)
                                        ) {
                                            $fotoUrl = asset('storage/' . $item->user->img);
                                        }

                                        // Detail produk
                                        $details = collect();
                                        if (
                                            $item instanceof \App\Models\Penjualan &&
                                            $item->detailPenjualans?->count() > 0
                                        ) {
                                            $details = $item->detailPenjualans;
                                        } elseif (
                                            $item instanceof \App\Models\Transaksi &&
                                            $item->detailTransaksi?->count() > 0
                                        ) {
                                            $details = $item->detailTransaksi;
                                        } elseif (
                                            $item instanceof \App\Models\Penjualan &&
                                            $item->transaksi?->detailTransaksi?->count() > 0
                                        ) {
                                            $details = $item->transaksi->detailTransaksi;
                                        }
                                        $totalJumlah = $details->sum('jumlah');

                                        // Bukti pembayaran
                                        if ($item instanceof \App\Models\Penjualan) {
                                            $buktiPath = $item->bukti_tf ?: $item->transaksi?->bukti_tf ?? null;
                                        } elseif ($item instanceof \App\Models\Transaksi) {
                                            $buktiPath = $item->bukti_tf ?: $item->penjualan?->bukti_tf ?? null;
                                        } else {
                                            $buktiPath = null;
                                        }

                                        if ($buktiPath && !str_starts_with($buktiPath, 'storage/')) {
                                            $buktiPath = 'storage/' . $buktiPath;
                                        }

                                        $buktiExists = $buktiPath
                                            ? Storage::disk('public')->exists(str_replace('storage/', '', $buktiPath))
                                            : false;
                                    @endphp
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ $fotoUrl }}" class="rounded-circle border" width="45"
                                                    height="45" style="object-fit: cover;">
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
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal ?? $item->created_at)->translatedFormat('l, d F Y H:i') }}
                                        </td>
                                        <td>Rp {{ number_format($item->total ?? 0, 0, ',', '.') }}</td>
                                        <td data-status="{{ $item->status }}">
                                            @if ($item instanceof \App\Models\Penjualan)
                                                <form id="status-form-{{ $uniqueId }}"
                                                    action="{{ route('admin.manajemen.penjualan_ubah_status', $item->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status" class="form-select form-select-sm status-select"
                                                        data-id="{{ $uniqueId }}">
                                                        <option value="lunas"
                                                            {{ $item->status == 'lunas' ? 'selected' : '' }}>LUNAS</option>
                                                        <option value="pending"
                                                            {{ $item->status == 'pending' ? 'selected' : '' }}>PENDING
                                                        </option>
                                                        <option value="batal"
                                                            {{ $item->status == 'batal' ? 'selected' : '' }}>BATAL</option>
                                                        <option value="belum_bayar"
                                                            {{ $item->status == 'belum_bayar' ? 'selected' : '' }}>BELUM
                                                            BAYAR</option>
                                                    </select>
                                                </form>
                                            @else
                                                <span
                                                    class="text-muted">{{ ucfirst(str_replace('_', ' ', $item->status ?? '-')) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $item->metode_pembayaran ?? '-')) }}</td>
                                        <td>
                                            @if ($buktiExists)
                                                <button type="button" class="btn btn-sm btn-outline-info"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#buktiModal-{{ $uniqueId }}">
                                                    <img src="{{ asset($buktiPath) }}" width="60" height="60"
                                                        class="rounded shadow-sm border" style="object-fit: cover;">
                                                </button>
                                            @elseif(($item->metode_pembayaran ?? '') === 'qris')
                                                <img src="{{ asset('images/qiris/qris.jpeg') }}" width="60"
                                                    height="60" class="rounded shadow-sm border"
                                                    style="object-fit: cover;">
                                            @elseif(($item->metode_pembayaran ?? '') === 'cod')
                                                <span class="badge bg-warning text-dark">COD — tidak memerlukan bukti
                                                    pembayaran</span>
                                            @else
                                                <span class="text-muted small">Belum upload</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                @if ($item instanceof \App\Models\Penjualan)
                                                    <a href="{{ route('admin.manajemen.manajemen_penjualan_edit', $item->id) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="ri-edit-line"></i>
                                                    </a>
                                                    <form id="delete-form-{{ $uniqueId }}"
                                                        action="{{ route('admin.manajemen.manajemen_penjualan_destroy', $item->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-danger delete-btn"
                                                            data-id="{{ $uniqueId }}">
                                                            <i class="ri-delete-bin-6-line"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Modal bukti --}}
                                    @if ($buktiExists)
                                        <div class="modal fade" id="buktiModal-{{ $uniqueId }}" tabindex="-1"
                                            aria-labelledby="buktiModalLabel-{{ $uniqueId }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="buktiModalLabel-{{ $uniqueId }}">
                                                            Bukti Pembayaran</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <img src="{{ asset($buktiPath) }}" class="img-fluid rounded"
                                                            alt="Bukti Pembayaran">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
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
                    search: "",
                    searchPlaceholder: "🔍 Cari penjualan...",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    zeroRecords: "Tidak ditemukan data",
                    info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                    infoEmpty: "Tidak ada data",
                    paginate: {
                        next: "➡",
                        previous: "⬅"
                    }
                },
                pageLength: 10,
                responsive: true
            });

            // Filter status
            $('#statusFilter').on('change', function() {
                const val = $(this).val();
                table.rows().every(function() {
                    const row = this.node();
                    const status = $('td:eq(5)', row).data('status') || '';
                    $(row).toggle(val === '' || status === val);
                });
            });

            // Pencarian global
            $('#global-search').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Submit status change
            $('.status-select').on('change', function() {
                const id = this.dataset.id;
                document.getElementById(`status-form-${id}`).submit();
            });

            // Hapus
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

            // Toast success
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
