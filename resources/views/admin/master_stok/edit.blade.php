@extends('layouts.app')

@section('title', 'Edit Master Stok - Admin')

@section('content')
<!-- Form Edit Data Master Stok -->
        <section class="bg-white p-6 rounded-xl shadow-md mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Edit Data Master Stok: {{ $masterStok->kecapMasuk->kode_kecap ?? 'N/A' }} (Ukuran: {{ $masterStok->kecapMasuk->ukuran ?? 'N/A' }})</h2>
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                    <strong class="font-bold">Oops!</strong>
                    <span class="block sm:inline">Ada beberapa masalah dengan input Anda.</span>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.master_stok.update', $masterStok) }}" method="POST">
                @csrf
                @method('PUT') {{-- Gunakan method PUT untuk update --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="kode_kecap_display" class="block text-gray-700 text-sm font-semibold mb-2">Kode Kecap</label>
                        <input type="text" id="kode_kecap_display" class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 bg-gray-100 cursor-not-allowed" value="{{ $masterStok->kecapMasuk->kode_kecap ?? 'N/A' }}" disabled>
                    </div>
                    <div>
                        <label for="ukuran_display" class="block text-gray-700 text-sm font-semibold mb-2">Ukuran</label>
                        <input type="text" id="ukuran_display" class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 bg-gray-100 cursor-not-allowed" value="{{ ucfirst($masterStok->kecapMasuk->ukuran ?? 'N/A') }}" disabled>
                    </div>
                    <div>
                        <label for="master_stok" class="block text-gray-700 text-sm font-semibold mb-2">Master Stok</label>
                        <input type="number" id="master_stok" name="master_stok" class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('master_stok', $masterStok->master_stok) }}" required min="0">
                    </div>
                    <div>
                        <label for="tanggal_input_stok" class="block text-gray-700 text-sm font-semibold mb-2">Tanggal Input Stok</label>
                        <input type="date" id="tanggal_input_stok" name="tanggal_input_stok" class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('tanggal_input_stok', $masterStok->tanggal_input_stok->format('Y-m-d')) }}" required>
                    </div>
                </div>
                <div class="mt-6 flex space-x-4">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-300 ease-in-out">
                        Update Data
                    </button>
                    <a href="{{ route('admin.master_stok.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-300 ease-in-out">
                        Batal
                    </a>
                </div>
            </form>
        </section>
@endsection
