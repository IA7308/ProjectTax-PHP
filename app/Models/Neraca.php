<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Neraca extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama_akun',
        'rpD',
        'rpK',
        'rpPD',
        'rpPK',
        'SaldoPenyesuaianP',
        'SaldoPenyesuaianN'
    ];
}
