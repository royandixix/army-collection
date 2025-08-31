<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 6px; text-align: left; }
        th { background: #f2f2f2; }
        h2 { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h2>Laporan Penjualan</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Pelanggan</th>
                <th>Produk</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualans as $index => $penjualan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $penjualan->pelanggan->user->username ?? '-' }}</td>
                    <td>
                        @if($penjualan->transaksi && $penjualan->transaksi->detailTransaksi)
                            <ul style="margin: 0; padding-left: 15px;">
                                @foreach($penjualan->transaksi->detailTransaksi as $detail)
                                    <li>{{ $detail->produk->nama ?? '-' }} (x{{ $detail->qty }})</li>
                                @endforeach
                            </ul>
                        @else
                            -
                        @endif
                    </td>
                    <td>Rp {{ number_format($penjualan->total, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($penjualan->status) }}</td>
                    <td>{{ $penjualan->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
