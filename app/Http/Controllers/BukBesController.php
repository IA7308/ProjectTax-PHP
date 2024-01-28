<?php

namespace App\Http\Controllers;

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
            if($j->akunD == $akunCOA->Nama_akun || $j->akunK == $akunCOA->Nama_akun){
                $data[] = $j;
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
