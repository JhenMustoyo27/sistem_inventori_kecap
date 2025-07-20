<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterStok;
use App\Models\KecapMasuk;
use App\Models\StokHistory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class MasterStokController extends Controller
{
    /**
     * Menampilkan daftar master stok dan form input.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = MasterStok::with('kecapMasuk');

        // Fitur pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('kecapMasuk', function ($q) use ($search) {
                $q->where('kode_kecap', 'like', '%' . $search . '%')
                  ->orWhere('ukuran', 'like', '%' . $search . '%');
            });
        }

        $masterStok = $query->orderBy('created_at', 'desc')->paginate(10);

        $availableKecapMasuk = KecapMasuk::select('id', 'kode_kecap', 'ukuran')
                                        ->orderBy('kode_kecap')
                                        ->get();

        return view('admin.master_stok.index', compact('masterStok', 'availableKecapMasuk'));
    }

    /**
     * Menyimpan data master stok baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'kecap_masuk_id' => [
                'required',
                'exists:kecap_masuk,id',
                Rule::unique('master_stok', 'kecap_masuk_id'),
            ],
            'master_stok' => 'required|integer|min:0',
            'tanggal_input_stok' => 'required|date',
        ], [
            'kecap_masuk_id.unique' => 'Kode Kecap ini sudah memiliki Master Stok. Harap pilih Kode Kecap yang berbeda atau edit data yang sudah ada.',
            'kecap_masuk_id.required' => 'Kode Kecap harus dipilih.',
            'kecap_masuk_id.exists' => 'Kode Kecap tidak valid.',
            'master_stok.required' => 'Master Stok harus diisi.',
            'master_stok.integer' => 'Master Stok harus berupa angka.',
            'master_stok.min' => 'Master Stok tidak boleh kurang dari 0.',
            'tanggal_input_stok.required' => 'Tanggal Input Stok harus diisi.',
            'tanggal_input_stok.date' => 'Tanggal Input Stok harus berupa tanggal yang valid.',
        ]);

        $kecapMasuk = KecapMasuk::find($request->kecap_masuk_id);

        $masterStok = MasterStok::create([
            'kecap_masuk_id' => $request->kecap_masuk_id,
            'master_stok' => $request->master_stok,
            'stok_terakhir' => $request->master_stok,
            'tanggal_input_stok' => $request->tanggal_input_stok,
        ]);

        StokHistory::create([
            'kecap_masuk_id' => $kecapMasuk->id,
            'master_stok_id' => $masterStok->id,
            'event_type' => 'master_stok_created',
            'kode_kecap' => $kecapMasuk->kode_kecap,
            'ukuran' => $kecapMasuk->ukuran,
            'quantity' => $request->master_stok,
            'current_stock_after_event' => $masterStok->stok_terakhir,
            'description' => 'Master stok baru ditambahkan.',
        ]);

        return redirect()->route('admin.master_stok.index')->with('success', 'Data master stok berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit data master stok.
     *
     * @param  \App\Models\MasterStok  $masterStok
     * @return \Illuminate\View\View
     */
    public function edit(MasterStok $masterStok)
    {
        $masterStok->load('kecapMasuk');
        return view('admin.master_stok.edit', compact('masterStok'));
    }

    /**
     * Memperbarui data master stok yang sudah ada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MasterStok  $masterStok
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, MasterStok $masterStok)
    {
        $request->validate([
            'master_stok' => 'required|integer|min:0',
            'tanggal_input_stok' => 'required|date',
        ], [
            'master_stok.required' => 'Master Stok harus diisi.',
            'master_stok.integer' => 'Master Stok harus berupa angka.',
            'master_stok.min' => 'Master Stok tidak boleh kurang dari 0.',
            'tanggal_input_stok.required' => 'Tanggal Input Stok harus diisi.',
            'tanggal_input_stok.date' => 'Tanggal Input Stok harus berupa tanggal yang valid.',
        ]);

        $old_master_stok = $masterStok->master_stok;
        $stok_diff = $request->master_stok - $old_master_stok;

        $masterStok->update([
            'master_stok' => $request->master_stok,
            'stok_terakhir' => $masterStok->stok_terakhir + $stok_diff,
            'tanggal_input_stok' => $request->tanggal_input_stok,
        ]);

        StokHistory::create([
            'kecap_masuk_id' => $masterStok->kecapMasuk->id,
            'master_stok_id' => $masterStok->id,
            'event_type' => 'master_stok_updated',
            'kode_kecap' => $masterStok->kecapMasuk->kode_kecap,
            'ukuran' => $masterStok->kecapMasuk->ukuran,
            'quantity' => $stok_diff,
            'current_stock_after_event' => $masterStok->stok_terakhir,
            'description' => 'Master stok diperbarui dari ' . $old_master_stok . ' menjadi ' . $request->master_stok . '.',
        ]);

        return redirect()->route('admin.master_stok.index')->with('success', 'Data master stok berhasil diperbarui!');
    }

    /**
     * Menghapus data master stok.
     *
     * @param  \App\Models\MasterStok  $masterStok
     * @return \Illuminate\Http\RedirectResponse
     */
  public function destroy(MasterStok $masterStok)
    {
        // Pastikan relasi kecapMasuk dimuat
        $masterStok->load('kecapMasuk');

        // Menggunakan null coalescing operator (??) untuk menangani kasus null
        // Atau Anda bisa menggunakan optional chaining (?->) jika Anda di PHP 8+ (Laravel 12.20.0 biasanya pakai PHP 8+)
        $kecapMasukId = $masterStok->kecapMasuk?->id ?? null;
        $kodeKecap = $masterStok->kecapMasuk?->kode_kecap ?? 'N/A';
        $ukuran = $masterStok->kecapMasuk?->ukuran ?? 'N/A';

        StokHistory::create([
            'kecap_masuk_id' => $kecapMasukId,
            'master_stok_id' => $masterStok->id,
            'event_type' => 'master_stok_deleted',
            'kode_kecap' => $kodeKecap,
            'ukuran' => $ukuran,
            'quantity' => -$masterStok->stok_terakhir,
            'current_stock_after_event' => 0,
            'description' => 'Master stok dihapus. Stok terakhir sebelum dihapus: ' . $masterStok->stok_terakhir . '.',
        ]);

        $masterStok->delete();
        return redirect()->route('admin.master_stok.index')->with('success', 'Data master stok berhasil dihapus!');
    }


    /**
     * Mengambil ukuran kecap berdasarkan ID kecap_masuk.
     * Digunakan untuk AJAX request di form master stok.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUkuranByKecapId(Request $request)
    {
        $kecapMasukId = $request->input('kecap_masuk_id');
        $kecapMasuk = KecapMasuk::find($kecapMasukId);

        if ($kecapMasuk) {
            return response()->json(['ukuran' => $kecapMasuk->ukuran]);
        }

        return response()->json(['ukuran' => null]);
    }

    /**
     * Menampilkan histori stok untuk kecap_masuk tertentu.
     *
     * @param  int  $kecapMasukId
     * @return \Illuminate\Http\JsonResponse
     */
    public function showHistory($kecapMasukId)
    {
        try {
            $kecapMasuk = KecapMasuk::findOrFail($kecapMasukId);

            // Mengambil histori stok yang terkait dengan kecap_masuk_id ini
            $histories = StokHistory::where('kecap_masuk_id', $kecapMasukId)
                                    ->orderBy('created_at', 'asc')
                                    ->get();

            return response()->json($histories);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error("KecapMasuk with ID {$kecapMasukId} not found for history. Error: " . $e->getMessage());
            return response()->json(['error' => 'Kecap Masuk tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            Log::error("Error fetching stock history for KecapMasuk ID {$kecapMasukId}. Error: " . $e->getMessage());
            return response()->json(['error' => 'Gagal memuat histori stok. Silakan coba lagi.'], 500);
        }
    }
}
