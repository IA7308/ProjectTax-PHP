<?php

namespace App\Http\Controllers;

use App\Models\bukubesar;
use App\Models\COA;
use App\Models\Jurnal;
use Carbon\Exceptions\EndLessPeriodException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BukBesController extends Controller
{
    public function index(){
        $perPage = request('pagination', 1000);
        $temp = COA::all();
            $dataC = [];
            foreach($temp as $t){
                if($t->keterangan == "Akun, Kredit" || $t->keterangan == "Akun, Debit"){
                    $dataC[] = $t;
                }
            }
            usort($dataC, function ($a, $b) {
                return $a->kode <=> $b->kode;
            });
            
        session(['pilihC' => false]);
        session(['paginate' => false]);
        if (strtolower($perPage) == 'all') {
            session(['paginate' => false]);
            $temp = COA::all();
            $dataC = [];
            foreach($temp as $t){
                if($t->keterangan == "Akun, Kredit" || $t->keterangan == "Akun, Debit"){
                    $dataC[] = $t;
                }
            }
            usort($dataC, function ($a, $b) {
                return $a->kode <=> $b->kode;
            });

            $data = $dataC;
            
        }else{
            $data = (new JurnalController)->getData($perPage);
        }
        return view("BukuBesar", ['dataC' => $dataC, 'data' => $data]);
    }
    public function getData($perPage)
    {
        return Jurnal::paginate($perPage);
    }

    public function show($id){
        session(['pilihC' => true]);
        session(['paginate' => true]);

        $requestedPerPage = request('pagination', 1000); // Default to 10 items per page
        $perPage = is_numeric($requestedPerPage) ? intval($requestedPerPage) : $requestedPerPage;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $temp = COA::all();
        $dataC = [];
        foreach($temp as $t) {
            if ($t->keterangan == "Akun, Kredit" || $t->keterangan == "Akun, Debit") {
                $dataC[] = $t;
            }
        }

        $akunCOA = COA::find($id);
        $data = [];

        // Only get journal entries related to the specific COA account
        Jurnal::chunk(100, function($jurnal) use (&$data, $akunCOA) {
            foreach ($jurnal as $item) {
                $item->debit = json_decode($item->debit); 
                $item->kredit = json_decode($item->kredit);

                foreach ($item->debit as $j) {
                    if ($j['akunD'] == $akunCOA->Nama_akun) {
                        $bukudata = new bukubesar;
                        $bukudata->tanggal = $j['tanggal'];
                        $bukudata->transaksi = $j['transaksi'];
                        $bukudata->keterangan = $j['keterangan'];
                        $bukudata->bukti = $j['bukti'];
                        $bukudata->rpD = $j['rpD'];
                        $bukudata->rpK = 0;
                        $data[] = $bukudata;
                    }
                }

                foreach ($item->kredit as $j) {
                    if ($j['akunK'] == $akunCOA->Nama_akun) {
                        $bukudata = new bukubesar;
                        $bukudata->tanggal = $j['tanggal'];
                        $bukudata->transaksi = $j['transaksi'];
                        $bukudata->keterangan = $j['keterangan'];
                        $bukudata->bukti = $j['bukti'];
                        $bukudata->rpD = 0;
                        $bukudata->rpK = $j['rpK'];
                        $data[] = $bukudata;
                    }
                }
            }
        });

        usort($data, function ($a, $b) {
            return strtotime($a->tanggal) - strtotime($b->tanggal);
        });

        for ($i = 0; $i < count($data); $i++) {
            if ($i == 0) {
                if ($akunCOA->keterangan == "Akun, Debit") {
                    $data[$i]->histori_saldo = $akunCOA->Saldo_awal + $data[$i]->rpD - $data[$i]->rpK;
                } else {
                    if ($akunCOA->Saldo_awal < 0) {
                        $akunCOA->Saldo_awal *= -1;
                    }
                    if ($akunCOA->jumlah_saldo < 0) {
                        $akunCOA->jumlah_saldo *= -1;
                    }
                    $data[$i]->histori_saldo = $akunCOA->Saldo_awal - $data[$i]->rpD + $data[$i]->rpK;
                }
            } else {
                if ($akunCOA->keterangan == "Akun, Debit") {
                    $data[$i]->histori_saldo = $data[$i - 1]->histori_saldo + $data[$i]->rpD - $data[$i]->rpK;
                } else {
                    $data[$i]->histori_saldo = $data[$i - 1]->histori_saldo - $data[$i]->rpD + $data[$i]->rpK;
                }
            }
        }

        // Convert $
        $dataCollection = collect($data);

        // Determine the number of items per page
        if (strtolower($perPage) == 'all') {
            $perPage = $dataCollection->count();
        }

        $currentPageData = $dataCollection->slice(($currentPage - 1) * $perPage, $perPage)->values();

        // Create LengthAwarePaginator instance
        $paginatedData = new LengthAwarePaginator(
            $currentPageData,
            $dataCollection->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return view("BukuBesar", [
            'dataC' => $dataC,
            'dataPilih' => $akunCOA,
            'data' => $paginatedData,
        ]);
    }
}
