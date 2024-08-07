<?php

namespace App\Imports;

use App\Models\COA;
use App\Models\Jurnal;
use App\Models\JurnalAkun;
use App\Models\JurnalAkunKredit;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use phpoffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JurnalsImport implements ToModel, WithHeadingRow, WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Check if Jurnal already exists or create a new one
        $jurnal = Jurnal::firstOrCreate(
            // Assuming you have a unique identifier for Jurnal, e.g., 'tanggal' and 'transaksi'
            ['JurnalId' => $row['jurnalid'] ?? 1,],
            [
                'debit' => json_encode([]),
                'kredit' => json_encode([]),
                'jumlah' => 0, // Jumlah awal, akan diperbarui kemudian
                'histori_saldo_debit' => 0,
                'histori_saldo_kredit' => 0,
            ]
        );

        $totalrpd = $jurnal->jumlah ?? 0;
        $totalrpk = $jurnal->jumlah ?? 0;
        
        if (isset($row['tanggaljurnal'])) {
            $date = Carbon::instance(ExcelDate::excelToDateTimeObject($row['tanggaljurnal']))->format('Y-m-d');
        } else {
            throw new Exception("Key 'tanggal' not found in the array \$row");
        }

        // Process debit entries
        $debitEntry = new JurnalAkun([
            'tanggal' => $date,
            'transaksi' => $row['transaksi'],
            'keterangan' => $row['keterangan'],
            'bukti' => $row['bukti'],
            'akunD' => $row['akundebit'],
            'rpD' => $row['saldodebit'],
            'histori_saldo_debit' => 0,
            'JurnalId' => $row['jurnalid']
        ]);
        Log::info('Debit Entry:', ['akunD' => $debitEntry->akunD]);

        $akunD = COA::where('Nama_akun', $debitEntry->akunD)->first();
        if($akunD->keterangan == "Akun, Kredit"){
            $akunD->jumlah_saldo = $akunD->jumlah_saldo + $debitEntry->rpD;
        }else{
            $akunD->jumlah_saldo = $akunD->jumlah_saldo + $debitEntry->rpD;
        }
        $debitEntry->histori_saldo_debit = $akunD->jumlah_saldo;
        $akunD->save();
        $debitEntry->save();
        $totalrpd += $row['saldodebit'];

        // Process kredit entries
        $kreditEntry = new JurnalAkunKredit([
            'tanggal' => $date,
            'transaksi' => $row['transaksi'],
            'keterangan' => $row['keterangan'],
            'bukti' => $row['bukti'],
            'akunK' => $row['akunkredit'],
            'rpK' => $row['saldokredit'],
            'histori_saldo_kredit' => 0,
            'JurnalId' => $row['jurnalid']
        ]);
        Log::info('Kredit Entry:', ['akunK' => $kreditEntry->akunK]);
        $akunK = COA::where('Nama_akun', $kreditEntry->akunK)->first();
        if($akunK->keterangan == "Akun, Kredit"){
            $akunK->jumlah_saldo = $akunK->jumlah_saldo - $kreditEntry->rpK; 
        }else{
            $akunK->jumlah_saldo = $akunK->jumlah_saldo - $kreditEntry->rpK;
        }
        $kreditEntry->histori_saldo_kredit = $akunK->jumlah_saldo;
        $akunK->save();
        $kreditEntry->save();
        $totalrpk += $row['saldokredit'];

        // Add entries to Jurnal's debit and kredit arrays
        $debits = json_decode($jurnal->debit) ?? [];
        $debits[] = $debitEntry;
        $jurnal->debit = json_encode($debits);

        $kredits = json_decode($jurnal->kredit) ?? [];
        $kredits[] = $kreditEntry;
        $jurnal->kredit = json_encode($kredits);
        if($totalrpd > $totalrpk){
            $jurnal->jumlah = $totalrpd;
        }else{
            $jurnal->jumlah = $totalrpk;
        }
        $jurnal->JurnalId = $row['jurnalid'];
        $jurnal->histori_saldo_debit = 0;
        $jurnal->histori_saldo_kredit = 0;

        $jurnal->save();
    }
    public function chunkSize(): int
    {
        return 5000; // Set chunk size according to your need
    }

}
