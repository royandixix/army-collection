@extends('admin.layouts.app')

@section('title', 'Edit Bukti Pembelian')

@section('content')
<div class="cx-main-content">

    <div class="cx-page-title d-flex justify-content-between align-items-center flex-wrap">
        <h5>Edit Bukti Pembelian</h5>
        <a href="{{ route('admin.bukti_pembelian.index') }}" class="btn btn-outline-secondary">
            <i class="ri-arrow-left-line me-1"></i> Kembali
        </a>
    </div>

    <div class="col-md-6 mt-3">
        <div class="cx-card revenue-overview">
            <div class="cx-card-header">
                <h4 class="cx-card-title mb-0">Detail Bukti Pembelian</h4>
            </div>

            <div class="cx-card-content card-default">
                <form action="{{ route('admin.bukti_pembelian.update_status', $bukti->id) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Pembelian ID</label>
                        <input type="text" class="form-control" value="{{ $bukti->pembelian->id ?? '-' }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Supplier</label>
                        <input type="text" class="form-control" value="{{ $bukti->pembelian->supplier->nama ?? '-' }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">File Bukti Pembelian</label>
                        <br>
                        <a href="{{ route('admin.bukti_pembelian.download', $bukti->id) }}" class="btn btn-primary btn-sm">
                            <i class="ri-download-line me-1"></i> Download File
                        </a>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status Pengiriman</label>
                        <select name="status_pengiriman" class="form-select">
                            <option value="pending" {{ $bukti->status_pengiriman == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="dikirim" {{ $bukti->status_pengiriman == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                            <option value="diterima" {{ $bukti->status_pengiriman == 'diterima' ? 'selected' : '' }}>Diterima</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="ri-check-line me-1"></i> Update Status
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
