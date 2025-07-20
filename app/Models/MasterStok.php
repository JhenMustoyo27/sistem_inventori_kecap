<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterStok extends Model
{
    use HasFactory;

    protected $table = 'master_stok';

    protected $fillable = [
        'kecap_masuk_id',
        'master_stok',
        'stok_terakhir',
        'tanggal_input_stok',
    ];

    protected $casts = [
        'tanggal_input_stok' => 'date',
    ];

    /**
     * Relasi ke model KecapMasuk.
     */
    public function kecapMasuk()
    {
        return $this->belongsTo(KecapMasuk::class, 'kecap_masuk_id');
    }

    /**
     * Relasi ke model StokKeluar.
     * Menunjukkan stok keluar yang berasal dari master stok ini.
     */
    public function stokKeluar()
    {
        return $this->hasMany(StokKeluar::class, 'master_stok_id');
    }
}

