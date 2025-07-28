@extends('layouts.app')

@section('title', 'Stok Keluar - Admin')

@section('content')
<!-- Form Tambah Data Stok Keluar -->
        <section class="bg-white p-6 rounded-xl shadow-md mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Input Stok Keluar</h2>
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

            @if (session('success'))
                <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('admin.stok_keluar.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="kecap_masuk_id" class="block text-gray-700 text-sm font-semibold mb-2">Kode Kecap</label>
                        <select id="kecap_masuk_id" name="kecap_masuk_id" class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                            <option value="">Pilih Kode Kecap</option>
                            @foreach($availableKecapMasuk as $kecap)
                                <option value="{{ $kecap->id }}" data-ukuran="{{ $kecap->ukuran }}" data-harga-jual="{{ $kecap->harga_jual }}">{{ $kecap->kode_kecap }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="ukuran_display" class="block text-gray-700 text-sm font-semibold mb-2">Ukuran</label>
                        <input type="text" id="ukuran_display" class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 bg-gray-100 cursor-not-allowed" disabled placeholder="Ukuran akan muncul otomatis">
                    </div>
                    <div>
                        <label for="jumlah_keluar" class="block text-gray-700 text-sm font-semibold mb-2">Jumlah Stok Keluar</label>
                        <input type="number" id="jumlah_keluar" name="jumlah_keluar" class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('jumlah_keluar') }}" required min="1">
                        <p id="stok_tersedia_info" class="text-xs text-gray-500 mt-1">Stok tersedia: 0</p>
                    </div>
                    <div>
                        <label for="tanggal_keluar" class="block text-gray-700 text-sm font-semibold mb-2">Tanggal Keluar</label>
                        <input type="date" id="tanggal_keluar" name="tanggal_keluar" class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('tanggal_keluar', date('Y-m-d')) }}" required>
                    </div>
                    <div>
                        <label for="harga_jual" class="block text-gray-700 text-sm font-semibold mb-2">Harga Jual</label>
                        <input type="number" step="0.01" id="harga_jual" name="harga_jual" class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('harga_jual') }}" required min="0">
                    </div>
                    <div>
                        <label for="keterangan" class="block text-gray-700 text-sm font-semibold mb-2">Keterangan (Opsional)</label>
                        <input type="text" id="keterangan" name="keterangan" class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('keterangan') }}">
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-300 ease-in-out">
                        Submit
                    </button>
                </div>
            </form>
        </section>

        <!-- Tabel Data Stok Keluar -->
        <section class="bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Data Stok Keluar</h2>

            <!-- Form Pencarian -->
            <form action="{{ route('admin.stok_keluar.index') }}" method="GET" class="mb-4 flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                <input type="text" name="search" placeholder="Cari kode kecap, ukuran, atau tanggal..." class="shadow-sm appearance-none border rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 w-full sm:flex-grow" value="{{ request('search') }}">
                <div class="flex space-x-2 w-full sm:w-auto">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300 ease-in-out w-full sm:w-auto">
                        Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.stok_keluar.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-300 ease-in-out w-full sm:w-auto">
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-lg shadow-sm">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Kode Kecap</th>
                            <th class="py-3 px-6 text-left">Ukuran</th>
                            <th class="py-3 px-6 text-left">Jumlah Keluar</th>
                            <th class="py-3 px-6 text-left">Tanggal Masuk (Batch)</th>
                            <th class="py-3 px-6 text-left">Tanggal Keluar</th>
                            <th class="py-3 px-6 text-left">Harga Jual</th>
                            <th class="py-3 px-6 text-left">Keterangan</th>
                            {{-- Removed Aksi column --}}
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm font-light">
                        @forelse ($stokKeluar as $stok)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6 text-left whitespace-nowrap">{{ $stok->kecapMasuk?->kode_kecap ?? 'N/A' }}</td>
                                <td class="py-3 px-6 text-left capitalize">{{ $stok->kecapMasuk?->ukuran ?? 'N/A' }}</td>
                                <td class="py-3 px-6 text-left">{{ $stok->jumlah_keluar }}</td>
                                <td class="py-3 px-6 text-left">{{ $stok->kecapMasuk?->tanggal_masuk?->format('d-m-Y') ?? 'N/A' }}</td>
                                <td class="py-3 px-6 text-left">{{ $stok->tanggal_keluar->format('d-m-Y') }}</td>
                                <td class="py-3 px-6 text-left">Rp {{ number_format($stok->harga_jual, 2, ',', '.') }}</td>
                                <td class="py-3 px-6 text-left">{{ $stok->keterangan ?? '-' }}</td>
                                {{-- Removed action buttons --}}
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-3 px-6 text-center text-gray-500">Tidak ada data stok keluar.</td> {{-- Adjusted colspan back to 7 --}}
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginasi -->
            <div class="mt-4">
                {{ $stokKeluar->links() }}
            </div>
        </section>

{{-- Removed Modal Konfirmasi Hapus --}}

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const kecapMasukIdSelect = document.getElementById('kecap_masuk_id');
        const ukuranDisplayInput = document.getElementById('ukuran_display');
        const hargaJualInput = document.getElementById('harga_jual');
        const stokTersediaInfo = document.getElementById('stok_tersedia_info');
        const jumlahKeluarInput = document.getElementById('jumlah_keluar');

        // Fungsi untuk memperbarui input ukuran dan harga jual berdasarkan pilihan kode kecap
        function updateUkuranDanHargaJual() {
            const selectedOption = kecapMasukIdSelect.options[kecapMasukIdSelect.selectedIndex];
            const kecapMasukId = selectedOption ? selectedOption.value : '';

            if (kecapMasukId) {
                // Ambil ukuran dan harga jual dari data-attribute pada option
                const ukuran = selectedOption.dataset.ukuran;
                const hargaJual = selectedOption.dataset.hargaJual;

                ukuranDisplayInput.value = ukuran ? ukuran.charAt(0).toUpperCase() + ukuran.slice(1) : '';
                hargaJualInput.value = hargaJual || '';

                // Lakukan fetch data stok tersedia dari server
                fetch(`/admin/get-ukuran-dan-harga-by-kecap-id?kecap_masuk_id=${kecapMasukId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.stok_tersedia !== undefined) {
                            stokTersediaInfo.textContent = `Stok tersedia: ${data.stok_tersedia}`;
                            jumlahKeluarInput.max = data.stok_tersedia; // Set max attribute for validation
                        } else {
                            stokTersediaInfo.textContent = 'Stok tersedia: N/A';
                            jumlahKeluarInput.max = '';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching stok tersedia:', error);
                        stokTersediaInfo.textContent = 'Stok tersedia: Error';
                        jumlahKeluarInput.max = '';
                    });

            } else {
                ukuranDisplayInput.value = '';
                hargaJualInput.value = '';
                stokTersediaInfo.textContent = 'Stok tersedia: 0';
                jumlahKeluarInput.max = '';
            }
        }

        // Panggil saat halaman dimuat
        updateUkuranDanHargaJual();

        // Tambahkan event listener untuk perubahan pada dropdown Kode Kecap
        kecapMasukIdSelect.addEventListener('change', updateUkuranDanHargaJual);
    });

    setTimeout(function() {
        var message = document.getElementById('success-message');
        if (message) {
            message.style.display = 'none';
        }
    }, 5000);
    {{-- Removed showDeleteModal and hideDeleteModal functions --}}
</script>
@endsection
