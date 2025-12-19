@extends('admin.layouts.app')

@section('title', 'Penjualan Belum Bayar')

@section('content')
<div class="cx-main-content">
    <div class="mb-4 ms-3">
        <h5>Penjualan Belum Bayar</h5>
    </div>

    <div class="col-md-12">
        <div class="cx-card revenue-overview">
            <div class="cx-card-header d-flex justify-content-between align-items-center">
                <h4 class="cx-card-title mb-0">Daftar Transaksi Belum Bayar</h4>
                <a href="{{ route('admin.manajemen.manajemen_penjualan') }}" class="btn btn-outline-secondary">Kembali</a>
            </div>

            <div class="cx-card-content card-default">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
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
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach($items as $item)
                                @php
                                    $nama = $item->pelanggan->nama ?? ($item->pelanggan?->user->name ?? 'Tidak diketahui');
                                    $fotoUrl = asset('img/default-user.png');
                                    if(!empty($item->pelanggan?->foto_profil)) {
                                        $fotoUrl = asset('storage/'.$item->pelanggan->foto_profil);
                                    } elseif(!empty($item->pelanggan?->user?->img)) {
                                        $fotoUrl = asset('storage/'.$item->pelanggan->user->img);
                                    }
                                    $details = $item->detailPenjualans;
                                    $totalJumlah = $details->sum('jumlah');
                                    $buktiPath = $item->bukti_tf ? asset('storage/'.$item->bukti_tf) : null;
                                @endphp
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $fotoUrl }}" width="45" height="45" class="rounded-circle border">
                                            <span>{{ $nama }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <ul class="m-0 p-0" style="list-style:none;">
                                            @foreach($details as $detail)
                                                <li>{{ $detail->produk?->nama ?? '-' }} x{{ $detail->jumlah }}</li>
                                            @endforeach
                                        </ul>
                                        <div><strong>Total: {{ $totalJumlah }}</strong></div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d F Y H:i') }}</td>
                                    <td>Rp {{ number_format($item->total,0,',','.') }}</td>
                                    <td>{{ strtoupper($item->status) }}</td>
                                    <td>{{ strtoupper($item->metode_pembayaran ?? '-') }}</td>
                                    <td>
                                        @if($buktiPath)
                                            <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#buktiModal-{{ $item->id }}">
                                                <img src="{{ $buktiPath }}" width="60" height="60" class="rounded">
                                            </button>

                                           

                                            <!-- Modal Bukti -->
                                            <div class="modal fade" id="buktiModal-{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                     <div class="modal-content">
                                                        <div class="modal-body text-center">
                                                            <img src="{{ $buktiPath }}" class="img-fluid rounded">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted small">Belum Upload</span>
                                        @endif
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
