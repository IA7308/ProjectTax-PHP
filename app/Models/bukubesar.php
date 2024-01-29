<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bukubesar extends Model
{
    use HasFactory;

    protected $fillable =[
        'tanggal',
        'transaksi',
        'keterangan',
        'bukti',
        'rpD',
        'rpK',
        'histori_saldo'
    ];
}
