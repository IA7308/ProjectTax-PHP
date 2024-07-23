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
        $jumlahpenyesuaian = 0;
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
            $jumlahpenyesuaian = $jumlahDebit;
        }else{
            $jumlahpenyesuaian = $jumlahKredit;
        }

        session(['jumlahpenyesuaian' => $jumlahpenyesuaian]);
        session(['jumlahDebit' => $jumlahDebit]);
        session(['jumlahKredit' => $jumlahKredit]);

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
        $data = penyesuaian::all();
        
        $kodeDuplikat = false;
        
        foreach ($data as $d) {
            if ($request->bukti == $d->bukti) {
                $kodeDuplikat = true;
                break;
            }
            
        }

        // if ($kodeDuplikat) {
        //     return redirect()->back()->with('error', 'BUKTI DUPLIKAT');
        // }
        
        $prod = new debitPenyesuaian;
        $prod->tanggal = $request->tanggal;
        $prod->transaksi = $request->transaksi;
        $prod->bukti = $request->bukti;
        $prod->akunD = $akunD->Nama_akun;
        $prod->rpD = $request->rpD;
        $prod->penyesuaianid = $request->penyesuaianid;
        
        $prod->save();
        
        return Redirect::route('pTambahData', [
            'penyesuaianid' => $prod->penyesuaianid,
            'bukti' => $prod->bukti,
            'tgl' => $prod->tanggal,
            'tr' => $prod->transaksi
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

    public function editDebit($id, $penyesuaianid, $bukti, $tgl, $tr){
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
        $jumlahpenyesuaian = 0;
        // $bukti = [];
        $jurnal = penyesuaian::all();
        
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
            $jumlahpenyesuaian = $jumlahDebit;
        }else{
            $jumlahpenyesuaian = $jumlahKredit;
        }

        session(['jumlahenyesuaian' => $jumlahpenyesuaian]);
        session(['jumlahDebit' => $jumlahDebit]);
        session(['jumlahKredit' => $jumlahKredit]);

        $datapilihan = debitPenyesuaian::find($id);

        return view('Tambah_Penyesuaian', [
            'title' => 'TAMBAH',
            'method' => 'POST',
            'methodModal' => 'POST',
            'action' => "/$id/$jumlahpenyesuaian/$bukti/$tgl/$tr/updateDP",
            'actionModalKredit' => '/pTambahDataKredit',
            'dataDebit' => $dataDebit,
            'dataKredit' => $dataKredit,
            'datapilihan' => $datapilihan,
            'dataMultipleDebit' => $dataMultipleDebit,
            'dataMultipleKredit' => $dataMultipleKredit,
            'dataKode' => $bukti
        ]);
    }

    public function UpdateDebit(Request $request, $id, $penyesuaianid, $bukti, $tgl, $tr){

        $prod = debitPenyesuaian::find($id);

        $prod->tanggal = $request->tanggal;
        $prod->transaksi = $request->transaksi;
        $prod->bukti = $request->bukti;
        $prod->save();

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
    public function DeleteKredit($id, $penyesuaianid, $bukti, $tgl, $tr){
        $prod = kreditPenyesuaian::find($id);
        kreditPenyesuaian::destroy($id);
        return Redirect::route('pTambahData', [
            'penyesuaianid' => $penyesuaianid,
            'bukti' => $bukti,
            'tgl' => $tgl,
            'tr' => $tr
        ])->with('msg', 'Akun Berhasil dibuat');

    }

    public function editKredit($id, $penyesuaianid, $bukti, $tgl, $tr){
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
        $jumlahpenyesuaian = 0;
        // $bukti = [];
        $jurnal = penyesuaian::all();
        
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
            $jumlahpenyesuaian = $jumlahDebit;
        }else{
            $jumlahpenyesuaian = $jumlahKredit;
        }

        session(['jumlahenyesuaian' => $jumlahpenyesuaian]);
        session(['jumlahDebit' => $jumlahDebit]);
        session(['jumlahKredit' => $jumlahKredit]);

        $datapilihan = kreditPenyesuaian::find($id);

        return view('Tambah_Penyesuaian', [
            'title' => 'TAMBAH',
            'method' => 'POST',
            'methodModal' => 'POST',
            'action' => "/$id/$jumlahpenyesuaian/$bukti/$tgl/$tr/updateDP",
            'actionModalKredit' => '/pTambahDataKredit',
            'dataDebit' => $dataDebit,
            'dataKredit' => $dataKredit,
            'datapilihan' => $datapilihan,
            'dataMultipleDebit' => $dataMultipleDebit,
            'dataMultipleKredit' => $dataMultipleKredit,
            'dataKode' => $bukti
        ]);
    }

    public function UpdateKredit(Request $request, $id, $penyesuaianid, $bukti, $tgl, $tr){

        $prod = kreditPenyesuaian::find($id);

        $prod->tanggal = $request->tanggal;
        $prod->transaksi = $request->transaksi;
        $prod->bukti = $request->bukti;
        $prod->save();

        return Redirect::route('pTambahData', [
            'penyesuaianid' => $penyesuaianid,
            'bukti' => $bukti,
            'tgl' => $tgl,
            'tr' => $tr
        ])->with('msg', 'Akun Berhasil dibuat');
        
    }
}
