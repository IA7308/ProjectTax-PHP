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
        Schema::create('c_o_a_s', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_akun')->nullable();
            $table->string('kelompok_akun')->nullable();
            $table->string('keterangan')->nullable();
            $table->integer('kode')->nullable();
            $table->string('Nama_akun')->nullable();
            $table->integer('Saldo_awal')->nullable();
            $table->integer('jumlah_saldo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('c_o_a_s');
    }
};
