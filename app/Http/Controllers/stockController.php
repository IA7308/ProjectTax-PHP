<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\COA;
use Illuminate\Http\Request;

class stockController extends Controller
{
    public function index(){
        $perPage = strtolower(request('pagination', 'all'));
        session(['paginate' => true]);
        $saldo = 0;
        $temp = Barang::all();
        $dataC = [];
            foreach($temp as $t){
                if($t->keterangan == "Akun, Kredit" || $t->keterangan == "Akun, Debit"){
                    $dataC[] = $t;
                }
            }
        if($dataC == []){
            session(['idDataterpilih' => '#']);
        }else{
            session(['idDataterpilih' => $dataC[0]->id]);
        }
        
        
        if (strtolower($perPage) == 'all') {
            session(['paginate' => false]);
            $data = Barang::all();
            foreach ($data as $d) {
                $saldo += $d->Saldo_awal;
                if($d->unit_keluar < 0){
                    $d->backgroundCell = 'table-danger';
                }else{
                    $d->backgroundCell = '';
                }
            }
            session(['saldo' => $saldo]);
        }else{
            $data = (new COAController)->getData($perPage);
            foreach ($data as $d) {
                $saldo += $d->Saldo_awal;
                if($d->unit_keluar < 0){
                    $d->backgroundCell = 'table-danger';
                }else{
                    $d->backgroundCell = '';
                }
            }
            session(['saldo' => $saldo]);
        }
        $nama_penjual = [];
        $dataCOA = COA::orderBy('kode', 'asc')->get();
        foreach($dataCOA as $d){
            if($d->keterangan == "Akun, Debit" || $d->keterangan == "Akun, Kredit"){
                $nama_penjual[] = $d;
            }
        }

        return view('BarangMasuk', [
            'data' => $data, 
            'title' => 'BARANG MASUK',
            'method' => 'POST',
            'action' => '/bStore',
            'nama_penjual' => $nama_penjual,
        ]);
    }

    public function indexBarangKeluar(){
        $perPage = strtolower(request('pagination', 'all'));
        session(['paginate' => true]);
        $saldo = 0;
        $temp = Barang::all();
        $dataC = [];
            foreach($temp as $t){
                if($t->keterangan == "Akun, Kredit" || $t->keterangan == "Akun, Debit"){
                    $dataC[] = $t;
                }
            }
        if($dataC == []){
            session(['idDataterpilih' => '#']);
        }else{
            session(['idDataterpilih' => $dataC[0]->id]);
        }
        
        
        if (strtolower($perPage) == 'all') {
            session(['paginate' => false]);
            $data = BarangKeluar::all();
            foreach ($data as $d) {
                $saldo += $d->Saldo_awal;
                if($d->unit_keluar < 0){
                    $d->backgroundCell = 'table-danger';
                }else{
                    $d->backgroundCell = '';
                }
            }
            session(['saldo' => $saldo]);
        }else{
            $data = (new COAController)->getData($perPage);
            foreach ($data as $d) {
                $saldo += $d->Saldo_awal;
                if($d->unit_keluar < 0){
                    $d->backgroundCell = 'table-danger';
                }else{
                    $d->backgroundCell = '';
                }
            }
            session(['saldo' => $saldo]);
        }
        $nama_penjual = [];
        $dataCOA = COA::orderBy('kode', 'asc')->get();
        foreach($dataCOA as $d){
            if($d->keterangan == "Akun, Debit" || $d->keterangan == "Akun, Kredit"){
                $nama_penjual[] = $d;
            }
        }

        return view('BarangMasuk', [
            'data' => $data,
            'title' => 'BARANG KELUAR', 
            'method' => 'POST',
            'action' => '/#',
            'nama_penjual' => $nama_penjual,
        ]);
    }

    public function store(Request $request){
        $nama_penjual = COA::find($request->nama_penjual);

        $prod = new Barang;
        $prod->tanggal = $request->tanggal;
        $prod->nama_penjual = $nama_penjual->Nama_akun;
        $prod->keterangan = $request->keterangan;
        $prod->kode_barang = $request->kode_barang;
        $prod->nama_barang = $request->nama_barang;
        $prod->unit_keluar = $request->unit_keluar;
        $prod->harga = $request->harga;

        $prod->save();
        return back();
    }
}
