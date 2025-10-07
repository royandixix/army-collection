<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        /* Mengatur dasar dokumen */
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 10pt; /* Ukuran font lebih kecil untuk cetak */
            color: #333;
            margin: 25px;
            padding: 0;
            line-height: 1.5;
        }

        /* Variabel Warna */
        :root {
            --primary-color: #004d99; /* Biru gelap untuk aksen */
            --header-bg: #eaf6ff;     /* Latar belakang header tabel */
            --footer-bg: #d9edf7;     /* Latar belakang total */
            --highlight-bg: #fffacd;  /* Latar belakang kolom total */
        }

        /* Bagian Header/Kop */
        header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 4px solid var(--primary-color);
            padding-bottom: 15px;
        }
        header h1 {
            margin: 0;
            font-size: 26pt;
            color: var(--primary-color);
            font-weight: 800;
            text-transform: uppercase;
        }
        header p {
            margin: 3px 0 0 0;
            font-size: 10pt;
            color: #555;
        }
        header h2 {
            font-size: 18pt; /* Judul laporan */
            margin: 20px 0 0;
            font-weight: 700;
            color: var(--primary-color);
            border-bottom: 1px dashed #ccc;
            padding-bottom: 5px;
        }

        /* Bagian Tabel */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border: 1px solid #ccc;
        }
        th, td {
            border: 1px solid #e0e0e0;
            padding: 8px 10px; /* Padding disesuaikan */
            text-align: left;
            vertical-align: top; /* Penting untuk kolom produk */
        }
        th {
            background-color: var(--header-bg);
            color: var(--primary-color);
            font-weight: 700;
            font-size: 10pt;
            text-transform: uppercase;
        }

        /* Baris ganjil/genap (zebra-striping) */
        tbody tr:nth-child(odd) {
            background-color: #fcfcfc;
        }
        
        /* Footer Tabel (Total) */
        tfoot td {
            font-weight: bold;
            border-top: 4px double var(--primary-color);
            background-color: var(--footer-bg);
            font-size: 12pt;
            color: var(--primary-color);
        }
        
        /* Penataan Teks */
        .right { text-align: right; }
        .center { text-align: center; }
        
        /* Highlight kolom Total */
        .total-col {
            font-weight: 700;
            background-color: var(--highlight-bg); /* Warna kuning muda */
        }
        th.total-col {
            background-color: var(--header-bg);
            color: #d9534f; /* Warna merah/aksen kuat untuk header Total */
        }

        /* Penyesuaian List Produk */
        ul {
            margin: 0;
            padding-left: 15px;
            list-style-type: square; /* Menggunakan square atau disc kecil */
            font-size: 9pt; /* Ukuran font detail produk sedikit lebih kecil */
            line-height: 1.3;
        }
        
        /* Keterangan Cetak */
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
        <h1>ARMY COLLECTION</h1> <p>Jl. Perintis Kemerdekaan Vll, Perumahan Green Hasanuddin Blok D1 No 3</p>
        <p>Telp: 085299006996 | Email: Army-Collection@email.com (Contoh)</p>
        <h2>LAPORAN PENJUALAN</h2>
    </header>

    <table>
        <thead>
            <tr>
                <th class="center" style="width: 5%;">No</th>
                <th style="width: 15%;">Pelanggan</th>
                <th style="width: 40%;">Produk</th> <th class="right total-col" style="width: 15%;">Total (Rp)</th>
                <th class="center" style="width: 10%;">Status</th>
                <th class="center" style="width: 15%;">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @forelse($penjualans as $index => $penjualan)
            @php $grandTotal += $penjualan->total_harga; @endphp
            <tr>
                <td class="center">{{ $index + 1 }}</td>
                <td>{{ $penjualan->user->name ?? '-' }}</td>
                <td>
                    @if(isset($penjualan->detailPesanans) && $penjualan->detailPesanans->count())
                    <ul>
                        @foreach($penjualan->detailPesanans as $detail)
                        <li>**{{ $detail->kambing->jenis_kambing ?? 'Produk Dihapus' }}** (x{{ $detail->jumlah }})</li>
                        @endforeach
                    </ul>
                    @else
                    -
                    @endif
                </td>
                <td class="right total-col">{{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                <td class="center">{{ ucfirst($penjualan->status) }}</td>
                <td class="center">{{ $penjualan->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="center">Tidak ada data penjualan</td>
            </tr>
            @endforelse
        </tbody>

        @if(isset($penjualans) && $penjualans->count() > 0)
        <tfoot>
            <tr>
                <td colspan="3" class="right">TOTAL KESELURUHAN</td>
                <td class="right total-col">{{ number_format($grandTotal, 0, ',', '.') }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
        @endif
    </table>

    <p class="print-info">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </p>
</body>

</html>