<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Produk</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: center; }
        th { background: #f2f2f2; }
        h2 { text-align: center; margin-bottom: 0; }
    </style>
</head>
<body>
    <h2>LAPORAN PRODUK</h2>
    <hr>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Jumlah Terjual</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($produks as $index => $produk)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $produk->nama }}</td>
                    <td>{{ $produk->kategori->name ?? '-' }}</td>
                    <td>Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                    <td>{{ $produk->stok }}</td>
                    <td>{{ $produk->detailTransaksis->sum('jumlah') ?? 0 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
