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
        Schema::create('barang_keluars', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('nama_penjual');
            $table->string('kode_barang');
            $table->string('nama_barang');
            $table->bigInteger('unit_keluar');
            $table->bigInteger('harga');
            $table->string('keterangan');
            $table->integer('JurnalId');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_keluars');
    }
};
