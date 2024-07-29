<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalAkun extends Model
{
    use HasFactory;

    protected $fillable =[
        'tanggal',
        'transaksi',
        'keterangan',
        'bukti',
        'akunD',
        'rpD',
        'histori_saldo_debit',
        'JurnalId'
    ];
    public $timestamps = false;
}
