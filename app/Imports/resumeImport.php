<?php

namespace App\Imports;

use App\Models\resumeBarang;
use DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class resumeImport implements ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        return new resumeBarang([
            'kode_barang'    => $row['kode_barang'],
            'nama_barang' => $row['nama_barang'],
            'stock_awal'    => $row['stock_awal'],
            'harga_masuk'          => $row['harga_masuk'],
            'harga_keluar'     => $row['harga_keluar'],
            'stock_akhir'    => $row['stock_awal'],
        ]);
    }
}
