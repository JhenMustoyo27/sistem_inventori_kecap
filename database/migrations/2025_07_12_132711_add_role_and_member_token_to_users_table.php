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
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom 'role' jika belum ada
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('admin')->after('password'); // Default 'admin' atau 'pemilik'
            }
            // Tambahkan kolom 'member_token' jika belum ada
            if (!Schema::hasColumn('users', 'member_token')) {
                $table->string('member_token')->nullable()->unique()->after('role');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom 'role' jika ada
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            // Hapus kolom 'member_token' jika ada
            if (Schema::hasColumn('users', 'member_token')) {
                $table->dropColumn('member_token');
            }
        });
    }
};

