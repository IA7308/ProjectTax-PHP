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
        Schema::create('jurnals', function (Blueprint $table) {
            $table->id();
            $table->integer('nama_akun');
            $table->string('transaksi');
            $table->string('keterangan');
            $table->string('bukti');
            $table->integer('jumlah');
            $table->string('akunD');
            $table->integer('rpD');
            $table->string('akunK');
            $table->integer('rpK');
            $table->date('tanggal');
            $table->integer('histori_saldo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnals');
    }
};
