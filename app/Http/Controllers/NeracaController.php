<?php

namespace App\Http\Controllers;

use App\Models\COA;
use App\Models\Neraca;
use App\Models\penyesuaian;
use Illuminate\Http\Request;

class NeracaController extends Controller
{
    public function index(){
        // $perPage = strtolower(request('pagination', 'all'));
        session(['paginate' => false]);
        $dataCOA = COA::all();
        $dataPenyesuaian = penyesuaian::all();
        $data = [];
        foreach($dataCOA as $dc){
            $neraca = new Neraca;
            if($dc->keterangan == 'Header' || $dc->keterangan == 'Jumlah'){
                $neraca->kode = $dc->kode;
                $neraca->nama_akun = $dc->Nama_akun;
                $neraca->rpD = 0;
                $neraca->rpK = 0;
                $neraca->backgroundClass = 'table-info';
                foreach($dataPenyesuaian as $dp){
                    if($neraca->nama_akun == $dp->akunD){
                        $neraca->rpPD = $dp->rpD;
                        $neraca->rpPK = 0;
                        $calc = $neraca->rpD - $neraca->rpPD;
                        if($calc<0){
                            $neraca->SaldoPenyesuaianP = $calc;
                            $neraca->SaldoPenyesuaianN = 0; 
                        }elseif($calc>0){
                            $neraca->SaldoPenyesuaianP = 0;
                            $neraca->SaldoPenyesuaianN = $calc;
                        }else{
                            $neraca->SaldoPenyesuaianP = 0;
                            $neraca->SaldoPenyesuaianN = 0;
                        }
                    }elseif($neraca->nama_akun == $dp->akunK){
                        $neraca->rpPD = 0;
                        $neraca->rpPK = $dp->rpK;
                        $calc = $neraca->rpK + $neraca->rpPK;
                        if($calc<0){
                            $neraca->SaldoPenyesuaianP = $calc;
                            $neraca->SaldoPenyesuaianN = 0; 
                        }elseif($calc>0){
                            $neraca->SaldoPenyesuaianP = 0;
                            $neraca->SaldoPenyesuaianN = $calc;
                        }else{
                            $neraca->SaldoPenyesuaianP = 0;
                            $neraca->SaldoPenyesuaianN = 0;
                        }
                    }else{
                        $neraca->rpPD = 0;
                        $neraca->rpPK = 0;
                        $neraca->SaldoPenyesuaianP = 0;
                        $neraca->SaldoPenyesuaianN = 0;
                    }
                }
            }elseif($dc->keterangan == 'Akun, Debit'){
                $neraca->kode = $dc->kode;
                $neraca->nama_akun = $dc->Nama_akun;
                $neraca->rpD = $dc->jumlah_saldo;
                $neraca->rpK = 0;
                foreach($dataPenyesuaian as $dp){
                    if($neraca->nama_akun == $dp->akunD){
                        $neraca->rpPD = $dp->rpD;
                        $neraca->rpPK = 0;
                        $calc = $neraca->rpD - $neraca->rpPD;
                        if($calc<0){
                            $neraca->SaldoPenyesuaianP = 0;
                            $neraca->SaldoPenyesuaianN = $calc; 
                        }elseif($calc>0){
                            $neraca->SaldoPenyesuaianP = $calc;
                            $neraca->SaldoPenyesuaianN = 0;
                        }else{
                            $neraca->SaldoPenyesuaianP = 0;
                            $neraca->SaldoPenyesuaianN = 0;
                        }
                    }elseif($neraca->nama_akun == $dp->akunK){
                        $neraca->rpPD = 0;
                        $neraca->rpPK = $dp->rpK;
                        $calc = $neraca->rpK + $neraca->rpPK;
                        if($calc<0){
                            $neraca->SaldoPenyesuaianP = 0;
                            $neraca->SaldoPenyesuaianN = $calc; 
                        }elseif($calc>0){
                            $neraca->SaldoPenyesuaianP = $calc;
                            $neraca->SaldoPenyesuaianN = 0;
                        }else{
                            $neraca->SaldoPenyesuaianP = 0;
                            $neraca->SaldoPenyesuaianN = 0;
                        }
                    }else{
                        $neraca->rpPD = 0;
                        $neraca->rpPK = 0;
                        $neraca->SaldoPenyesuaianP = 0;
                        $neraca->SaldoPenyesuaianN = 0;
                    }
                }
            }else{
                $neraca->kode = $dc->kode;
                $neraca->nama_akun = $dc->Nama_akun;
                $neraca->rpD = 0;
                $neraca->rpK = $dc->jumlah_saldo;
                foreach($dataPenyesuaian as $dp){
                    if($neraca->nama_akun == $dp->akunD){
                        $neraca->rpPD = $dp->rpD;
                        $neraca->rpPK = 0;
                        $calc = $neraca->rpD - $neraca->rpPD;
                        if($calc<0){
                            $neraca->SaldoPenyesuaianP = 0;
                            $neraca->SaldoPenyesuaianN = $calc; 
                        }elseif($calc>0){
                            $neraca->SaldoPenyesuaianP = $calc;
                            $neraca->SaldoPenyesuaianN = 0;
                        }else{
                            $neraca->SaldoPenyesuaianP = 0;
                            $neraca->SaldoPenyesuaianN = 0;
                        }
                    }elseif($neraca->nama_akun == $dp->akunK){
                        $neraca->rpPD = 0;
                        $neraca->rpPK = $dp->rpK;
                        $calc = $neraca->rpK + $neraca->rpPK;
                        if($calc<0){
                            $neraca->SaldoPenyesuaianP = 0;
                            $neraca->SaldoPenyesuaianN = $calc; 
                        }elseif($calc>0){
                            $neraca->SaldoPenyesuaianP = $calc;
                            $neraca->SaldoPenyesuaianN = 0;
                        }else{
                            $neraca->SaldoPenyesuaianP = 0;
                            $neraca->SaldoPenyesuaianN = 0;
                        }
                    }else{
                        $neraca->rpPD = 0;
                        $neraca->rpPK = 0;
                        $neraca->SaldoPenyesuaianP = 0;
                        $neraca->SaldoPenyesuaianN = 0;
                    }
                }
            }
            $data[] = $neraca;
        }
        return view('Neraca_Jalur', ['data' => $data]);
    }
}
