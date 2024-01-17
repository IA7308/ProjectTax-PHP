<?php

namespace App\Http\Controllers;

use App\Models\COA;
use App\Models\Jurnal;
use Illuminate\Http\Request;

class JurnalController extends Controller
{
    public function index(){
        $data =Jurnal::all();
        return view("Lihat_Data_Jurnal", compact('data'));
    }

    public function create()
    {
        $data = COA::get();
        return view('Tambah_Input_Jurnal', [
            'title' => 'EDIT',
            'method' => 'POST',
            'action' => '/jStore',
            'data' => $data
        ]);
    }
    public function store(Request $request)
    {
        $akunCOA = COA::find($request->Nama_akun);
        $prod = new Jurnal;
        $prod->tanggal = $request->tanggal;
        $prod->transaksi = $request->transaksi;
        $prod->keterangan = $request->keterangan;
        $prod->bukti = $request->bukti;
        $prod->jumlah = $request->jumlah;
        $prod->akunD = $request->akunD;
        $prod->rpD = $request->rpD;
        $prod->akunK = $request->akunK;
        $prod->rpK = $request->rpK;
        
        $akunCOA->Saldo_awal = ($akunCOA->Saldo_awal + $request->rpD) + ($akunCOA->Saldo_awal - $request->rpK);
        $akunCOA->save();
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
        $akunCOA = COA::find($request->Nama_akun);
        $prod = Jurnal::find($id);
        $prod->tanggal = $request->tanggal;
        $prod->transaksi = $request->transaksi;
        $prod->keterangan = $request->keterangan;
        $prod->bukti = $request->bukti;
        $prod->jumlah = $request->jumlah;
        $prod->akunD = $request->akunD;
        $prod->rpD = $request->rpD;
        $prod->akunK = $request->akunK;
        $prod->rpK = $request->rpK;
        
        $akunCOA->Saldo_awal = ($akunCOA->Saldo_awal + $request->rpD) + ($akunCOA->Saldo_awal - $request->rpK);
        $akunCOA->save();
        $prod->save();
        return redirect('/jurnal')->with('msg', 'Akun Berhasil dibuat');
    }
    public function destroy($id)
    {
        Jurnal::destroy($id);
        return redirect('/jurnal')->with('msg', 'Hapus berhasil');
    }

}
