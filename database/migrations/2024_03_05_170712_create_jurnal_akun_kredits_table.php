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
        Schema::create('jurnal_akun_kredits', function (Blueprint $table) {
            $table->id();
            $table->string('bukti');
            $table->string('akunK');
            $table->bigInteger('rpK');
            $table->date('tanggal');
            $table->bigInteger('histori_saldo_kredit');
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_akun_kredits');
    }
};
