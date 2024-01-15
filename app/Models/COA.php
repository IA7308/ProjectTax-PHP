<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class COA extends Model
{
    use HasFactory;
    protected $fillable = [
        'jenis_akun',
        'kelompok_akun',
        'keterangan',
        'kode',
        'Nama_akun',
        'Saldo_awal'
    ];
}
