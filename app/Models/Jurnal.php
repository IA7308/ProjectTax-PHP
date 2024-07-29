<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    use HasFactory;
    protected $fillable =[
        // 'tanggal',
        // 'transaksi',
        // 'keterangan',
        // 'bukti',
        'jumlah',
        'histori_saldo_debit',
        'histori_saldo_kredit',
        'JurnalId'
    ];
    public $timestamps = false;
    protected $casts = [
        'debit' => 'json', // Tentukan bahwa debit adalah tipe data JSON
        'kredit' => 'json' // Tentukan bahwa kredit adalah tipe data JSON
    ];
}
