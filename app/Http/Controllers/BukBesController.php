<?php

namespace App\Http\Controllers;

use App\Models\COA;
use App\Models\Jurnal;
use Illuminate\Http\Request;

class BukBesController extends Controller
{
    public function index(){
        $perPage = request('pagination', 5);
        $dataC = COA::all();
        session(['pilihC' => false]);
        session(['paginate' => true]);
        if (strtolower($perPage) == 'all') {
            session(['paginate' => false]);
            $dataC = COA::all();
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
        $dataC = COA::all();
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
