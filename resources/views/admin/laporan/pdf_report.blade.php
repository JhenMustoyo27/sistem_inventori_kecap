<!DOCTYPE html>
<html>
<head>
    <title>Laporan Akhir Stok Kecap Riboet</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .footer {
            font-size: 8px;
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <h1>Laporan Akhir Stok Kecap Riboet</h1>
    <p>Tanggal Laporan: {{ date('d-m-Y H:i:s') }}</p>

    <table>
        <thead>
            <tr>
                <th>Kode Kecap</th>
                <th>Ukuran</th>
                <th>Stok Masuk Awal</th>
                <th>Stok Keluar</th>
                <th>Stok Tersisa</th>
                <th>Tgl. Masuk (Batch Awal)</th>
                <th>Tgl. Keluar (Terakhir)</th>
                <th>Harga Beli Satuan</th>
                <th>Total Harga Jual (dari Keluar)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laporan as $item)
                <tr>
                    <td>{{ $item['kode_kecap'] }}</td>
                    <td>{{ $item['ukuran'] }}</td>
                    <td>{{ $item['jumlah_stok_masuk_awal'] }}</td>
                    <td>{{ $item['jumlah_stok_keluar'] }}</td>
                    <td>{{ $item['jumlah_stok_tersisa'] }}</td>
                    <td>{{ $item['tanggal_masuk'] ? $item['tanggal_masuk']->format('d-m-Y') : 'N/A' }}</td>
                    <td>{{ $item['tanggal_keluar_terakhir'] ? $item['tanggal_keluar_terakhir']->format('d-m-Y') : 'N/A' }}</td>
                    <td>Rp {{ number_format($item['harga_jual'], 2, ',', '.') }}</td>
                    <td>Rp {{ number_format($item['total_harga_jual_dari_keluar'], 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center;">Tidak ada data laporan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Laporan ini dibuat secara otomatis oleh sistem Kecap Riboet.
    </div>
</body>
</html>

