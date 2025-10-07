<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Laporan Data Produk</title>
    <style>
        /* Mengatur dasar dokumen */
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 10pt;
            color: #333;
            margin: 25px;
            padding: 0;
            line-height: 1.5;
        }

        /* Variabel Warna */
        :root {
            --primary-color: #004d99; /* Biru gelap untuk aksen */
            --header-bg: #eaf6ff;     /* Latar belakang header tabel */
            --highlight-bg: #f5f5dc;  /* Latar belakang kolom harga/stok */
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
            padding: 8px 10px;
            text-align: left; /* Default text-align diubah ke kiri */
        }
        th {
            background-color: var(--header-bg);
            color: var(--primary-color);
            font-weight: 700;
            font-size: 10pt;
            text-transform: uppercase;
            text-align: center; /* Header tetap di tengah */
        }
        
        /* Baris ganjil/genap (zebra-striping) */
        tbody tr:nth-child(odd) {
            background-color: #fcfcfc;
        }

        /* Penataan Teks */
        .right { text-align: right; }
        .center { text-align: center; }
        .left { text-align: left; }
        
        /* Highlight kolom data numerik (Harga, Stok, Terjual) */
        .data-col {
            text-align: right;
            background-color: var(--highlight-bg); /* Warna krem muda untuk menyorot */
        }
        .data-col-center {
            text-align: center;
            background-color: var(--highlight-bg);
        }
        /* Header untuk kolom numerik (optional: bisa diberi warna berbeda jika diperlukan) */
        th.data-header {
             color: #d9534f; /* Warna merah untuk header data penting */
        }
    </style>
</head>
<body>
    <header>
        <h1>ARMY COLLECTION</h1>
        <p>Jl. Perintis Kemerdekaan Vll, Perumahan Green Hasanuddin Blok D1 No 3</p>
        <p>Telp: 085299006996 | Email: Army-Collection@email.com (Contoh)</p>
        <h2>LAPORAN DATA PRODUK</h2>
    </header>
    
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th class="left" style="width: 30%;">Nama Produk</th>
                <th class="center" style="width: 20%;">Kategori</th>
                <th class="data-header" style="width: 15%;">Harga</th>
                <th class="data-header" style="width: 15%;">Stok</th>
                <th class="data-header" style="width: 15%;">Jumlah Terjual</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($produks as $index => $produk)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td class="left">{{ $produk->nama }}</td>
                    <td class="center">{{ $produk->kategori->name ?? '-' }}</td>
                    <td class="data-col">Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                    <td class="data-col-center">{{ number_format($produk->stok, 0, ',', '.') }}</td>
                    <td class="data-col-center">{{ number_format($produk->detailTransaksis->sum('jumlah') ?? 0, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top: 30px; text-align: right; font-size: 9pt; color: #777;">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </p>
</body>
</html>