<?php

namespace App\Http\Controllers;

use App\Models\COA;
use App\Models\Jurnal;
use Illuminate\Http\Request;

class JurnalController extends Controller
{
    public function index(){
        $perPage = request('pagination', 5);
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
            if($d->keterangan == "Akun, Debit"){
                $dataDebit[] = $d;
            }if($d->keterangan == "Akun, Kredit"){
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

        $akundebit->jumlah_saldo = $akundebit->jumlah_saldo - $request->rpD;
        $akunkredit->jumlah_saldo = $akunkredit->jumlah_saldo + $request->rpK; 
        $prod->histori_saldo = ($request->rpD - $request->rpK) + ($akundebit->jumlah_saldo -$akunkredit->jumlah_saldo);
        // $prod->histori_saldo = $akunCOA->jumlah_saldo;
        // $akunCOA->save();
        $akundebit->save();
        $akunkredit->save();
        $prod->save();
        return redirect('/jurnal')->with('msg', 'Akun Berhasil dibuat');
    }

    public function edit($id)
    {
        $data = COA::get();
        return view('Tambah_Input_Jurnal', [
            'title' => 'EDIT',
            'method' => 'PUT',
            'action' => "/$id/updateJ",
            'dataJ' => Jurnal::find($id),
            'data' => $data
        ]);
    }
    public function update(Request $request, $id)
    {
        $akunCOA = COA::find($request->Nama_akun_kredit);
        $prod = Jurnal::find($id);
        // $prod->nama_akun = $request->Nama_akun;
        $prod->tanggal = $request->tanggal;
        $prod->transaksi = $request->transaksi;
        $prod->keterangan = $request->keterangan;
        $prod->bukti = $request->bukti;
        $prod->jumlah = $request->jumlah;
        $prod->akunD = $request->akunD;
        $akunCOA->jumlah_saldo = $akunCOA->jumlah_saldo - $prod->rpD;
        $prod->rpD = $request->rpD;
        $akunCOA->jumlah_saldo = $akunCOA->jumlah_saldo + $request->rpD;
        $prod->akunK = $request->akunK;
        $akunCOA->jumlah_saldo = $akunCOA->jumlah_saldo + $prod->rpK;
        $prod->rpK = $request->rpK;
        $akunCOA->jumlah_saldo = $akunCOA->jumlah_saldo - $request->rpK;
        $prod->histori_saldo = $akunCOA->jumlah_saldo;
        $akunCOA->save();
        $prod->save();
        return redirect('/jurnal')->with('msg', 'Akun Berhasil dibuat');
    }
    public function destroy($id)
    {
        
        $prod = Jurnal::find($id);
        $akunCOA = COA::find($prod->nama_akun);
        $akunCOA->jumlah_saldo = $akunCOA->jumlah_saldo + $prod->rpK; 
        $akunCOA->jumlah_saldo = $akunCOA->jumlah_saldo - $prod->rpD;
        $akunCOA->save();
        Jurnal::destroy($id);
        return redirect('/jurnal')->with('msg', 'Hapus berhasil');
    }

    public function getData($perPage)
    {
        return Jurnal::paginate($perPage);
    }

}
