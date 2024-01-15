<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\COA;

class COAController extends Controller
{
    public function index(){
        $data =COA::all();
        return view("Lihat_Data", compact('data'));
    }

    public function create()
    {
        return view('Tambah_Data_COA', [
            'method' => 'POST',
            'action' => '/cStore'
        ]);
    }
    public function store(Request $request)
    {
        $prod = new COA;
        $prod->jenis_akun = $request->jenis_akun;
        $prod->kelompok_akun = $request->kelompok_akun;
        $prod->keterangan = $request->keterangan;
        $prod->kode = $request->kode;
        $prod->Nama_akun = $request->Nama_akun;
        $prod->Saldo_awal = $request->Saldo_awal;
        $prod->save();
        return redirect('/')->with('msg', 'Akun Berhasil dibuat');
    }

}
