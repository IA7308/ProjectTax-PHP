<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    use HasFactory;
    protected $fillable =[
        'nama_akun',
        'tanggal',
        'transaksi',
        'keterangan',
        'bukti',
        'jumlah',
        'akunD',
        'rpD',
        'akunK',
        'rpK',
        'histori_saldo'
    ];
}
