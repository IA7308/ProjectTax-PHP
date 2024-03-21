<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    use HasFactory;
    protected $fillable =[
        'tanggal',
        'transaksi',
        'keterangan',
        'bukti',
        'jumlah',
        'histori_saldo_debit',
        'histroi_saldo_kredit'
    ];
    protected $casts = [
        'debit' => 'json', // Tentukan bahwa debit adalah tipe data JSON
        'kredit' => 'json' // Tentukan bahwa kredit adalah tipe data JSON
    ];
}
