<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class neracaLaporan extends Model
{
    use HasFactory;
    protected $fillable = [
        'golongan',
        'keterangan',
        'nama_akun',
        'Saldo_awal',
        'saldo_periode',
        'saldo_akhir'
    ];
}
