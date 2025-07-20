<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok Kedaluwarsa Kecap Riboet</title>
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
        .expired {
            color: red;
            font-weight: bold;
        }
        .soon-expired {
            color: orange;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Laporan Stok Kedaluwarsa Kecap Riboet</h1>
    <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}</p>

    <table>
        <thead>
            <tr>
                <th>Kode Kecap</th>
                <th>Ukuran</th>
                <th>Stok Masuk Awal</th>
                <th>Stok Keluar</th>
                <th>Stok Tersisa</th>
                <th>Tgl. Masuk</th>
                <th>Tgl. Expired</th>
                <th>Keterangan Akan Kedaluwarsa</th>
                <th>Keterangan Sudah Kedaluwarsa</th>
            </tr>
        </thead>
        <tbody>
            {{-- Mengakses sebagai array --}}
            @forelse ($laporanExpired as $data)
                <tr>
                    <td>{{ $data['kode_kecap'] }}</td>
                    <td>{{ ucfirst($data['ukuran']) }}</td>
                    <td>{{ $data['jumlah_stok_masuk_awal'] }}</td>
                    <td>{{ $data['jumlah_stok_keluar'] }}</td>
                    <td>{{ $data['jumlah_stok_tersisa'] }}</td>
                    {{-- Pastikan tanggal di-parse jika bukan objek Carbon --}}
                    <td>{{ $data['tanggal_masuk'] ? \Carbon\Carbon::parse($data['tanggal_masuk'])->format('d-m-Y') : 'N/A' }}</td>
                    <td>{{ $data['tanggal_expired'] ? \Carbon\Carbon::parse($data['tanggal_expired'])->format('d-m-Y') : 'N/A' }}</td>
                    <td>{{ $data['keterangan_akan_kedaluwarsa'] ?? '-' }}</td>
                    <td>{{ $data['keterangan_sudah_kedaluwarsa'] ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    {{-- Update colspan menjadi 9 karena ada 9 kolom --}}
                    <td colspan="9" style="text-align: center;">Tidak ada data stok kedaluwarsa atau mendekati kedaluwarsa.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Laporan ini dibuat secara otomatis oleh Sistem Inventori Kecap Riboet.
    </div>
</body>
</html>
