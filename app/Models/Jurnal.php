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
        'akunD',
        'rpD',
        'akunK',
        'rpK',
        'histori_saldo_debit',
        'histroi_saldo_kredit'
    ];
}
