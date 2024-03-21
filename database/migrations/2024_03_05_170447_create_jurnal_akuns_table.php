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
        Schema::create('jurnal_akuns', function (Blueprint $table) {
            $table->id();
            $table->string('bukti');
            $table->string('akunD');
            $table->bigInteger('rpD');
            $table->date('tanggal');
            $table->bigInteger('histori_saldo_debit');
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_akuns');
    }
};
