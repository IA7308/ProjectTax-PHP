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
                $neraca->rpPD = 0;
                $neraca->rpPK = 0;
                $neraca->backgroundClass = 'table-info';
                foreach($dataPenyesuaian as $dp){
                    if($neraca->nama_akun == $dp->akunD){
                        if($neraca->rpD == 0){
                            $neraca->rpPD = $dp->rpD;
                        }else{
                            $neraca->rpPD = $neraca->rpPD+$dp->rpD;
                        }
                        
                        $neraca->rpPK = 0;
                        $calc = $neraca->rpD + $neraca->rpPD;
                        if($calc<0){
                            $neraca->SaldoPenyesuaianP = $calc;
                            $neraca->SaldoPenyesuaianN = 0; 
                        }elseif($calc>=0){
                            $neraca->SaldoPenyesuaianP = 0;
                            $neraca->SaldoPenyesuaianN = $calc;
                        }
                    }elseif($neraca->nama_akun == $dp->akunK){
                        $neraca->rpPD = 0;
                        if($neraca->rpPK==0){
                            $neraca->rpPK = $dp->rpK;
                        }else{
                            $neraca->rpPK = $neraca->rpPK-$dp->rpK;
                        }
                        
                        $calc = $neraca->rpK + $neraca->rpPK;
                        if($calc<0){
                            $neraca->SaldoPenyesuaianP = $calc;
                            $neraca->SaldoPenyesuaianN = 0; 
                        }elseif($calc>=0){
                            $neraca->SaldoPenyesuaianP = 0;
                            $neraca->SaldoPenyesuaianN = $calc;
                        }
                    }else{
                        $neraca->rpPD = 0;
                        $neraca->rpPK = 0;
                        $neraca->SaldoPenyesuaianP = 0;
                        $neraca->SaldoPenyesuaianN = 0;
                    }
                }
                if($neraca->SaldoPenyesuaianN != 0 || $neraca->SaldoPenyesuaianP != 0){
                    if($dc->jenis_akun == 'A REAL'){
                        $neraca->LRD = 0;
                        $neraca->LRK = 0;
                        $neraca->nD = $neraca->SaldoPenyesuaianP;
                        $neraca->nK = $neraca->SaldoPenyesuaianN;
                    }elseif($dc->jenis_akun == 'A NOMINAL'){
                        $neraca->LRD = $neraca->SaldoPenyesuaianP;
                        $neraca->LRK = $neraca->SaldoPenyesuaianN;
                        $neraca->nD = 0;
                        $neraca->nK = 0;
                    }
                }else{
                    if($dc->jenis_akun == 'A REAL'){
                        $neraca->LRD = 0;
                        $neraca->LRK = 0;
                        $neraca->nD = $neraca->rpD;
                        $neraca->nK = $neraca->rpK;
                    }elseif($dc->jenis_akun == 'A NOMINAL'){
                        $neraca->LRD = $neraca->rpD;
                        $neraca->LRK = $neraca->rpK;
                        $neraca->nD = 0;
                        $neraca->nK = 0;
                    }
                }
                
            }elseif($dc->keterangan == 'Akun, Debit'){
                $neraca->kode = $dc->kode;
                $neraca->nama_akun = $dc->Nama_akun;
                $neraca->rpD = $dc->jumlah_saldo;
                $neraca->rpK = 0;
                $neraca->rpPD = 0;
                $neraca->rpPK = 0;
                foreach($dataPenyesuaian as $dp){
                    if($neraca->nama_akun == $dp->akunD){
                        if($neraca->rpD == 0){
                            $neraca->rpPD = $dp->rpD;
                        }else{
                            $neraca->rpPD = $neraca->rpPD+$dp->rpD;
                        }
                        $neraca->rpPK = 0;                       
                        $calc = $neraca->rpD + $neraca->rpPD;
                        if($calc<0){
                            $neraca->SaldoPenyesuaianP = 0;
                            $neraca->SaldoPenyesuaianN = $calc; 
                        }elseif($calc>=0){
                            $neraca->SaldoPenyesuaianP = $calc;
                            $neraca->SaldoPenyesuaianN = 0;
                        }
                    }elseif($neraca->nama_akun == $dp->akunK){
                        $neraca->rpPD = 0;
                        if($neraca->rpPK==0){
                            $neraca->rpPK = $dp->rpK;
                        }else{
                            $neraca->rpPK = $neraca->rpPK+$dp->rpK;
                        }
                        $calc = $neraca->rpK - $neraca->rpPK;
                        if($calc<0){
                            $neraca->SaldoPenyesuaianP = 0;
                            $neraca->SaldoPenyesuaianN = $calc; 
                        }elseif($calc>=0){
                            $neraca->SaldoPenyesuaianP = $calc;
                            $neraca->SaldoPenyesuaianN = 0;
                        }
                    }else{
                        $neraca->rpPD = 0;
                        $neraca->rpPK = 0;
                        $neraca->SaldoPenyesuaianP = $neraca->rpD;
                        $neraca->SaldoPenyesuaianN = 0;
                    }
                }
                if($neraca->SaldoPenyesuaianN != 0 || $neraca->SaldoPenyesuaianP != 0){
                    if($dc->jenis_akun == 'A REAL'){
                        $neraca->LRD = 0;
                        $neraca->LRK = 0;
                        $neraca->nD = $neraca->SaldoPenyesuaianP;
                        $neraca->nK = $neraca->SaldoPenyesuaianN;
                    }elseif($dc->jenis_akun == 'A NOMINAL'){
                        $neraca->LRD = $neraca->SaldoPenyesuaianP;
                        $neraca->LRK = $neraca->SaldoPenyesuaianN;
                        $neraca->nD = 0;
                        $neraca->nK = 0;
                    }
                }else{
                    if($dc->jenis_akun == 'A REAL'){
                        $neraca->LRD = 0;
                        $neraca->LRK = 0;
                        $neraca->nD = $neraca->rpD;
                        $neraca->nK = $neraca->rpK;
                    }elseif($dc->jenis_akun == 'A NOMINAL'){
                        $neraca->LRD = $neraca->rpD;
                        $neraca->LRK = $neraca->rpK;
                        $neraca->nD = 0;
                        $neraca->nK = 0;
                    }
                }
            }else{
                $neraca->kode = $dc->kode;
                $neraca->nama_akun = $dc->Nama_akun;
                $neraca->rpD = 0;
                $neraca->rpK = $dc->jumlah_saldo;
                $neraca->rpPD = 0;
                $neraca->rpPK = 0;
                foreach($dataPenyesuaian as $dp){
                    if($neraca->nama_akun == $dp->akunD){
                        if($neraca->rpD == 0){
                            $neraca->rpPD = $dp->rpD;
                        }else{
                            $neraca->rpPD = $neraca->rpPD+$dp->rpD;
                        }
                        $neraca->rpPK = 0;
                        $calc = $neraca->rpD + $neraca->rpPD;
                        if($calc<0){
                            $neraca->SaldoPenyesuaianP = 0;
                            $neraca->SaldoPenyesuaianN = $calc; 
                        }elseif($calc>=0){
                            $neraca->SaldoPenyesuaianP = $calc;
                            $neraca->SaldoPenyesuaianN = 0;
                        }
                    }elseif($neraca->nama_akun == $dp->akunK){
                        $neraca->rpPD = 0;
                        if($neraca->rpPK==0){
                            $neraca->rpPK = $dp->rpK;
                        }else{
                            $neraca->rpPK = $neraca->rpPK+$dp->rpK;
                        }
                        $calc = $neraca->rpK - $neraca->rpPK;
                        if($calc<0){
                            $neraca->SaldoPenyesuaianP = 0;
                            $neraca->SaldoPenyesuaianN = $calc; 
                        }elseif($calc>=0){
                            $neraca->SaldoPenyesuaianP = $calc;
                            $neraca->SaldoPenyesuaianN = 0;
                        }
                    }else{
                        $neraca->rpPD = 0;
                        $neraca->rpPK = 0;
                        $neraca->SaldoPenyesuaianP = 0;
                        $neraca->SaldoPenyesuaianN = $neraca->rpK;
                    }
                }
                if($neraca->SaldoPenyesuaianN != 0 || $neraca->SaldoPenyesuaianP != 0){
                    if($dc->jenis_akun == 'A REAL'){
                        $neraca->LRD = 0;
                        $neraca->LRK = 0;
                        $neraca->nD = $neraca->SaldoPenyesuaianP;
                        $neraca->nK = $neraca->SaldoPenyesuaianN;
                    }elseif($dc->jenis_akun == 'A NOMINAL'){
                        $neraca->LRD = $neraca->SaldoPenyesuaianP;
                        $neraca->LRK = $neraca->SaldoPenyesuaianN;
                        $neraca->nD = 0;
                        $neraca->nK = 0;
                    }
                }else{
                    if($dc->jenis_akun == 'A REAL'){
                        $neraca->LRD = 0;
                        $neraca->LRK = 0;
                        $neraca->nD = $neraca->rpD;
                        $neraca->nK = $neraca->rpK;
                    }elseif($dc->jenis_akun == 'A NOMINAL'){
                        $neraca->LRD = $neraca->rpD;
                        $neraca->LRK = $neraca->rpK;
                        $neraca->nD = 0;
                        $neraca->nK = 0;
                    }
                }
            }
            $data[] = $neraca;
        }
        return view('Neraca_Jalur', ['data' => $data]);
    }

    
}
