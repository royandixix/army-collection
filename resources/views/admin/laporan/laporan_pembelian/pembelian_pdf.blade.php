<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pembelian</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 12px;
            color: #000;
            margin: 0;
            padding: 0;
        }
        header {
            text-align: center;
            margin-bottom: 20px;
        }
        header h1 {
            margin: 0;
            font-size: 18px;
        }
        header p {
            margin: 2px 0;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
        }
        th {
            background-color: #eaeaea;
            font-weight: bold;
        }
        tfoot td {
            font-weight: bold;
            border-top: 2px solid #000;
        }
        .right {
            text-align: right;
        }
        .center {
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <h1>Perusahaan Contoh</h1>
        <p>Jl. Contoh No.1, Kota Contoh</p>
        <p>Telp: 08123456789 | Email: info@perusahaan.com</p>
        <h2>Laporan Pembelian</h2>
    </header>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Supplier</th>
                <th>Alamat</th>
                <th>Telepon</th>
                <th>Tanggal</th>
                <th>Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @forelse($pembelians as $index => $pembelian)
                @php $grandTotal += $pembelian->total; @endphp
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $pembelian->supplier->nama ?? '-' }}</td>
                    <td>{{ $pembelian->supplier->alamat ?? '-' }}</td>
                    <td>{{ $pembelian->supplier->telepon ?? '-' }}</td>
                    <td class="center">{{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d/m/Y') }}</td>
                    <td class="right">{{ number_format($pembelian->total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="center">Tidak ada data pembelian</td>
                </tr>
            @endforelse
        </tbody>
        @if($pembelians->count() > 0)
        <tfoot>
            <tr>
                <td colspan="5" class="right">Total Keseluruhan</td>
                <td class="right">{{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    <p style="margin-top: 20px; text-align: right; font-size: 12px;">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </p>
</body>
</html>
