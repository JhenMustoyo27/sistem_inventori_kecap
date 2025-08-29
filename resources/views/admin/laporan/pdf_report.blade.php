<!DOCTYPE html>
<html>
<head>
    <title>Laporan Akhir Stok Kecap Riboet</title>
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
            <h1>Laporan Akhir Stok Kecap Putra Riboet</h1>
        </td>
    </tr>
</table>
<hr>
<p style="text-align: center;">Tanggal Laporan: {{ date('d-m-Y H:i:s') }}</p>

    <table>
        <thead>
            <tr>
                <th>Id Input</th>
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
                    <td>{{ $item['kecap_masuk_id'] }}</td>
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

    <!-- Tanda tangan -->
<div class="signature">
        <p>Purwokerto, {{ now()->format('d F Y') }}</p>
        <p>Pemilik Kecap Riboet</p>
        <p class="nama"><strong>Indra Kumaladewa</strong></p> <!-- Ganti dengan nama pemilik -->
    </div>

    <div class="footer">
        Laporan ini dibuat secara otomatis oleh sistem Kecap Putra Riboet.
    </div>
</body>
</html>

