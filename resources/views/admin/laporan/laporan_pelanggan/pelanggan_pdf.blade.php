<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pembelian</title>
    <style>
        /* Mengatur dasar dokumen */
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 10pt;
            color: #333;
            margin: 25px; /* Margin diperbesar sedikit */
            padding: 0;
            line-height: 1.5;
        }

        /* Variabel Warna */
        :root {
            --primary-color: #004d99; /* Biru gelap untuk aksen */
            --header-bg: #eaf6ff;     /* Latar belakang header lebih terang */
            --footer-bg: #d9edf7;     /* Latar belakang total */
        }

        /* Bagian Header/Kop */
        header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 4px solid var(--primary-color); /* Garis pemisah lebih tebal */
            padding-bottom: 15px;
        }
        header h1 {
            margin: 0;
            font-size: 26pt; /* Ukuran h1 diperbesar */
            color: var(--primary-color);
            font-weight: 800; /* Lebih Bold */
            text-transform: uppercase;
        }
        header p {
            margin: 3px 0 0 0;
            font-size: 10pt;
            color: #555;
        }
        .report-title {
            font-size: 18pt; /* Ukuran judul laporan diperbesar */
            margin: 20px 0;
            text-align: center;
            font-weight: 700;
            color: var(--primary-color); /* Judul menggunakan warna aksen */
            border-bottom: 1px dashed #ccc; /* Garis putus-putus di bawah judul */
            padding-bottom: 5px;
        }

        /* Bagian Tabel */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); Dihapus untuk tampilan cetak */
            border: 1px solid #ccc; /* Border luar tabel tipis */
        }
        th, td {
            border: 1px solid #e0e0e0; /* Garis tabel sangat tipis dan terang */
            padding: 10px 12px; /* Padding ditingkatkan */
            text-align: left;
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
            background-color: #fcfcfc; /* Warna stripe yang sangat halus */
        }
        
        /* Footer Tabel (Total) */
        tfoot td {
            font-weight: bold;
            border-top: 4px double var(--primary-color); /* Garis ganda yang kuat */
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
            background-color: #fffacd; /* Warna kuning muda untuk menyorot total */
        }
        th.total-col {
            background-color: var(--header-bg);
            color: #d9534f; /* Warna merah/aksen kuat untuk header Total */
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
        <h1>ARMY COLLECTION</h1>
        <p>Jl. Perintis Kemerdekaan Vll, Perumahan Green Hasanuddin Blok D1 No 3</p>
        <p>Telp: 085299006996 | Email: Army-Collection@email.com (Contoh)</p>
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
                <th class="right total-col" style="width: 15%;">Total (Rp)</th>
                <th class="center" style="width: 10%;">Jenis</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @forelse($pembelians as $index => $pembelian)
                @php
                    // Gunakan array style agar aman (karena data gabungan)
                    $nama = $pembelian['supplier'] ?? '-';
                    $alamat = $pembelian['alamat'] ?? '-';
                    $telepon = $pembelian['telepon'] ?? '-';
                    $tanggal = isset($pembelian['tanggal'])
                        ? \Carbon\Carbon::parse($pembelian['tanggal'])->format('d/m/Y')
                        : '-';
                    $total = $pembelian['total'] ?? 0;
                    $jenis = $pembelian['jenis'] ?? 'Supplier';
                    $grandTotal += $total;
                @endphp

                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $nama }}</td>
                    <td>{{ $alamat }}</td>
                    <td>{{ $telepon }}</td>
                    <td class="center">{{ $tanggal }}</td>
                    <td class="right total-col">{{ number_format($total, 0, ',', '.') }}</td>
                    <td class="center">{{ $jenis }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="center">Tidak ada data pembelian</td>
                </tr>
            @endforelse
        </tbody>

        @if(count($pembelians) > 0)
        <tfoot>
            <tr>
                <td colspan="5" class="right">TOTAL KESELURUHAN</td>
                <td class="right total-col">{{ number_format($grandTotal, 0, ',', '.') }}</td>
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