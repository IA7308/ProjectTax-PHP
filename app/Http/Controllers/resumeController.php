<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\COA;
use App\Models\resumeBarang;
use Illuminate\Http\Request;

class resumeController extends Controller
{
    public function index(){
        $perPage = strtolower(request('pagination', 'all'));
        session(['paginate' => true]);
        $saldo = 0;
        

        
        
        if (strtolower($perPage) == 'all') {
            session(['paginate' => false]);
            $arrayBarangMasuk=[];
            $arrayBarangKeluar=[];
            $data = resumeBarang::all();
            $barang_masuk= Barang::all();
            $barang_keluar= BarangKeluar::all();
            foreach ($data as $d) {
                $countin=0;
                $countout=0;
                foreach($barang_masuk as $bm){
                    if ($d->kode_barang == $bm->kode_barang){
                        $countin=+$bm->unit_keluar;
                    }
                }
                $arrayBarangMasuk[]=$countin;
                foreach($barang_keluar as $bk){
                    if ($d->kode_barang == $bk->kode_barang){
                        $countout=-$bk->unit_keluar;
                    }
                }
                $arrayBarangKeluar[]=$countout;
            }
            // foreach ($data as $d) {
            //     $saldo += $d->Saldo_awal;
            //     if ($d->keterangan == 'Header') {
            //         $d->backgroundClass = 'table-info';
            //     } elseif ($d->keterangan == 'Jumlah'||$d->keterangan == 'Total') {
            //         $d->backgroundClass = 'table-secondary';
            //     }elseif ($d->keterangan == 'Akun, Kredit') {
            //         $d->backgroundClass = 'table-warning';
            //     } else {
            //         $d->backgroundClass = ''; // Kosongkan jika tidak ada keterangan tertentu
            //     }
            //     if($d->Saldo_awal < 0){
            //         $d->backgroundCell = 'table-danger';
            //     }else{
            //         $d->backgroundCell = '';
            //     }
            // }
            session(['saldo' => $saldo]);
        }else{
            $data = (new COAController)->getData($perPage);
            // foreach ($data as $d) {
            //     $saldo += $d->Saldo_awal;
            //     if ($d->keterangan == 'Header') {
            //         $d->backgroundClass = 'table-info';
            //     } elseif ($d->keterangan == 'Jumlah'||$d->keterangan == 'Total') {
            //         $d->backgroundClass = 'table-secondary';
            //     }elseif ($d->keterangan == 'Akun, Kredit') {
            //         $d->backgroundClass = 'table-warning';
            //     } else {
            //         $d->backgroundClass = ''; // Kosongkan jika tidak ada keterangan tertentu
            //     }
            //     if($d->Saldo_awal < 0){
            //         $d->backgroundCell = 'table-danger';
            //     }else{
            //         $d->backgroundCell = '';
            //     }
            // }
            session(['saldo' => $saldo]);
        }

        return view('Resume', [
            'data' => $data,
            'barang_keluar' => $arrayBarangKeluar,
            'barang_masuk' => $arrayBarangMasuk
        ]);
    }

    public function store(Request $request){
        $prod = new resumeBarang;
        $prod->kode_barang = $request->kode_barang;
        $prod->nama_barang = $request->nama_barang;
        $prod->stock_awal = $request->stock_awal;
        $prod->harga_masuk = $request->harga_masuk;
        $prod->harga_keluar = $request->harga_keluar;
        $prod->stock_akhir = $request->stock_awal;
        $prod->save();
        return back();
    }

    public function destroy($id){
        resumeBarang::destroy($id);
        return redirect('/resume');
    }
}
