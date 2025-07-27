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
            <input type="text" id="global-search" class="form-control form-control-lg" placeholder="Cari berdasarkan pelanggan, tanggal, atau total...">
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
                <a href="{{ route('admin.manajemen.manajemen_penjualan_create') }}" class="btn btn-outline-primary rounded-pill d-inline-flex align-items-center mb-3">
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
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Jenis</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach($penjualans->concat($transaksis) as $item)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @php
                                        $user = $item->user ?? null;
                                        $fotoUrl = $user?->profile_photo_url ?? asset('img/default-user.png');
                                        $nama = $item->pelanggan->nama
                                        ?? $user?->name
                                        ?? $user?->username
                                        ?? $user?->email
                                        ?? 'Pengguna';
                                        @endphp
                                        <img src="{{ $fotoUrl }}" alt="Foto" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                                        <span>{{ $nama }}</span>
                                    </div>
                                </td>
                                <td>
                                    @php
                                    // Ambil nilai tanggal dari properti 'tanggal' jika ada, jika tidak gunakan 'created_at'
                                    \Carbon\Carbon::setLocale('id');
                                    $waktu = $item->tanggal ?? $item->created_at;
                                    @endphp
                                    <!-- Format: Sabtu, 26 Juli 2025 14:30 -->
                                    <span>{{ \Carbon\Carbon::parse($waktu)->translatedFormat('l, d F Y H:i') }}</span>
                                </td>


                                <td>Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                <td>
                                    @php $isPenjualan = isset($item->pelanggan); @endphp
                                    <form id="status-form-{{ $item->id }}" action="{{ route('admin.manajemen.penjualan_ubah_status', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="form-select form-select-sm status-select" data-id="{{ $item->id }}">
                                            @if ($isPenjualan)
                                            <option value="lunas" {{ $item->status == 'lunas' ? 'selected' : '' }}>LUNAS</option>
                                            <option value="pending" {{ $item->status == 'pending' ? 'selected' : '' }}>PENDING</option>
                                            <option value="batal" {{ $item->status == 'batal' ? 'selected' : '' }}>BATAL</option>
                                            @else
                                            <option value="pending" {{ $item->status == 'pending' ? 'selected' : '' }}>PENDING</option>
                                            <option value="diproses" {{ $item->status == 'diproses' ? 'selected' : '' }}>DIPROSES</option>
                                            <option value="selesai" {{ $item->status == 'selesai' ? 'selected' : '' }}>SELESAI</option>
                                            <option value="batal" {{ $item->status == 'batal' ? 'selected' : '' }}>BATAL</option>
                                            @endif
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    @php
                                    $role = $item->user->role ?? 'unknown';
                                    @endphp

                                    @if ($role === 'admin')
                                    <span class="badge bg-dark">Admin</span>
                                    @elseif ($role === 'user')
                                    <span class="badge bg-secondary">User</span>
                                    @else
                                    <span class="badge bg-danger">Unknown</span>
                                    @endif
                                </td>


                                <td>
                                    <div class="d-flex gap-2">
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('admin.manajemen.manajemen_penjualan_edit', $item->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <form id="delete-form-{{ $item->id }}" action="{{ route('admin.manajemen.manajemen_penjualan_destroy', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="{{ $item->id }}" title="Hapus">
                                                <i class="ri-delete-bin-6-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div> <!-- /.cx-card-content -->
        </div> <!-- /.cx-card -->
    </div> <!-- /.col-md-12 -->
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
    document.querySelectorAll('.status-select').forEach(function(select) {
        select.addEventListener('change', function() {
            const id = this.dataset.id;
            const form = document.getElementById(`status-form-${id}`);
            if (form) {
                form.submit();
            }
        });
    });

    $(document).ready(function() {
        const table = $('#penjualan-table').DataTable({
            language: {
                search: "_INPUT_"
                , searchPlaceholder: "🔍 Cari penjualan..."
                , lengthMenu: "Tampilkan _MENU_ entri"
                , zeroRecords: "Tidak ditemukan data"
                , info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri"
                , infoEmpty: "Tidak ada data"
                , paginate: {
                    next: "➡️"
                    , previous: "⬅️"
                }
            }
            , pageLength: 10
            , responsive: true
        });

        $('#statusFilter').on('change', function() {
            table.column(4).search($(this).val()).draw();
        });

        $('#global-search').on('keyup', function() {
            table.search(this.value).draw();
        });

        $('.delete-btn').on('click', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Yakin ingin menghapus?'
                , text: "Data penjualan tidak bisa dikembalikan."
                , icon: 'warning'
                , showCancelButton: true
                , confirmButtonColor: '#ef4444'
                , cancelButtonColor: '#3b82f6'
                , confirmButtonText: 'Ya, hapus!'
                , cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(`#delete-form-${id}`).submit();
                }
            });
        });
    });

    const Toast = Swal.mixin({
        toast: true
        , position: 'top-end'
        , showConfirmButton: false
        , timer: 3000
        , timerProgressBar: true
        , background: '#f9f9f9'
        , color: '#1e293b'
        , iconColor: '#10b981'
        , customClass: {
            popup: 'rounded-xl shadow-md text-sm px-4 py-3 mt-4 border border-gray-200'
        }
        , didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    @if(session('success'))
    Toast.fire({
        icon: 'success'
        , title: @js(session('success'))
    });
    @endif

    @if(session('error'))
    Toast.fire({
        icon: 'error'
        , title: @js(session('error'))
    });
    @endif

    @if($errors->any())
    Toast.fire({
        icon: 'warning'
        , title: 'Form belum lengkap'
        , text: @js($errors->first())
    });
    @endif

</script>
@endpush
