<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokHistory extends Model
{
    use HasFactory;

    // Menentukan nama tabel yang terkait dengan model ini
    protected $table = 'stok_histories';

    // Menentukan kolom-kolom yang dapat diisi secara massal (mass assignable)
    protected $fillable = [
        'kecap_masuk_id',
        'master_stok_id',
        'event_type',
        'kode_kecap',
        'ukuran',
        'quantity',
        'current_stock_after_event',
        'description',
    ];

    // Mendefinisikan relasi ke model KecapMasuk
    public function kecapMasuk()
    {
        return $this->belongsTo(KecapMasuk::class);
    }

    // Mendefinisikan relasi ke model MasterStok
    public function masterStok()
    {
        return $this->belongsTo(MasterStok::class);
    }
}

