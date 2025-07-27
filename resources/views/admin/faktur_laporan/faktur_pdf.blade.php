<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Faktur Semua Penjualan</title>
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 12px;
            color: #333;
            margin: 20px;
        }

        h2.judul {
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .info-table td {
            padding: 5px;
            vertical-align: top;
        }

        .info-table td:first-child {
            width: 160px;
            font-weight: bold;
        }

        .produk-table th,
        .produk-table td {
            border: 1px solid #999;
            padding: 6px;
            text-align: left;
        }

        .produk-table th {
            background-color: #f4f4f4;
        }

        .produk-table tr:nth-child(even) {
            background-color: #fcfcfc;
        }

        .produk-table tfoot td {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-style: italic;
            font-size: 11px;
        }

        .section-break {
            page-break-after: always;
            margin-top: 50px;
        }
    </style>
</head>
<body>

@foreach($penjualans as $index => $penjualan)
    <h2 class="judul">🧾 Faktur Penjualan #{{ $penjualan->id }}</h2>

    <table class="info-table">
        <tr>
            <td>Nama Pelanggan:</td>
            <td>{{ $penjualan->pelanggan->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td>Email:</td>
            <td>{{ $penjualan->pelanggan->user->email ?? '-' }}</td>
        </tr>
        <tr>
            <td>Alamat:</td>
            <td>{{ $penjualan->pelanggan->alamat ?? '-' }}</td>
        </tr>
        <tr>
            <td>Tanggal Transaksi:</td>
            <td>{{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <td>Status:</td>
            <td>{{ strtoupper($penjualan->status) }}</td>
        </tr>
        <tr>
            <td>Metode Pembayaran:</td>
            <td>{{ strtoupper(optional($penjualan->transaksi)->metode ?? '-') }}</td>
        </tr>
    </table>

    <h4 style="margin-top: 20px;">Detail Produk</h4>
    <table class="produk-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($penjualan->transaksi->detailTransaksi as $i => $detail)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $detail->produk->nama }}</td>
                    <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                    <td>{{ $detail->jumlah }}</td>
                    <td>Rp {{ number_format($detail->jumlah * $detail->harga, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada produk.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right;">Total</td>
                <td>Rp {{ number_format($penjualan->total, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d-m-Y H:i') }}
    </div>

    @if (!$loop->last)
    <div class="section-break"></div>
    @endif
@endforeach

</body>
</html>
