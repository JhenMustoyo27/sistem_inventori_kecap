@extends('layouts.app')

@section('title', 'Laporan Akhir Stok - Admin')

@section('content')
<!-- Filter dan Tombol Download -->
        <section class="bg-white p-6 rounded-xl shadow-md mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Filter Laporan</h2>
                <form action="{{ route('admin.laporan.index') }}" method="GET" class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <input type="text" name="search" placeholder="Cari kode kecap atau ukuran..." class="shadow-sm appearance-none border rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 flex-grow w-full" value="{{ request('search') }}">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300 ease-in-out w-full sm:w-auto">
                        Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.laporan.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-300 ease-in-out w-full sm:w-auto">
                            Reset
                        </a>
                    @endif
                    <a href="{{ route('admin.laporan.download_pdf', ['search' => request('search')]) }}" class="bg-green-600 text-center hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-300 ease-in-out w-full sm:w-auto">
                        Unduh PDF
                    </a>
                </form>
            </section>

        <!-- Tabel Laporan -->
        <section class="bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Ringkasan Stok</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-lg shadow-sm">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Kode Kecap</th>
                            <th class="py-3 px-6 text-left">Ukuran</th>
                            <th class="py-3 px-6 text-left">Stok Masuk Awal</th>
                            <th class="py-3 px-6 text-left">Stok Keluar</th>
                            <th class="py-3 px-6 text-left">Stok Tersisa</th>
                            <th class="py-3 px-6 text-left">Tgl. Masuk (Batch Awal)</th>
                            <th class="py-3 px-6 text-left">Tgl. Keluar (Terakhir)</th>
                            <th class="py-3 px-6 text-left">Harga Jual </th>
                            <th class="py-3 px-6 text-left">Total Harga Jual (dari Keluar)</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm font-light">
                        @forelse ($laporanPaginated as $item)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6 text-left whitespace-nowrap">{{ $item['kode_kecap'] }}</td>
                                <td class="py-3 px-6 text-left capitalize">{{ $item['ukuran'] }}</td>
                                <td class="py-3 px-6 text-left">{{ $item['jumlah_stok_masuk_awal'] }}</td>
                                <td class="py-3 px-6 text-left">{{ $item['jumlah_stok_keluar'] }}</td>
                                <td class="py-3 px-6 text-left">{{ $item['jumlah_stok_tersisa'] }}</td>
                                <td class="py-3 px-6 text-left">{{ $item['tanggal_masuk'] ? $item['tanggal_masuk']->format('d-m-Y') : 'N/A' }}</td>
                                <td class="py-3 px-6 text-left">{{ $item['tanggal_keluar_terakhir'] ? $item['tanggal_keluar_terakhir']->format('d-m-Y') : 'N/A' }}</td>
                                <td class="py-3 px-6 text-left">Rp {{ number_format($item['harga_jual'], 2, ',', '.') }}</td>
                                <td class="py-3 px-6 text-left">Rp {{ number_format($item['total_harga_jual_dari_keluar'], 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-3 px-6 text-center text-gray-500">Tidak ada data laporan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginasi -->
            <div class="mt-4">
                {{ $laporanPaginated->links() }}
            </div>
        </section>
@endsection
