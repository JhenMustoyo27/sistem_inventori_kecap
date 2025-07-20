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
        // Membuat tabel 'master_stok' untuk menyimpan data master stok kecap
        Schema::create('master_stok', function (Blueprint $table) {
            $table->id(); // Kolom ID otomatis (primary key)
            // Foreign key ke tabel 'kecap_masuk'
            $table->foreignId('kecap_masuk_id')->constrained('kecap_masuk')->onDelete('cascade');
            $table->integer('master_stok'); // Jumlah master stok
            $table->integer('stok_terakhir'); // Stok terakhir (akan diupdate)
            $table->date('tanggal_input_stok'); // Tanggal input master stok
            $table->timestamps(); // Kolom created_at dan updated_at

            // Menambahkan unique constraint untuk memastikan satu kecap_masuk_id hanya memiliki satu master_stok
            // Ini penting jika master stok adalah entri unik per jenis kecap dari stok masuk
            // Jika Anda ingin mengizinkan beberapa entri master stok untuk kecap_masuk_id yang sama (misal, batch berbeda),
            // maka unique constraint ini perlu disesuaikan atau dihapus.
            // Untuk kebutuhan ini, saya asumsikan satu master stok per kecap_masuk_id.
            $table->unique('kecap_masuk_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menghapus tabel jika migrasi di-rollback
        Schema::dropIfExists('master_stok');
    }
};

