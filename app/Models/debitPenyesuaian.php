<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class debitPenyesuaian extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'transaksi',
        'bukti',
        'akunD',
        'rpD',
        'penyesuaianid',
    ];
}
