<?php

namespace App\Http\Controllers;

use App\Models\COA;
use App\Models\debitPenyesuaian;
use App\Models\kreditPenyesuaian;
use App\Models\penyesuaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class penyesuaianAkunController extends Controller
{
    public function create($penyesuaianid, $bukti, $tgl, $tr)
    {
        session(['Multiple' => true]);
        session(['namaBkt' => $bukti]);
        session(['namaTgl' => $tgl]);
        session(['namaTr' => $tr]);
        session(['penyesuaianid' => $penyesuaianid]);
        
        $data = COA::orderBy('kode', 'asc')->get();
        $dataDebit = [];
        $dataKredit= [];
        $dataMultipleD = debitPenyesuaian::all();
        $dataMultipleK = kreditPenyesuaian::all();
        $dataMultipleDebit = [];
        $dataMultipleKredit = [];
        $jumlahDebit = 0;
        $jumlahKredit = 0;
        $jumlahJurnal = 0;
        // $bukti = [];
        $penyesuaian = penyesuaian::all();
        
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
            if($MD->penyesuaianid == $penyesuaianid){
                $dataMultipleDebit[] = $MD;
                $jumlahDebit += $MD->rpD;
            }
        }
        foreach($dataMultipleK as $MK){
            if($MK->penyesuaianid == $penyesuaianid){
                $dataMultipleKredit[] = $MK;
                $jumlahKredit += $MK->rpK;
            }
        }
        if($jumlahDebit > $jumlahKredit){
            $jumlahJurnal = $jumlahDebit;
        }else{
            $jumlahJurnal = $jumlahKredit;
        }

        // session(['jumlahJurnal' => $jumlahJurnal]);
        // session(['jumlahDebit' => $jumlahDebit]);
        // session(['jumlahKredit' => $jumlahKredit]);

        return view('Tambah_Input_Jurnal', [
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
        session(['namaTgl' => $request->tanggal]);
        session(['namaTr' => $request->transaksi]);
        
        $akunD = COA::find($request->akunD);
        $keterangan = $request->keterangan;
        $transaksi = $request->transaksi;
        $data = penyesuaian::all();
        
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
        
        $prod = new debitPenyesuaian;
        $prod->tanggal = $request->tanggal;
        $prod->transaksi = $request->transaksi;
        $prod->bukti = $request->bukti;
        $prod->akunD = $akunD->Nama_akun;
        $prod->rpD = $request->rpD;
        $prod->histori_saldo_debit = $akunD->jumlah_saldo;
        $prod->penyesuaianid = $request->penyesuaianid;
        
        $prod->save();
        
        return Redirect::route('pTambahData', [
            'penyesuaianid' => $prod->penyesuaianid,
            'bukti' => $prod->bukti,
            'tgl' => $prod->tanggal,
            'tr' => $transaksi
        ])->with('msg', 'Akun Berhasil dibuat');

    }
    public function DeleteDebit($id, $penyesuaianid, $bukti, $tgl, $tr){
        $prod = debitPenyesuaian::find($id);
        debitPenyesuaian::destroy($id);
        return Redirect::route('pTambahData', [
            'penyesuaianid' => $penyesuaianid,
            'bukti' => $bukti,
            'tgl' => $tgl,
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
        session(['namaTgl' => $request->tanggal]);
        session(['namaTr' => $request->transaksi]);

        $akunK = COA::find($request->akunK);
        $transaksi = $request->transaksi;

        $prod = new kreditPenyesuaian;
        $prod->tanggal = $request->tanggal;
        $prod->transaksi = $request->transaksi;
        $prod->bukti = $request->bukti;
        $prod->akunK = $akunK->Nama_akun;
        $prod->rpK = $request->rpK;
        $prod->penyesuaianid = $request->penyesuaianid;
        
        $prod->save();

        return Redirect::route('pTambahData', [
            'penyesuaianid' => $prod->penyesuaianid,
            'bukti' => $prod->bukti,
            'tgl' => $prod->tanggal,
            'tr' => $transaksi
        ])->with('msg', 'Akun Berhasil dibuat');
    }
    public function DeleteKredit($id, $penyesuaianid, $bukti, $tgl, $tr, $ktr){
        $prod = kreditPenyesuaian::find($id);
        kreditPenyesuaian::destroy($id);
        return Redirect::route('pTambahData', [
            'penyesuaianid' => $penyesuaianid,
            'bukti' => $bukti,
            'tgl' => $tgl,
            'ktr' => $ktr,
            'tr' => $tr
        ])->with('msg', 'Akun Berhasil dibuat');

    }
}
