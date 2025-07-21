@extends('layouts.app')

@section('title', 'Laporan Akhir - Pemilik')

@section('content')
<section class="bg-white p-6 rounded-xl shadow-md mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Ringkasan Laporan Stok</h2>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                    <strong class="font-bold">Oops!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Form Pencarian dan Tombol Download PDF -->

            <div class="flex flex-col sm:flex-row justify-between items-center mb-4 space-y-4 sm:space-y-0">
                <form action="{{ route('pemilik.laporan.index') }}" method="GET" class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-2 w-full sm:w-2/3">
                    <input type="text" name="search" placeholder="Cari kode kecap, ukuran, atau kualitas..." class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 flex-grow" value="{{ request('search') }}">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300 ease-in-out w-full sm:w-auto">
                        Cari
                    </button>
                     @if(request('search'))
                        <a href="{{ route('pemilik.laporan.index') }}" class="bg-gray-400 text-center hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-300 ease-in-out w-full sm:w-auto">
                            Reset
                        </a>
                    @endif
                </form>
                <a href="{{ route('pemilik.laporan.download_pdf', ['search' => request('search')]) }}" class="w-full sm:w-auto text-center bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-300 ease-in-out mt-4 sm:ml-4 sm:mt-0">
                    Unduh PDF
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-lg shadow-sm">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Kode Kecap</th>
                            <th class="py-3 px-6 text-left">Ukuran</th>
                            <th class="py-3 px-6 text-left">Stok Masuk</th>
                            <th class="py-3 px-6 text-left">Stok Keluar</th>
                            <th class="py-3 px-6 text-left">Stok Tersisa</th>
                            <th class="py-3 px-6 text-left">Tanggal Masuk</th>
                            <th class="py-3 px-6 text-left">Tanggal Keluar Terakhir</th>
                            <th class="py-3 px-6 text-left">Harga Satuan</th>
                            <th class="py-3 px-6 text-left">Harga Jual Total (Dari Keluar)</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm font-light">
                        {{-- Mengubah $laporan menjadi $laporanPaginated --}}
                        @forelse ($laporanPaginated as $data)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6 text-left whitespace-nowrap">{{ $data['kode_kecap'] }}</td>
                                <td class="py-3 px-6 text-left capitalize">{{ $data['ukuran'] }}</td>
                                <td class="py-3 px-6 text-left">{{ $data['jumlah_stok_masuk'] }}</td>
                                <td class="py-3 px-6 text-left">{{ $data['jumlah_stok_keluar'] }}</td>
                                <td class="py-3 px-6 text-left">{{ $data['jumlah_stok_tersisa'] }}</td>
                                <td class="py-3 px-6 text-left">{{ \Carbon\Carbon::parse($data['tanggal_masuk'])->format('d-m-Y') }}</td>
                                <td class="py-3 px-6 text-left">{{ $data['tanggal_keluar'] }}</td>
                                <td class="py-3 px-6 text-left">Rp{{ number_format($data['harga_jual'], 2, ',', '.') }}</td>
                                <td class="py-3 px-6 text-left">Rp{{ number_format($data['harga_jual_total'], 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-3 px-6 text-center text-gray-500">Tidak ada data laporan yang tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginasi -->
            <div class="mt-4">
                {{-- Mengubah $laporan menjadi $laporanPaginated --}}
                {{ $laporanPaginated->links() }}
            </div>
        </section>
@endsection
