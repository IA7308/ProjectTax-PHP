<?php

namespace App\Http\Controllers;

use App\Models\COA;
use App\Models\labarugi;
use App\Models\penyesuaian;
use Illuminate\Http\Request;

class labarugiController extends Controller
{
    public function index(){
        session(['paginate' => false]);
        $datacoa = COA::orderBy('kode', 'asc')->get();
        $dataPenyesuaian = penyesuaian::all();
        $data = [];
        $totalsaldo = 0;
        $datajumlah = [];
        foreach($datacoa as $dc){
            if($dc->jenis_akun == 'A NOMINAL'){
                if($dc->keterangan == 'Header'){
                    $calc = 0;
                    $sumDebit = 0;
                    $sumKredit = 0;
                    $labarugi = new labarugi;
                    $labarugi->golongan = $dc->kode;
                    $labarugi->nama_akun = $dc->Nama_akun;
                    $labarugi->keterangan = $dc->keterangan;
                    $labarugi->Saldo_awal = $dc->Saldo_awal;
                    $labarugi->backgroundClass = 'table-info';
                    foreach($dataPenyesuaian as $dj){
                        if($dj->akunD == $labarugi->nama_akun){
                            $sumDebit+=$dj->rpD;
                        }elseif($dj->akunK == $labarugi->nama_akun){
                            $sumKredit+=$dj->rpK;
                        }
                    }
                    $calc = $sumDebit - $sumKredit;
                    if($dataPenyesuaian->has($labarugi->nama_akun)){
                        $labarugi->saldo_periode = $calc;
                    }else{
                        $labarugi->saldo_periode = $calc;
                    }
                    $labarugi->saldo_akhir = $dc->Saldo_awal + $calc;
                    $datajumlah[] = $labarugi;
                    $data[] = $labarugi;    
                }elseif($dc->keterangan == 'Jumlah'){
                    $calc = 0;
                    $sumDebit = 0;
                    $sumKredit = 0;
                    $labarugi = new labarugi;
                    $labarugi->golongan = $dc->kode;
                    $labarugi->nama_akun = $dc->Nama_akun;
                    $labarugi->keterangan = $dc->keterangan;
                    $labarugi->Saldo_awal = $dc->Saldo_awal;
                    $labarugi->backgroundClass = 'table-secondary';
                    foreach($dataPenyesuaian as $dj){
                        if($dj->akunD == $labarugi->nama_akun){
                            $sumDebit+=$dj->rpD;
                        }elseif($dj->akunK == $labarugi->nama_akun){
                            $sumKredit+=$dj->rpK;
                        }
                    }
                    $calc = $sumDebit - $sumKredit;
                    if($dataPenyesuaian->has($labarugi->nama_akun)){
                        $labarugi->saldo_periode = $calc;
                    }else{
                        $labarugi->saldo_periode = $calc;
                    }
                    $labarugi->saldo_akhir = $totalsaldo;
                    $totalsaldo = 0;
                    $data[] = $labarugi;
                }elseif($dc->keterangan == 'Total'){
                    $calc = 0;
                    $sumDebit = 0;
                    $sumKredit = 0;
                    $labarugi = new labarugi;
                    $labarugi->golongan = $dc->kode;
                    $labarugi->nama_akun = $dc->Nama_akun;
                    $labarugi->keterangan = $dc->keterangan;
                    $labarugi->Saldo_awal = $dc->Saldo_awal;
                    $labarugi->backgroundClass = 'table-secondary';
                    $labarugi->saldo_akhir = $datajumlah[count($datajumlah)-1]->saldo_akhir - $datajumlah[count($datajumlah) - 2]->saldo_akhir;
                    $data[] = $labarugi;
                }else{
                    $calc = 0;
                    $sumDebit = 0;
                    $sumKredit = 0;
                    $labarugi = new labarugi;
                    $labarugi->golongan = $dc->kode;
                    $labarugi->nama_akun = $dc->Nama_akun;
                    $labarugi->keterangan = $dc->keterangan;
                    $labarugi->Saldo_awal = $dc->Saldo_awal;
                    foreach($dataPenyesuaian as $dj){
                        if($dj->akunD == $labarugi->nama_akun){
                            $sumDebit+=$dj->rpD;
                        }elseif($dj->akunK == $labarugi->nama_akun){
                            $sumKredit+=$dj->rpK;
                        }
                    }
                    $calc = $sumDebit - $sumKredit;
                    if($dataPenyesuaian->has($labarugi->nama_akun)){
                        $labarugi->saldo_periode = $calc;
                    }else{
                        $labarugi->saldo_periode = $calc;
                    }
                    $labarugi->saldo_akhir = $dc->Saldo_awal + $calc;
                    $totalsaldo += $labarugi->saldo_akhir;
                    $data[] = $labarugi;
                }
            }
        }
        return view('labarugi', ['data' => $data]);
    }
}
