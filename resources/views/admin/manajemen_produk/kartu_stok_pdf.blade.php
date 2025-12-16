<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Stok Produk</title>
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 10pt; color: #333; margin: 25px; line-height: 1.5; }
        header { text-align: center; margin-bottom: 25px; border-bottom: 3px solid #004d99; padding-bottom: 10px; }
        header h1 { margin: 0; font-size: 22pt; color: #004d99; font-weight: 800; text-transform: uppercase; }
        header p { margin: 2px 0; font-size: 9pt; color: #555; }
        header h2 { font-size: 14pt; margin-top: 10px; color: #004d99; border-bottom: 1px dashed #ccc; padding-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; vertical-align: middle; }
        th { background-color: #eaf6ff; color: #004d99; font-weight: bold; text-transform: uppercase; font-size: 9pt; }
        tbody tr:nth-child(odd) { background-color: #fcfcfc; }
        .right { text-align: right; }
        .center { text-align: center; }
        tfoot td { font-weight: bold; border-top: 2px solid #004d99; background-color: #d9edf7; color: #004d99; }
        .print-info { margin-top: 20px; text-align: right; font-size: 9pt; color: #777; }
    </style>
</head>
<body>
    <header>
        <h1>ARMY COLLECTION</h1>
        <p>Jl. Perintis Kemerdekaan VII, Green Hasanuddin Blok D1 No 3</p>
        <p>Telp: 085299006996 | Email: Army-Collection@email.com</p>
        <h2>KARTU STOK PRODUK</h2>
    </header>

    <table>
        <thead>
            <tr>
                <th class="center" style="width:5%;">No</th>
                <th>Nama Produk</th>
                <th class="center" style="width:15%;">Barang Masuk</th>
                <th class="center" style="width:15%;">Barang Keluar</th>
                <th class="center" style="width:15%;">Sisa Stok</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($kartuStok as $item)
                <tr>
                    <td class="center">{{ $no++ }}</td>
                    <td>{{ $item['nama'] }}</td>
                    <td class="center">{{ $item['masuk'] }}</td>
                    <td class="center">{{ $item['keluar'] }}</td>
                    <td class="center">{{ $item['sisa'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="center">Tidak ada data produk.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p class="print-info">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </p>
</body>
</html>
