<?php

namespace App\Imports;

use App\Models\debitPenyesuaian;
use App\Models\kreditPenyesuaian;
use App\Models\penyesuaian;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use phpoffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class penyesuaianImport implements ToModel, WithHeadingRow, WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        DB::transaction(function () use ($row) {
            // Check if Jurnal already exists or create a new one
            $penyesuaian = penyesuaian::firstOrCreate(
                ['penyesuaianid' => $row['penyesuaianid'] ?? 1],
                [
                    'debit' => json_encode([]),
                    'kredit' => json_encode([]),
                    'jumlah' => 0, // Jumlah awal, akan diperbarui kemudian
                    'histori_saldo_debit' => 0,
                    'histori_saldo_kredit' => 0,
                ]
            );
        
            // $totalrpd = $penyesuaian->jumlah ?? 0;
            // $totalrpk = $penyesuaian->jumlah ?? 0;
        
            if (isset($row['tanggalpenyesuaian'])) {
                $date = Carbon::instance(ExcelDate::excelToDateTimeObject($row['tanggalpenyesuaian']))->format('Y-m-d');
            } else {
                throw new Exception("Key 'tanggal' not found in the array \$row");
            }
        
            if(isset($row['akundebit'])){
                $debitEntry = new debitPenyesuaian([
                    'tanggal' => $date,
                    'transaksi' => $row['transaksi'],
                    'bukti' => $row['bukti'],
                    'akunD' => $row['akundebit'],
                    'rpD' => $row['saldodebit'],
                    'histori_saldo_debit' => 0,
                    'penyesuaianid' => $row['penyesuaianid']
                ]);
            }else{
                $debitEntry = new debitPenyesuaian([
                    'tanggal' => $date,
                    'transaksi' => $row['transaksi'],
                    'bukti' => $row['bukti'],
                    'akunD' => '',
                    'rpD' => 0,
                    'histori_saldo_debit' => 0,
                    'penyesuaianid' => $row['penyesuaianid']
                ]);
            }
            // Process debit entries
            
            Log::info('Debit Entry:', ['akunD' => $debitEntry->penyesuaianid]);
        
            // $akunD = COA::where('Nama_akun', $debitEntry->akunD)->first();
            // if ($akunD->keterangan == "Akun, Kredit") {
            //     $akunD->jumlah_saldo += $debitEntry->rpD;
            // } else {
            //     $akunD->jumlah_saldo += $debitEntry->rpD;
            // }
            // $debitEntry->histori_saldo_debit = $akunD->jumlah_saldo;
            // $akunD->save();
            $debitEntry->save();
            // $totalrpd += $row['saldodebit'];
        
            if(isset($row['akunkredit'])){
                $kreditEntry = new kreditPenyesuaian([
                    'tanggal' => $date,
                    'transaksi' => $row['transaksi'],
                    'bukti' => $row['bukti'],
                    'akunK' => $row['akunkredit'],
                    'rpK' => $row['saldokredit'],
                    'histori_saldo_kredit' => 0,
                    'penyesuaianid' => $row['penyesuaianid']
                ]);
            }else{
                $kreditEntry = new kreditPenyesuaian([
                    'tanggal' => $date,
                    'transaksi' => $row['transaksi'],
                    'bukti' => $row['bukti'],
                    'akunK' => '',
                    'rpK' => 0,
                    'histori_saldo_kredit' => 0,
                    'penyesuaianid' => $row['penyesuaianid']
                ]);
            }
            // Process kredit entries
            
            Log::info('Kredit Entry:', ['akunK' => $kreditEntry->penyesuaianid]);
        
            // $akunK = COA::where('Nama_akun', $kreditEntry->akunK)->first();
            // if ($akunK->keterangan == "Akun, Kredit") {
            //     $akunK->jumlah_saldo -= $kreditEntry->rpK;
            // } else {
            //     $akunK->jumlah_saldo -= $kreditEntry->rpK;
            // }
            // $kreditEntry->histori_saldo_kredit = $akunK->jumlah_saldo;
            // $akunK->save();
            $kreditEntry->save();
            // $totalrpk += $row['saldokredit'];
        
            // Add entries to Jurnal's debit and kredit arrays
            $debits = json_decode($penyesuaian->debit) ?? [];
            $debits[] = $debitEntry;
            $penyesuaian->debit = json_encode($debits);
        
            $kredits = json_decode($penyesuaian->kredit) ?? [];
            $kredits[] = $kreditEntry;
            $penyesuaian->kredit = json_encode($kredits);
        
            // Update the total amount based on the greater of debit or kredit
            // $penyesuaian->jumlah = max($totalrpd, $totalrpk);
            // $penyesuaian->histori_saldo_debit = $totalrpd;
            // $penyesuaian->histori_saldo_kredit = $totalrpk;
            $penyesuaian->save();
        });
    }

    public function chunkSize(): int{
        return 5000;
    }
}
