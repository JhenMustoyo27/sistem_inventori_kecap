<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        // Menambahkan kolom 'email' ke tabel 'users'
        Schema::table('users', function (Blueprint $table) {
            // Kolom email, string unik, bisa null (jika Anda ingin mengizinkan user tanpa email)
            // Atau tambahkan ->nullable(false)->after('username'); jika email wajib
            $table->string('email')->unique()->nullable()->after('username');
            // Jika Anda ingin email wajib dan tidak bisa null, gunakan:
            // $table->string('email')->unique()->after('username');
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        // Menghapus kolom 'email' jika migrasi di-rollback
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }
};
