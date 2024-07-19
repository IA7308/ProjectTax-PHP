<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kreditPenyesuaian extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'transaksi',
        'bukti',
        'jumlah',
        'akunK',
        'rpK',
        'penyesuaianid',
    ];
}
