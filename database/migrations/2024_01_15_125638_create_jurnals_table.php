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
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('logins')->onDelete('cascade');
            // $table->string('transaksi');
            // $table->string('keterangan');
            // $table->string('bukti');
            $table->bigInteger('jumlah');
            $table->json('debit')->default(DB::raw('(JSON_ARRAY())'));
            $table->json('kredit')->default(DB::raw('(JSON_ARRAY())'));
            $table->integer('JurnalId');
            // $table->string('akunD');
            // $table->bigInteger('rpD');
            // $table->string('akunK');
            // $table->bigInteger('rpK');
            // $table->date('tanggal');
            $table->bigInteger('histori_saldo_debit');
            $table->bigInteger('histori_saldo_kredit');
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
