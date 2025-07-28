@extends('layouts.app')

@section('title', 'Kelola Stok - Admin')

@section('content')
<!-- Form Tambah Data Master Stok -->
        <section class="bg-white p-6 rounded-xl shadow-md mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Tambah Data Master Stok</h2>
            @if ($errors->any())
                <div id="eror-message" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
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
            
             @elseif (session('error'))
                <div id="gagal-message" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                    <strong class="font-bold">Gagal Hapus!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <form action="{{ route('admin.master_stok.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="kecap_masuk_id" class="block text-gray-700 text-sm font-semibold mb-2">Kode Kecap</label>
                        <select id="kecap_masuk_id" name="kecap_masuk_id" class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                            <option value="">Pilih Kode Kecap</option>
                            @foreach($availableKecapMasuk as $kecap)
                                <option value="{{ $kecap->id }}" data-ukuran="{{ $kecap->ukuran }}">{{ $kecap->kode_kecap }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="ukuran_display" class="block text-gray-700 text-sm font-semibold mb-2">Ukuran</label>
                        <input type="text" id="ukuran_display" class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 bg-gray-100 cursor-not-allowed" disabled placeholder="Ukuran akan muncul otomatis">
                    </div>
                    <div>
                        <label for="master_stok" class="block text-gray-700 text-sm font-semibold mb-2">Master Stok</label>
                        <input type="number" id="master_stok" name="master_stok" class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('master_stok') }}" required min="0">
                    </div>
                    <div>
                        <label for="tanggal_input_stok" class="block text-gray-700 text-sm font-semibold mb-2">Tanggal Input Stok</label>
                        <input type="date" id="tanggal_input_stok" name="tanggal_input_stok" class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('tanggal_input_stok', date('Y-m-d')) }}" required>
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-300 ease-in-out">
                        Submit
                    </button>
                </div>
            </form>
        </section>

        <!-- Tabel Data Master Stok -->
        <section class="bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Data Master Stok</h2>

            <!-- Form Pencarian -->         
            <form action="{{ route('admin.master_stok.index') }}" method="GET" class="mb-4 flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                <input type="text" name="search" placeholder="Cari kode kecap atau ukuran..." class="shadow-sm appearance-none border rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 w-full sm:flex-grow" value="{{ request('search') }}">
                <div class="flex space-x-2 w-full sm:w-auto">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300 ease-in-out w-full sm:w-auto">
                        Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.master_stok.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-300 ease-in-out w-full sm:w-auto">
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
                            <th class="py-3 px-6 text-left">Master Stok</th>
                            <th class="py-3 px-6 text-left">Stok Terakhir</th>
                            <th class="py-3 px-6 text-left">Tanggal Input</th>
                            <th class="py-3 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm font-light">
                        @forelse ($masterStok as $stok)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6 text-left whitespace-nowrap">{{ $stok->kecapMasuk->kode_kecap ?? 'N/A' }}</td>
                                <td class="py-3 px-6 text-left capitalize">{{ $stok->kecapMasuk->ukuran ?? 'N/A' }}</td>
                                <td class="py-3 px-6 text-left">{{ $stok->master_stok }}</td>
                                <td class="py-3 px-6 text-left">{{ $stok->stok_terakhir }}</td>
                                <td class="py-3 px-6 text-left">{{ $stok->tanggal_input_stok->format('d-m-Y') }}</td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-center">
                                        <a href="{{ route('admin.master_stok.edit', $stok) }}" class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>
                                        <button type="button" onclick="showDeleteModal({{ $stok->id }})" class="w-4 mr-2 transform hover:text-red-500 hover:scale-110">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                        {{-- Tombol Lihat Histori --}}
                                        <button type="button"
                                            @if ($stok->kecapMasuk)
                                                onclick="showHistoryModal({{ $stok->kecapMasuk->id }})"
                                            @else
                                                disabled title="No associated Kecap Masuk"
                                            @endif
                                            class="w-4 ml-2 transform hover:text-blue-500 hover:scale-110">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-3 px-6 text-center text-gray-500">Tidak ada data master stok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginasi -->
            <div class="mt-4">
                {{ $masterStok->links() }}
            </div>

            {{-- <h3 class="text-xl font-semibold text-gray-800 mt-8 mb-4">Histori Stok (Fitur Mendatang)</h3> --}}
            {{-- <p class="text-gray-700">Fitur untuk melihat histori perubahan stok akan ditambahkan di kemudian hari.</p> --}}
        </section>

<!-- Modal Konfirmasi Hapus -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-sm">
        <h3 class="text-lg font-bold mb-4 text-gray-800">Konfirmasi Hapus</h3>
        <p class="mb-6 text-gray-700">Apakah Anda yakin ingin menghapus data ini?</p>
        <div class="flex justify-end space-x-4">
            <button type="button" onclick="hideDeleteModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200">Batal</button>
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200">Hapus</button>
            </form>
        </div>
    </div>
</div>

<!-- Modal Histori Stok -->
<div id="historyModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden p-4 sm:p-6">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl max-h-[90vh] overflow-hidden flex flex-col">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-200 p-4 sm:p-6">
            <h3 class="text-lg sm:text-xl font-bold text-gray-800">Histori Stok untuk <span id="historyKecapCode"></span> (<span id="historyUkuran"></span>)</h3>
            <button type="button" onclick="hideHistoryModal()" class="text-gray-400 hover:text-gray-600 text-2xl sm:text-3xl">&times;</button>
        </div>
        <div id="historyContent" class="text-gray-700 overflow-y-auto px-4 sm:px-6 flex-grow">
            <!-- Histori akan dimuat di sini -->
            <p>Memuat histori...</p>
        </div>
        <div class="flex justify-end mt-4 p-4 sm:p-6 border-t border-gray-200">
            <button type="button" onclick="hideHistoryModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200">Tutup</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const kecapMasukIdSelect = document.getElementById('kecap_masuk_id');
        const ukuranDisplayInput = document.getElementById('ukuran_display');
        const historyModal = document.getElementById('historyModal');
        const historyKecapCodeSpan = document.getElementById('historyKecapCode');
        const historyUkuranSpan = document.getElementById('historyUkuran');
        const historyContentDiv = document.getElementById('historyContent');

        // Fungsi untuk memperbarui input ukuran berdasarkan pilihan kode kecap
        function updateUkuranDisplay() {
            const selectedOption = kecapMasukIdSelect.options[kecapMasukIdSelect.selectedIndex];
            const ukuran = selectedOption ? selectedOption.dataset.ukuran : '';
            ukuranDisplayInput.value = ukuran ? ukuran.charAt(0).toUpperCase() + ukuran.slice(1) : ''; // Capitalize first letter
        }

        // Panggil saat halaman dimuat (jika ada nilai terpilih dari old input)
        updateUkuranDisplay();

        // Tambahkan event listener untuk perubahan pada dropdown Kode Kecap
        kecapMasukIdSelect.addEventListener('change', updateUkuranDisplay);

        // Fungsi untuk menampilkan modal konfirmasi hapus
        window.showDeleteModal = function(id) {
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = `/admin/master-stok/${id}`; // Sesuaikan dengan rute delete master stok
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        // Fungsi untuk menyembunyikan modal konfirmasi hapus
        window.hideDeleteModal = function() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Fungsi untuk menampilkan modal histori stok
        window.showHistoryModal = function(kecapMasukId) {
            // Reset konten histori
            historyContentDiv.innerHTML = '<p>Memuat histori...</p>';
            historyKecapCodeSpan.textContent = '';
            historyUkuranSpan.textContent = '';

            // Ambil kode kecap dan ukuran dari baris tabel yang diklik
            const row = event.target.closest('tr'); // Dapatkan baris tabel
            const kodeKecap = row.children[0].textContent; // Kolom pertama adalah Kode Kecap
            const ukuran = row.children[1].textContent; // Kolom kedua adalah Ukuran

            historyKecapCodeSpan.textContent = kodeKecap;
            historyUkuranSpan.textContent = ukuran;

            // Lakukan fetch data histori
            fetch(`/admin/master-stok/${kecapMasukId}/history`)
                .then(response => {
                    if (!response.ok) {
                        // Jika respons tidak OK (misal 404, 500), lemparkan error
                        return response.json().then(err => { throw new Error(err.error || `HTTP error! status: ${response.status}`); });
                    }
                    return response.json();
                })
                .then(data => {
                    historyContentDiv.innerHTML = ''; // Bersihkan pesan loading
                    if (data.length > 0) {
                        const table = document.createElement('table');
                        table.className = 'min-w-full bg-white rounded-lg shadow-sm';
                        table.innerHTML = `
                            <thead>
                                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left">Tanggal</th>
                                    <th class="py-3 px-6 text-left">Kode Kecap</th>
                                    <th class="py-3 px-6 text-left">Ukuran</th>
                                    <th class="py-3 px-6 text-left">Jumlah</th>
                                    <th class="py-3 px-6 text-left">Stok Akhir</th>
                                    <th class="py-3 px-6 text-left">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 text-sm font-light">
                            </tbody>
                        `;
                        const tbody = table.querySelector('tbody');

                        data.forEach(history => {
                            const tr = document.createElement('tr');
                            tr.className = 'border-b border-gray-200 hover:bg-gray-100';

                            // Format tanggal
                            const date = new Date(history.created_at).toLocaleString('id-ID', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            });

                            // Tentukan tampilan kuantitas
                            let quantityDisplay = history.quantity;
                            if (history.event_type === 'stok_keluar') {
                                quantityDisplay = `-${history.quantity}`; // Tampilkan negatif untuk stok keluar
                            } else if (history.event_type === 'master_stok_deleted') {
                                quantityDisplay = `-${Math.abs(history.quantity)}`; // Pastikan negatif untuk dihapus
                            } else if (history.event_type === 'master_stok_updated') {
                                if (history.quantity > 0) {
                                    quantityDisplay = `+${history.quantity}`; // Jika update menambah stok
                                } else if (history.quantity < 0) {
                                    quantityDisplay = `${history.quantity}`; // Jika update mengurangi stok
                                }
                            } else if (history.event_type === 'stok_masuk_created') {
                                // Untuk stok_masuk_created, quantity di history bisa 0 atau jumlah awal
                                // Jika Anda ingin menampilkan jumlah stok masuk awal di sini, Anda perlu menyimpannya di kolom quantity saat event stok_masuk_created
                                // Untuk saat ini, kita bisa biarkan sesuai data atau tampilkan "N/A" jika quantity 0
                                quantityDisplay = history.quantity === 0 ? 'N/A' : `+${history.quantity}`;
                            }


                            tr.innerHTML = `
                                <td class="py-3 px-6 text-left whitespace-nowrap">${date}</td>
                                <td class="py-3 px-6 text-left">${history.kode_kecap}</td>
                                <td class="py-3 px-6 text-left capitalize">${history.ukuran}</td>
                                <td class="py-3 px-6 text-left">${quantityDisplay}</td>
                                <td class="py-3 px-6 text-left">${history.current_stock_after_event !== null ? history.current_stock_after_event : 'N/A'}</td>
                                <td class="py-3 px-6 text-left">${history.description}</td>
                            `;
                            tbody.appendChild(tr);
                        });
                        historyContentDiv.appendChild(table);
                    } else {
                        historyContentDiv.innerHTML = '<p class="text-gray-500">Tidak ada histori stok untuk kode kecap ini.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching stock history:', error);
                    historyContentDiv.innerHTML = `<p class="text-red-500">Gagal memuat histori stok. Detail: ${error.message}. Silakan coba lagi.</p>`;
                });

            historyModal.classList.remove('hidden');
        }

        // Fungsi untuk menyembunyikan modal histori stok
        window.hideHistoryModal = function() {
            historyModal.classList.add('hidden');
        }
    });
    setTimeout(function() {
        var message = document.getElementById('success-message');
        if (message) {
            message.style.display = 'none';
        }
    }, 5000);
    setTimeout(function() {
        var message = document.getElementById('gagal-message');
        if (message) {
            message.style.display = 'none';
        }
    }, 5000);
    setTimeout(function() {
        var message = document.getElementById('eror-message');
        if (message) {
            message.style.display = 'none';
        }
    }, 5000);
</script>
@endsection
