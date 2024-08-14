<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    use HasFactory;

    protected $fillable =[
        'tanggal',
        'nama_penjual',
        'kode_barang',
        'nama_barang',
        'unit_keluar',
        'harga'
    ];
}
