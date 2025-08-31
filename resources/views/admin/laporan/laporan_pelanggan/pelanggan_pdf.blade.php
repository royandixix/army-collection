<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pelanggan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background: #f2f2f2; }
        h2 { text-align: center; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h2>Laporan Data Pelanggan</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>No HP</th>
                <th>Alamat</th>
                <th>Jumlah Transaksi</th>
                <th>Bergabung</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pelanggans as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $user->pelanggan->nama ?? '-' }}</td>
                    <td>{{ $user->email ?? '-' }}</td>
                    <td>{{ $user->pelanggan->no_hp ?? '-' }}</td>
                    <td>{{ $user->pelanggan->alamat ?? '-' }}</td>
                    <td>{{ $user->transaksis_count ?? 0 }}</td>
                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
