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
        Schema::create('resume_barangs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('kode_barang');
            $table->string('nama_barang');
            $table->bigInteger('stock_awal');
            $table->bigInteger('harga_masuk');
            $table->bigInteger('harga_keluar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resume_barangs');
    }
};
