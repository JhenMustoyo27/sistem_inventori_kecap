<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokKeluar extends Model
{
    use HasFactory;

    protected $table = 'stok_keluar'; // Nama tabel di database

    protected $fillable = [
        'kecap_masuk_id', // ID dari stok masuk yang dikeluarkan (untuk FIFO)
        'master_stok_id', // ID dari master stok yang terpengaruh (opsional, tapi baik untuk jejak)
        'jumlah_keluar',
        'tanggal_keluar',
        'harga_jual',
        'keterangan', // Tambahkan kolom keterangan jika diperlukan
    ];

    protected $casts = [
        'tanggal_keluar' => 'date',
    ];

    /**
     * Relasi ke model KecapMasuk.
     * Menunjukkan dari batch kecap masuk mana stok ini dikeluarkan.
     */
    public function kecapMasuk()
    {
        return $this->belongsTo(KecapMasuk::class);
    }

    /**
     * Relasi ke model MasterStok.
     * Menunjukkan master stok mana yang terpengaruh oleh transaksi ini.
     */
    public function masterStok()
    {
        return $this->belongsTo(MasterStok::class);
    }
}
