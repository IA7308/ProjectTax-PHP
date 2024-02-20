<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use \App\Models\COA;

class COAController extends Controller
{
    public function index(){
        $perPage = strtolower(request('pagination', 'all'));
        session(['paginate' => true]);
        $saldo = 0;
        $temp = COA::all();
        $dataC = [];
            foreach($temp as $t){
                if($t->keterangan == "Akun, Kredit" || $t->keterangan == "Akun, Debit"){
                    $dataC[] = $t;
                }
            }
        if($dataC != []){
            session(['idDataterpilih' => '#']);
        }else{
            session(['idDataterpilih' => $dataC[0]->id]);
        }
        
        
        if (strtolower($perPage) == 'all') {
            session(['paginate' => false]);
            $data = COA::all();
            foreach ($data as $d) {
                $saldo += $d->Saldo_awal;
                if ($d->keterangan == 'Header') {
                    $d->backgroundClass = 'table-info';
                } elseif ($d->keterangan == 'Jumlah'||$d->keterangan == 'Total') {
                    $d->backgroundClass = 'table-secondary';
                }elseif ($d->keterangan == 'Akun, Kredit') {
                    $d->backgroundClass = 'table-warning';
                } else {
                    $d->backgroundClass = ''; // Kosongkan jika tidak ada keterangan tertentu
                }
                if($d->Saldo_awal < 0){
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
                if ($d->keterangan == 'Header') {
                    $d->backgroundClass = 'table-info';
                } elseif ($d->keterangan == 'Jumlah'||$d->keterangan == 'Total') {
                    $d->backgroundClass = 'table-secondary';
                }elseif ($d->keterangan == 'Akun, Kredit') {
                    $d->backgroundClass = 'table-warning';
                } else {
                    $d->backgroundClass = ''; // Kosongkan jika tidak ada keterangan tertentu
                }
                if($d->Saldo_awal < 0){
                    $d->backgroundCell = 'table-danger';
                }else{
                    $d->backgroundCell = '';
                }
            }
            session(['saldo' => $saldo]);
        }

        return view('Lihat_Data', compact('data'));
    }

    public function create()
    {
        $data = COA::all();
        $kode = [];
        foreach($data as $d){
            $kode[] = $d->kode;
        }
        return view('Tambah_Data_COA', [
            'title' => 'TAMBAH',
            'method' => 'POST',
            'action' => '/cStore',
            'dataKode' => $kode
        ]);
    }
    public function store(Request $request)
    {
        $data = COA::all();
        $prod = new COA;
        $prod->jenis_akun = $request->jenis_akun;
        $prod->kelompok_akun = $request->kelompok_akun;
        $prod->keterangan = $request->keterangan;
        $kodeDuplikat = false;
        foreach ($data as $d) {
            if ($request->kode == $d->kode) {
                $kodeDuplikat = true;
                break;
            }
        }

        if ($kodeDuplikat) {
            return redirect()->back()->with('error', 'KODE DUPLIKAT');
        }

        $prod->kode = $request->kode;
        $prod->Nama_akun = $request->Nama_akun;
        if ($request->Saldo_awal == null){
            $prod->Saldo_awal = 0;
        } else {
            $prod->Saldo_awal = $request->Saldo_awal;
        }
        // $prod->Saldo_awal = $request->Saldo_awal;
        if ($request->Saldo_awal == null){
            $prod->jumlah_saldo = 0;
        } else {
            $prod->jumlah_saldo = $request->Saldo_awal;
        }
        // $prod->jumlah_saldo = $request->Saldo_awal;
        $prod->save();
        return redirect('/beranda')->with('msg', 'Akun Berhasil dibuat');
    }

    public function edit($id)
    {
        $data = COA::all();
        $kode = [];
        foreach($data as $d){
            $kode[] = $d->kode;
        }
        return view('Tambah_Data_COA', [
            'title' => 'EDIT',
            'method' => 'PUT',
            'action' => "/$id/update",
            'data' => COA::find($id),
            'dataKode' => $kode
        ]);
    }
    public function update(Request $request, $id)
    {
        $prod = COA::find($id);
        $prod->jenis_akun = $request->jenis_akun;
        $prod->kelompok_akun = $request->kelompok_akun;
        $prod->keterangan = $request->keterangan;
        $prod->kode = $request->kode;
        $prod->Nama_akun = $request->Nama_akun;
        $prod->Saldo_awal = $request->Saldo_awal;
        //$prod->jumlah_saldo = $prod->jumlah_saldo - $request->Saldo_awal;
        
        $dataJ = Jurnal::all();
        $data = [];
        foreach($dataJ as $d){
            if($d->akunD == $request->Nama_akun || $d->akunK == $request->Nama_akun){
                $data[] = $d;
            }
        }
        if($data == []){
            $prod->jumlah_saldo = $request->Saldo_awal;
        }else{
            $totalSaldoJurnal = 0;
            foreach($data as $d){
                if($d->akunD == $request->Nama_akun){
                    $totalSaldoJurnal = $totalSaldoJurnal + $d->rpD;
                }elseif($d->akunK == $request->Nama_akun){
                    $totalSaldoJurnal = $totalSaldoJurnal - $d->rpK;
                }
            }
            $prod->jumlah_saldo = $request->Saldo_awal + $totalSaldoJurnal;
        }

        $prod->save();
        return redirect('/beranda')->with('msg', 'Edit berhasil');
    }
    public function destroy($id)
    {
        COA::destroy($id);
        return redirect('/beranda')->with('msg', 'Hapus berhasil');
    }

    protected $table = 'tabelCOA'; // Ganti dengan nama tabel sebenarnya

    public function getData($perPage)
    {
        return COA::paginate($perPage);
    }

    
}
