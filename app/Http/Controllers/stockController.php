<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\COA;
use App\Models\Jurnal;
use App\Models\JurnalAkun;
use App\Models\JurnalAkunKredit;
use Illuminate\Http\Request;

class stockController extends Controller
{
    public function index(){
        $perPage = strtolower(request('pagination', 'all'));
        session(['paginate' => true]);
        $saldo = 0;
        $temp = Barang::all();
        $dataC = [];
            foreach($temp as $t){
                if($t->keterangan == "Akun, Kredit" || $t->keterangan == "Akun, Debit"){
                    $dataC[] = $t;
                }
            }
        if($dataC == []){
            session(['idDataterpilih' => '#']);
        }else{
            session(['idDataterpilih' => $dataC[0]->id]);
        }
        
        
        if (strtolower($perPage) == 'all') {
            session(['paginate' => false]);
            $data = Barang::all();
            foreach ($data as $d) {
                $saldo += $d->Saldo_awal;
                if($d->unit_keluar < 0){
                    $d->backgroundCell = 'table-danger';
                }else{
                    $d->backgroundCell = '';
                }
            }
            session(['saldo' => $saldo]);
        }else{
            $data = (new COAController)->getData($perPage);
            foreach ($data as $d) {
                $saldo += $d->Saldo_awal;
                if($d->unit_keluar < 0){
                    $d->backgroundCell = 'table-danger';
                }else{
                    $d->backgroundCell = '';
                }
            }
            session(['saldo' => $saldo]);
        }
        $nama_penjual = [];
        $dataCOA = COA::orderBy('kode', 'asc')->get();
        foreach($dataCOA as $d){
            if($d->keterangan == "Akun, Debit" || $d->keterangan == "Akun, Kredit"){
                $nama_penjual[] = $d;
            }
        }

        return view('BarangMasuk', [
            'data' => $data, 
            'title' => 'MASUK',
            'method' => 'POST',
            'action' => '/bStore',
            'actionDelete' => '/bDelete',
            'editAction' => '/bEdit',
            'nama_penjual' => $nama_penjual,
        ]);
    }

    public function indexBarangKeluar(){
        $perPage = strtolower(request('pagination', 'all'));
        session(['paginate' => true]);
        $saldo = 0;
        $temp = Barang::all();
        $dataC = [];
            foreach($temp as $t){
                if($t->keterangan == "Akun, Kredit" || $t->keterangan == "Akun, Debit"){
                    $dataC[] = $t;
                }
            }
        if($dataC == []){
            session(['idDataterpilih' => '#']);
        }else{
            session(['idDataterpilih' => $dataC[0]->id]);
        }
        
        
        if (strtolower($perPage) == 'all') {
            session(['paginate' => false]);
            $data = BarangKeluar::all();
            foreach ($data as $d) {
                $saldo += $d->Saldo_awal;
                if($d->unit_keluar < 0){
                    $d->backgroundCell = 'table-danger';
                }else{
                    $d->backgroundCell = '';
                }
            }
            session(['saldo' => $saldo]);
        }else{
            $data = (new COAController)->getData($perPage);
            foreach ($data as $d) {
                $saldo += $d->Saldo_awal;
                if($d->unit_keluar < 0){
                    $d->backgroundCell = 'table-danger';
                }else{
                    $d->backgroundCell = '';
                }
            }
            session(['saldo' => $saldo]);
        }
        $nama_penjual = [];
        $dataCOA = COA::orderBy('kode', 'asc')->get();
        foreach($dataCOA as $d){
            if($d->keterangan == "Akun, Debit" || $d->keterangan == "Akun, Kredit"){
                $nama_penjual[] = $d;
            }
        }

        return view('BarangMasuk', [
            'data' => $data,
            'title' => 'KELUAR', 
            'method' => 'POST',
            'action' => '/bOutStore',
            'actionDelete' => 'bOutDelete',
            'editAction' => '/bEdit',
            'nama_penjual' => $nama_penjual,
        ]);
    }

    public function store(Request $request){

        // Jurnal

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

        $prod->jumlah = $request->harga * $request->unit_masuk;
        $prod->debit = json_encode($akundebit); // Ubah menjadi JSON sebelum menyimpan
        $prod->kredit = json_encode($akunkredit);
        $prod->JurnalId = session('jurnalid');
        $prod->histori_saldo_debit = 0;
        $prod->histori_saldo_kredit = 0;
        
        $prod->save();

        // Barang

        // $nama_penjual = COA::find($request->akunD);
        


        $prod = new Barang;
        $prod->tanggal = $request->tanggal;
        $prod->nama_penjual = $request->nama_penjual;
        $prod->keterangan = $request->keterangan;
        $prod->kode_barang = $request->kode_barang;
        $prod->nama_barang = $request->nama_barang;
        $prod->unit_keluar =+ $request->unit_masuk;
        $prod->harga = $request->harga;
        $prod->JurnalId = session('jurnalid');

        $prod->save();
        return redirect('/stock');
    }

    public function storeBarangKeluar(Request $request){
        // Jurnal

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

        $prod->jumlah = $request->harga * $request->unit_masuk;
        $prod->debit = json_encode($akundebit); // Ubah menjadi JSON sebelum menyimpan
        $prod->kredit = json_encode($akunkredit);
        $prod->JurnalId = session('jurnalid');
        $prod->histori_saldo_debit = 0;
        $prod->histori_saldo_kredit = 0;
        
        $prod->save();

        //barang

        // $nama_penjual = COA::find($request->nama_penjual);
        $items = Barang::find($request->nama_barang);

        $prod = new BarangKeluar();
        $prod->tanggal = $request->tanggal;
        $prod->nama_penjual = $request->nama_penjual;
        $prod->keterangan = $request->keterangan;
        $prod->kode_barang = $request->kode_barang;
        $prod->nama_barang = $items->nama_barang;
        $prod->unit_keluar = $request->unit_keluar;
        $prod->harga = $request->harga;

        $prod->save();

        return back();
    }

    public function delete($id){
        $items = Barang::find($id);
        $jurnalController = new JurnalController();
        $jurnalController->destroy($items->JurnalId);
        Barang::destroy($id);
        return redirect('/stock');
    }

    public function deleteBarangKeluar($id){
        $items = BarangKeluar::find($id);
        $jurnalController = new JurnalController();
        $jurnalController->destroy($items->JurnalId);
        $barang = Barang::find($items->nama_barang, ['nama_barang']);
        $barang->unit_keluar =+ $items->unit_keluar;
        BarangKeluar::destroy($id);
        return back();
    }

    public function createbarangmasuk(){
        $dataDebit = [];
        $dataKredit= [];
        $dataMultipleD = JurnalAkun::all();
        $dataMultipleK = JurnalAkunKredit::all();
        $dataJurnal = Jurnal::all();
        $dataMultipleDebit = [];
        $dataMultipleKredit = [];
        $dataCOA = COA::orderBy('kode', 'asc')->get();
        $dataBarang = Barang::all();

        foreach($dataCOA as $d){
            if($d->keterangan == "Akun, Debit" || $d->keterangan == "Akun, Kredit"){
                if(stripos($d->Nama_akun, 'persd') === 0){
                    $dataDebit[] = $d;
                }
                $dataKredit[] = $d;
            }
        }
        
        $idjurnal = 0;
        if(empty($dataJurnal)){  
            session(['jurnalid' => $idjurnal+1]);
        }else{
            foreach($dataJurnal as $dj){
                if($dj->JurnalId > $idjurnal){
                    $idjurnal = $dj->JurnalId;
                }
            }
            session(['jurnalid' => $idjurnal+1]);
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

        session(['jumlahJurnal' => 0]);
        session(['jumlahDebit' => 0]);
        session(['jumlahKredit' => 0]);

        return view('halamanJurnalBaru', [
            'title' => 'TAMBAH',
            'method' => 'POST',
            'methodModal' => 'POST',
            'action' => '/bStore',
            'actionModalKredit' => '/jTambahDataKredit',
            'dataDebit' => $dataDebit,
            'dataKredit' => $dataKredit,
            'dataMultipleDebit' => $dataMultipleDebit,
            'dataMultipleKredit' => $dataMultipleKredit,
            'dataBarang' => $dataBarang,]);
    }

    public function createbarangkeluar(){
        $dataDebit = [];
        $dataKredit= [];
        $dataMultipleD = JurnalAkun::all();
        $dataMultipleK = JurnalAkunKredit::all();
        $dataJurnal = Jurnal::all();
        $dataMultipleDebit = [];
        $dataMultipleKredit = [];
        $dataCOA = COA::orderBy('kode', 'asc')->get();
        $dataBarang = Barang::all();

        foreach($dataCOA as $d){
            if($d->keterangan == "Akun, Debit" || $d->keterangan == "Akun, Kredit"){
                if(stripos($d->Nama_akun, 'persd') === 0){
                    $dataDebit[] = $d;
                }
                $dataKredit[] = $d;
            }
        }
        
        $idjurnal = 0;
        if(empty($dataJurnal)){  
            session(['jurnalid' => $idjurnal+1]);
        }else{
            foreach($dataJurnal as $dj){
                if($dj->JurnalId > $idjurnal){
                    $idjurnal = $dj->JurnalId;
                }
            }
            session(['jurnalid' => $idjurnal+1]);
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

        session(['jumlahJurnal' => 0]);
        session(['jumlahDebit' => 0]);
        session(['jumlahKredit' => 0]);
        
        return view('halamanJurnalKeluar', [
            'title' => 'TAMBAH',
            'method' => 'POST',
            'methodModal' => 'POST',
            'action' => '/bStore',
            'actionModalKredit' => '/jTambahDataKredit',
            'dataDebit' => $dataDebit,
            'dataKredit' => $dataKredit,
            'dataMultipleDebit' => $dataMultipleDebit,
            'dataMultipleKredit' => $dataMultipleKredit,
            'dataBarang' => $dataBarang,]);
    }

    public function createBM($jurnalid)
    {
        $dataDebit = [];
        $dataKredit= [];
        $dataMultipleD = JurnalAkun::all();
        $dataMultipleK = JurnalAkunKredit::all();
        $dataMultipleDebit = [];
        $dataMultipleKredit = [];
        $dataCOA = COA::orderBy('kode', 'asc')->get();
        $dataBarang = Barang::all();
        $jumlahDebit = 0;
        $jumlahJurnal = 0;
        $jumlahKredit = 0;

        foreach($dataCOA as $d){
            if($d->keterangan == "Akun, Debit" || $d->keterangan == "Akun, Kredit"){
                if(stripos($d->Nama_akun, 'persd') === 0){
                    $dataDebit[] = $d;
                }
                $dataKredit[] = $d;
            }
        }
        
        session(['jurnalid' => $jurnalid]);
        
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

        return view('halamanJurnalBaru', [
            'title' => 'TAMBAH',
            'method' => 'POST',
            'methodModal' => 'POST',
            'action' => '/bStore',
            'actionModalKredit' => '/jTambahDataKredit',
            'dataDebit' => $dataDebit,
            'dataKredit' => $dataKredit,
            'dataMultipleDebit' => $dataMultipleDebit,
            'dataMultipleKredit' => $dataMultipleKredit,
            'dataBarang' => $dataBarang,]);
    }

    public function edit($id){
        $dataDebit = [];
        $dataKredit= [];
        $dataMultipleD = JurnalAkun::all();
        $dataMultipleK = JurnalAkunKredit::all();
        $dataMultipleDebit = [];
        $dataMultipleKredit = [];
        $dataCOA = COA::orderBy('kode', 'asc')->get();
        $dataBarang = Barang::all();
        $jumlahDebit = 0;
        $jumlahJurnal = 0;
        $jumlahKredit = 0;
        $data = Barang::find($id);
        $datapilihan = Jurnal::find($data->JurnalId);
        $jurnalid = $data->JurnalId;

        foreach($dataCOA as $d){
            if($d->keterangan == "Akun, Debit" || $d->keterangan == "Akun, Kredit"){
                if(stripos($d->Nama_akun, 'persd') === 0){
                    $dataDebit[] = $d;
                }
                $dataKredit[] = $d;
            }
        }
        
        session(['jurnalid' => $jurnalid]);
        
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

        return view('halamanJurnalBaru', [
            'title' => 'EDIT',
            'method' => 'POST',
            'methodModal' => 'POST',
            'action' => '/bStore',
            'actionModalKredit' => '/jTambahDataKredit',
            'dataDebit' => $dataDebit,
            'dataKredit' => $dataKredit,
            'dataMultipleDebit' => $dataMultipleDebit,
            'dataMultipleKredit' => $dataMultipleKredit,
            'dataBarang' => $dataBarang,
            'data' => $data,
            'datapilihan' => $datapilihan
        ]);
    }

    public function updateBM(Request $request, $id){
        $debit = [];
        $items = Barang::find($id);
        $jurnal = Jurnal::find($items->JurnalId);
        $jurnalController = new JurnalController();
        $debit = json_decode($jurnal->debit);
        
        $items->tanggal = $request->tanggal;
        $items->nama_penjual = $request->nama_penjual;
        $items->keterangan = $request->keterangan;
        $items->kode_barang = $request->kode_barang;
        $items->nama_barang = $request->nama_barang;
        $items->unit_keluar = $request->unit_masuk;
        $items->harga = $request->harga;
        $items->JurnalId = session('jurnalid');

        $items->save();

        $jurnalController->update($request, $jurnal->id, $items->JurnalId, $debit[0]->id);

        return redirect('/stock');
    }

}

