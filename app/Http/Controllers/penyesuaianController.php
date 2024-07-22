<?php

namespace App\Http\Controllers;

use App\Models\COA;
use App\Models\debitPenyesuaian;
use App\Models\kreditPenyesuaian;
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
        // $data = COA::orderBy('kode', 'asc')->get();
        // $dataDebit = [];
        // $dataKredit= [];
        // foreach($data as $d){
        //     if($d->keterangan == "Akun, Debit" || $d->keterangan == "Akun, Kredit"){
        //         $dataDebit[] = $d;
        //         $dataKredit[] = $d;
        //     }
        // }
        // return view('Tambah_Penyesuaian', [
        //     'title' => 'TAMBAH',
        //     'method' => 'POST',
        //     'action' => '/pStore',
        //     'dataDebit' => $dataDebit,
        //     'dataKredit' => $dataKredit
        // ]);

        session(['Multiple' => false]);
        session(['editMode' => false]);
        session(['jumlahPenyesuaian' => 0]);
        session(['jumlahDebit' => 0]);
        session(['jumlahKredit' => 0]);
        $data = COA::orderBy('kode', 'asc')->get();
        $dataDebit = [];
        $dataKredit= [];
        $dataMultipleD = debitPenyesuaian::all();
        $dataMultipleK = kreditPenyesuaian::all();
        $dataMultipleDebit = [];
        $dataMultipleKredit = [];
        $idpenyesuaian = 0;
        $bukti = [];
        $penyesuaian = penyesuaian::all();
        
        foreach($penyesuaian as $d){
            $bukti[] = $d->bukti;
            if($d->id > $idpenyesuaian){
                $idjurnal = $d->id;
            }
        }
        session(['penyesuaianid' => $idpenyesuaian+1]);
        foreach($data as $d){
            if($d->keterangan == "Akun, Debit" || $d->keterangan == "Akun, Kredit"){
                $dataDebit[] = $d;
                $dataKredit[] = $d;
            }
        }
        foreach($dataMultipleD as $MD){
            if($MD->bukti == ''){
                $dataMultipleDebit[] = $MD;
            }
        }
        foreach($dataMultipleK as $MK){
            if($MK->bukti == ''){
                $dataMultipleKredit[] = $MK;  
            }
        }
        return view('Tambah_Penyesuaian', [
            'title' => 'TAMBAH',
            'method' => 'POST',
            'methodModal' => 'POST',
            'action' => '/pStore',
            'actionModalKredit' => '/pTambahDataKredit',
            'dataDebit' => $dataDebit,
            'dataKredit' => $dataKredit,
            'dataMultipleDebit' => $dataMultipleDebit,
            'dataMultipleKredit' => $dataMultipleKredit,
            'dataKode' => $bukti
        ]);
    }

    public function store(Request $request)
    {
        // $akundebit = COA::find($request->Nama_akun_debit);
        // $akunkredit = COA::find($request->Nama_akun_kredit);
        $datadebit = debitPenyesuaian::all();
        $akundebit = [];
        foreach($datadebit as $ad){
            if($ad->JurnalId == session('penyesuaianid')){
                $akundebit[] = $ad;
            }
        };
        $datakredit = kreditPenyesuaian::all();
        $akunkredit = [];
        foreach($datakredit as $ad){
            if($ad->JurnalId == session('penyesuaianid')){
                $akunkredit[] = $ad;
            }
        };

        $prod = new penyesuaian;

        // $prod->tanggal = $request->tanggal;
        // $prod->transaksi = $request->transaksi;
        // $prod->bukti = $request->bukti;
        $prod->jumlah = $request->jumlah;
        // $prod->akunD = $akundebit->Nama_akun;
        // $prod->rpD = $request->rpD;
        // $prod->akunK = $akunkredit->Nama_akun;
        // $prod->rpK = $request->rpK;
        $prod->debit = json_encode($akundebit); // Ubah menjadi JSON sebelum menyimpan
        $prod->kredit = json_encode($akunkredit);
        
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
