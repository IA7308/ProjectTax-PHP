<?php

namespace App\Imports;

use App\Models\COA;
use Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CoasImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $user = Auth::user();
        return new COA([
            'user_id'       => $user->id,
            'jenis_akun'    => $row['jenis_akun'],
            'kelompok_akun' => $row['kelompok_akun'],
            'keterangan'    => $row['keterangan'],
            'kode'          => $row['kode'],
            'Nama_akun'     => $row['nama_akun'],
            'Saldo_awal'    => $row['saldo_awal'],
            'jumlah_saldo'  => $row['saldo_awal'],
        ]);
    }
}
