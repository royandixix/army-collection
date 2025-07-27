<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan & Pelanggan</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 20px;
        }

        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
            border-bottom: 2px solid #444;
            padding-bottom: 5px;
            letter-spacing: 1px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 10.5px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: right;
            font-style: italic;
            color: #666;
        }

        .status-lunas {
            color: green;
            font-weight: bold;
        }

        .status-pending {
            color: orange;
            font-weight: bold;
        }

        .status-batal {
            color: red;
            font-weight: bold;
        }

        .badge {
            padding: 3px 6px;
            border-radius: 4px;
            color: #fff;
            font-size: 9px;
        }

        .bg-success { background-color: #28a745; }
        .bg-primary { background-color: #007bff; }
        .bg-warning { background-color: #ffc107; color: #000; }
        .bg-secondary { background-color: #6c757d; }
    </style>
</head>
<body>
    <div class="title">🧾 Laporan Penjualan & Pelanggan</div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>No HP</th>
                <th>Alamat</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Status</th>
                <th>Jenis</th>
                <th>Metode</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penjualans as $index => $penjualan)
                @php
                    $status = strtoupper($penjualan->status);
                    $status_class = match($penjualan->status) {
                        'lunas' => 'status-lunas',
                        'pending' => 'status-pending',
                        default => 'status-batal'
                    };

                    $metode = strtolower($penjualan->transaksi->metode ?? '-');
                    $badge_class = match($metode) {
                        'cod' => 'bg-success',
                        'transfer' => 'bg-primary',
                        'qris' => 'bg-warning',
                        default => 'bg-secondary',
                    };
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ optional($penjualan->pelanggan)->nama ?? '-' }}</td>
                    <td>{{ optional(optional($penjualan->pelanggan)->user)->email ?? '-' }}</td>
                    <td>{{ optional($penjualan->pelanggan)->no_hp ?? '-' }}</td>
                    <td>{{ optional($penjualan->pelanggan)->alamat ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($penjualan->tanggal ?? $penjualan->created_at)->format('d-m-Y H:i') }}</td>
                    <td>Rp {{ number_format($penjualan->total, 0, ',', '.') }}</td>
                    <td class="{{ $status_class }}">{{ $status }}</td>
                    <td>{{ $penjualan->jenis_transaksi ?? '-' }}</td>
                    <td><span class="badge {{ $badge_class }}">{{ strtoupper($metode) }}</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">Dicetak pada: {{ now()->format('d-m-Y H:i') }}</div>
</body>
</html>
