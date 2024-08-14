<?php

namespace App\Http\Controllers;

use App\Exports\ExportJurnal;
use App\Imports\JurnalsImport;
use App\Models\COA;
use App\Models\Jurnal;
use App\Models\JurnalAkun;
use App\Models\JurnalAkunKredit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JurnalExport;

class JurnalController extends Controller
{
    public function index(){
        $perPage = strtolower(request('pagination', '100'));;
        $saldoDebit = 0;
        $saldoKredit = 0;
        session(['paginate' => true]);
        if (strtolower($perPage) == 'all') {
            session(['paginate' => false]);
            $data = Jurnal::all();
            foreach ($data as $entry) {
                $entry->debit = json_decode($entry->debit); // true untuk mengembalikan array asosiatif
                $entry->kredit = json_decode($entry->kredit);
                foreach($entry->debit as $d){
                    $saldoDebit += $d['rpD'];
                }
                foreach($entry->kredit as $k){
                    $saldoKredit += $k['rpK'];
                }
            }
        }else{
            $data = (new JurnalController)->getData($perPage);
            foreach ($data as $entry) {
                $entry->debit = json_decode($entry->debit); // true untuk mengembalikan array asosiatif
                $entry->kredit = json_decode($entry->kredit);
                foreach($entry->debit as $d){
                    $saldoDebit += $d['rpD'];
                }
                foreach($entry->kredit as $k){
                    $saldoKredit += $k['rpK'];
                }
            }
        }
        session(['saldoDebit' => $saldoDebit]);
        session(['saldoKredit' => $saldoKredit]);
        return view("Lihat_Data_Jurnal", compact('data'));
    }

    public function create()
    {
        session(['Multiple' => false]);
        session(['editMode' => false]);
        session(['jumlahJurnal' => 0]);
        session(['jumlahDebit' => 0]);
        session(['jumlahKredit' => 0]);
        $data = COA::orderBy('kode', 'asc')->get();
        $dataDebit = [];
        $dataKredit= [];
        $dataMultipleD = JurnalAkun::all();
        $dataMultipleK = JurnalAkunKredit::all();
        $dataMultipleDebit = [];
        $dataMultipleKredit = [];
        $idjurnal = 0;
        $bukti = [];
        $jurnal = Jurnal::all();
        
        foreach($jurnal as $d){
            $bukti[] = $d->bukti;
            if($d->id > $idjurnal){
                $idjurnal = $d->id;
            }
        }
        session(['jurnalid' => $idjurnal+1]);
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
        // foreach ($data as $d) {
        //     if ($request->bukti == $d->bukti) {
        //         $kodeDuplikat = true;
        //         break;
        //     }
        // }

        // if ($kodeDuplikat) {
        //     return redirect()->back()->with('error', 'BUKTI DUPLIKAT');
        // }

        $datadebit = JurnalAkun::all();
        $akundebit = [];
        foreach($datadebit as $ad){
            if($ad->JurnalId == session('jurnalid')){
                $akundebit[] = $ad;
            }
        };
        $datakredit = JurnalAkunKredit::all();
        $akunkredit = [];
        foreach($datakredit as $ad){
            if($ad->JurnalId == session('jurnalid')){
                $akunkredit[] = $ad;
            }
        };

        $prod = new Jurnal;

        // $prod->tanggal = $request->tanggal;
        // $prod->transaksi = $request->transaksi;
        // $prod->keterangan = $request->keterangan;
        // $prod->bukti = $request->bukti;
        $prod->jumlah = $request->jumlah;
        $prod->debit = json_encode($akundebit); // Ubah menjadi JSON sebelum menyimpan
        $prod->kredit = json_encode($akunkredit);
        $prod->JurnalId = session('jurnalid');
        $prod->histori_saldo_debit = 0;
        $prod->histori_saldo_kredit = 0;
        
        $prod->save();

        
        // $prod->akunD = $akundebit[0]->akunD;
        // $prod->rpD = $akundebit[0]->rpD;
        // $prod->akunK = $akunkredit[0]->akunK;
        // $prod->rpK = $akunkredit[0]->rpK;
        
        
        return redirect('/jurnal')->with('msg', 'Akun Berhasil dibuat');
    }

    public function edit($id, $jurnalid, $idakun, $bukti, $tgl, $ktr, $tr)
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
        session(['Multiple' => true]);
        session(['editMode' => true]);
        session(['namaBkt' => $bukti]);
        session(['namaKtr' => $ktr]);
        session(['namaTgl' => $tgl]);
        session(['namaTr' => $tr]);
        session(['jurnalid' => $jurnalid]);
        session(['idjurnal' => $id]);
        
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
        // $jurnalpilihan = Jurnal::find($id);
        // $debitjurnal = json_decode($jurnalpilihan->debit);
        // $kreditjurnal = json_decode($jurnalpilihan->kredit);
        // foreach($debitjurnal as $d){
        //     if($d->id == $idakun){
        //         $dataj = $d;
        //     }
        // }
        // foreach($kreditjurnal as $d){
        //     if($d->id == $idakun){
        //         $dataj = $d;
        //     }
        // }

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

        if($idakun != 0){
            $datapilihan = JurnalAkun::find($idakun);
            if($datapilihan == null || ($datapilihan->keterangan != $ktr && $datapilihan->bukti != $bukti && $datapilihan->transaksi != $tr)){
                $datapilihan = JurnalAkunKredit::find($idakun);
            }
        }else{
            $datapilihan = new JurnalAkun();
            $datapilihan->tanggal = '';
            $datapilihan->keterangan = '';
            $datapilihan->bukti = '';
            $datapilihan->transaksi = '';

        }
        
        return view('Tambah_Input_Jurnal', [
            'title' => 'EDIT',
            'method' => 'PUT',
            'action' => "/$id/$jurnalid/$idakun/$bukti/$tgl/$ktr/$tr/updateJ",
            'methodModal' => 'POST',
            'actionModalKredit' => '/jTambahDataKredit',
            'dataMultipleDebit' => $dataMultipleDebit,
            'dataMultipleKredit' => $dataMultipleKredit,
            'dataKode' => $bukti,
            'dataJ' => Jurnal::find($id),
            'datapilihan' => $datapilihan,
            'dataDebit' => $dataDebit,
            'dataKredit' => $dataKredit
        ]);
    }
    public function update(Request $request, $id, $jurnalid, $idakun)
    {


        
        $prod = Jurnal::find($id);

        $datadebit = JurnalAkun::all();
        $akundebit = [];
        foreach($datadebit as $ad){
            if($ad->JurnalId == $jurnalid){
                $akundebit[] = $ad;
                if($ad->id == $idakun){
                    $ad->tanggal = $request->tanggal;
                    $ad->transaksi = $request->transaksi;
                    $ad->keterangan = $request->keterangan;
                    $ad->bukti = $request->bukti;
                    $ad->save();
                }
            }
        };
        $datakredit = JurnalAkunKredit::all();
        $akunkredit = [];
        foreach($datakredit as $ad){
            if($ad->JurnalId == $jurnalid){
                $akunkredit[] = $ad;
                if($ad->id == $idakun){
                    $ad->tanggal = $request->tanggal;
                    $ad->transaksi = $request->transaksi;
                    $ad->keterangan = $request->keterangan;
                    $ad->bukti = $request->bukti;
                    $ad->save();
                }
            }
        };

        // $prod->tanggal = $request->tanggal;
        // $prod->transaksi = $request->transaksi;
        // $prod->keterangan = $request->keterangan;
        // $prod->bukti = $request->bukti;
        if(session('jumlahDebit') > session('jumlahKredit')){
            $prod->jumlah = session('jumlahDebit');
        }else{
            $prod->jumlah = session('jumlahKredit');
        }
        $prod->debit = json_encode($akundebit); // Ubah menjadi JSON sebelum menyimpan
        $prod->kredit = json_encode($akunkredit);
        $prod->histori_saldo_debit = 0;
        $prod->histori_saldo_kredit = 0;

        $prod->save();

        session(['editMode' => true]);
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

    public function resetJurnal($jurnalid)
    {
        $data = COA::all();
        $akundebit = [];
        $akunkredit = [];
        foreach($data as $d){
            foreach(JurnalAkunKredit::where('jurnalid', $jurnalid)->get() as $k){
                if($d->Nama_akun == $k['akunK']){
                    $akunkredit[] = $d;
                }
            }
            foreach(JurnalAkun::where('jurnalid', $jurnalid)->get() as $de){
                if($d->Nama_akun == $de['akunD']){
                    $akundebit[] = $d;
                }
            }
        }

        foreach($akundebit as $ad){
            if($ad->keterangan == "Akun, Kredit"){
                foreach(JurnalAkun::where('jurnalid', $jurnalid)->get() as $p){
                    if($ad->Nama_akun == $p['akunD']){
                        $ad->jumlah_saldo = $ad->jumlah_saldo + $p['rpD'];
                    }
                }
            }else{
                foreach(JurnalAkun::where('jurnalid', $jurnalid)->get() as $p){
                    if($ad->Nama_akun == $p['akunD']){
                        $ad->jumlah_saldo = $ad->jumlah_saldo - $p['rpD'];
                    }
                }
            }
            $ad->save();
        }
        
        foreach($akunkredit as $ak){
            if($ak->keterangan == "Akun, Kredit"){
                foreach(JurnalAkunKredit::where('jurnalid', $jurnalid)->get() as $p){
                    if($ak->Nama_akun == $p['akunK']){
                        $ak->jumlah_saldo = $ak->jumlah_saldo + $p['rpK'];
                    }
                }                 
            }else{
                foreach(JurnalAkunKredit::where('jurnalid', $jurnalid)->get() as $p){
                    if($ak->Nama_akun == $p['akunK']){
                        $ak->jumlah_saldo = $ak->jumlah_saldo + $p['rpK'];
                    }
                }             
            }
            $ak->save();
        }

        // Hapus semua entri kredit yang terkait dengan jurnal ID
        JurnalAkunKredit::where('jurnalid', $jurnalid)->delete();

        // Hapus semua entri debit yang terkait dengan jurnal ID
        JurnalAkun::where('jurnalid', $jurnalid)->delete();

        if(session('editMode')){
            Jurnal::where('id', $jurnalid)->delete();
        }

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

        session(['jumlahDebit' => 0]);
        session(['jumlahKredit' => 0]);
        
        return redirect('/jTambahData')->with('msg', 'Data Jurnal Telah di Reset');
    }

    public function kembaliJurnal($jurnalid)
    {
        $data = COA::all();
        $akundebit = [];
        $akunkredit = [];
        foreach($data as $d){
            foreach(JurnalAkunKredit::where('jurnalid', $jurnalid)->get() as $k){
                if($d->Nama_akun == $k['akunK']){
                    $akunkredit[] = $d;
                }
            }
            foreach(JurnalAkun::where('jurnalid', $jurnalid)->get() as $de){
                if($d->Nama_akun == $de['akunD']){
                    $akundebit[] = $d;
                }
            }
        }

        foreach($akundebit as $ad){
            if($ad->keterangan == "Akun, Kredit"){
                foreach(JurnalAkun::where('jurnalid', $jurnalid)->get() as $p){
                    if($ad->Nama_akun == $p['akunD']){
                        $ad->jumlah_saldo = $ad->jumlah_saldo + $p['rpD'];
                    }
                }
            }else{
                foreach(JurnalAkun::where('jurnalid', $jurnalid)->get() as $p){
                    if($ad->Nama_akun == $p['akunD']){
                        $ad->jumlah_saldo = $ad->jumlah_saldo - $p['rpD'];
                    }
                }
            }
            $ad->save();
        }
        
        foreach($akunkredit as $ak){
            if($ak->keterangan == "Akun, Kredit"){
                foreach(JurnalAkunKredit::where('jurnalid', $jurnalid)->get() as $p){
                    if($ak->Nama_akun == $p['akunK']){
                        $ak->jumlah_saldo = $ak->jumlah_saldo + $p['rpK'];
                    }
                }                 
            }else{
                foreach(JurnalAkunKredit::where('jurnalid', $jurnalid)->get() as $p){
                    if($ak->Nama_akun == $p['akunK']){
                        $ak->jumlah_saldo = $ak->jumlah_saldo + $p['rpK'];
                    }
                }             
            }
            $ak->save();
        }

        // Hapus semua entri kredit yang terkait dengan jurnal ID
        JurnalAkunKredit::where('jurnalid', $jurnalid)->delete();

        // Hapus semua entri debit yang terkait dengan jurnal ID
        JurnalAkun::where('jurnalid', $jurnalid)->delete();

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

        session(['jumlahDebit' => 0]);
        session(['jumlahKredit' => 0]);
        
        return redirect('/jurnal')->with('msg', 'Data Jurnal Telah di Reset');
    }

    public function getData($perPage)
    {
        return Jurnal::paginate($perPage);
    }

    public function export()
    {
        return Excel::download(new ExportJurnal, 'jurnals'.Carbon::now()->timestamp.'.xlsx');
    }

    public function import(Request $request)
    {
        // dd($request->file('file'));
        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);
        DB::statement('ALTER TABLE jurnals DISABLE KEYS');
        DB::statement('ALTER TABLE jurnal_akuns DISABLE KEYS');
        DB::statement('ALTER TABLE jurnal_akun_kredits DISABLE KEYS');

        Excel::import(new JurnalsImport, $request->file('file'));

        DB::statement('ALTER TABLE jurnals ENABLE KEYS');
        DB::statement('ALTER TABLE jurnal_akuns ENABLE KEYS');
        DB::statement('ALTER TABLE jurnal_akun_kredits ENABLE KEYS');


        return back()->with('success', 'File Excel berhasil diimpor.');
    }
}
