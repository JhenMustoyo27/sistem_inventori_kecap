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
        // Membuat tabel 'kecap_masuk' untuk menyimpan data stok kecap yang masuk
        Schema::create('kecap_masuk', function (Blueprint $table) {
            $table->id(); // Kolom ID otomatis (primary key)
            $table->string('kode_kecap')->unique(); // Kode unik untuk setiap jenis kecap
            $table->enum('ukuran', ['besar', 'sedang', 'kecil']); // Ukuran kecap (enum: besar, sedang, kecil)
            $table->date('tanggal_masuk'); // Tanggal kecap masuk
            $table->date('tanggal_expired'); // Tanggal kedaluwarsa (akan dihitung otomatis)
            $table->string('kualitas'); // Kualitas kecap (misal: "Baik", "Sedang")
            $table->decimal('harga_jual', 10, 2); // Harga jual kecap (decimal untuk presisi)
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menghapus tabel jika migrasi di-rollback
        Schema::dropIfExists('kecap_masuk');
    }
};
