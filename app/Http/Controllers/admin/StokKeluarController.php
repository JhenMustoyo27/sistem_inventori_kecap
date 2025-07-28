<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StokKeluar;
use App\Models\MasterStok;
use App\Models\KecapMasuk;
use App\Models\StokHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StokKeluarController extends Controller
{
    /**
     * Menampilkan daftar stok keluar dan form input.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $availableKecapMasuk = KecapMasuk::whereHas('masterStok')
                                         ->select('id', 'kode_kecap', 'ukuran', 'harga_jual')
                                         ->orderBy('kode_kecap')
                                         ->get();

        $query = StokKeluar::with(['kecapMasuk', 'masterStok']);

        // Fitur pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('kecapMasuk', function ($q) use ($search) {
                $q->where('kode_kecap', 'like', '%' . $search . '%')
                  ->orWhere('ukuran', 'like', '%' . $search . '%');
            })->orWhere('tanggal_keluar', 'like', '%' . $search . '%');
        }

        $stokKeluar = $query->orderBy('tanggal_keluar', 'desc')->paginate(10);

        return view('admin.stok_keluar.index', compact('availableKecapMasuk', 'stokKeluar'));
    }

    /**
     * Menyimpan data stok keluar baru dengan metode FIFO.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'kecap_masuk_id' => 'required|exists:kecap_masuk,id',
            'jumlah_keluar' => 'required|integer|min:1',
            'tanggal_keluar' => 'required|date',
            'harga_jual' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'kecap_masuk_id.required' => 'Kode Kecap harus dipilih.',
            'kecap_masuk_id.exists' => 'Kode Kecap tidak valid.',
            'jumlah_keluar.required' => 'Jumlah Keluar harus diisi.',
            'jumlah_keluar.integer' => 'Jumlah Keluar harus berupa angka.',
            'jumlah_keluar.min' => 'Jumlah Keluar minimal 1.',
            'tanggal_keluar.required' => 'Tanggal Keluar harus diisi.',
            'tanggal_keluar.date' => 'Tanggal Keluar harus berupa tanggal yang valid.',
            'harga_jual.required' => 'Harga Jual harus diisi.',
            'harga_jual.numeric' => 'Harga Jual harus berupa angka.',
            'harga_jual.min' => 'Harga Jual tidak boleh kurang dari 0.',
        ]);

        $requestedKecapMasukId = $request->kecap_masuk_id;
        $requestedQuantity = $request->jumlah_keluar;
        $currentQuantity = $requestedQuantity;

        $kecapMasukInfo = KecapMasuk::find($requestedKecapMasukId);
        if (!$kecapMasukInfo) {
            return redirect()->back()->withErrors(['kecap_masuk_id' => 'Kode Kecap tidak ditemukan.']);
        }

        DB::beginTransaction();

        try {
            $masterStoks = MasterStok::where('kecap_masuk_id', $requestedKecapMasukId)
                                     ->where('stok_terakhir', '>', 0)
                                     ->orderBy('tanggal_input_stok', 'asc')
                                     ->get();

            $totalAvailableStok = $masterStoks->sum('stok_terakhir');

            if ($totalAvailableStok < $requestedQuantity) {
                DB::rollBack();
                return redirect()->back()->withErrors(['jumlah_keluar' => "Stok yang tersedia untuk kode kecap {$kecapMasukInfo->kode_kecap} ({$kecapMasukInfo->ukuran}) tidak mencukupi. Stok tersedia: {$totalAvailableStok}."]);
            }

            // Loop through master stock batches to deduct stock (FIFO)
            foreach ($masterStoks as $masterStok) {
                if ($currentQuantity <= 0) {
                    break;
                }

                $quantityToDeduct = min($currentQuantity, $masterStok->stok_terakhir);

                $masterStok->stok_terakhir -= $quantityToDeduct;
                $masterStok->save();

                StokKeluar::create([
                    'kecap_masuk_id' => $masterStok->kecap_masuk_id,
                    'master_stok_id' => $masterStok->id, // Link to the specific master_stok batch
                    'jumlah_keluar' => $quantityToDeduct,
                    'tanggal_keluar' => $request->tanggal_keluar,
                    'harga_jual' => $request->harga_jual,
                    'keterangan' => $request->keterangan,
                ]);

                // Catat histori stok
                StokHistory::create([
                    'kecap_masuk_id' => $masterStok->kecap_masuk_id,
                    'master_stok_id' => $masterStok->id,
                    'event_type' => 'stok_keluar',
                    'kode_kecap' => $kecapMasukInfo->kode_kecap,
                    'ukuran' => $kecapMasukInfo->ukuran,
                    'quantity' => -$quantityToDeduct, // Negative for stock reduction
                    'current_stock_after_event' => $masterStok->stok_terakhir,
                    'description' => "Stok keluar sejumlah {$quantityToDeduct} botol.",
                ]);

                $currentQuantity -= $quantityToDeduct;
            }

            DB::commit();

            return redirect()->route('admin.stok_keluar.index')->with('success', 'Stok keluar berhasil dicatat!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error processing stok keluar: " . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat mencatat stok keluar. Silakan coba lagi.']);
        }
    }
  
    /**
     * Mengambil ukuran dan harga jual kecap berdasarkan ID kecap_masuk.
     * Digunakan untuk AJAX request di form stok keluar.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUkuranDanHargaByKecapId(Request $request)
    {
        $kecapMasukId = $request->input('kecap_masuk_id');
        $kecapMasuk = KecapMasuk::find($kecapMasukId);

        if ($kecapMasuk) {
            $totalStokTersedia = MasterStok::where('kecap_masuk_id', $kecapMasukId)->sum('stok_terakhir');

            return response()->json([
                'ukuran' => $kecapMasuk->ukuran,
                'harga_jual' => $kecapMasuk->harga_jual,
                'stok_tersedia' => $totalStokTersedia
            ]);
        }

        return response()->json(['ukuran' => null, 'harga_jual' => null, 'stok_tersedia' => 0]);
    }
}
