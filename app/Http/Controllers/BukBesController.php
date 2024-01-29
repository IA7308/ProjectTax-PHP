<?php

namespace App\Http\Controllers;

use App\Models\bukubesar;
use App\Models\COA;
use App\Models\Jurnal;
use Illuminate\Http\Request;

class BukBesController extends Controller
{
    public function index(){
        $perPage = request('pagination', 5);
        $temp = COA::all();
            $dataC = [];
            foreach($temp as $t){
                if($t->keterangan == "Akun, Kredit" || $t->keterangan == "Akun, Debit"){
                    $dataC[] = $t;
                }
            }
            usort($dataC, function ($a, $b) {
                return $a->kode <=> $b->kode;
            });
        session(['pilihC' => false]);
        session(['paginate' => true]);
        if (strtolower($perPage) == 'all') {
            session(['paginate' => false]);
            $temp = COA::all();
            $dataC = [];
            foreach($temp as $t){
                if($t->keterangan == "Akun, Kredit" || $t->keterangan == "Akun, Debit"){
                    $dataC[] = $t;
                }
            }
            usort($dataC, function ($a, $b) {
                return $a->kode <=> $b->kode;
            });

            $data = $dataC;
            
        }else{
            $data = (new JurnalController)->getData($perPage);
        }
        return view("BukuBesar", ['dataC' => $dataC, 'data' => $data]);
    }
    public function getData($perPage)
    {
        return Jurnal::paginate($perPage);
    }

    public function show($id){
        session(['pilihC' => true]);
        session(['paginate' => false]);
        $temp = COA::all();
            $dataC = [];
            foreach($temp as $t){
                if($t->keterangan == "Akun, Kredit" || $t->keterangan == "Akun, Debit"){
                    $dataC[] = $t;
                }
            }
        $akunCOA = COA::find($id);
        $data = [];
        $jurnal = Jurnal::all();
        foreach($jurnal as $j){
            if($j->akunD == $akunCOA->Nama_akun){
                $bukudata = new bukubesar;
                $bukudata->tanggal = $j->tanggal;
                $bukudata->transaksi = $j->transaksi;
                $bukudata->keterangan = $j->keterangan;
                $bukudata->bukti = $j->bukti;
                $bukudata->rpD = $j->rpD;
                $bukudata->rpK = 0;
                $bukudata->histori_saldo = $j->histori_saldo_debit;
                $data[] = $bukudata;
            }elseif($j->akunK == $akunCOA->Nama_akun){
                $bukudata = new bukubesar;
                $bukudata->tanggal = $j->tanggal;
                $bukudata->transaksi = $j->transaksi;
                $bukudata->keterangan = $j->keterangan;
                $bukudata->bukti = $j->bukti;
                $bukudata->rpD = 0;
                $bukudata->rpK = $j->rpD*-1;
                $bukudata->histori_saldo = $j->histori_saldo_kredit;
                $data[] = $bukudata;
            }
        }   
        return view("BukuBesar", 
        [
            'dataC' => $dataC,
            'dataPilih' => $akunCOA,
            'data' => $data
        ]);
    }
}
