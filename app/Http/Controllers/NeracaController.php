<?php

namespace App\Http\Controllers;

use App\Models\COA;
use App\Models\Neraca;
use Illuminate\Http\Request;

class NeracaController extends Controller
{
    public function index(){
        // $perPage = strtolower(request('pagination', 'all'));
        session(['paginate' => false]);
        $dataCOA = COA::all();
        $data = [];
        foreach($dataCOA as $dc){
            $neraca = new Neraca;
            if($dc->keterangan == 'Header' || $dc->keterangan == 'Jumlah'){
                $neraca->kode = $dc->kode;
                $neraca->nama_akun = $dc->Nama_akun;
                $neraca->rpD = 0;
                $neraca->rpK = 0;
                $neraca->backgroundClass = 'table-info';
            }elseif($dc->keterangan == 'Akun, Debit'){
                $neraca->kode = $dc->kode;
                $neraca->nama_akun = $dc->Nama_akun;
                $neraca->rpD = $dc->jumlah_saldo;
                $neraca->rpK = 0;
            }else{
                $neraca->kode = $dc->kode;
                $neraca->nama_akun = $dc->Nama_akun;
                $neraca->rpD = 0;
                $neraca->rpK = $dc->jumlah_saldo;
            }
            $data[] = $neraca;
        }
        return view('Neraca_Jalur', ['data' => $data]);
    }
}
