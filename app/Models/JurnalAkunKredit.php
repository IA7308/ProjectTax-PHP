<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalAkunKredit extends Model
{
    use HasFactory;

    protected $fillable =[
        'tanggal',
        'bukti',
        'akunK',
        'rpK',
        'histroi_saldo_kredit'
    ];
}
