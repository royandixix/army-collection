@extends('user.layouts.app')

@section('title', 'Upload Bukti Pembayaran')

@section('content')
<div class="container py-5 animate__animated animate__fadeInUp animate__faster">

    <div class="mb-4 d-flex align-items-center gap-3">
        <i class="bi bi-cloud-upload fs-2 text-primary"></i>
        <h2 class="fw-bold m-0">Upload Bukti Pembayaran</h2>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">

                <div class="card-header bg-primary text-white py-3">
                    <h5 class="m-0 fw-semibold">
                        <i class="bi bi-info-circle me-2"></i> Detail Transaksi
                    </h5>
                </div>

                <div class="card-body p-4">

                    {{-- TOTAL PEMBAYARAN --}}
                    <div class="alert alert-primary d-flex justify-content-between align-items-center rounded-3 mb-4">
                        <div>
                            <i class="bi bi-cash-stack fs-4 me-2"></i>
                            <strong>Total Pembayaran</strong>
                        </div>
                        <h4 class="fw-bold m-0">
                            Rp {{ number_format($transaksi->penjualan->total ?? 0, 0, ',', '.') }}
                        </h4>
                    </div>

                    {{-- BUKTI YANG SUDAH ADA --}}
                    @if ($transaksi && $transaksi->file)
                        <div class="alert alert-success d-flex align-items-center gap-2 rounded-3 mb-4">
                            <i class="bi bi-check-circle-fill fs-5"></i>
                            <div>Bukti pembayaran sudah diupload. Kamu bisa mengganti jika diperlukan.</div>
                        </div>

                        <div class="text-center mb-4 p-3 bg-light rounded-3">
                            <p class="small text-muted mb-2">Bukti yang sudah diupload:</p>
                            <img src="{{ asset('storage/' . $transaksi->file) }}"
                                 class="rounded shadow-sm img-fluid"
                                 style="max-height: 400px; max-width: 100%;"
                                 alt="Bukti Pembayaran">
                        </div>
                    @endif

                    {{-- FORM UPLOAD --}}
                    <form action="{{ route('user.riwayat.upload.submit', $pembelian_id) }}" 
                          method="POST" 
                          enctype="multipart/form-data" 
                          id="uploadForm">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-image me-1"></i>
                                {{ $transaksi && $transaksi->file ? 'Ganti Bukti Pembayaran' : 'Upload Bukti Pembayaran' }}
                            </label>
                            
                            <input type="file" 
                                   name="bukti_pembayaran" 
                                   id="buktiInput"
                                   class="form-control @error('bukti_pembayaran') is-invalid @enderror"
                                   accept="image/jpeg,image/jpg,image/png"
                                   required>

                            @error('bukti_pembayaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="form-text mt-2">
                                <i class="bi bi-info-circle me-1"></i>
                                Format: <strong>JPG, JPEG, PNG</strong> | Maksimal: <strong>3 MB</strong>
                            </div>
                        </div>

                        {{-- PREVIEW IMAGE --}}
                        <div id="imagePreview" class="mb-4 text-center d-none">
                            <p class="small text-muted mb-2">Preview:</p>
                            <img id="previewImg" class="rounded shadow-sm img-fluid" 
                                 style="max-height: 300px;" alt="Preview">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold">
                                <i class="bi bi-cloud-arrow-up me-2"></i>
                                {{ $transaksi && $transaksi->file ? 'Update Bukti Pembayaran' : 'Upload Bukti Pembayaran' }}
                            </button>
                            <a href="{{ route('user.riwayat.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    .card { transition: box-shadow 0.3s ease; }
    #imagePreview img { border: 3px solid #e9ecef; }
    .btn-primary { transition: all 0.3s ease; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3); }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const buktiInput = document.getElementById('buktiInput');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    buktiInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (file.size > 3*1024*1024) {
                Swal.fire({icon:'error', title:'File Terlalu Besar!', text:'Ukuran file maksimal 3 MB.', confirmButtonText:'OK'});
                buktiInput.value = ''; imagePreview.classList.add('d-none'); return;
            }
            const validTypes = ['image/jpeg','image/jpg','image/png'];
            if (!validTypes.includes(file.type)) {
                Swal.fire({icon:'error', title:'Format File Salah!', text:'Hanya file JPG, JPEG, dan PNG yang diperbolehkan.', confirmButtonText:'OK'});
                buktiInput.value = ''; imagePreview.classList.add('d-none'); return;
            }
            const reader = new FileReader();
            reader.onload = function(e) { previewImg.src=e.target.result; imagePreview.classList.remove('d-none'); imagePreview.classList.add('animate__animated','animate__fadeIn'); };
            reader.readAsDataURL(file);
        } else { imagePreview.classList.add('d-none'); }
    });

    const uploadForm = document.getElementById('uploadForm');
    uploadForm.addEventListener('submit', function(e){
        if(!buktiInput.files.length){
            e.preventDefault();
            Swal.fire({icon:'warning', title:'Belum Ada File!', text:'Silakan pilih file bukti pembayaran terlebih dahulu.', confirmButtonText:'OK'});
            return false;
        }
    });

    @if(session('success'))
    Swal.fire({icon:'success', title:'Berhasil!', text:'{{ session('success') }}', confirmButtonText:'OK', timer:3000});
    @endif
    @if(session('error'))
    Swal.fire({icon:'error', title:'Gagal!', text:'{{ session('error') }}', confirmButtonText:'OK'});
    @endif
});
</script>
@endpush
