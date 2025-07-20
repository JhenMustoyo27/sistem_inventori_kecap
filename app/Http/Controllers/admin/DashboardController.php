<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterStok;
use App\Models\StokKeluar;
use App\Models\KecapMasuk;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard admin dengan ringkasan data stok.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // 1. Jumlah Kecap Masuk (berdasarkan total master stok awal dari semua batch)
        $totalKecapMasuk = MasterStok::sum('master_stok');

        // 2. Jumlah Kecap Keluar (berdasarkan total stok yang dikeluarkan)
        $totalKecapKeluar = StokKeluar::sum('jumlah_keluar');

        // 3. Jumlah Stok Sisa Saat Ini (total stok_terakhir dari MasterStok)
        $totalStokSisa = MasterStok::sum('stok_terakhir');

        // 4. Kecap Expired (sudah melewati tanggal expired, hitung berdasarkan stok_terakhir)
        $today = Carbon::today();
        $totalKecapExpired = 0;
        $kecapExpiredItems = KecapMasuk::where('tanggal_expired', '<', $today)
                                       ->get();

        foreach ($kecapExpiredItems as $kecap) {
            $masterStok = MasterStok::where('kecap_masuk_id', $kecap->id)->first();
            if ($masterStok && $masterStok->stok_terakhir > 0) {
                $totalKecapExpired += $masterStok->stok_terakhir;
            }
        }

        // 5. Kecap Yang Akan Expired (kurang dari 30 hari dari sekarang, hitung berdasarkan stok_terakhir)
        $soonToExpireThresholdDays = 30;
        $totalKecapAkanExpired = 0;
        $kecapAkanExpiredItems = KecapMasuk::where('tanggal_expired', '>=', $today)
                                           ->where('tanggal_expired', '<=', $today->copy()->addDays($soonToExpireThresholdDays))
                                           ->get();

        foreach ($kecapAkanExpiredItems as $kecap) {
            $masterStok = MasterStok::where('kecap_masuk_id', $kecap->id)->first();
            if ($masterStok && $masterStok->stok_terakhir > 0) {
                $totalKecapAkanExpired += $masterStok->stok_terakhir;
            }
        }

        return view('admin.dashboard', compact(
            'totalKecapMasuk',
            'totalKecapKeluar',
            'totalStokSisa',
            'totalKecapExpired',
            'totalKecapAkanExpired'
        ));
    }
}

