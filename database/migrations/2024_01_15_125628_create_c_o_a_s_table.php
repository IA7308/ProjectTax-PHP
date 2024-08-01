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
            $table->string('jenis_akun');
            $table->string('kelompok_akun');
            $table->string('keterangan');
            $table->decimal('kode', 20, 3);
            $table->string('Nama_akun');
            $table->bigInteger('Saldo_awal');
            $table->bigInteger('jumlah_saldo');
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
