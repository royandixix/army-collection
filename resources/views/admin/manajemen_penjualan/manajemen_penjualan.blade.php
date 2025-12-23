@extends('admin.layouts.app')

@section('title', 'Manajemen Penjualan')

@section('content')
    <div class="cx-main-content">

        {{-- ==================== MANAGEMEN PENJUALAN ==================== --}}
        <div class="cx-page-title d-flex justify-content-between align-items-center flex-wrap mb-3">
            <h5>Manajemen Penjualan</h5>
            <div class="cx-tools d-flex gap-2 align-items-center">
                <a href="javascript:void(0)" class="refresh" data-bs-toggle="tooltip" title="Refresh">
                    <i class="ri-refresh-line"></i>
                </a>
            </div>
        </div>

        {{-- Filter & Pencarian --}}
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

        {{-- Tombol Tambah Penjualan --}}
        <div class="mb-4 ms-3">
            <a href="{{ route('admin.manajemen.manajemen_penjualan_create') }}"
                class="btn btn-outline-primary rounded-pill d-inline-flex align-items-center mb-3">
                <i class="ri-add-line me-1"></i> Tambah Penjualan
            </a>
            <a href="{{ route('admin.manajemen.penjualan.belum_bayar') }}" class="btn btn-warning mb-3">
                Penjualan Belum Bayar
            </a>
        </div>

        {{-- Tabel Penjualan --}}
        <div class="col-md-12 mb-5">
            <div class="cx-card revenue-overview">
                <div class="cx-card-header d-flex justify-content-between align-items-center">
                    <h4 class="cx-card-title mb-0">Daftar Transaksi Penjualan</h4>
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
                                    <th>Status Pengiriman</th>
                                    <th>Bukti Pengiriman</th>
                                    <th>Bukti Diterima</th>

                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp

                                @foreach ($items as $item)
                                    @php
                                        $uniqueId = 'penjualan-' . $item->id;

                                        // ================== Nama Pelanggan / User ==================
                                        $nama = 'Tidak diketahui';
                                        if ($item->pelanggan) {
                                            $nama =
                                                $item->pelanggan->nama ??
                                                ($item->pelanggan->user->name ?? 'Tidak diketahui');
                                        } elseif ($item->user) {
                                            $nama = $item->user->name ?? 'Tidak diketahui';
                                        }

                                        // ================== Foto Profil ==================
                                        $defaultImg = asset('img/default-user.png');
                                        $fotoUrl = $defaultImg;

                                        if (
                                            $item->pelanggan &&
                                            !empty($item->pelanggan->foto_profil) &&
                                            Storage::disk('public')->exists($item->pelanggan->foto_profil)
                                        ) {
                                            $fotoUrl = asset('storage/' . $item->pelanggan->foto_profil);
                                        } elseif (
                                            $item->pelanggan?->user &&
                                            !empty($item->pelanggan->user->img) &&
                                            Storage::disk('public')->exists($item->pelanggan->user->img)
                                        ) {
                                            $fotoUrl = asset('storage/' . $item->pelanggan->user->img);
                                        } elseif (
                                            !empty($item->user?->img) &&
                                            Storage::disk('public')->exists($item->user->img)
                                        ) {
                                            $fotoUrl = asset('storage/' . $item->user->img);
                                        }

                                        $details = $item->detailPenjualans ?? collect();
                                        $totalJumlah = $details->sum('jumlah');

                                        $buktiPembayaran = $item->bukti_tf ? asset('storage/' . $item->bukti_tf) : null;
                                        $statusKirim = $item->status_pengiriman ?? 'pending';
                                        $buktiKirim = $item->bukti_pengiriman
                                            ? asset('storage/' . $item->bukti_pengiriman)
                                            : null;
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
                                                        {{ $item->status == 'pending' ? 'selected' : '' }}>PENDING</option>
                                                    <option value="batal"
                                                        {{ $item->status == 'batal' ? 'selected' : '' }}>BATAL</option>
                                                    <option value="belum_bayar"
                                                        {{ $item->status == 'belum_bayar' ? 'selected' : '' }}>BELUM BAYAR
                                                    </option>
                                                </select>
                                            </form>
                                        </td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $item->metode_pembayaran ?? '-')) }}</td>
                                        <td>
                                            @if ($buktiPembayaran)
                                                <button type="button" class="btn btn-sm btn-outline-info"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#buktiModal-{{ $uniqueId }}">
                                                    <img src="{{ $buktiPembayaran }}" width="60" height="60"
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

                                        {{-- Status Pengiriman & Upload Bukti --}}
                                        <td>
                                            <form action="{{ route('admin.penjualan.update_pengiriman', $item->id) }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status_pengiriman" class="form-select form-select-sm mb-1">
                                                    <option value="pending"
                                                        {{ $statusKirim == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="dikirim"
                                                        {{ $statusKirim == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                                    <option value="diterima"
                                                        {{ $statusKirim == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                                </select>
                                                <input type="file" name="bukti_pengiriman"
                                                    class="form-control form-control-sm mb-1">
                                                <button type="submit" class="btn btn-sm btn-primary w-100">Update</button>
                                            </form>
                                        </td>
                                        <td>
                                            @if ($buktiKirim)
                                                <a href="{{ $buktiKirim }}" target="_blank">
                                                    <img src="{{ $buktiKirim }}" width="60" height="60"
                                                        style="object-fit: cover;">
                                                </a>
                                            @else
                                                <span class="text-muted small">Belum ada</span>
                                            @endif
                                        </td>
                                        {{-- <td>
                                            @if ($item->buktiDiterima)
                                                <a href="{{ $item->buktiDiterima }}" target="_blank">
                                                    <img src="{{ $item->buktiDiterima }}" width="60" height="60"
                                                        style="object-fit: cover;">
                                                </a>
                                            @else
                                                <span class="text-muted small">Belum ada</span>
                                            @endif
                                        </td> --}}

                                        <td>
                                            <div class="d-flex gap-2">
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
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Modal bukti pembayaran --}}
                                    @if ($buktiPembayaran)
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
                                                        <img src="{{ $buktiPembayaran }}" class="img-fluid rounded"
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
            const tablePenjualan = $('#penjualan-table').DataTable({
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

            $('#statusFilter').on('change', function() {
                const val = $(this).val();
                tablePenjualan.rows().every(function() {
                    const row = this.node();
                    const status = $('td:eq(5)', row).data('status') || '';
                    $(row).toggle(val === '' || status === val);
                });
            });

            $('#global-search').on('keyup', function() {
                tablePenjualan.search(this.value).draw();
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
