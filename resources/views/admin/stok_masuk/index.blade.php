@extends('layouts.app')

@section('title', 'Stok Masuk - Admin')

@section('content')
<!-- Form Tambah Data -->
        <section class="bg-white p-6 rounded-xl shadow-md mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Tambah Data Kecap Masuk</h2>
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

            {{-- @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif --}}

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @elseif (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                    <strong class="font-bold">Gagal Hapus!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <form action="{{ route('admin.stok_masuk.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="kode_kecap" class="block text-gray-700 text-sm font-semibold mb-2">Kode Kecap</label>
                        <input type="text" id="kode_kecap" name="kode_kecap" class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('kode_kecap') }}" required>
                    </div>
                    <div>
                        <label for="ukuran" class="block text-gray-700 text-sm font-semibold mb-2">Ukuran</label>
                        <select id="ukuran" name="ukuran" class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                            <option value="">Pilih Ukuran</option>
                            <option value="besar" {{ old('ukuran') == 'besar' ? 'selected' : '' }}>Besar</option>
                            <option value="sedang" {{ old('ukuran') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="kecil" {{ old('ukuran') == 'kecil' ? 'selected' : '' }}>Kecil</option>
                        </select>
                    </div>
                    <div>
                        <label for="tanggal_masuk" class="block text-gray-700 text-sm font-semibold mb-2">Tanggal Masuk</label>
                        <input type="date" id="tanggal_masuk" name="tanggal_masuk" class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('tanggal_masuk') }}" required>
                    </div>
                    <!-- New input field for Tanggal Expired -->
                    <div>
                        <label for="tanggal_expired" class="block text-gray-700 text-sm font-semibold mb-2">Tanggal Expired</label>
                        <input type="date" id="tanggal_expired" name="tanggal_expired" class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('tanggal_expired') }}" required>
                    </div>
                    <div>
                        <label for="kualitas" class="block text-gray-700 text-sm font-semibold mb-2">Kualitas</label>
                        <input type="text" id="kualitas" name="kualitas" class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('kualitas') }}" required>
                    </div>
                    <div>
                        <label for="harga_jual" class="block text-gray-700 text-sm font-semibold mb-2">Harga Jual</label>
                        <input type="number" step="0.01" id="harga_jual" name="harga_jual" class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('harga_jual') }}" required>
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-300 ease-in-out">
                        Submit
                    </button>
                </div>
            </form>
        </section>

        <!-- Tabel Data Kecap Masuk -->
        <section class="bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Data Kecap Masuk</h2>

            <!-- Form Pencarian -->
            <form action="{{ route('admin.stok_masuk.index') }}" method="GET" class="mb-4 flex items-center space-x-2">
                <input type="text" name="search" placeholder="Cari kode, ukuran, atau kualitas..." class="shadow-sm appearance-none border rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 flex-grow" value="{{ request('search') }}">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300 ease-in-out">
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.stok_masuk.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-300 ease-in-out">
                        Reset
                    </a>
                @endif
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-lg shadow-sm">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Kode Kecap</th>
                            <th class="py-3 px-6 text-left">Ukuran</th>
                            <th class="py-3 px-6 text-left">Tanggal Masuk</th>
                            <th class="py-3 px-6 text-left">Tanggal Expired</th>
                            <th class="py-3 px-6 text-left">Kualitas</th>
                            <th class="py-3 px-6 text-left">Harga Jual</th>
                            <th class="py-3 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm font-light">
                        @forelse ($kecapMasuk as $kecap)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6 text-left whitespace-nowrap">{{ $kecap->kode_kecap }}</td>
                                <td class="py-3 px-6 text-left capitalize">{{ $kecap->ukuran }}</td>
                                <td class="py-3 px-6 text-left">{{ $kecap->tanggal_masuk->format('d-m-Y') }}</td>
                                <td class="py-3 px-6 text-left">{{ $kecap->tanggal_expired->format('d-m-Y') }}</td>
                                <td class="py-3 px-6 text-left">{{ $kecap->kualitas }}</td>
                                <td class="py-3 px-6 text-left">Rp{{ number_format($kecap->harga_jual, 2, ',', '.') }}</td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-center">
                                        <a href="{{ route('admin.stok_masuk.edit', $kecap->id) }}" class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>
                                        <button type="button" onclick="showDeleteModal({{ $kecap->id }})" class="w-4 mr-2 transform hover:text-red-500 hover:scale-110">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-3 px-6 text-center text-gray-500">Tidak ada data kecap masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginasi -->
            <div class="mt-4">
                {{ $kecapMasuk->links() }}
            </div>
        </section>

<!-- Modal Konfirmasi Hapus -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-sm">
        <h3 class="text-lg font-bold mb-4 text-gray-800">Konfirmasi Hapus</h3>
        <p class="mb-6 text-gray-700">Apakah Anda yakin ingin menghapus data ini?</p>
        <div class="flex justify-end space-x-4">
            <button type="button" onclick="hideDeleteModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200">Batal</button>
            <form id="deleteForm" method="POST" action="{{}}">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200">Hapus</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk menampilkan modal konfirmasi hapus
    function showDeleteModal(id) {
        const deleteForm = document.getElementById('deleteForm');
        // Mengatur action form ke route delete yang benar
        deleteForm.action = `/admin/stok-masuk/${id}`;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    // Fungsi untuk menyembunyikan modal konfirmasi hapus
    function hideDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endsection
