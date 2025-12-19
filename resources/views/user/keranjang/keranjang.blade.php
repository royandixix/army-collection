@extends('user.layouts.app')

@section('title', 'Keranjang & Checkout')

@section('content')
<div class="container py-5 animate__animated animate__fadeInUp animate__faster">

    {{-- HEADER --}}
    <div class="mb-4 d-flex align-items-center gap-3">
        <i class="bi bi-cart-check-fill fs-2 text-primary"></i>
        <h2 class="fw-bold m-0">Keranjang & Checkout</h2>
    </div>

    {{-- KERANJANG KOSONG --}}
    @if ($keranjang->isEmpty())
        <div class="alert alert-light text-center py-5 shadow-sm rounded">
            <i class="bi bi-cart-x fs-1 text-secondary"></i>
            <p class="fs-5 mt-3 text-muted">Keranjang kamu masih kosong.</p>
            <a href="{{ route('user.produk.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-1"></i> Mulai Belanja
            </a>
        </div>
    @else

    <form id="checkoutForm" method="POST" action="{{ route('user.keranjang.checkout') }}">
        @csrf

        {{-- LIST KERANJANG --}}
        <ul class="list-group shadow-sm rounded mb-4">
            @foreach ($keranjang as $item)
                @php $subtotal = $item->produk->harga * $item->jumlah; @endphp
                <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $item->id }}">

                    <div class="d-flex align-items-center gap-3">
                        <input type="checkbox" class="form-check-input pilih-item" name="pilih[]" value="{{ $item->id }}">

                        <img src="{{ asset('storage/'.$item->produk->gambar) }}" class="rounded" style="width:80px;height:80px;object-fit:cover" alt="{{ $item->produk->nama }}">

                        <div>
                            <div class="fw-semibold">{{ $item->produk->nama }}</div>
                            <div class="text-muted small">Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <div class="input-group input-group-sm" style="width:120px">
                            <button type="button" class="btn btn-outline-secondary kurang">-</button>
                            <input type="text" class="form-control text-center jumlah" value="{{ $item->jumlah }}">
                            <button type="button" class="btn btn-outline-secondary tambah">+</button>
                        </div>

                        <div class="fw-semibold subtotal" data-subtotal="{{ $subtotal }}">
                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                        </div>

                        <button type="button" class="btn btn-sm btn-outline-danger btn-hapus" data-id="{{ $item->id }}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </li>
            @endforeach
        </ul>

        {{-- TOTAL --}}
        <div class="d-flex justify-content-end mb-4">
            <h4>Total: <span id="grandTotal" class="text-primary">Rp 0</span></h4>
        </div>

        {{-- ALAMAT PENGIRIMAN --}}
        <div class="mb-4">
            <label class="fw-semibold mb-2">Pilih Alamat Pengiriman</label>

            @if($alamats->isEmpty())
                <div class="alert alert-warning">
                    Kamu belum memiliki alamat. Klik tombol di bawah untuk menambahkan alamat.
                </div>
            @else
                @foreach ($alamats as $alamat)
                    <label class="card p-3 mb-2 alamat-card">
                        <div class="d-flex align-items-start gap-2">
                            <input type="radio" name="alamat_pilih" class="form-check-input alamat-radio mt-1" value="{{ $alamat->alamat }}" {{ $alamat->is_default ? 'checked' : '' }}>
                            <div class="flex-grow-1">
                                <div class="text-dark">{{ $alamat->alamat }}</div>
                            </div>
                        </div>
                    </label>
                @endforeach
            @endif

            {{-- Tambah Alamat Baru dengan Maps --}}
            <button type="button" class="btn btn-outline-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#modalTambahAlamat">
                <i class="bi bi-plus-circle me-1"></i> Tambah Alamat Baru
            </button>

            <input type="hidden" name="alamat" id="alamat_hidden">
        </div>

        {{-- METODE PEMBAYARAN --}}
        <div class="mb-4">
            <label class="form-label fw-semibold">Metode Pembayaran</label>
            <select name="metode" id="metode" class="form-select" required>
                <option value="">-- Pilih Metode --</option>
                <option value="cod">COD (Cash on Delivery)</option>
                <option value="transfer">Transfer Bank</option>
                <option value="qris">QRIS</option>
            </select>
        </div>

        {{-- PREVIEW METODE --}}
        <div id="metodeContainer" class="mb-4 text-center" style="display:none;">
            <img id="metodeImage" class="img-fluid rounded shadow-sm" style="max-width:300px" alt="Payment Method">
            <div id="metodeText" class="mt-3"></div>
            <button type="button" id="copyRekBtn" class="btn btn-sm btn-outline-primary mt-2" style="display:none;">
                <i class="bi bi-clipboard"></i> Copy Nomor Rekening
            </button>
        </div>

        {{-- BUTTON --}}
        <div class="text-end">
            <div id="loadingSpinner" class="spinner-border spinner-border-sm text-primary d-none me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <button type="submit" class="btn btn-primary btn-lg checkout-btn">
                <i class="bi bi-check-circle me-2"></i> Checkout Sekarang
            </button>
        </div>
    </form>
    @endif
</div>

{{-- MODAL TAMBAH ALAMAT --}}
<div class="modal fade" id="modalTambahAlamat" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('user.alamat.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-geo-alt-fill me-2"></i>Tambah Alamat Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- Map --}}
                    <div id="map" style="height: 400px; border-radius: 8px; margin-bottom: 15px;"></div>

                    {{-- Form Alamat --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat Lengkap <span class="text-danger">*</span></label>
                        <textarea id="alamatText" name="alamat" class="form-control" rows="3" required placeholder="Klik pada peta untuk mengisi alamat otomatis atau ketik manual..."></textarea>
                        <small class="text-muted">Klik pada peta untuk mengisi alamat otomatis</small>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_default" class="form-check-input" id="isDefaultCheck" value="1">
                        <label class="form-check-label" for="isDefaultCheck">Jadikan alamat utama</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Alamat</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

{{-- STYLES --}}
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .checkout-btn {
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .checkout-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .alamat-card {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid #e0e0e0;
    }
    .alamat-card:hover {
        border-color: #0d6efd;
        background-color: #f8f9fa;
    }
    .alamat-card:has(input:checked) {
        border-color: #0d6efd;
        background-color: #e7f1ff;
    }
    .list-group-item {
        transition: background-color 0.2s ease;
    }
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
    #map {
        z-index: 1;
    }
    .leaflet-popup-content-wrapper {
        border-radius: 8px;
    }
</style>
@endpush

{{-- SCRIPTS --}}
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
// ================ MAP VARIABLES ================
let map;
let marker;
let currentLat = -5.147665;
let currentLng = 119.432732;

// ================ UTILITY FUNCTIONS ================
function formatRupiah(val) {
    return 'Rp ' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function updateTotal() {
    let total = 0;
    $('.pilih-item:checked').each(function() {
        const li = $(this).closest('[data-id]');
        total += parseInt(li.find('.subtotal').data('subtotal'));
    });
    $('#grandTotal').text(formatRupiah(total));
}

function updateAlamat() {
    $('#alamat_hidden').val($('.alamat-radio:checked').val() || '');
}

function updateSubtotal(li, qty) {
    const id = li.data('id');
    $.post("{{ route('user.keranjang.update') }}", {
        _token: '{{ csrf_token() }}',
        id: id,
        jumlah: qty
    })
    .done(function(res) {
        li.find('.jumlah').val(qty);
        li.find('.subtotal')
            .data('subtotal', res.subtotal)
            .text(formatRupiah(res.subtotal));
        updateTotal();
    })
    .fail(function() {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Tidak dapat memperbarui jumlah produk.'
        });
    });
}

function toggleMetode() {
    const metode = $('#metode').val();
    const container = $('#metodeContainer');
    const img = $('#metodeImage');
    const text = $('#metodeText');
    const copyBtn = $('#copyRekBtn');

    if (metode === 'qris') {
        container.show();
        img.attr('src', "{{ asset('images/qiris/qr_123.jpeg') }}");
        text.html(`
            <strong class="d-block mb-2">Scan QRIS untuk Pembayaran</strong>
            <small class="text-muted">Berlaku untuk Dana, OVO, Gopay, ShopeePay, LinkAja, dll.</small>
        `);
        copyBtn.hide();
    } else if (metode === 'transfer') {
        container.show();
        img.attr('src', "{{ asset('images/bank/transfer.webp') }}");
        text.html(`
            <strong class="d-block mb-2">Transfer ke Bank BRI</strong>
            <ul class="list-unstyled">
                <li><strong>No. Rekening:</strong> <span id="nomorRek">218101004389533</span></li>
                <li><strong>Atas Nama:</strong> Army Collection</li>
            </ul>
            <small class="text-muted">Silakan transfer sesuai total pembayaran</small>
        `);
        copyBtn.show();
    } else {
        container.hide();
        copyBtn.hide();
    }
}

// ================ MAP FUNCTIONS ================
function initMap() {
    // Initialize map
    map = L.map('map').setView([currentLat, currentLng], 13);

    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19,
    }).addTo(map);

    // Add marker
    marker = L.marker([currentLat, currentLng], {
        draggable: true
    }).addTo(map);

    // Update coordinates when marker is dragged
    marker.on('dragend', function(e) {
        const position = marker.getLatLng();
        getAddressFromCoordinates(position.lat, position.lng);
    });

    // Add click event on map
    map.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        
        marker.setLatLng([lat, lng]);
        getAddressFromCoordinates(lat, lng);
    });
}

function getAddressFromCoordinates(lat, lng) {
    const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.display_name) {
                $('#alamatText').val(data.display_name);
            }
        })
        .catch(error => {
            console.error('Error getting address:', error);
        });
}

function searchLocation() {
    const query = $('#searchLocation').val();
    if (!query) {
        Swal.fire('Peringatan', 'Masukkan lokasi yang ingin dicari', 'warning');
        return;
    }

    const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                const lat = parseFloat(data[0].lat);
                const lng = parseFloat(data[0].lon);
                
                map.setView([lat, lng], 15);
                marker.setLatLng([lat, lng]);
                $('#alamatText').val(data[0].display_name);
                
                Swal.fire({
                    icon: 'success',
                    title: 'Lokasi Ditemukan!',
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire('Tidak Ditemukan', 'Lokasi tidak ditemukan, coba kata kunci lain', 'info');
            }
        })
        .catch(error => {
            console.error('Error searching location:', error);
            Swal.fire('Error', 'Terjadi kesalahan saat mencari lokasi', 'error');
        });
}

function getCurrentLocation() {
    if (navigator.geolocation) {
        Swal.fire({
            title: 'Mengambil Lokasi...',
            text: 'Mohon izinkan akses lokasi',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                map.setView([lat, lng], 15);
                marker.setLatLng([lat, lng]);
                getAddressFromCoordinates(lat, lng);
                
                Swal.close();
                Swal.fire({
                    icon: 'success',
                    title: 'Lokasi Ditemukan!',
                    timer: 1500,
                    showConfirmButton: false
                });
            },
            function(error) {
                Swal.close();
                Swal.fire('Error', 'Tidak dapat mengambil lokasi. Pastikan GPS aktif dan izin lokasi diberikan.', 'error');
            }
        );
    } else {
        Swal.fire('Error', 'Browser tidak mendukung geolocation', 'error');
    }
}

// ================ DOCUMENT READY ================
$(function() {
    // Initial setup
    updateTotal();
    updateAlamat();
    toggleMetode();

    // Event listeners
    $('.pilih-item').on('change', updateTotal);
    $('.alamat-radio').on('change', updateAlamat);
    $('#metode').on('change', toggleMetode);

    // Initialize map when modal is shown
    $('#modalTambahAlamat').on('shown.bs.modal', function() {
        if (!map) {
            setTimeout(() => {
                initMap();
            }, 200);
        } else {
            setTimeout(() => {
                map.invalidateSize();
            }, 200);
        }
    });

    // Search location
    $('#btnSearch').on('click', searchLocation);
    $('#searchLocation').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            searchLocation();
        }
    });

    // Current location
    $('#btnCurrentLocation').on('click', getCurrentLocation);

    // Simpan alamat
    $('#btnSimpanAlamat').on('click', function() {
        const alamat = $('#alamatText').val().trim();
        const isDefault = $('#isDefaultCheck').is(':checked');

        if (!alamat) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan!',
                text: 'Mohon isi alamat terlebih dahulu'
            });
            return;
        }

        // Simpan alamat
        $.ajax({
            url: "{{ route('user.alamat.store') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                alamat: alamat,
                is_default: isDefault ? 1 : 0
            },
            beforeSend: function() {
                $('#btnSimpanAlamat').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...');
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Alamat berhasil ditambahkan',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                let errorMsg = 'Terjadi kesalahan saat menyimpan alamat';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: errorMsg
                });
                
                $('#btnSimpanAlamat').prop('disabled', false).html('<i class="bi bi-save me-1"></i> Simpan Alamat');
            }
        });
    });

    // Quantity controls
    $('.tambah').on('click', function() {
        const li = $(this).closest('[data-id]');
        const newQty = parseInt(li.find('.jumlah').val()) + 1;
        updateSubtotal(li, newQty);
    });

    $('.kurang').on('click', function() {
        const li = $(this).closest('[data-id]');
        const currentQty = parseInt(li.find('.jumlah').val());
        const newQty = Math.max(1, currentQty - 1);
        updateSubtotal(li, newQty);
    });

    $('.jumlah').on('input', function() {
        const li = $(this).closest('[data-id]');
        const qty = parseInt($(this).val());
        
        if (!isNaN(qty) && qty >= 1) {
            updateSubtotal(li, qty);
        } else {
            $(this).addClass('animate__animated animate__shakeX');
            setTimeout(() => {
                $(this).removeClass('animate__animated animate__shakeX');
            }, 600);
        }
    });

    // Copy rekening button
    $('#copyRekBtn').on('click', function() {
        const rek = $('#nomorRek').text();
        navigator.clipboard.writeText(rek)
            .then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Nomor rekening berhasil disalin.',
                    timer: 2000,
                    showConfirmButton: false
                });
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Tidak dapat menyalin nomor rekening.'
                });
            });
    });

    // Delete item from cart
    $('.btn-hapus').on('click', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Hapus Produk?',
            text: "Produk akan dihapus dari keranjang.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/user/keranjang/" + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function() {
                        location.reload();
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Produk gagal dihapus!'
                        });
                    }
                });
            }
        });
    });

    // Form checkout validation
    $('#checkoutForm').on('submit', function(e) {
        if ($('.pilih-item:checked').length === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Oops!',
                text: 'Pilih setidaknya satu produk untuk checkout.'
            });
            return false;
        }

        if (!$('#alamat_hidden').val()) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Oops!',
                text: 'Pilih alamat pengiriman terlebih dahulu.'
            });
            return false;
        }

        $('.pilih-item:not(:checked)').each(function() {
            const li = $(this).closest('[data-id]');
            li.find('input, select, textarea').prop('disabled', true);
        });

        $('#loadingSpinner').removeClass('d-none');
        $('.checkout-btn').prop('disabled', true);
    });

    // Session alerts
    @if (session('checkout_success'))
        Swal.fire({
            icon: 'success',
            title: 'Checkout Berhasil!',
            html: `
                <p>Pesanan berhasil diproses.</p>
                <p><strong>Total:</strong> Rp {{ number_format(session('total'), 0, ',', '.') }}</p>
                <p><strong>ID Pesanan:</strong> #{{ session('penjualan_id') }}</p>
            `,
            confirmButtonText: 'Lihat Riwayat',
            allowOutsideClick: false
        }).then(() => {
            window.location.href = "{{ route('user.riwayat.index') }}";
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            timer: 3500,
            showConfirmButton: false
        });
    @endif
});
</script>
@endpush