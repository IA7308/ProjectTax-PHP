<?php

namespace App\Http\Controllers;

use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use \App\Models\COA;

class COAController extends Controller
{
    public function index(){
        $perPage = request('pagination', 5);
        
        if (strtolower($perPage) == 'all') {
            $data = COA::paginate();
        }else{
            $data = (new COAController)->getData($perPage);
        }

        return view('Lihat_Data', compact('data'));
    }

    public function create()
    {
        return view('Tambah_Data_COA', [
            'title' => 'TAMBAH',
            'method' => 'POST',
            'action' => '/cStore'
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
            return redirect()->back()->with('error', 'Kode Duplikat');
        }

        $prod->kode = $request->kode;
        $prod->Nama_akun = $request->Nama_akun;
        $prod->Saldo_awal = $request->Saldo_awal;
        $prod->save();
        return redirect('/')->with('msg', 'Akun Berhasil dibuat');
    }

    public function edit($id)
    {
        return view('Tambah_Data_COA', [
            'title' => 'EDIT',
            'method' => 'PUT',
            'action' => "/$id/update",
            'data' => COA::find($id)
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
        $prod->save();
        return redirect('/')->with('msg', 'Edit berhasil');
    }
    public function destroy($id)
    {
        COA::destroy($id);
        return redirect('/')->with('msg', 'Hapus berhasil');
    }

    protected $table = 'tabelCOA'; // Ganti dengan nama tabel sebenarnya

    public function getData($perPage)
    {
        return COA::paginate($perPage);
    }

}
