@extends('admin.layouts.app')

@section('title', 'Laporan Supplier')

@section('content')
<div class="cx-main-content">

    <div class="mb-4 ms-3 d-flex justify-content-between align-items-center">
        <h5>Laporan Supplier</h5>

        <a href="{{ route('admin.laporan.supplier.cetak') }}" target="_blank"
           class="btn btn-danger rounded-pill">
            <i class="ri-printer-line me-1"></i> Cetak PDF
        </a>
    </div>

    <div class="col-md-12">
        <div class="cx-card revenue-overview">
            <div class="cx-card-header">
                <h4 class="cx-card-title mb-0">Data Supplier</h4>
            </div>

            <div class="cx-card-content">
                <div class="table-responsive">
                    <table class="table table-striped table-hover text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Supplier</th>
                                <th>Alamat</th>
                                <th>Telepon</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($suppliers as $supplier)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $supplier->nama }}</td>
                                    <td>{{ $supplier->alamat }}</td>
                                    <td>{{ $supplier->telepon }}</td>
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
