<?php

namespace App\Http\Controllers;

use App\Models\COA;
use App\Models\penyesuaian;
use Illuminate\Http\Request;

class penyesuaianController extends Controller
{
    public function index(){
        $perPage = strtolower(request('pagination', 'all'));
        session(['paginate' => true]);
        if (strtolower($perPage) == 'all') {
            session(['paginate' => false]);
            $data = penyesuaian::all();
        }else{
            $data = (new JurnalController)->getData($perPage);
        }
        return view("Penyesuaian", compact('data'));
    }

    public function getData($perPage)
    {
        return penyesuaian::paginate($perPage);
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
        return view('Tambah_Penyesuaian', [
            'title' => 'TAMBAH',
            'method' => 'POST',
            'action' => '/pStore',
            'dataDebit' => $dataDebit,
            'dataKredit' => $dataKredit
        ]);
    }

    public function store(Request $request)
    {
        $akundebit = COA::find($request->Nama_akun_debit);
        $akunkredit = COA::find($request->Nama_akun_kredit);

        $prod = new penyesuaian;

        $prod->tanggal = $request->tanggal;
        $prod->transaksi = $request->transaksi;
        $prod->bukti = $request->bukti;
        $prod->jumlah = $request->jumlah;
        $prod->akunD = $akundebit->Nama_akun;
        $prod->rpD = $request->rpD;
        $prod->akunK = $akunkredit->Nama_akun;
        $prod->rpK = $request->rpK;
        
        $prod->save();
        return redirect('/penyesuaian');
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
        return view('Tambah_Penyesuaian', [
            'title' => 'EDIT',
            'method' => 'PUT',
            'action' => "/$id/updateP",
            'dataJ' => penyesuaian::find($id),
            'dataDebit' => $dataDebit,
            'dataKredit' => $dataKredit
        ]);
    }
    public function update(Request $request, $id)
    {
        $data = COA::all();
        $prod = penyesuaian::find($id);
        $akundebit = COA::find($request->Nama_akun_debit);
        $akunkredit = COA::find($request->Nama_akun_kredit);   

        $prod->tanggal = $request->tanggal;
        $prod->transaksi = $request->transaksi;
        $prod->bukti = $request->bukti;
        $prod->jumlah = $request->jumlah;
        $prod->akunD = $akundebit->Nama_akun;
        $prod->rpD = $request->rpD;
        $prod->akunK = $akunkredit->Nama_akun;
        $prod->rpK = $request->rpK;

        $prod->save();
        return redirect('/penyesuaian');
    }
    public function destroy($id){
        penyesuaian::destroy($id);
        return redirect('/penyesuaian');
    }
}
