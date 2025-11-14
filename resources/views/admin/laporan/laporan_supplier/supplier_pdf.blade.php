<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Laporan Data Supplier</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 10pt;
            color: #333;
            margin: 25px;
            padding: 0;
            line-height: 1.5;
        }

        :root {
            --primary-color: #004d99;
            --header-bg: #eaf6ff;
            --highlight-bg: #f5f5dc;
        }

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
            font-size: 18pt;
            margin: 20px 0 0;
            font-weight: 700;
            color: var(--primary-color);
            border-bottom: 1px dashed #ccc;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border: 1px solid #ccc;
        }
        th, td {
            border: 1px solid #e0e0e0;
            padding: 8px 10px;
        }
        th {
            background-color: var(--header-bg);
            color: var(--primary-color);
            text-align: center;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10pt;
        }

        tbody tr:nth-child(odd) {
            background-color: #fcfcfc;
        }

        .center { text-align: center; }
        .left { text-align: left; }
        .right { text-align: right; }

        .data-col-center {
            text-align: center;
            background-color: var(--highlight-bg);
        }

        th.data-header {
            color: #d9534f;
        }
    </style>
</head>

<body>
    <header>
        <h1>ARMY COLLECTION</h1>
        <p>Jl. Perintis Kemerdekaan Vll, Perumahan Green Hasanuddin Blok D1 No 3</p>
        <p>Telp: 085299006996 | Email: Army-Collection@email.com</p>
        <h2>LAPORAN DATA SUPPLIER</h2>
    </header>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 30%;">Nama Supplier</th>
                <th style="width: 35%;">Alamat</th>
                <th class="data-header" style="width: 20%;">Telepon</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($suppliers as $index => $supplier)
            <tr>
                <td class="center">{{ $index + 1 }}</td>
                <td class="left">{{ $supplier->nama }}</td>
                <td class="left">{{ $supplier->alamat }}</td>
                <td class="data-col-center">{{ $supplier->telepon }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top: 30px; text-align: right; font-size: 9pt; color: #777;">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </p>

</body>
</html>
