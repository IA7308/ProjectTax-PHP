<?php

namespace App\Http\Controllers;

use App\Models\COA;
use App\Models\Neraca;
use App\Models\penyesuaian;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class NeracaController extends Controller
{
    public function index(){
        // $perPage = strtolower(request('pagination', 'all'));
        session(['paginate' => false]);
        $dataCOA = COA::all();
        $dataPenyesuaian = penyesuaian::all();
        $dataPenyesuaianDebit = penyesuaian::all()->keyBy('akunD');
        $dataPenyesuaianKredit = penyesuaian::all()->keyBy('akunK');
        $data = [];
        foreach($dataCOA as $dc){
            $neraca = new Neraca;
            $totalsaldoPenyesuaian = 0;
            if($dc->keterangan == 'Header' || $dc->keterangan == 'Jumlah'){
                $neraca->kode = $dc->kode;
                $neraca->nama_akun = $dc->Nama_akun;
                $neraca->rpD = 0;
                $neraca->rpK = 0;
                $neraca->rpPD = 0;
                $neraca->rpPK = 0;
                $neraca->backgroundClass = 'table-info';
                if($dataPenyesuaianDebit->has($neraca->nama_akun)){
                    $penyesuaian = $dataPenyesuaianDebit[$neraca->nama_akun];
                    if($neraca->rpPD == 0){
                        $neraca->rpPD = $penyesuaian->rpD;
                    }else{
                        $neraca->rpPD = $neraca->rpPD+$penyesuaian->rpD;
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
                }else{
                    $neraca->rpPD = 0;
                    $neraca->rpPK = 0;
                    $neraca->SaldoPenyesuaianP = 0;
                    $neraca->SaldoPenyesuaianN = 0;
                }
                if($dataPenyesuaianKredit->has($neraca->nama_akun)){
                    $penyesuaianK = $dataPenyesuaianKredit[$neraca->nama_akun];
                    if($neraca->rpPK==0){
                        $neraca->rpPK = $penyesuaianK->rpK;
                    }else{
                        $neraca->rpPK = $neraca->rpPK-$penyesuaianK->rpK;
                    }
                    
                    $calc = $neraca->rpK + $neraca->rpPK;
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
                    $neraca->SaldoPenyesuaianN = 0;
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
                    if($dp->akunD == $neraca->nama_akun){
                        $totalsaldoPenyesuaian+=$dp->rpD;
                    }elseif($dp->akunK == $neraca->nama_akun){
                        $totalsaldoPenyesuaian-=$dp->rpK;
                    }
                }
                if($dataPenyesuaianDebit->has($neraca->nama_akun)){
                    $penyesuaian = $dataPenyesuaianDebit[$neraca->nama_akun];
                    if($neraca->rpPD == 0){
                        $neraca->rpPD = $totalsaldoPenyesuaian;
                    }else{
                        $neraca->rpPD = $totalsaldoPenyesuaian;
                    } 
                }
                if($dataPenyesuaianKredit->has($neraca->nama_akun)){
                    $penyesuaianK = $dataPenyesuaianKredit[$neraca->nama_akun];
                    if($neraca->rpPK==0){
                        $neraca->rpPK = $totalsaldoPenyesuaian;
                    }else{
                        $neraca->rpPK = $totalsaldoPenyesuaian;
                    }    
                }
                $calc = $neraca->rpD + $neraca->rpPD - $neraca->rpPK;
                    if($calc<0){
                        $neraca->SaldoPenyesuaianP = 0;
                        $neraca->SaldoPenyesuaianN = $calc; 
                    }elseif($calc>=0){
                        $neraca->SaldoPenyesuaianP = $calc;
                        $neraca->SaldoPenyesuaianN = 0;
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
                    if($dp->akunD == $neraca->nama_akun){
                        $totalsaldoPenyesuaian+=$dp->rpD;
                    }elseif($dp->akunK == $neraca->nama_akun){
                        $totalsaldoPenyesuaian-=$dp->rpK;
                    }
                }
                if($dataPenyesuaianDebit->has($neraca->nama_akun)){
                    $penyesuaian = $dataPenyesuaianDebit[$neraca->nama_akun];
                    if($neraca->rpPD == 0){
                        $neraca->rpPD = $totalsaldoPenyesuaian;
                    }else{
                        $neraca->rpPD = $totalsaldoPenyesuaian;
                    } 
                }
                if($dataPenyesuaianKredit->has($neraca->nama_akun)){
                    $penyesuaianK = $dataPenyesuaianKredit[$neraca->nama_akun];
                    if($neraca->rpPK==0){
                        $neraca->rpPK = $totalsaldoPenyesuaian;
                    }else{
                        $neraca->rpPK = $totalsaldoPenyesuaian;
                    }    
                }
                $calc = $neraca->rpK + $neraca->rpPK - $neraca->rpPD;
                    if($calc<0){
                        $neraca->SaldoPenyesuaianP = 0;
                        $neraca->SaldoPenyesuaianN = $calc; 
                    }elseif($calc>=0){
                        $neraca->SaldoPenyesuaianP = $calc;
                        $neraca->SaldoPenyesuaianN = 0;
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
        $collection = new Collection($data);

        // Melakukan perhitungan total dari masing-masing kolom
        $totalRpD = $collection->sum('rpD');
        $totalRpK = $collection->sum('rpK');
        $totalRpPD = $collection->sum('rpPD');
        $totalRpPK = $collection->sum('rpPK');
        $totalSaldoPenyesuaianP = $collection->sum('SaldoPenyesuaianP');
        $totalSaldoPenyesuaianN = $collection->sum('SaldoPenyesuaianN');
        $totalLRD = $collection->sum('LRD');
        $totalLRK = $collection->sum('LRK');
        $totalND = $collection->sum('nD');
        $totalNK = $collection->sum('nK');

        $totalLU = $totalLRD + $totalLRK;
        if($totalLU >= 0){
            $LULBp = 0;
            $LULBn = $totalLU;
        }else{
            $LULBp = $totalLU;
            $LULBn = 0;
        }
        $totalLUN = $totalND + $totalNK;
        if($totalLUN >= 0){
            $LUNp = $totalLUN;
            $LUNn = 0;
        }else{
            $LUNp = $totalLUN;
            $LUNn = 0;
        }
        $balanceLBp = $totalLRD - $LULBp;
        $balanceLBn = $totalLRK + $LULBn;
        $balanceNp = $totalND - $LUNp;
        $balanceNn = $totalNK + $LUNn;

        $kesusaianLB = $balanceLBp - $balanceLBn; 
        $kesuaianLK = $balanceNp - $balanceNn;
        $kesuaianSaldo = $totalRpD + $totalRpK;
        if($kesuaianSaldo > -1 && $kesuaianSaldo < 1){
            session(['kesesuaian' => 'NERACA SALDO SESUAI']);
        }else{
            session(['kesesuaian' => 'NERACA SALDO TIDAK SESUAI']);
        }
        return view('Neraca_Jalur', ['data' => $data,
                                        'totalRpD' => $totalRpD,
                                        'totalRpK' => $totalRpK,
                                        'totalRpPD' => $totalRpPD,
                                        'totalRpPK' => $totalRpPK,
                                        'totalSaldoPenyesuaianP' => $totalSaldoPenyesuaianP,
                                        'totalSaldoPenyesuaianN' => $totalSaldoPenyesuaianN,
                                        'totalLRD' => $totalLRD,
                                        'totalLRK' => $totalLRK,
                                        'totalND' => $totalND,
                                        'totalNK' => $totalNK,
                                        'LULBp' => $LULBp,
                                        'LULBn' => $LULBn,
                                        'LUNp' => $LUNp,
                                        'LUNn' => $LUNn,
                                        'balanceLBp' => $balanceLBp,
                                        'balanceLBn' => $balanceLBn,
                                        'balanceNp' => $balanceNp,
                                        'balanceNn' => $balanceNn
                                    ]
                    );
    }

    // public function isiPenyesuaian($id,$neraca){
    //     $akun = COA::find($id);
    //     $dataPenyesuaian = penyesuaian::all()->toArray();
        // if($akun->keterangan == 'Header' || $akun->keterangan == 'Jumlah'){
        //     for($i = 0; $i < count($dataPenyesuaian); $i++){
        //         if($neraca->nama_akun == $dataPenyesuaian[$i]['akunD']){
                    // if($neraca->rpD == 0){
                    //     $neraca->rpPD = $dataPenyesuaian[$i]['rpD'];
                    // }else{
                    //     $neraca->rpPD = $neraca->rpPD+$dataPenyesuaian[$i]['rpD'];
                    // }
                    
                    // $neraca->rpPK = 0;
                    // $calc = $neraca->rpD + $neraca->rpPD;
                    // if($calc<0){
                    //     $neraca->SaldoPenyesuaianP = $calc;
                    //     $neraca->SaldoPenyesuaianN = 0; 
                    // }elseif($calc>=0){
                    //     $neraca->SaldoPenyesuaianP = 0;
                    //     $neraca->SaldoPenyesuaianN = $calc;
                    // }
        //         }elseif($neraca->nama_akun == $dataPenyesuaian[$i]['akunK']){
        //             $neraca->rpPD = 0;
                    // if($neraca->rpPK==0){
                    //     $neraca->rpPK = $dataPenyesuaian[$i]['rpK'];
                    // }else{
                    //     $neraca->rpPK = $neraca->rpPK-$dataPenyesuaian[$i]['rpK'];
                    // }
                    
                    // $calc = $neraca->rpK + $neraca->rpPK;
                    // if($calc<0){
                    //     $neraca->SaldoPenyesuaianP = $calc;
                    //     $neraca->SaldoPenyesuaianN = 0; 
                    // }elseif($calc>=0){
                    //     $neraca->SaldoPenyesuaianP = 0;
                    //     $neraca->SaldoPenyesuaianN = $calc;
                    // }
        //         }else{
        //             $neraca->rpPD = 0;
        //             $neraca->rpPK = 0;
        //             $neraca->SaldoPenyesuaianP = 0;
        //             $neraca->SaldoPenyesuaianN = 0;
        //         }
        //     }
        // }elseif($akun->keterangan == 'Akun, Debit'){
    //         for($i = 0; $i < count($dataPenyesuaian); $i++){
    //             if($akun->Nama_akun == $dataPenyesuaian[$i]['akunD']){
    //                 if($neraca->rpD == 0){
    //                     $neraca->rpPD = $dataPenyesuaian[$i]['rpD'];
    //                 }else{
    //                     $neraca->rpPD = $neraca->rpPD+$dataPenyesuaian[$i]['rpD'];
    //                 }
    //                 $neraca->rpPK = 0;                       
    //                 $calc = $neraca->rpD + $neraca->rpPD;
    //                 if($calc<0){
    //                     $neraca->SaldoPenyesuaianP = 0;
    //                     $neraca->SaldoPenyesuaianN = $calc; 
    //                 }elseif($calc>=0){
    //                     $neraca->SaldoPenyesuaianP = $calc;
    //                     $neraca->SaldoPenyesuaianN = 0;
    //                 }
    //             }elseif($neraca->nama_akun == $dataPenyesuaian[$i]['akunK']){
    //                 $neraca->rpPD = 0;
    //                 if($neraca->rpPK==0){
    //                     $neraca->rpPK = $dataPenyesuaian[$i]['rpK'];
    //                 }else{
    //                     $neraca->rpPK = $neraca->rpPK+$dataPenyesuaian[$i]['rpK'];
    //                 }
    //                 $calc = $neraca->rpK - $neraca->rpPK;
    //                 if($calc<0){
    //                     $neraca->SaldoPenyesuaianP = 0;
    //                     $neraca->SaldoPenyesuaianN = $calc; 
    //                 }elseif($calc>=0){
    //                     $neraca->SaldoPenyesuaianP = $calc;
    //                     $neraca->SaldoPenyesuaianN = 0;
    //                 }
    //             }else{
    //                 $neraca->rpPD = 0;
    //                 $neraca->rpPK = 0;
    //                 $neraca->SaldoPenyesuaianP = $neraca->rpD;
    //                 $neraca->SaldoPenyesuaianN = 0;
    //             }
    //         }
    //     }else{
    //         for($i = 0; $i < count($dataPenyesuaian); $i++){
    //             if($neraca->nama_akun == $dataPenyesuaian[$i]['akunD']){
    //                 if($neraca->rpD == 0){
    //                     $neraca->rpPD = $dataPenyesuaian[$i]['rpD'];
    //                 }else{
    //                     $neraca->rpPD = $neraca->rpPD+$dataPenyesuaian[$i]['rpD'];
    //                 }
    //                 $neraca->rpPK = 0;
    //                 $calc = $neraca->rpD + $neraca->rpPD;
    //                 if($calc<0){
    //                     $neraca->SaldoPenyesuaianP = 0;
    //                     $neraca->SaldoPenyesuaianN = $calc; 
    //                 }elseif($calc>=0){
    //                     $neraca->SaldoPenyesuaianP = $calc;
    //                     $neraca->SaldoPenyesuaianN = 0;
    //                 }
    //             }elseif($neraca->nama_akun == $dataPenyesuaian[$i]['akunK']){
    //                 $neraca->rpPD = 0;
    //                 if($neraca->rpPK==0){
    //                     $neraca->rpPK = $dataPenyesuaian[$i]['rpK'];
    //                 }else{
    //                     $neraca->rpPK = $neraca->rpPK+$dataPenyesuaian[$i]['rpK'];
    //                 }
    //                 $calc = $neraca->rpK - $neraca->rpPK;
    //                 if($calc<0){
    //                     $neraca->SaldoPenyesuaianP = 0;
    //                     $neraca->SaldoPenyesuaianN = $calc; 
    //                 }elseif($calc>=0){
    //                     $neraca->SaldoPenyesuaianP = $calc;
    //                     $neraca->SaldoPenyesuaianN = 0;
    //                 }
    //             }else{
    //                 $neraca->rpPD = 0;
    //                 $neraca->rpPK = 0;
    //                 $neraca->SaldoPenyesuaianP = 0;
    //                 $neraca->SaldoPenyesuaianN = $neraca->rpK;
    //             }
    //         }
    //     }
    // }    
}
