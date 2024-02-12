<?php

namespace App\Http\Controllers;

use App\Models\bukubesar;
use App\Models\COA;
use App\Models\Jurnal;
use App\Models\konsep;
use Illuminate\Http\Request;

class konsepController extends Controller
{
    public function index(){
        session(['paginate' => false]);
        $datacoa = COA::all();
        $datajurnal = Jurnal::all();
        $data = [];
        
        foreach($datacoa as $dc){
            if($dc->keterangan == 'Header'){
                $calc = 0;
                $sumDebit = 0;
                $sumKredit = 0;
                $konsep = new konsep;
                $konsep->kode = $dc->kode;
                $konsep->nama_akun = $dc->Nama_akun;
                $konsep->keterangan = $dc->keterangan;
                $konsep->Saldo_awal = $dc->Saldo_awal;
                $konsep->backgroundClass = 'table-info';
                foreach($datajurnal as $dj){
                    if($dj->akunD == $konsep->nama_akun){
                        $sumDebit+=$dj->rpD;
                    }elseif($dj->akunK == $konsep->nama_akun){
                        $sumKredit+=$dj->rpK;
                    }
                }
                $calc = $sumDebit - $sumKredit;
                if($datajurnal->has($konsep->nama_akun)){
                    $konsep->saldo_periode = $calc;
                }else{
                    $konsep->saldo_periode = $calc;
                }
                $konsep->saldo_akhir = $dc->Saldo_awal + $calc;
                $konsep->backgroundCell = 'table-secondary';
                $data[] = $konsep;    
            }elseif($dc->keterangan == 'Jumlah'){
                $calc = 0;
                $sumDebit = 0;
                $sumKredit = 0;
                $konsep = new konsep;
                $konsep->kode = $dc->kode;
                $konsep->nama_akun = $dc->Nama_akun;
                $konsep->keterangan = $dc->keterangan;
                $konsep->Saldo_awal = $dc->Saldo_awal;
                $konsep->backgroundClass = 'table-secondary';
                foreach($datajurnal as $dj){
                    if($dj->akunD == $konsep->nama_akun){
                        $sumDebit+=$dj->rpD;
                    }elseif($dj->akunK == $konsep->nama_akun){
                        $sumKredit+=$dj->rpK;
                    }
                }
                $calc = $sumDebit - $sumKredit;
                if($datajurnal->has($konsep->nama_akun)){
                    $konsep->saldo_periode = $calc;
                }else{
                    $konsep->saldo_periode = $calc;
                }
                $konsep->saldo_akhir = $dc->Saldo_awal + $calc;
                $konsep->backgroundCell = 'table-secondary';
                $data[] = $konsep;
            }else{
                $calc = 0;
                $sumDebit = 0;
                $sumKredit = 0;
                $konsep = new konsep;
                $konsep->kode = $dc->kode;
                $konsep->nama_akun = $dc->Nama_akun;
                $konsep->keterangan = $dc->keterangan;
                $konsep->Saldo_awal = $dc->Saldo_awal;
                foreach($datajurnal as $dj){
                    if($dj->akunD == $konsep->nama_akun){
                        $sumDebit+=$dj->rpD;
                    }elseif($dj->akunK == $konsep->nama_akun){
                        $sumKredit+=$dj->rpK;
                    }
                }
                $calc = $sumDebit - $sumKredit;
                if($datajurnal->has($konsep->nama_akun)){
                    $konsep->saldo_periode = $calc;
                }else{
                    $konsep->saldo_periode = $calc;
                }
                $konsep->saldo_akhir = $dc->Saldo_awal + $calc;
                $konsep->backgroundCell = 'table-secondary';
                $data[] = $konsep;
            }
            
        }

        return view('konsep', ['data' => $data]);
    }
}
