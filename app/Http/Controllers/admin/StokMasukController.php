<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KecapMasuk;
use App\Models\StokHistory; // Import model StokHistory
use Illuminate\Http\Request;
use Carbon\Carbon; // Untuk manipulasi tanggal (meskipun tidak lagi digunakan untuk expired, mungkin masih berguna di tempat lain)

class StokMasukController extends Controller
{
    /**
     * Menampilkan daftar stok masuk.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = KecapMasuk::query();

        // Fitur pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('kode_kecap', 'like', '%' . $search . '%')
                  ->orWhere('ukuran', 'like', '%' . $search . '%')
                  ->orWhere('kualitas', 'like', '%' . $search . '%');
        }

        // Mengambil data kecap masuk dengan paginasi
        $kecapMasuk = $query->orderBy('tanggal_masuk', 'desc')->paginate(10);

        return view('admin.stok_masuk.index', compact('kecapMasuk'));
    }

    /**
     * Menyimpan data stok masuk baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'kode_kecap' => 'required|string|max:255|unique:kecap_masuk,kode_kecap',
            'ukuran' => 'required|string|in:besar,sedang,kecil',
            'tanggal_masuk' => 'required|date',
            'tanggal_expired' => 'required|date|after_or_equal:tanggal_masuk', // Validasi tanggal expired
            'kualitas' => 'required|string|max:255',
            'harga_jual' => 'required|numeric|min:0',
        ], [
            'kode_kecap.unique' => 'Kode Kecap ini sudah ada. Harap gunakan kode lain.',
            'ukuran.in' => 'Ukuran harus besar, sedang, atau kecil.',
            'tanggal_expired.after_or_equal' => 'Tanggal Expired tidak boleh sebelum Tanggal Masuk.',
        ]);

        // Buat data kecap masuk baru
        $kecapMasuk = KecapMasuk::create([
            'kode_kecap' => $request->kode_kecap,
            'ukuran' => $request->ukuran,
            'tanggal_masuk' => $request->tanggal_masuk,
            'tanggal_expired' => $request->tanggal_expired, // Menggunakan input manual
            'kualitas' => $request->kualitas,
            'harga_jual' => $request->harga_jual,
        ]);

        // Catat histori stok masuk
        StokHistory::create([
            'kecap_masuk_id' => $kecapMasuk->id,
            'event_type' => 'stok_masuk_created',
            'kode_kecap' => $kecapMasuk->kode_kecap,
            'ukuran' => $kecapMasuk->ukuran,
            'quantity' => 0, // Kuantitas di sini bisa 0 atau disesuaikan jika ada konsep "kuantitas masuk" di KecapMasuk
            'description' => 'Data kecap masuk baru ditambahkan. Kode: ' . $kecapMasuk->kode_kecap . ', Ukuran: ' . $kecapMasuk->ukuran . '.',
        ]);

        return redirect()->route('admin.stok_masuk.index')->with('success', 'Data kecap masuk berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit data stok masuk.
     *
     * @param  \App\Models\KecapMasuk  $kecapMasuk
     * @return \Illuminate\View\View
     */
    public function edit(KecapMasuk $kecapMasuk)
    {
            // Fetch all available KecapMasuk entries for the dropdown, similar to index
            // Although for edit, we might just display the current kecapMasuk info
        $availableKecapMasuk = KecapMasuk::whereHas('masterStok')
                                        ->select('id', 'kode_kecap', 'ukuran', 'harga_jual')
                                        ->orderBy('kode_kecap')
                                        ->get();
        return view('admin.stok_masuk.edit', compact('kecapMasuk'));
    }

    /**
     * Memperbarui data stok masuk yang sudah ada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\KecapMasuk  $kecapMasuk
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, KecapMasuk $kecapMasuk)
    {
        // Validasi input
        $request->validate([
            'kode_kecap' => 'required|string|max:255|unique:kecap_masuk,kode_kecap,' . $kecapMasuk->id,
            'ukuran' => 'required|string|in:besar,sedang,kecil',
            'tanggal_masuk' => 'required|date',
            'tanggal_expired' => 'required|date|after_or_equal:tanggal_masuk', // Validasi tanggal expired
            'kualitas' => 'required|string|max:255',
            'harga_jual' => 'required|numeric|min:0',
        ], [
            'kode_kecap.unique' => 'Kode Kecap ini sudah ada. Harap gunakan kode lain.',
            'ukuran.in' => 'Ukuran harus besar, sedang, atau kecil.',
            'tanggal_expired.after_or_equal' => 'Tanggal Expired tidak boleh sebelum Tanggal Masuk.',
        ]);

        // Perbarui data kecap masuk
        $kecapMasuk->update([
            'kode_kecap' => $request->kode_kecap,
            'ukuran' => $request->ukuran,
            'tanggal_masuk' => $request->tanggal_masuk,
            'tanggal_expired' => $request->tanggal_expired, // Menggunakan input manual
            'kualitas' => $request->kualitas,
            'harga_jual' => $request->harga_jual,
        ]);

        // Catat histori stok masuk yang diperbarui
        StokHistory::create([
            'kecap_masuk_id' => $kecapMasuk->id,
            'event_type' => 'stok_masuk_updated',
            'kode_kecap' => $kecapMasuk->kode_kecap,
            'ukuran' => $kecapMasuk->ukuran,
            'quantity' => 0, // Kuantitas di sini bisa 0 atau disesuaikan
            'description' => 'Data kecap masuk diperbarui. Kode: ' . $kecapMasuk->kode_kecap . ', Ukuran: ' . $kecapMasuk->ukuran . '.',
        ]);

        return redirect()->route('admin.stok_masuk.index')->with('success', 'Data kecap masuk berhasil diperbarui!');
    }

    /**
     * Menghapus data stok masuk.
     *
     * @param  \App\Models\KecapMasuk  $kecapMasuk
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(KecapMasuk $kecapMasuk)
    {
        // Cek apakah ada stok keluar yang terkait
        if ($kecapMasuk->stokKeluar()->exists()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus data kecap masuk ini karena masih ada data stok keluar yang terkait.');
        }

        // Catat histori sebelum dihapus
        StokHistory::create([
            'kecap_masuk_id' => $kecapMasuk->id,
            'event_type' => 'stok_masuk_deleted',
            'kode_kecap' => $kecapMasuk->kode_kecap,
            'ukuran' => $kecapMasuk->ukuran,
            'quantity' => 0, // Kuantitas di sini bisa 0 atau disesuaikan
            'description' => 'Data kecap masuk dihapus. Kode: ' . $kecapMasuk->kode_kecap . ', Ukuran: ' . $kecapMasuk->ukuran . '.',
        ]);

        $kecapMasuk->delete();
        return redirect()->route('admin.stok_masuk.index')->with('success', 'Data kecap masuk berhasil dihapus!');
    }
}
