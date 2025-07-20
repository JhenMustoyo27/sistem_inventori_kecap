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
        // Membuat tabel 'stok_histories' untuk mencatat setiap perubahan stok
        Schema::create('stok_histories', function (Blueprint $table) {
            $table->id(); // Kolom ID otomatis (primary key)
            $table->foreignId('kecap_masuk_id')->nullable()->constrained('kecap_masuk')->onDelete('set null'); // ID Kecap Masuk (bisa null jika data kecap masuk dihapus)
            $table->foreignId('master_stok_id')->nullable()->constrained('master_stok')->onDelete('set null'); // ID Master Stok (bisa null jika data master stok dihapus)
            $table->string('event_type'); // Tipe event (e.g., 'stok_masuk_created', 'master_stok_created', 'master_stok_updated', 'stok_keluar')
            $table->string('kode_kecap'); // Kode Kecap saat event terjadi
            $table->string('ukuran'); // Ukuran Kecap saat event terjadi
            $table->integer('quantity'); // Kuantitas perubahan (positif untuk masuk, negatif untuk keluar)
            $table->integer('current_stock_after_event')->nullable(); // Stok saat ini setelah event (opsional)
            $table->text('description')->nullable(); // Deskripsi tambahan tentang event
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menghapus tabel jika migrasi di-rollback
        Schema::dropIfExists('stok_histories');
    }
};

