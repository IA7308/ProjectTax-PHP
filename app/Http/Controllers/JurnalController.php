<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use Illuminate\Http\Request;

class JurnalController extends Controller
{
    public function index(){
        $data =Jurnal::all();
        return view("Lihat_Data_Jurnal", compact('data'));
    }

    public function create()
    {
        return view('Tambah_Input_Jurnal', [
            'method' => 'POST',
            'action' => '/jStore'
        ]);
    }
    public function store(Request $request)
    {
        $prod = new Jurnal;
        $prod->tanggal = $request->tanggal;
        $prod->transaksi = $request->transaksi;
        $prod->keterangan = $request->keterangan;
        $prod->bukti = $request->bukti;
        $prod->jumlah = $request->jumlah;
        $prod->akunD = $request->akunD;
        $prod->rpD = $request->rpD;
        $prod->akunK = $request->akunK;
        $prod->rpK = $request->rpK;
        $prod->save();
        return redirect('/jurnal')->with('msg', 'Akun Berhasil dibuat');
    }
}
