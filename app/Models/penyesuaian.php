<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class penyesuaian extends Model
{
    use HasFactory;
    protected $fillable = [
        // 'tanggal',
        // 'transaksi',
        // 'bukti',
        'jumlah',
    ];

    protected $casts = [
        'debit' => 'json', // Tentukan bahwa debit adalah tipe data JSON
        'kredit' => 'json' // Tentukan bahwa kredit adalah tipe data JSON
    ];
}
