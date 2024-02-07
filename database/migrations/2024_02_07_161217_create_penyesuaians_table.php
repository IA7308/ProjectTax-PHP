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
        Schema::create('penyesuaians', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('transaksi');
            $table->string('bukti');
            $table->bigInteger('jumlah');
            $table->string('akunD');
            $table->bigInteger('rpD');
            $table->string('akunK');
            $table->bigInteger('rpK');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyesuaians');
    }
};
