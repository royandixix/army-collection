<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 10pt;
            color: #333;
            margin: 25px;
            line-height: 1.5;
        }
        header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #004d99;
            padding-bottom: 10px;
        }
        header h1 {
            margin: 0;
            font-size: 22pt;
            color: #004d99;
            font-weight: 800;
            text-transform: uppercase;
        }
        header p {
            margin: 2px 0;
            font-size: 9pt;
            color: #555;
        }
        header h2 {
            font-size: 14pt;
            margin-top: 10px;
            color: #004d99;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            vertical-align: middle;
        }
        th {
            background-color: #eaf6ff;
            color: #004d99;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9pt;
        }
        tbody tr:nth-child(odd) { background-color: #fcfcfc; }
        .right { text-align: right; }
        .center { text-align: center; }
        tfoot td {
            font-weight: bold;
            border-top: 2px solid #004d99;
            background-color: #d9edf7;
            color: #004d99;
        }
        .print-info {
            margin-top: 20px;
            text-align: right;
            font-size: 9pt;
            color: #777;
        }
    </style>
</head>

<body>
    <header>
        <h1>ARMY COLLECTION</h1>
        <p>Jl. Perintis Kemerdekaan VII, Green Hasanuddin Blok D1 No 3</p>
        <p>Telp: 085299006996 | Email: Army-Collection@email.com</p>
        <h2>LAPORAN PENJUALAN</h2>
    </header>

    <table>
        <thead>
            <tr>
                <th class="center" style="width:5%;">No</th>
                <th style="width:20%;">Nama User</th>
                <th style="width:35%;">Produk</th>
                <th class="center" style="width:10%;">Jumlah</th>
                <th class="right" style="width:15%;">Harga (Rp)</th>
                <th class="right" style="width:15%;">Subtotal (Rp)</th>
                <th class="center" style="width:15%;">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; $grandTotal = 0; @endphp
            @forelse($items as $item)
                @php
                    $isPenjualan = $item instanceof \App\Models\Penjualan;
                    $details = $isPenjualan ? $item->detailPenjualans : $item->detailTransaksi;
                    $tanggal = $item->tanggal ?? $item->created_at;
                @endphp

                @if($details && $details->count() > 0)
                    @foreach($details as $detail)
                        @php
                            $produkNama = $detail->produk->nama ?? $detail->nama ?? '-';
                            $harga = $detail->produk->harga ?? $detail->harga ?? 0;
                            $jumlah = $detail->jumlah ?? 0;
                            $subtotal = $harga * $jumlah;
                            $grandTotal += $subtotal;

                            $userName = $isPenjualan
                                ? ($item->pelanggan->nama ?? $item->pelanggan->user->username ?? '-')
                                : ($item->user->username ?? '-');
                        @endphp
                        <tr>
                            <td class="center">{{ $no++ }}</td>
                            <td>{{ $userName }}</td>
                            <td>{{ $produkNama }}</td>
                            <td class="center">{{ $jumlah }}</td>
                            <td class="right">{{ number_format($harga, 0, ',', '.') }}</td>
                            <td class="right">{{ number_format($subtotal, 0, ',', '.') }}</td>
                            <td class="center">{{ \Carbon\Carbon::parse($tanggal)->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        @php
                            $userName = $isPenjualan
                                ? ($item->pelanggan->nama ?? $item->pelanggan->user->username ?? '-')
                                : ($item->user->username ?? '-');
                        @endphp
                        <td class="center">{{ $no++ }}</td>
                        <td>{{ $userName }}</td>
                        <td colspan="5" class="center text-muted">
                            {{ $isPenjualan ? 'Tidak ada detail penjualan.' : 'Tidak ada detail transaksi.' }}
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="7" class="center text-muted">Tidak ada data penjualan atau transaksi.</td>
                </tr>
            @endforelse
        </tbody>

        @if($grandTotal > 0)
            <tfoot>
                <tr>
                    <td colspan="5" class="right">TOTAL KESELURUHAN</td>
                    <td class="right">{{ number_format($grandTotal, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        @endif
    </table>

    <p class="print-info">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </p>
</body>
</html>
