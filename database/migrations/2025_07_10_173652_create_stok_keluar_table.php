<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stok_keluar', function (Blueprint $table) {
            $table->id();
            // Foreign key ke tabel 'kecap_masuk'
            // Menggunakan onDelete('restrict') untuk mencegah penghapusan kecap_masuk jika masih ada stok keluar terkait
            // $table->foreignId('kecap_masuk_id')->constrained('kecap_masuk')->onDelete('restrict'); 
            // Foreign key ke tabel 'master_stok'
            // Menggunakan onDelete('set null') agar master_stok_id bisa null jika master_stok dihapus
            $table->foreignId('kecap_masuk_id')->constrained('kecap_masuk')->onDelete('cascade');
            $table->foreignId('master_stok_id')->nullable()->constrained('master_stok')->onDelete('set null'); 
            $table->integer('jumlah_keluar'); // Jumlah kecap yang keluar
            $table->date('tanggal_keluar'); // Tanggal kecap keluar
            $table->decimal('harga_jual', 10, 2); // Harga jual kecap saat keluar (decimal untuk presisi)
            $table->string('keterangan')->nullable(); // Kolom keterangan opsional
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_keluar');
    }
};
