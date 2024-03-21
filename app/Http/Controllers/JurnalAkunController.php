<?php

namespace App\Http\Controllers;

use App\Models\COA;
use App\Models\Jurnal;
use App\Models\JurnalAkun;
use App\Models\JurnalAkunKredit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class JurnalAkunController extends Controller
{

    public function create($bukti, $tgl, $tr, $ktr)
    {
        session(['Multiple' => true]);
        session(['namaBkt' => $bukti]);
        session(['namaKtr' => $ktr]);
        session(['namaTgl' => $tgl]);
        session(['namaTr' => $tr]);
        
        $data = COA::orderBy('kode', 'asc')->get();
        $dataDebit = [];
        $dataKredit= [];
        $dataMultipleD = JurnalAkun::all();
        $dataMultipleK = JurnalAkunKredit::all();
        $dataMultipleDebit = [];
        $dataMultipleKredit = [];
        $jumlahDebit = 0;
        $jumlahKredit = 0;
        $jumlahJurnal = 0;
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
            if($MD->bukti == $bukti){
                $dataMultipleDebit[] = $MD;
                $jumlahDebit += $MD->rpD;
            }
        }
        foreach($dataMultipleK as $MK){
            if($MK->bukti == $bukti){
                $dataMultipleKredit[] = $MK;
                $jumlahKredit += $MK->rpK;
            }
        }
        if($jumlahDebit > $jumlahKredit){
            $jumlahJurnal = $jumlahDebit;
        }else{
            $jumlahJurnal = $jumlahKredit;
        }

        session(['jumlahJurnal' => $jumlahJurnal]);
        session(['jumlahDebit' => $jumlahDebit]);
        session(['jumlahKredit' => $jumlahKredit]);

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
    public function storeDebit(Request $request)
    {
        // $akunCOA = COA::all();
        // $akundebit = null;
        // foreach($akunCOA as $d){
        //     if($d->Nama_akun == $request->akunD){
        //         $akundebit = $d;
        //     }
        // }
        $akunD = COA::find($request->akunD);
        $keterangan = $request->keterangan;
        $transaksi = $request->transaksi;
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
        
        $prod = new JurnalAkun;
        $prod->tanggal = $request->tanggal;
        $prod->bukti = $request->bukti;
        $prod->akunD = $akunD->Nama_akun;
        $prod->rpD = $request->rpD;
        $prod->histori_saldo_debit = $akunD->jumlah_saldo;
        
        if($akunD->keterangan == "Akun, Kredit"){
            $akunD->jumlah_saldo = $akunD->jumlah_saldo + $request->rpD;
        }else{
            $akunD->jumlah_saldo = $akunD->jumlah_saldo + $request->rpD;
        }

        
        $akunD->save();
        $prod->save();
        
        return Redirect::route('jTambahData', [
            'bukti' => $prod->bukti,
            'tgl' => $prod->tanggal,
            'ktr' => $transaksi,
            'tr' => $keterangan
        ])->with('msg', 'Akun Berhasil dibuat');

    }
    public function DeleteDebit($id, $bukti, $tgl, $tr, $ktr){
        $data = COA::all();
        $prod = JurnalAkun::find($id);
        foreach($data as $d){
            if($d->Nama_akun == $prod->akunD){
                $akundebit = $d;
            }
        }
        if($akundebit->keterangan == "Akun, Kredit"){
            $akundebit->jumlah_saldo = $akundebit->jumlah_saldo + $prod->rpD;
        }else{
            $akundebit->jumlah_saldo = $akundebit->jumlah_saldo - $prod->rpD;
        }
        $akundebit->save();
        JurnalAkun::destroy($id);
        return Redirect::route('jTambahData', [
            'bukti' => $bukti,
            'tgl' => $tgl,
            'ktr' => $tr,
            'tr' => $ktr
        ])->with('msg', 'Akun Berhasil dibuat');

    }

    public function storeKredit(Request $request)
    {
        // $akunCOA = COA::all();
        // $akunkredit = null;
        // foreach($akunCOA as $d){
        //     if($d->Nama_akun == $request->akunK){
        //         $akunkredit = $d;
        //     }
        // }
        $akunK = COA::find($request->akunK);
        $keterangan = $request->keterangan;
        $transaksi = $request->transaksi;

        $prod = new JurnalAkunKredit;
        $prod->tanggal = $request->tanggal;
        $prod->bukti = $request->bukti;
        $prod->akunK = $akunK->Nama_akun;
        $prod->rpK = $request->rpK;
        
        
        if($akunK->keterangan == "Akun, Kredit"){
            $akunK->jumlah_saldo = $akunK->jumlah_saldo - $request->rpK; 
        }else{
            $akunK->jumlah_saldo = $akunK->jumlah_saldo - $request->rpK;
        }

        $prod->histori_saldo_kredit = $akunK->jumlah_saldo;
        $akunK->save();
        $prod->save();

        return Redirect::route('jTambahData', [
            'bukti' => $prod->bukti,
            'tgl' => $prod->tanggal,
            'ktr' => $transaksi,
            'tr' => $keterangan
        ])->with('msg', 'Akun Berhasil dibuat');
    }
    public function DeleteKredit($id, $bukti, $tgl, $tr, $ktr){
        $data = COA::all();
        $prod = JurnalAkunKredit::find($id);
        foreach($data as $d){
            if($d->Nama_akun == $prod->akunK){
                $akunkredit = $d;
            }
        } 
        if($akunkredit->keterangan == "Akun, Kredit"){
            $akunkredit->jumlah_saldo = $akunkredit->jumlah_saldo + $prod->rpK; 
        }else{
            $akunkredit->jumlah_saldo = $akunkredit->jumlah_saldo + $prod->rpK; 
        }

        $akunkredit->save();
        JurnalAkunKredit::destroy($id);
        return Redirect::route('jTambahData', [
            'bukti' => $bukti,
            'tgl' => $tgl,
            'ktr' => $tr,
            'tr' => $ktr
        ])->with('msg', 'Akun Berhasil dibuat');

    }
}
