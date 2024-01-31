<?php

namespace App\Http\Controllers;

use App\Models\COA;
use App\Models\Jurnal;
use Illuminate\Http\Request;

class JurnalController extends Controller
{
    public function index(){
        $perPage = strtolower(request('pagination', 'all'));
        session(['paginate' => true]);
        if (strtolower($perPage) == 'all') {
            session(['paginate' => false]);
            $data = Jurnal::all();
        }else{
            $data = (new JurnalController)->getData($perPage);
        }
        return view("Lihat_Data_Jurnal", compact('data'));
    }

    public function create()
    {
        $data = COA::all();
        $dataDebit = [];
        $dataKredit= [];
        foreach($data as $d){
            if($d->keterangan == "Akun, Debit" || $d->keterangan == "Akun, Kredit"){
                $dataDebit[] = $d;
                $dataKredit[] = $d;
            }
        }
        return view('Tambah_Input_Jurnal', [
            'title' => 'EDIT',
            'method' => 'POST',
            'action' => '/jStore',
            'dataDebit' => $dataDebit,
            'dataKredit' => $dataKredit
        ]);
    }
    public function store(Request $request)
    {
        $akundebit = COA::find($request->Nama_akun_debit);
        $akunkredit = COA::find($request->Nama_akun_kredit);

        $prod = new Jurnal;

        $prod->tanggal = $request->tanggal;
        $prod->transaksi = $request->transaksi;
        $prod->keterangan = $request->keterangan;
        $prod->bukti = $request->bukti;
        $prod->jumlah = $request->jumlah;
        $prod->akunD = $akundebit->Nama_akun;
        $prod->rpD = $request->rpD;
        $prod->akunK = $akunkredit->Nama_akun;
        $prod->rpK = $request->rpK;
        
        // $akunCOA->jumlah_saldo = ($request->rpD - $request->rpK) + $akunCOA->jumlah_saldo;
        if($akundebit->keterangan == "Akun, Kredit"){
            $akundebit->jumlah_saldo = $akundebit->jumlah_saldo - $request->rpD;
        }else{
            $akundebit->jumlah_saldo = $akundebit->jumlah_saldo + $request->rpD;
        }
        if($akunkredit->keterangan == "Akun, Kredit"){
            $akunkredit->jumlah_saldo = $akunkredit->jumlah_saldo + $request->rpK; 
        }else{
            $akunkredit->jumlah_saldo = $akunkredit->jumlah_saldo - $request->rpK;
        }
        
        $prod->histori_saldo_debit = $akundebit->jumlah_saldo;
        $prod->histori_saldo_kredit = $akunkredit->jumlah_saldo;
        // $prod->histori_saldo = $akunCOA->jumlah_saldo;
        // $akunCOA->save();
        $akundebit->save();
        $akunkredit->save();
        $prod->save();
        return redirect('/jurnal')->with('msg', 'Akun Berhasil dibuat');
    }

    public function edit($id)
    {
        $data = COA::all();
        $dataDebit = [];
        $dataKredit= [];
        foreach($data as $d){
            if($d->keterangan == "Akun, Debit" || $d->keterangan == "Akun, Kredit"){
                $dataDebit[] = $d;
                $dataKredit[] = $d;
            }
        }
        return view('Tambah_Input_Jurnal', [
            'title' => 'EDIT',
            'method' => 'PUT',
            'action' => "/$id/updateJ",
            'dataJ' => Jurnal::find($id),
            'dataDebit' => $dataDebit,
            'dataKredit' => $dataKredit
        ]);
    }
    public function update(Request $request, $id)
    {
        $data = COA::all();
        $prod = Jurnal::find($id);
        $akundebit = COA::find($request->Nama_akun_debit);
        $akunkredit = COA::find($request->Nama_akun_kredit);
        
        

        $prod->tanggal = $request->tanggal;
        $prod->transaksi = $request->transaksi;
        $prod->keterangan = $request->keterangan;
        $prod->bukti = $request->bukti;
        $prod->jumlah = $request->jumlah;
        
        if($akundebit->Nama_akun == $prod->akunD){
            $prod->akunD = $akundebit->Nama_akun;
            if($akundebit->keterangan == "Akun, Kredit"){
                $akundebit->jumlah_saldo = $akundebit->jumlah_saldo - ($prod->rpD - $request->rpD);
            }else{
                $akundebit->jumlah_saldo = $akundebit->jumlah_saldo + ($prod->rpD - $request->rpD);
            }    
            $prod->rpD = $request->rpD;
            // $akundebit->jumlah_saldo = $akundebit->jumlah_saldo - $request->rpD;        
        }else{
            $akundebitlama = null;
            foreach($data as $d){
                if($d->Nama_akun == $prod->akunD){
                    $akundebitlama = $d;
                }
            }
            if($akundebitlama->keterangan == "Akun, Kredit"){
                $akundebitlama->jumlah_saldo = $akundebitlama->jumlah_saldo + $prod->rpD;
            }else{
                $akundebitlama->jumlah_saldo = $akundebitlama->jumlah_saldo - $prod->rpD;
            }
            
            $prod->akunD = $akundebit->Nama_akun;
            $prod->rpD = $request->rpD;
            if($akundebit->keterangan == "Akun, Kredit"){
                $akundebit->jumlah_saldo = $akundebit->jumlah_saldo - $request->rpD;
            }else{
                $akundebit->jumlah_saldo = $akundebit->jumlah_saldo + $request->rpD;
            }
            $akundebitlama->save();
        }

        if($akunkredit->Nama_akun == $prod->akunK){
            $prod->akunK = $akunkredit->Nama_akun;
            if($akunkredit->keterangan == "Akun, Kredit"){
                $akunkredit->jumlah_saldo = $akunkredit->jumlah_saldo + ($prod->rpK - $request->rpK); 
            }else{
                $akunkredit->jumlah_saldo = $akunkredit->jumlah_saldo - ($prod->rpK - $request->rpK);
            }
            $prod->rpK = $request->rpK;
            // $akunkredit->jumlah_saldo = $akunkredit->jumlah_saldo + $request->rpK;        
        }else{
            $akunkreditlama = null;
            foreach($data as $d){
                if($d->Nama_akun == $prod->akunK){
                    $akunkreditlama = $d;
                }
            }
            if($akundebitlama->keterangan == "Akun, Kredit"){
                $akunkreditlama->jumlah_saldo = $akunkreditlama->jumlah_saldo + $prod->rpK;
            }else{
                $akunkreditlama->jumlah_saldo = $akunkreditlama->jumlah_saldo - $prod->rpK;
            }

            $prod->akunK = $akunkredit->Nama_akun;
            $prod->rpK = $request->rpK;
            if($akunkredit->keterangan == "Akun, Kredit"){
                $akunkredit->jumlah_saldo = $akunkredit->jumlah_saldo + $request->rpK; 
            }else{
                $akunkredit->jumlah_saldo = $akunkredit->jumlah_saldo - $request->rpK;
            }
            $akunkreditlama->save();
        }        
        
        $akundebit->save();
        $akunkredit->save();
        
        $prod->histori_saldo_debit = $akundebit->jumlah_saldo;
        $prod->histori_saldo_kredit = $akunkredit->jumlah_saldo;

        $prod->save();
        return redirect('/jurnal')->with('msg', 'Akun Berhasil dibuat');
    }
    public function destroy($id)
    {
        $data = COA::all();
        $prod = Jurnal::find($id);
        foreach($data as $d){
            if($d->Nama_akun == $prod->akunD){
                $akundebit = $d;
            }
            if($d->Nama_akun == $prod->akunK){
                $akunkredit = $d;
            }
        }
        if($akundebit->keterangan == "Akun, Kredit"){
            $akundebit->jumlah_saldo = $akundebit->jumlah_saldo + $prod->rpD;
        }else{
            $akundebit->jumlah_saldo = $akundebit->jumlah_saldo - $prod->rpD;
        }
        if($akunkredit->keterangan == "Akun, Kredit"){
            $akunkredit->jumlah_saldo = $akunkredit->jumlah_saldo - $prod->rpK; 
        }else{
            $akunkredit->jumlah_saldo = $akunkredit->jumlah_saldo + $prod->rpK; 
        }

        $akundebit->save();
        $akunkredit->save();
        Jurnal::destroy($id);

        $allJurnals = Jurnal::all();

        foreach ($allJurnals as $jurnal) {
            $akunDebit = COA::where('Nama_akun', $jurnal->akunD)->first();
            $akunKredit = COA::where('Nama_akun', $jurnal->akunK)->first();
    
            $jurnal->histori_saldo_debit = $akunDebit->jumlah_saldo;
            $jurnal->histori_saldo_kredit = $akunKredit->jumlah_saldo;
            $jurnal->save();
        }

        return redirect('/jurnal')->with('msg', 'Hapus berhasil');
    }

    public function getData($perPage)
    {
        return Jurnal::paginate($perPage);
    }

}
