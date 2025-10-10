<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pembelian</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 10pt;
            color: #333;
            margin: 20px;
            line-height: 1.5;
        }
        header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #0056b3;
            padding-bottom: 10px;
        }
        header h1 {
            margin: 0;
            font-size: 24pt;
            color: #0056b3;
            font-weight: bold;
        }
        header p {
            margin: 5px 0 0 0;
            font-size: 10pt;
            color: #555;
        }
        .report-title {
            font-size: 16pt;
            margin: 15px 0;
            text-align: center;
            font-weight: 600;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background-color: #f0f8ff;
            color: #000;
            font-weight: 700;
            font-size: 11pt;
            text-transform: uppercase;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tfoot td {
            font-weight: bold;
            border-top: 3px solid #0056b3;
            background-color: #e6f7ff;
            font-size: 12pt;
            color: #0056b3;
        }
        .right { text-align: right; }
        .center { text-align: center; }
        .print-info {
            margin-top: 30px;
            text-align: right;
            font-size: 9pt;
            color: #777;
        }
    </style>
</head>
<body>
    <header>
        <h1>ARMY COLLECTION</h1>
        <p>Jl. Perintis Kemerdekaan VII, Perumahan Green Hasanuddin Blok D1 No 3</p>
        <p>Telp: 0852-9900-6996 | Email: Army-Collection@email.com</p>
    </header>

    <div class="report-title">LAPORAN PEMBELIAN</div>

    <table>
        <thead>
            <tr>
                <th class="center" style="width: 5%;">No</th>
                <th style="width: 20%;">Nama</th>
                <th style="width: 25%;">Alamat</th>
                <th style="width: 15%;">Telepon</th>
                <th class="center" style="width: 10%;">Tanggal</th>
                <th class="right" style="width: 15%;">Total (Rp)</th>
                <th class="center" style="width: 10%;">Jenis</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @forelse($pembelians as $index => $pembelian)
                @php
                    $nama = $pembelian->supplier ?? '-';
                    $alamat = $pembelian->alamat ?? '-';
                    $telepon = $pembelian->telepon ?? '-';
                    $tanggal = isset($pembelian->tanggal)
                        ? \Carbon\Carbon::parse($pembelian->tanggal)->format('d/m/Y')
                        : '-';
                    $total = (float) ($pembelian->total ?? 0);
                    $jenis = $pembelian->jenis ?? 'Supplier';
                    $grandTotal += $total;
                @endphp
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $nama }}</td>
                    <td>{{ $alamat }}</td>
                    <td>{{ $telepon }}</td>
                    <td class="center">{{ $tanggal }}</td>
                    <td class="right">{{ number_format($total, 0, ',', '.') }}</td>
                    <td class="center">{{ $jenis }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="center text-muted">Tidak ada data pembelian.</td>
                </tr>
            @endforelse
        </tbody>

        @if(count($pembelians) > 0)
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
