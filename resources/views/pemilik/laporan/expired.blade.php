@extends('layouts.app')

@section('title', 'Laporan Stok Expired - Pemilik')

@section('content')
<!-- Filter dan Tombol Download -->
        <section class="bg-white p-6 rounded-xl shadow-md mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Filter Laporan Expired</h2>
                <form action="{{ route('pemilik.laporan.expired') }}" method="GET" class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <input type="text" name="search" placeholder="Cari kode kecap atau ukuran..." class="shadow-sm appearance-none border rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 flex-grow w-full" value="{{ request('search') }}">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300 ease-in-out w-full sm:w-auto">
                        Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('pemilik.laporan.expired') }}" class="bg-gray-400 text-center hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-300 ease-in-out w-full sm:w-auto">
                            Reset
                        </a>
                    @endif
                    <a href="{{ route('pemilik.laporan.download_expired_pdf', ['search' => request('search')]) }}" class="bg-green-600 text-center hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-300 ease-in-out w-full sm:w-auto">
                        Unduh PDF
                    </a>
                </form>
            </section>

        <!-- Tabel Laporan Expired -->
        <section class="bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Ringkasan Stok Expired & Akan Expired</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-lg shadow-sm">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Kode Kecap</th>
                            <th class="py-3 px-6 text-left">Ukuran</th>
                            <th class="py-3 px-6 text-left">Stok Masuk Awal</th>
                            <th class="py-3 px-6 text-left">Stok Keluar</th>
                            <th class="py-3 px-6 text-left">Stok Tersisa</th>
                            <th class="py-3 px-6 text-left">Tgl. Masuk</th>
                            <th class="py-3 px-6 text-left">Tgl. Expired</th>
                            <th class="py-3 px-6 text-left">Keterangan Akan Kedaluwarsa</th>
                            <th class="py-3 px-6 text-left">Keterangan Sudah Kedaluwarsa</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm font-light">
                        @forelse ($laporanExpiredPaginated as $item)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6 text-left whitespace-nowrap">{{ $item['kode_kecap'] }}</td>
                                <td class="py-3 px-6 text-left capitalize">{{ $item['ukuran'] }}</td>
                                <td class="py-3 px-6 text-left">{{ $item['jumlah_stok_masuk_awal'] }}</td>
                                <td class="py-3 px-6 text-left">{{ $item['jumlah_stok_keluar'] }}</td>
                                <td class="py-3 px-6 text-left">{{ $item['jumlah_stok_tersisa'] }}</td>
                                <td class="py-3 px-6 text-left">{{ $item['tanggal_masuk'] ? \Carbon\Carbon::parse($item['tanggal_masuk'])->format('d-m-Y') : 'N/A' }}</td>
                                <td class="py-3 px-6 text-left">{{ $item['tanggal_expired'] ? \Carbon\Carbon::parse($item['tanggal_expired'])->format('d-m-Y') : 'N/A' }}</td>
                                <td class="py-3 px-6 text-left">{{ $item['keterangan_akan_kedaluwarsa'] ?? '-' }}</td>
                                <td class="py-3 px-6 text-left">{{ $item['keterangan_sudah_kedaluwarsa'] ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-3 px-6 text-center text-gray-500">Tidak ada data laporan expired.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginasi -->
            <div class="mt-4">
                {{ $laporanExpiredPaginated->links() }}
            </div>
        </section>
@endsection
