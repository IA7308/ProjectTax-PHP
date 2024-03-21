<?php

namespace App\Http\Controllers;

use App\Models\COA;
use App\Models\Jurnal;
use App\Models\JurnalAkun;
use App\Models\JurnalAkunKredit;
use Illuminate\Http\Request;

class JurnalController extends Controller
{
    public function index(){
        $perPage = strtolower(request('pagination', 'all'));
        session(['paginate' => true]);
        if (strtolower($perPage) == 'all') {
            session(['paginate' => false]);
            $data = Jurnal::all();
            foreach ($data as $entry) {
                $entry->debit = json_decode($entry->debit); // true untuk mengembalikan array asosiatif
                $entry->kredit = json_decode($entry->kredit);
            }
        }else{
            $data = (new JurnalController)->getData($perPage);
            foreach ($data as $entry) {
                $entry->debit = json_decode($entry->debit); // true untuk mengembalikan array asosiatif
                $entry->kredit = json_decode($entry->kredit);
            }
        }
        return view("Lihat_Data_Jurnal", compact('data'));
    }

    public function create()
    {
        session(['Multiple' => false]);
        session(['jumlahJurnal' => 0]);
        $data = COA::orderBy('kode', 'asc')->get();
        $dataDebit = [];
        $dataKredit= [];
        $dataMultipleD = JurnalAkun::all();
        $dataMultipleK = JurnalAkunKredit::all();
        $dataMultipleDebit = [];
        $dataMultipleKredit = [];
        $bukti = [];
        $jurnal = Jurnal::all();
        
        foreach($jurnal as $d){
            $bukti[] = $d->bukti;
        }
        
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
        return view('Tambah_Input_Jurnal', [
            'title' => 'TAMBAH',
            'method' => 'POST',
            'methodModal' => 'POST',
            'action' => '/jStore',
            'actionModalKredit' => '/jTambahDataKredit',
            'dataDebit' => $dataDebit,
            'dataKredit' => $dataKredit,
            'dataMultipleDebit' => $dataMultipleDebit,
            'dataMultipleKredit' => $dataMultipleKredit,
            'dataKode' => $bukti
        ]);
    }
    public function store(Request $request)
    {
        $data = Jurnal::all();
        $kodeDuplikat = false;
        foreach ($data as $d) {
            if ($request->bukti == $d->bukti) {
                $kodeDuplikat = true;
                break;
            }
        }

        if ($kodeDuplikat) {
            return redirect()->back()->with('error', 'BUKTI DUPLIKAT');
        }

        $datadebit = JurnalAkun::all();
        $akundebit = [];
        foreach($datadebit as $ad){
            if($ad->bukti == $request->bukti){
                $akundebit[] = $ad;
            }
        };
        $datakredit = JurnalAkunKredit::all();
        $akunkredit = [];
        foreach($datakredit as $ad){
            if($ad->bukti == $request->bukti){
                $akunkredit[] = $ad;
            }
        };

        $prod = new Jurnal;

        $prod->tanggal = $request->tanggal;
        $prod->transaksi = $request->transaksi;
        $prod->keterangan = $request->keterangan;
        $prod->bukti = $request->bukti;
        $prod->jumlah = $request->jumlah;
        $prod->debit = json_encode($akundebit); // Ubah menjadi JSON sebelum menyimpan
        $prod->kredit = json_encode($akunkredit);
        $prod->histori_saldo_debit = 0;
        $prod->histori_saldo_kredit = 0;
        
        $prod->save();

        
        // $prod->akunD = $akundebit[0]->akunD;
        // $prod->rpD = $akundebit[0]->rpD;
        // $prod->akunK = $akunkredit[0]->akunK;
        // $prod->rpK = $akunkredit[0]->rpK;
        
        
        return redirect('/jurnal')->with('msg', 'Akun Berhasil dibuat');
    }

    public function edit($id)
    {
        $data = COA::orderBy('kode', 'asc')->get();
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
        $allJurnals = Jurnal::orderBy('tanggal', 'asc')->get();

        $prod->tanggal = $request->tanggal;
        $prod->transaksi = $request->transaksi;
        $prod->keterangan = $request->keterangan;
        $prod->bukti = $request->bukti;
        $prod->jumlah = $request->jumlah;
        
        if($akundebit->Nama_akun == $prod->akunD){
            $prod->akunD = $akundebit->Nama_akun;
            if($akundebit->keterangan == "Akun, Kredit"){
                $akundebit->jumlah_saldo = ($akundebit->jumlah_saldo - $prod->rpD) + $request->rpD;
            }else{
                $akundebit->jumlah_saldo = ($akundebit->jumlah_saldo - $prod->rpD) + $request->rpD;
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
                $akundebitlama->jumlah_saldo = $akundebitlama->jumlah_saldo - $prod->rpD;
            }else{
                $akundebitlama->jumlah_saldo = $akundebitlama->jumlah_saldo - $prod->rpD;
            }
            
            $prod->akunD = $akundebit->Nama_akun;
            $prod->rpD = $request->rpD;
            if($akundebit->keterangan == "Akun, Kredit"){
                $akundebit->jumlah_saldo = $akundebit->jumlah_saldo + $request->rpD;
            }else{
                $akundebit->jumlah_saldo = $akundebit->jumlah_saldo + $request->rpD;
            }
            $akundebitlama->save();
        }

        if($akunkredit->Nama_akun == $prod->akunK){
            $prod->akunK = $akunkredit->Nama_akun;
            if($akunkredit->keterangan == "Akun, Kredit"){
                $akunkredit->jumlah_saldo = ($akunkredit->jumlah_saldo + $prod->rpK) - $request->rpK; 
            }else{
                $akunkredit->jumlah_saldo = ($akunkredit->jumlah_saldo + $prod->rpK) - $request->rpK;
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
            if($akunkreditlama->keterangan == "Akun, Kredit"){
                $akunkreditlama->jumlah_saldo = $akunkreditlama->jumlah_saldo + $prod->rpK;
            }else{
                $akunkreditlama->jumlah_saldo = $akunkreditlama->jumlah_saldo + $prod->rpK;
            }

            $prod->akunK = $akunkredit->Nama_akun;
            $prod->rpK = $request->rpK;
            if($akunkredit->keterangan == "Akun, Kredit"){
                $akunkredit->jumlah_saldo = $akunkredit->jumlah_saldo - $request->rpK; 
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
        $prod->debit = json_decode($prod->debit);
        $prod->kredit = json_decode($prod->kredit);
        $akundebit = [];
        $akunkredit = [];
        foreach($data as $d){
            foreach($prod->debit as $p){
                if($d->Nama_akun == $p['akunD']){
                    $akundebit[] = $d;
                }
            }
            foreach($prod->kredit as $p){
                if($d->Nama_akun == $p['akunK']){
                    $akunkredit[] = $d;
                }
            }
        }

        foreach($akundebit as $ad){
            if($ad->keterangan == "Akun, Kredit"){
                foreach($prod->debit as $p){
                    if($ad->Nama_akun == $p['akunD']){
                        $ad->jumlah_saldo = $ad->jumlah_saldo + $p['rpD'];
                    }
                    JurnalAkun::destroy($p['id']);
                }
            }else{
                foreach($prod->debit as $p){
                    if($ad->Nama_akun == $p['akunD']){
                        $ad->jumlah_saldo = $ad->jumlah_saldo - $p['rpD'];
                    }
                    JurnalAkun::destroy($p['id']);
                }
            }
            $ad->save();
        }
        
        foreach($akunkredit as $ak){
            if($ak->keterangan == "Akun, Kredit"){
                foreach($prod->kredit as $p){
                    if($ak->Nama_akun == $p['akunK']){
                        $ak->jumlah_saldo = $ak->jumlah_saldo + $p['rpK'];
                    }
                    JurnalAkunKredit::destroy($p['id']);
                }                 
            }else{
                foreach($prod->kredit as $p){
                    if($ak->Nama_akun == $p['akunK']){
                        $ak->jumlah_saldo = $ak->jumlah_saldo + $p['rpK'];
                    }
                    JurnalAkunKredit::destroy($p['id']);
                }             
            }
            $ak->save();
        }
        
        Jurnal::destroy($id);

        $allJurnals = Jurnal::all();

        foreach ($allJurnals as $jurnal) {
            $jurnal->debit = json_decode($jurnal->debit);
            $jurnal->kredit = json_decode($jurnal->kredit);
            foreach($jurnal->debit as $d){
                $akunDebit = COA::where('Nama_akun', $d['akunD'])->first();
                $d['histori_saldo_debit'] = $akunDebit->jumlah_saldo;
            }
            foreach($jurnal->kredit as $k){
                $akunKredit = COA::where('Nama_akun', $k['akunK'])->first();                
                $k['histori_saldo_kredit'] = $akunKredit->jumlah_saldo;
            }
            $jurnal->debit = json_encode($jurnal->debit);
            $jurnal->kredit = json_encode($jurnal->kredit);        
            $jurnal->save();
        }

        return redirect('/jurnal')->with('msg', 'Hapus berhasil');
    }

    public function getData($perPage)
    {
        return Jurnal::paginate($perPage);
    }

}
