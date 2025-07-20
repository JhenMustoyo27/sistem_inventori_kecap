@extends('layouts.app')

@section('title', 'Edit Stok Masuk - Admin')

@section('content')
<!-- Form Edit Data -->
        <section class="bg-white p-6 rounded-xl shadow-md mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Edit Data Kecap Masuk: {{ $kecapMasuk->kode_kecap }}</h2>
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

            <form action="{{ route('admin.stok_masuk.update', $kecapMasuk->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- Gunakan method PUT untuk update --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="kode_kecap" class="block text-gray-700 text-sm font-semibold mb-2">Kode Kecap</label>
                        <input type="text" id="kode_kecap" name="kode_kecap" class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('kode_kecap', $kecapMasuk->kode_kecap) }}" required>
                    </div>
                    <div>
                        <label for="ukuran" class="block text-gray-700 text-sm font-semibold mb-2">Ukuran</label>
                        <select id="ukuran" name="ukuran" class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                            <option value="">Pilih Ukuran</option>
                            <option value="besar" {{ old('ukuran', $kecapMasuk->ukuran) == 'besar' ? 'selected' : '' }}>Besar</option>
                            <option value="sedang" {{ old('ukuran', $kecapMasuk->ukuran) == 'sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="kecil" {{ old('ukuran', $kecapMasuk->ukuran) == 'kecil' ? 'selected' : '' }}>Kecil</option>
                        </select>
                    </div>
                    <div>
                        <label for="tanggal_masuk" class="block text-gray-700 text-sm font-semibold mb-2">Tanggal Masuk</label>
                        <input type="date" id="tanggal_masuk" name="tanggal_masuk" class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('tanggal_masuk', $kecapMasuk->tanggal_masuk->format('Y-m-d')) }}" required>
                    </div>
                    <div>
                        <label for="kualitas" class="block text-gray-700 text-sm font-semibold mb-2">Kualitas</label>
                        <input type="text" id="kualitas" name="kualitas" class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('kualitas', $kecapMasuk->kualitas) }}" required>
                    </div>
                    <div>
                        <label for="harga_jual" class="block text-gray-700 text-sm font-semibold mb-2">Harga Jual</label>
                        <input type="number" step="0.01" id="harga_jual" name="harga_jual" class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('harga_jual', $kecapMasuk->harga_jual) }}" required>
                    </div>
                </div>
                <div class="mt-6 flex space-x-4">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-300 ease-in-out">
                        Update Data
                    </button>
                    <a href="{{ route('admin.stok_masuk.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-300 ease-in-out">
                        Batal
                    </a>
                </div>
            </form>
        </section>
@endsection
