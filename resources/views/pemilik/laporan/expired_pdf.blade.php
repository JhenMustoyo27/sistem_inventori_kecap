<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok Kedaluwarsa Kecap Riboet</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
        }
        .header {
            text-align: start;
            margin-bottom: 20px;
        }
        .logo {
            width: 80px;
            height: auto;
            display: block;
            margin: 0 auto 10px auto;
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
        .signature {
            margin-top: 5px;
            text-align: right;
            font-size: 10px;
        }
        .signature p {
            margin-bottom: 6px; /* space for actual signature */
        }

        .signature .nama {
            margin-top: 60px;
        }
        .no-border-table,
        .no-border-table td {
            border: none;
            padding: 0;
            margin: 0;
        }
        .no-border-table {
            margin-bottom: 10px;
            margin-left: auto;
            margin-right: auto;
        }
        .no-border-table h1 {
            margin: 0;
            font-size: 16px;
            text-align: left;
        }
    </style>
</head>
<body>
     {{-- Laravel Blade --}}
    @php
        $logoPath = public_path('image/logo-ishaku.png');
        $logoBase64 = 'public:image/logo-ishaku.png;base64,' . base64_encode(file_get_contents($logoPath));
    @endphp

    <table class="no-border-table">
    <tr>
        <td style="padding-left: 80px;">
            <img src="{{ public_path('image/logo-ishaku.png') }}" alt="Logo" width="70">
        </td>
        <td style="padding-left: 0px;">
            <h1>Laporan Expired Stok Kecap Riboet</h1>
        </td>
    </tr>
</table>
<hr>
<p style="text-align: center;">Tanggal Laporan: {{ date('d-m-Y H:i:s') }}</p>

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

    <!-- Tanda tangan -->
   <div class="signature">
        <p>Purwokerto, {{ now()->format('d F Y') }}</p>
        <p>Pemilik Kecap Riboet</p>
        <p class="nama"><strong>Indra Kumaladewa</strong></p> <!-- Ganti dengan nama pemilik -->
    </div>

    <div class="footer">
        Laporan ini dibuat secara otomatis oleh sistem Kecap Riboet.
    </div>
</body>
</html>
