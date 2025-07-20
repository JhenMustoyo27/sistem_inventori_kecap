<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KecapMasuk;
use App\Models\MasterStok;
use App\Models\StokKeluar;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // Pastikan ini di-import

class LaporanController extends Controller
{
    /**
     * Menampilkan laporan akhir.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Ambil semua data KecapMasuk sebagai dasar laporan
        $query = KecapMasuk::query();

        // Fitur pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_kecap', 'like', '%' . $search . '%')
                  ->orWhere('ukuran', 'like', '%' . $search . '%')
                  ->orWhere('kualitas', 'like', '%' . $search . '%');
            });
        }

        // Ambil data KecapMasuk tanpa join awal
        $kecapMasukData = $query->orderBy('tanggal_masuk', 'desc')->get();

        $laporan = [];

        foreach ($kecapMasukData as $kecap) {
            // Jumlah Stok Masuk (berdasarkan Master Stok awal)
            $masterStok = MasterStok::where('kecap_masuk_id', $kecap->id)->first();
            $jumlahStokMasukAwal = $masterStok ? $masterStok->master_stok : 0;
            $jumlahStokTersisa = $masterStok ? $masterStok->stok_terakhir : 0;

            // Jumlah Stok Keluar
            $jumlahStokKeluar = StokKeluar::where('kecap_masuk_id', $kecap->id)->sum('jumlah_keluar');

            // Tanggal Keluar (terakhir)
            $latestStokKeluar = StokKeluar::where('kecap_masuk_id', $kecap->id)
                                          ->orderBy('tanggal_keluar', 'desc')
                                          ->first();
            $tanggalKeluarTerakhir = $latestStokKeluar ? $latestStokKeluar->tanggal_keluar : null;

            // Total Harga Jual (berdasarkan total jumlah stok keluar)
            // Menggunakan harga_jual dari KecapMasuk untuk perhitungan harga jual total stok tersisa
            $totalHargaJualDariKeluar = StokKeluar::where('kecap_masuk_id', $kecap->id)
                                        ->selectRaw('SUM(jumlah_keluar * harga_jual) as total_penjualan')
                                        ->first()
                                        ->total_penjualan ?? 0;

            $laporan[] = [
                'kode_kecap' => $kecap->kode_kecap,
                'ukuran' => $kecap->ukuran,
                'jumlah_stok_masuk' => $jumlahStokMasukAwal, // Menggunakan nama kolom yang sama dengan view
                'jumlah_stok_keluar' => $jumlahStokKeluar,
                'jumlah_stok_tersisa' => $jumlahStokTersisa,
                'tanggal_masuk' => $kecap->tanggal_masuk,
                'tanggal_keluar' => $tanggalKeluarTerakhir ? Carbon::parse($tanggalKeluarTerakhir)->format('d-m-Y') : '-',
                'harga_jual' => $kecap->harga_jual, // Menggunakan harga_jual sebagai harga satuan
                'harga_jual_total' => $kecap->harga_jual * $jumlahStokKeluar, // Harga jual total dari stok tersisa
            ];
        }

        // Karena kita mengolah data di PHP, paginasi akan dilakukan secara manual
        $perPage = 10;
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $currentItems = array_slice($laporan, ($currentPage - 1) * $perPage, $perPage);
        $laporanPaginated = new \Illuminate\Pagination\LengthAwarePaginator($currentItems, count($laporan), $perPage);
        $laporanPaginated->setPath($request->url());

        return view('pemilik.laporan.index', compact('laporanPaginated'));
    }

    /**
     * Mengunduh laporan akhir dalam bentuk PDF.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function downloadPdf(Request $request)
    {
        $query = KecapMasuk::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_kecap', 'like', '%' . $search . '%')
                  ->orWhere('ukuran', 'like', '%' . $search . '%')
                  ->orWhere('kualitas', 'like', '%' . $search . '%');
            });
        }

        $kecapMasukData = $query->orderBy('tanggal_masuk', 'desc')->get();

        $laporanData = [];

        foreach ($kecapMasukData as $kecap) {
            $masterStok = MasterStok::where('kecap_masuk_id', $kecap->id)->first();
            $jumlahStokMasukAwal = $masterStok ? $masterStok->master_stok : 0;
            $jumlahStokTersisa = $masterStok ? $masterStok->stok_terakhir : 0;

            $jumlahStokKeluar = StokKeluar::where('kecap_masuk_id', $kecap->id)->sum('jumlah_keluar');

            $latestStokKeluar = StokKeluar::where('kecap_masuk_id', $kecap->id)
                                          ->orderBy('tanggal_keluar', 'desc')
                                          ->first();
            $tanggalKeluarTerakhir = $latestStokKeluar ? $latestStokKeluar->tanggal_keluar : null;

            $totalHargaJualDariKeluar = StokKeluar::where('kecap_masuk_id', $kecap->id)
                                        ->selectRaw('SUM(jumlah_keluar * harga_jual) as total_penjualan')
                                        ->first()
                                        ->total_penjualan ?? 0;

            $laporanData[] = [
                'kode_kecap' => $kecap->kode_kecap,
                'ukuran' => $kecap->ukuran,
                'jumlah_stok_masuk' => $jumlahStokMasukAwal,
                'jumlah_stok_keluar' => $jumlahStokKeluar,
                'jumlah_stok_tersisa' => $jumlahStokTersisa,
                'tanggal_masuk' => $kecap->tanggal_masuk,
                'tanggal_keluar' => $tanggalKeluarTerakhir ? Carbon::parse($tanggalKeluarTerakhir)->format('d-m-Y') : '-',
                'harga_jual' => $kecap->harga_jual,
                'harga_jual_total' => $kecap->harga_jual * $jumlahStokKeluar,
            ];
        }

        $pdf = Pdf::loadView('pemilik.laporan.pdf', compact('laporanData'));
        return $pdf->download('laporan_akhir_kecap_riboet.pdf');
    }

    /**
     * Menampilkan laporan stok kedaluwarsa.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function expired(Request $request)
    {
        $today = Carbon::today();
        $soonToExpireThresholdDays = 30; // Batas hari untuk 'akan kedaluwarsa'

        $query = KecapMasuk::query();

        // Filter berdasarkan tanggal expired: yang sudah expired atau akan expired dalam X hari
        $query->where(function ($q) use ($today, $soonToExpireThresholdDays) {
            $q->where('tanggal_expired', '<', $today) // Sudah expired
              ->orWhere('tanggal_expired', '<=', $today->copy()->addDays($soonToExpireThresholdDays)); // Akan expired dalam X hari
        });

        // Fitur pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_kecap', 'like', '%' . $search . '%')
                  ->orWhere('ukuran', 'like', '%' . $search . '%');
            });
        }

        $kecapExpiredData = $query->orderBy('tanggal_expired', 'asc')->get();

        $laporanExpired = [];

        foreach ($kecapExpiredData as $kecap) {
            $masterStok = MasterStok::where('kecap_masuk_id', $kecap->id)->first();
            $jumlahStokMasukAwal = $masterStok ? $masterStok->master_stok : 0;
            $jumlahStokTersisa = $masterStok ? $masterStok->stok_terakhir : 0;

            $jumlahStokKeluar = StokKeluar::where('kecap_masuk_id', $kecap->id)->sum('jumlah_keluar');

            $keteranganAkanKedaluwarsa = null;
            $keteranganSudahKedaluwarsa = null;

            if ($kecap->tanggal_expired->isPast() && $kecap->tanggal_expired->lt($today)) {
                $keteranganSudahKedaluwarsa = 'Sudah kedaluwarsa sejak ' . $kecap->tanggal_expired->diffForHumans($today);
            } elseif ($kecap->tanggal_expired->isFuture() && $kecap->tanggal_expired->diffInDays($today) <= $soonToExpireThresholdDays) {
                $keteranganAkanKedaluwarsa = 'Akan kedaluwarsa dalam ' . $kecap->tanggal_expired->diffInDays($today) . ' hari';
            }

            $laporanExpired[] = [
                'kode_kecap' => $kecap->kode_kecap,
                'ukuran' => $kecap->ukuran,
                'jumlah_stok_masuk_awal' => $jumlahStokMasukAwal,
                'jumlah_stok_keluar' => $jumlahStokKeluar,
                'jumlah_stok_tersisa' => $jumlahStokTersisa,
                'tanggal_masuk' => $kecap->tanggal_masuk,
                'tanggal_expired' => $kecap->tanggal_expired,
                'keterangan_akan_kedaluwarsa' => $keteranganAkanKedaluwarsa,
                'keterangan_sudah_kedaluwarsa' => $keteranganSudahKedaluwarsa,
            ];
        }

        $perPage = 10;
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $currentItems = array_slice($laporanExpired, ($currentPage - 1) * $perPage, $perPage);
        $laporanExpiredPaginated = new \Illuminate\Pagination\LengthAwarePaginator($currentItems, count($laporanExpired), $perPage);
        $laporanExpiredPaginated->setPath($request->url());

        return view('pemilik.laporan.expired', compact('laporanExpiredPaginated'));
    }

    /**
     * Mengunduh laporan stok kedaluwarsa dalam bentuk PDF.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function downloadExpiredPdf(Request $request)
    {
        $today = Carbon::today();
        $soonToExpireThresholdDays = 30;

        $query = KecapMasuk::query();

        $query->where(function ($q) use ($today, $soonToExpireThresholdDays) {
            $q->where('tanggal_expired', '<', $today)
              ->orWhere('tanggal_expired', '<=', $today->copy()->addDays($soonToExpireThresholdDays));
        });

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_kecap', 'like', '%' . $search . '%')
                  ->orWhere('ukuran', 'like', '%' . $search . '%');
            });
        }

        $kecapExpiredData = $query->orderBy('tanggal_expired', 'asc')->get();

        $laporanExpired = [];

        foreach ($kecapExpiredData as $kecap) {
            $masterStok = MasterStok::where('kecap_masuk_id', $kecap->id)->first();
            $jumlahStokMasukAwal = $masterStok ? $masterStok->master_stok : 0;
            $jumlahStokTersisa = $masterStok ? $masterStok->stok_terakhir : 0;

            $jumlahStokKeluar = StokKeluar::where('kecap_masuk_id', $kecap->id)->sum('jumlah_keluar');

            $keteranganAkanKedaluwarsa = null;
            $keteranganSudahKedaluwarsa = null;

            if ($kecap->tanggal_expired->isPast() && $kecap->tanggal_expired->lt($today)) {
                $keteranganSudahKedaluwarsa = 'Sudah kedaluwarsa sejak ' . $kecap->tanggal_expired->diffForHumans($today);
            } elseif ($kecap->tanggal_expired->isFuture() && $kecap->tanggal_expired->diffInDays($today) <= $soonToExpireThresholdDays) {
                $keteranganAkanKedaluwarsa = 'Akan kedaluwarsa dalam ' . $kecap->tanggal_expired->diffInDays($today) . ' hari';
            }

            $laporanExpired[] = [
                'kode_kecap' => $kecap->kode_kecap,
                'ukuran' => $kecap->ukuran,
                'jumlah_stok_masuk_awal' => $jumlahStokMasukAwal,
                'jumlah_stok_keluar' => $jumlahStokKeluar,
                'jumlah_stok_tersisa' => $jumlahStokTersisa,
                'tanggal_masuk' => $kecap->tanggal_masuk,
                'tanggal_expired' => $kecap->tanggal_expired,
                'keterangan_akan_kedaluwarsa' => $keteranganAkanKedaluwarsa,
                'keterangan_sudah_kedaluwarsa' => $keteranganSudahKedaluwarsa,
            ];
        }

        $pdf = Pdf::loadView('pemilik.laporan.expired_pdf', compact('laporanExpired'));
        return $pdf->download('laporan_stok_expired_' . date('Ymd_His') . '.pdf');
    }
}
