@extends('admin.layouts.app')

@section('title', 'Manajemen Pelanggan')

@section('content')
<div class="cx-main-content">
    <!-- Judul Halaman -->
    <div class="cx-page-title d-flex justify-content-between align-items-center flex-wrap mb-3">
        <h4 class="mb-0">Daftar Pelanggan</h4>
        <a href="{{ route('admin.manajemen.manajemen_pelanggan_create') }}" class="btn btn-outline-primary rounded-pill">
            <i class="ri-add-line me-1"></i> Tambah Pelanggan
        </a>
    </div>

    <!-- Input Pencarian -->
    <div class="mb-3">
        <input type="text" id="searchPelanggan" class="form-control form-control-lg rounded-pill" placeholder="🔍 Cari nama, email, atau nomor HP pelanggan...">
    </div>

    <!-- Tabel Pelanggan -->
    <div class="col-md-12">
        <div class="cx-card card-default">
            <div class="cx-card-content p-4">
                <div class="table-responsive">
                    <table id="pelanggan-table" class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Nomor HP</th>
                                <th>Alamat</th>
                                <th>Transaksi</th>
                                <th>Metode Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pelanggans as $pelanggan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $pelanggan->username }}</td>
                                <td>{{ $pelanggan->email }}</td>
                                <td>{{ $pelanggan->pelanggan->no_hp ?? '-' }}</td>
                                <td>{{ $pelanggan->pelanggan->alamat ?? '-' }}</td>
                                <td>{{ $pelanggan->transaksis_count }} transaksi</td>
                                <td>
                                    @php
                                        // Ambil transaksi terbaru berdasarkan created_at
                                        $transaksi = $pelanggan->transaksis->sortByDesc('created_at')->first();
                                        $metode = optional($transaksi)->metode;
                                        $total  = optional($transaksi)->total;
                                        $tanggal = optional($transaksi)->created_at;
                                    @endphp
                        
                                    @if ($metode)
                                    <a href="#" 
                                       class="badge bg-{{ 
                                           $metode === 'cod' ? 'success' : 
                                           ($metode === 'transfer' ? 'primary' : 
                                           ($metode === 'qris' ? 'warning text-dark' : 'secondary')) 
                                       }} text-decoration-none"
                                       data-bs-toggle="modal"
                                       data-bs-target="#detailTransaksiModal"
                                       data-nama="{{ $pelanggan->username }}"
                                       data-metode="{{ strtoupper($metode) }}"
                                       data-total="Rp {{ number_format($total ?? 0, 0, ',', '.') }}"
                                       data-tanggal="{{ $tanggal ? $tanggal->format('d-m-Y H:i') : '-' }}">
                                        {{ strtoupper($metode) }}
                                    </a>
                                    @else
                                    <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                
            
                                <td>
                                    <div class="d-flex gap-2">
                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('admin.manajemen.manajemen_pelanggan_edit', $pelanggan->pelanggan->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="ri-edit-line"></i>
                                        </a>
                        
                                        {{-- Tombol Hapus --}}
                                        <form id="delete-form-{{ $pelanggan->pelanggan->id }}" action="{{ route('admin.manajemen.manajemen_pelanggan_destroy', $pelanggan->pelanggan->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="{{ $pelanggan->pelanggan->id }}" title="Hapus">
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
            </div>
        </div>
    </div>

    <!-- Toast Notifikasi -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
        <div id="liveToast" class="toast align-items-center text-white bg-success border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage">Action executed</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Modal Detail Transaksi -->
    <div class="modal fade" id="detailTransaksiModal" tabindex="-1" aria-labelledby="detailTransaksiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg rounded-4 border-0">
                <div class="modal-header bg-gradient bg-primary text-white rounded-top-4">
                    <h5 class="modal-title fw-semibold" id="detailTransaksiModalLabel">
                        <i class="ri-file-info-line me-2"></i>Detail Transaksi Terakhir
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body px-4 pt-4 pb-0">
                    <div class="mb-3 d-flex align-items-center">
                        <i class="ri-user-3-line text-primary fs-5 me-2"></i>
                        <strong class="me-2">Nama Pelanggan:</strong> <span id="modalNama" class="text-muted"></span>
                    </div>
                    <div class="mb-3 d-flex align-items-center">
                        <i class="ri-bank-card-line text-success fs-5 me-2"></i>
                        <strong class="me-2">Metode Pembayaran:</strong> <span id="modalMetode" class="text-muted"></span>
                    </div>
                    <div class="mb-3 d-flex align-items-center">
                        <i class="ri-wallet-3-line text-warning fs-5 me-2"></i>
                        <strong class="me-2">Total:</strong> <span id="modalTotal" class="text-muted"></span>
                    </div>
                    <div class="mb-4 d-flex align-items-center">
                        <i class="ri-calendar-line text-info fs-5 me-2"></i>
                        <strong class="me-2">Tanggal:</strong> <span id="modalTanggal" class="text-muted"></span>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i> Tutup
                    </button>
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
    // Konfirmasi Hapus dengan SweetAlert
    $('.delete-btn').on('click', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Yakin ingin menghapus?'
            , text: "Data pelanggan akan dihapus permanen!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#d33'
            , cancelButtonColor: '#6c757d'
            , confirmButtonText: 'Ya, hapus'
            , cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $(`#delete-form-${id}`).submit();
            }
        });
    });

    // Inisialisasi DataTables
    $(document).ready(function() {
        const table = $('#pelanggan-table').DataTable({
            language: {
                search: "_INPUT_"
                , searchPlaceholder: "🔍 Cari pelanggan..."
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

        // Search manual (opsional jika input terpisah dari bawaan DataTables)
        $('#searchPelanggan').on('keyup', function() {
            table.search(this.value).draw();
        });
    });

    // SweetAlert Toast
    const Toast = Swal.mixin({
        toast: true
        , position: 'top-end'
        , showConfirmButton: false
        , timer: 3000
        , timerProgressBar: true
        , iconColor: '#10b981'
        , background: '#fff'
        , color: '#333'
        , customClass: {
            popup: 'rounded-xl shadow text-sm px-4 py-3 mt-4 bg-white'
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
        , title: 'Periksa form kamu'
        , text: @js($errors->first())
    });
    @endif

    // Isi modal saat badge diklik
    $('#detailTransaksiModal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget);
        $('#modalNama').text(button.data('nama'));
        $('#modalMetode').text(button.data('metode'));
        $('#modalTotal').text(button.data('total'));
        $('#modalTanggal').text(button.data('tanggal'));
    });

</script>
@endpush
