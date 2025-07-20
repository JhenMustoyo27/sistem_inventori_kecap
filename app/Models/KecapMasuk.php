<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KecapMasuk extends Model
{
    use HasFactory;

    protected $table = 'kecap_masuk';

    protected $fillable = [
        'kode_kecap',
        'ukuran',
        'jumlah_botol',
        'tanggal_masuk',
        'tanggal_expired',
        'kualitas',
        'harga_beli', // Pastikan ini ada di sini dan di migrasi tabel
        'harga_jual', // Pastikan ini ada di sini dan di migrasi tabel
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'tanggal_expired' => 'date',
        'harga_beli' => 'decimal:2', // Contoh casting ke decimal
        'harga_jual' => 'decimal:2', // Contoh casting ke decimal
    ];

    /**
     * Relasi ke model MasterStok.
     */
    public function masterStok()
    {
        return $this->hasOne(MasterStok::class, 'kecap_masuk_id');
    }

    /**
     * Relasi ke model StokKeluar.
     * Menunjukkan stok keluar yang berasal dari batch kecap masuk ini.
     */
    public function stokKeluar()
    {
        return $this->hasMany(StokKeluar::class, 'kecap_masuk_id');
    }
}

