<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class resumeBarang extends Model
{
    use HasFactory;
    protected $fillable =[
        'kode_barang',
        'nama_barang',
        'stock_awal',
        'harga_masuk',
        'harga_keluar'
    ];
}
