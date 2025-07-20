<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Akhir Stok Kecap Riboet</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10pt;
        }
        h1 {
            text-align: center;
            font-size: 18pt;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            text-align: center;
            font-size: 8pt;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <h1>Laporan Akhir Stok Kecap Riboet</h1>
    <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}</p>

    <table>
        <thead>
            <tr>
                <th>Kode Kecap</th>
                <th>Ukuran</th>
                <th>Stok Masuk</th>
                <th>Stok Keluar</th>
                <th>Stok Tersisa</th>
                <th>Tgl. Masuk</th>
                <th>Tgl. Keluar Terakhir</th>
                <th>Harga Satuan</th>
                <th>Harga Jual Total (Stok Tersisa)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laporanData as $data)
                <tr>
                    {{-- Mengakses elemen array menggunakan sintaks array --}}
                    <td>{{ $data['kode_kecap'] }}</td>
                    <td>{{ ucfirst($data['ukuran']) }}</td>
                    <td>{{ $data['jumlah_stok_masuk'] }}</td>
                    <td>{{ $data['jumlah_stok_keluar'] }}</td>
                    <td>{{ $data['jumlah_stok_tersisa'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($data['tanggal_masuk'])->format('d-m-Y') }}</td>
                    <td>{{ $data['tanggal_keluar'] }}</td>
                    <td>Rp{{ number_format($data['harga_jual'], 2, ',', '.') }}</td>
                    <td>Rp{{ number_format($data['harga_jual_total'], 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center;">Tidak ada data laporan yang tersedia.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Laporan ini dibuat secara otomatis oleh Sistem Inventori Kecap Riboet.
    </div>
</body>
</html>
