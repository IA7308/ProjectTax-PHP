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

    public function create($jurnalid, $bukti, $tgl, $ktr, $tr)
    {
        session(['Multiple' => true]);
        session(['editMode' => false]);
        session(['namaBkt' => $bukti]);
        session(['namaKtr' => $ktr]);
        session(['namaTgl' => $tgl]);
        session(['namaTr' => $tr]);
        session(['jurnalid' => $jurnalid]);
        
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
        // $bukti = [];
        $jurnal = Jurnal::all();
        
        // foreach($jurnal as $d){
        //     $bukti[] = $d->bukti;
        // }

        foreach($data as $d){
            if($d->keterangan == "Akun, Debit" || $d->keterangan == "Akun, Kredit"){
                $dataDebit[] = $d;
                $dataKredit[] = $d;
            }
        }
        foreach($dataMultipleD as $MD){
            if($MD->JurnalId == $jurnalid){
                $dataMultipleDebit[] = $MD;
                $jumlahDebit += $MD->rpD;
            }
        }
        foreach($dataMultipleK as $MK){
            if($MK->JurnalId == $jurnalid){
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

        session(['namaBkt' => $request->bukti]);
        session(['namaKtr' => $request->keterangan]);
        session(['namaTgl' => $request->tanggal]);
        session(['namaTr' => $request->transaksi]);
        
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
        $prod->transaksi = $request->transaksi;
        $prod->keterangan = $request->keterangan;
        $prod->bukti = $request->bukti;
        $prod->akunD = $akunD->Nama_akun;
        $prod->rpD = $request->rpD;
        $prod->histori_saldo_debit = $akunD->jumlah_saldo;
        $prod->JurnalId = $request->jurnalid;
        
        if($akunD->keterangan == "Akun, Kredit"){
            $akunD->jumlah_saldo = $akunD->jumlah_saldo + $request->rpD;
        }else{
            $akunD->jumlah_saldo = $akunD->jumlah_saldo + $request->rpD;
        }

        
        $akunD->save();
        $prod->save();
        
    
        return Redirect::route('jTambahData', [
            'jurnalid' => $prod->JurnalId,
            'bukti' => $prod->bukti,
            'tgl' => $prod->tanggal,
            'ktr' => $keterangan,
            'tr' => $transaksi
        ])->with('msg', 'Akun Berhasil dibuat');
    
        

    }
    public function DeleteDebit($id, $jurnalid, $bukti, $tgl, $ktr, $tr){
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

        if(session('editMode')){
            return Redirect::route('jEdit', [
                'id' => $prod->JurnalId,
                'jurnalid' => $prod->JurnalId,
                'idakun' => 0,
                'bukti' => $prod->bukti,
                'tgl' => $prod->tanggal,
                'ktr' => $prod->keterangan,
                'tr' => $prod->transaksi
            ])->with('msg', 'Akun Berhasil dibuat');
        }else{
            return Redirect::route('jTambahData', [
                'jurnalid' => $jurnalid,
                'bukti' => $bukti,
                'tgl' => $tgl,
                'ktr' => $ktr,
                'tr' => $tr
            ])->with('msg', 'Akun Berhasil dibuat');
    
        }
    }

    public function editDebit($id, $jurnalid, $bukti, $tgl, $ktr, $tr){
        session(['Multiple' => true]);
        
        session(['namaBkt' => $bukti]);
        session(['namaKtr' => $ktr]);
        session(['namaTgl' => $tgl]);
        session(['namaTr' => $tr]);
        session(['jurnalid' => $jurnalid]);
        
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
        // $bukti = [];
        $jurnal = Jurnal::all();
        
        // foreach($jurnal as $d){
        //     $bukti[] = $d->bukti;
        // }

        foreach($data as $d){
            if($d->keterangan == "Akun, Debit" || $d->keterangan == "Akun, Kredit"){
                $dataDebit[] = $d;
                $dataKredit[] = $d;
            }
        }
        foreach($dataMultipleD as $MD){
            if($MD->JurnalId == $jurnalid){
                $dataMultipleDebit[] = $MD;
                $jumlahDebit += $MD->rpD;
            }
        }
        foreach($dataMultipleK as $MK){
            if($MK->JurnalId == $jurnalid){
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

        $datapilihan = JurnalAkun::find($id);
        
        if($id != 0){
            session(['editMode'=>false]);
        }else{
            session(['editMode'=>true]);
        }

        return view('Tambah_Input_Jurnal', [
            'title' => 'TAMBAH',
            'method' => 'POST',
            'methodModal' => 'POST',
            'action' => "/$id/$jurnalid/$bukti/$tgl/$ktr/$tr/updateD",
            'actionModalKredit' => '/jTambahDataKredit',
            'dataDebit' => $dataDebit,
            'dataKredit' => $dataKredit,
            'datapilihan' => $datapilihan,
            'dataMultipleDebit' => $dataMultipleDebit,
            'dataMultipleKredit' => $dataMultipleKredit,
            'dataKode' => $bukti
        ]);
    }

    public function UpdateDebit(Request $request, $id, $jurnalid, $bukti, $tgl, $ktr, $tr){

        $prod = JurnalAkun::find($id);

        $prod->tanggal = $request->tanggal;
        $prod->transaksi = $request->transaksi;
        $prod->keterangan = $request->keterangan;
        $prod->bukti = $request->bukti;
        $prod->save();

        return Redirect::route('jTambahData', [
            'jurnalid' => $jurnalid,
            'bukti' => $bukti,
            'tgl' => $tgl,
            'ktr' => $ktr,
            'tr' => $tr
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
        session(['namaBkt' => $request->bukti]);
        session(['namaKtr' => $request->keterangan]);
        session(['namaTgl' => $request->tanggal]);
        session(['namaTr' => $request->transaksi]);

        $akunK = COA::find($request->akunK);
        $keterangan = $request->keterangan;
        $transaksi = $request->transaksi;

        $prod = new JurnalAkunKredit;
        $prod->tanggal = $request->tanggal;
        $prod->transaksi = $request->transaksi;
        $prod->keterangan = $request->keterangan;
        $prod->bukti = $request->bukti;
        $prod->akunK = $akunK->Nama_akun;
        $prod->rpK = $request->rpK;
        $prod->JurnalId = $request->jurnalid;
        
        if($akunK->keterangan == "Akun, Kredit"){
            $akunK->jumlah_saldo = $akunK->jumlah_saldo - $request->rpK; 
        }else{
            $akunK->jumlah_saldo = $akunK->jumlah_saldo - $request->rpK;
        }

        $prod->histori_saldo_kredit = $akunK->jumlah_saldo;
        $akunK->save();
        $prod->save();
        
        return Redirect::route('jTambahData', [
            'jurnalid' => $prod->JurnalId,
            'bukti' => $prod->bukti,
            'tgl' => $prod->tanggal,
            'ktr' => $keterangan,
            'tr' => $transaksi
        ])->with('msg', 'Akun Berhasil dibuat');
    }
    public function DeleteKredit($id, $jurnalid, $bukti, $tgl, $ktr, $tr){
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

        if(session('editMode')){
            return Redirect::route('jEdit', [
                'id' => $prod->JurnalId,
                'jurnalid' => $prod->JurnalId,
                'idakun' => 0,
                'bukti' => $prod->bukti,
                'tgl' => $prod->tanggal,
                'ktr' => $prod->keterangan,
                'tr' => $prod->transaksi
            ])->with('msg', 'Akun Berhasil dibuat');
        }else{
            return Redirect::route('jTambahData', [
                'jurnalid' => $jurnalid,
                'bukti' => $bukti,
                'tgl' => $tgl,
                'ktr' => $ktr,
                'tr' => $tr
            ])->with('msg', 'Akun Berhasil dibuat');
    
        }
        
    }
    public function editKredit($id, $jurnalid, $bukti, $tgl, $ktr, $tr){
        session(['Multiple' => true]);
        
        session(['namaBkt' => $bukti]);
        session(['namaKtr' => $ktr]);
        session(['namaTgl' => $tgl]);
        session(['namaTr' => $tr]);
        session(['jurnalid' => $jurnalid]);
        
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
        // $bukti = [];
        $jurnal = Jurnal::all();
        
        // foreach($jurnal as $d){
        //     $bukti[] = $d->bukti;
        // }

        foreach($data as $d){
            if($d->keterangan == "Akun, Debit" || $d->keterangan == "Akun, Kredit"){
                $dataDebit[] = $d;
                $dataKredit[] = $d;
            }
        }
        foreach($dataMultipleD as $MD){
            if($MD->JurnalId == $jurnalid){
                $dataMultipleDebit[] = $MD;
                $jumlahDebit += $MD->rpD;
            }
        }
        foreach($dataMultipleK as $MK){
            if($MK->JurnalId == $jurnalid){
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

        $datapilihan = JurnalAkunKredit::find($id);
        if($id != 0){
            session(['editMode'=>false]);
        }else{
            session(['editMode'=>true]);
        }

        return view('Tambah_Input_Jurnal', [
            'title' => 'TAMBAH',
            'method' => 'POST',
            'methodModal' => 'POST',
            'action' => "/$id/$jurnalid/$bukti/$tgl/$ktr/$tr/updateK",
            'actionModalKredit' => '/jTambahDataKredit',
            'dataDebit' => $dataDebit,
            'dataKredit' => $dataKredit,
            'datapilihan' => $datapilihan,
            'dataMultipleDebit' => $dataMultipleDebit,
            'dataMultipleKredit' => $dataMultipleKredit,
            'dataKode' => $bukti
        ]);
    }

    public function UpdateKredit(Request $request, $id, $jurnalid, $bukti, $tgl, $ktr, $tr){

        $prod = JurnalAkunKredit::find($id);

        $prod->tanggal = $request->tanggal;
        $prod->transaksi = $request->transaksi;
        $prod->keterangan = $request->keterangan;
        $prod->bukti = $request->bukti;
        $prod->save();

        return Redirect::route('jTambahData', [
            'jurnalid' => $jurnalid,
            'bukti' => $bukti,
            'tgl' => $tgl,
            'ktr' => $ktr,
            'tr' => $tr
        ])->with('msg', 'Akun Berhasil dibuat');
        
    }
}
