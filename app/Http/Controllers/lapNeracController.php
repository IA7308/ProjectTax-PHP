<?php

namespace App\Http\Controllers;

use App\Models\COA;
use App\Models\Jurnal;
use App\Models\neracaLaporan;
use App\Models\penyesuaian;
use Illuminate\Http\Request;

class lapNeracController extends Controller
{
    public function index(){
        session(['paginate' => false]);
        $datacoa = COA::all();
        $dataPenyesuaian = penyesuaian::all();
        $data = [];
        
        foreach($datacoa as $dc){
            if($dc->jenis_akun == 'A REAL'){
                if($dc->keterangan == 'Header'){
                    $calc = 0;
                    $sumDebit = 0;
                    $sumKredit = 0;
                    $neracaLap = new neracaLaporan;
                    $neracaLap->golongan = 'NERACA';
                    $neracaLap->nama_akun = $dc->Nama_akun;
                    $neracaLap->keterangan = $dc->keterangan;
                    $neracaLap->Saldo_awal = $dc->Saldo_awal;
                    $neracaLap->backgroundClass = 'table-info';
                    foreach($dataPenyesuaian as $dj){
                        if($dj->akunD == $neracaLap->nama_akun){
                            $sumDebit+=$dj->rpD;
                        }elseif($dj->akunK == $neracaLap->nama_akun){
                            $sumKredit+=$dj->rpK;
                        }
                    }
                    $calc = $sumDebit - $sumKredit;
                    if($dataPenyesuaian->has($neracaLap->nama_akun)){
                        $neracaLap->saldo_periode = $calc;
                    }else{
                        $neracaLap->saldo_periode = $calc;
                    }
                    $neracaLap->saldo_akhir = $dc->Saldo_awal + $calc;
                    $data[] = $neracaLap;    
                }elseif($dc->keterangan == 'Jumlah'){
                    $calc = 0;
                    $sumDebit = 0;
                    $sumKredit = 0;
                    $neracaLap = new neracaLaporan;
                    $neracaLap->golongan = 'NERACA';
                    $neracaLap->nama_akun = $dc->Nama_akun;
                    $neracaLap->keterangan = $dc->keterangan;
                    $neracaLap->Saldo_awal = $dc->Saldo_awal;
                    $neracaLap->backgroundClass = 'table-secondary';
                    foreach($dataPenyesuaian as $dj){
                        if($dj->akunD == $neracaLap->nama_akun){
                            $sumDebit+=$dj->rpD;
                        }elseif($dj->akunK == $neracaLap->nama_akun){
                            $sumKredit+=$dj->rpK;
                        }
                    }
                    $calc = $sumDebit - $sumKredit;
                    if($dataPenyesuaian->has($neracaLap->nama_akun)){
                        $neracaLap->saldo_periode = $calc;
                    }else{
                        $neracaLap->saldo_periode = $calc;
                    }
                    $neracaLap->saldo_akhir = $dc->Saldo_awal + $calc;
                    $data[] = $neracaLap;
                }else{
                    $calc = 0;
                    $sumDebit = 0;
                    $sumKredit = 0;
                    $neracaLap = new neracaLaporan;
                    $neracaLap->golongan = 'NERACA';
                    $neracaLap->nama_akun = $dc->Nama_akun;
                    $neracaLap->keterangan = $dc->keterangan;
                    $neracaLap->Saldo_awal = $dc->Saldo_awal;
                    foreach($dataPenyesuaian as $dj){
                        if($dj->akunD == $neracaLap->nama_akun){
                            $sumDebit+=$dj->rpD;
                        }elseif($dj->akunK == $neracaLap->nama_akun){
                            $sumKredit+=$dj->rpK;
                        }
                    }
                    $calc = $sumDebit - $sumKredit;
                    if($dataPenyesuaian->has($neracaLap->nama_akun)){
                        $neracaLap->saldo_periode = $calc;
                    }else{
                        $neracaLap->saldo_periode = $calc;
                    }
                    $neracaLap->saldo_akhir = $dc->Saldo_awal + $calc;
                    $data[] = $neracaLap;
                }
            }
        }

        return view('neraca', ['data' => $data]);
    }
}
