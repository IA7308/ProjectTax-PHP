<?php

namespace App\Http\Controllers;

use App\Imports\penyesuaianImport;
use App\Models\COA;
use App\Models\debitPenyesuaian;
use App\Models\kreditPenyesuaian;
use App\Models\penyesuaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class penyesuaianController extends Controller
{
    public function index(){
        $perPage = strtolower(request('pagination', 'all'));
        session(['paginate' => true]);
        if (strtolower($perPage) == 'all') {
            session(['paginate' => false]);
            $data = penyesuaian::all();
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
        session(['jumlahpenyesuaian' => 0]);
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
            if($d->id >= $idpenyesuaian){
                $idpenyesuaian = $d->id;
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
            if($ad->penyesuaianid == session('penyesuaianid')){
                $akundebit[] = $ad;
            }
        };
        $datakredit = kreditPenyesuaian::all();
        $akunkredit = [];
        foreach($datakredit as $ad){
            if($ad->penyesuaianid == session('penyesuaianid')){
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

    public function edit($id, $penyesuaianid, $idakun, $bukti, $tgl, $tr)
    {
        session(['Multiple' => true]);
        session(['editMode' => true]);
        session(['namaBkt' => $bukti]);
        session(['namaTgl' => $tgl]);
        session(['namaTr' => $tr]);
        session(['penyesuaianid' => $penyesuaianid]);
        session(['idpenyesuaian' => $id]);

        $data = COA::all();
        $dataDebit = [];
        $dataKredit= [];
        $dataMultipleD = debitPenyesuaian::all();
        $dataMultipleK = kreditPenyesuaian::all();
        $dataMultipleDebit = [];
        $dataMultipleKredit = [];
        $jumlahDebit = 0;
        $jumlahKredit = 0;
        $jumlahpenyesuaian = 0;
        
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

        session(['jumlahpenyesuaian' => $jumlahpenyesuaian]);
        session(['jumlahDebit' => $jumlahDebit]);
        session(['jumlahKredit' => $jumlahKredit]);
    
        $datapilihan = debitPenyesuaian::find($idakun);
        if($datapilihan->bukti != $bukti && $datapilihan->transaksi != $tr){
            $datapilihan = kreditPenyesuaian::find($idakun);
        }

        return view('Tambah_Penyesuaian', [
            'title' => 'EDIT',
            'method' => 'PUT',
            'action' => "/$id/$penyesuaianid/$idakun/$bukti/$tgl/$tr/updateP",
            'methodModal' => 'POST',
            'actionModalKredit' => '/pTambahDataKredit',
            'dataMultipleDebit' => $dataMultipleDebit,
            'dataMultipleKredit' => $dataMultipleKredit,
            'dataKode' => $bukti,
            'dataJ' => penyesuaian::find($id),
            'datapilihan' => $datapilihan,
            'dataDebit' => $dataDebit,
            'dataKredit' => $dataKredit
        ]);
    }
    public function update(Request $request, $id, $penyesuaianid, $idakun)
    {
        $data = COA::all();
        $prod = penyesuaian::find($id);

        $datadebit = debitPenyesuaian::all();
        $akundebit = [];
        foreach($datadebit as $ad){
            if($ad->penyesuaianid == $penyesuaianid){
                $akundebit[] = $ad;
                if($ad->id == $idakun){
                    $ad->tanggal = $request->tanggal;
                    $ad->transaksi = $request->transaksi;
                    $ad->bukti = $request->bukti;
                    $ad->save();
                }
            }
        };
        $datakredit = kreditPenyesuaian::all();
        $akunkredit = [];
        foreach($datakredit as $ad){
            if($ad->penyesuaianid == $penyesuaianid){
                $akunkredit[] = $ad;
                if($ad->id == $idakun){
                    $ad->tanggal = $request->tanggal;
                    $ad->transaksi = $request->transaksi;
                    $ad->bukti = $request->bukti;
                    $ad->save();
                }
            }
        }; 

        // $prod->tanggal = $request->tanggal;
        // $prod->transaksi = $request->transaksi;
        // $prod->bukti = $request->bukti;
        $prod->jumlah = $request->jumlah;
        $prod->debit = json_encode($akundebit); // Ubah menjadi JSON sebelum menyimpan
        $prod->kredit = json_encode($akunkredit);
        // $prod->akunD = $akundebit->Nama_akun;
        // $prod->rpD = $request->rpD;
        // $prod->akunK = $akunkredit->Nama_akun;
        // $prod->rpK = $request->rpK;

        $prod->save();
        return redirect('/penyesuaian');
    }
    public function destroy($id){
        $data = COA::all();
        $prod = penyesuaian::find($id);
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
                    debitPenyesuaian::destroy($p['id']);
                }
            }else{
                foreach($prod->debit as $p){
                    debitPenyesuaian::destroy($p['id']);
                }
            }
            
        }
        
        foreach($akunkredit as $ak){
            if($ak->keterangan == "Akun, Kredit"){
                foreach($prod->kredit as $p){                    
                    kreditPenyesuaian::destroy($p['id']);
                }                 
            }else{
                foreach($prod->kredit as $p){                    
                    kreditPenyesuaian::destroy($p['id']);
                }             
            }
            
        }

        penyesuaian::destroy($id);
        return redirect('/penyesuaian');
    }

    public function resetPenyesuaian($penyesuaianid)
    {
        $data = COA::all();
        // Hapus semua entri kredit yang terkait dengan jurnal ID
        kreditPenyesuaian::where('penyesuaianid', $penyesuaianid)->delete();

        // Hapus semua entri debit yang terkait dengan jurnal ID
        debitPenyesuaian::where('penyesuaianid', $penyesuaianid)->delete();

        if(session('editMode')){
            penyesuaian::where('id', $penyesuaianid)->delete();
        }

        session(['jumlahDebit' => 0]);
        session(['jumlahKredit' => 0]);
        
        return redirect('/pTambahData')->with('msg', 'Data Jurnal Telah di Reset');
    }

    public function kembaliPenyesuaian($penyesuaianid)
    {
        $data = COA::all();

        // Hapus semua entri kredit yang terkait dengan jurnal ID
        kreditPenyesuaian::where('penyesuaianid', $penyesuaianid)->delete();

        // Hapus semua entri debit yang terkait dengan jurnal ID
        debitPenyesuaian::where('penyesuaianid', $penyesuaianid)->delete();


        session(['jumlahDebit' => 0]);
        session(['jumlahKredit' => 0]);
        
        return redirect('/penyesuaian')->with('msg', 'Data Jurnal Telah di Reset');
    }

    public function import(Request $request)
    {
        // dd($request->file('file'));
        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);
        DB::statement('ALTER TABLE penyesuaians DISABLE KEYS');
        DB::statement('ALTER TABLE debit_penyesuaians DISABLE KEYS');
        DB::statement('ALTER TABLE kredit_penyesuaians DISABLE KEYS');

        Excel::import(new penyesuaianImport, $request->file('file'));

        DB::statement('ALTER TABLE penyesuaians ENABLE KEYS');
        DB::statement('ALTER TABLE debit_penyesuaians ENABLE KEYS');
        DB::statement('ALTER TABLE kredit_penyesuaians ENABLE KEYS');


        return back()->with('success', 'File Excel berhasil diimpor.');
    }

}
