<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_kecap', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kecap')->unique();
            $table->string('nama_kecap');
            $table->enum('ukuran', ['besar', 'sedang', 'kecil']);
            $table->decimal('harga_jual', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_kecap');
    }
};