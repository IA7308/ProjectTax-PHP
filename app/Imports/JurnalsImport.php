<?php

namespace App\Imports;

use App\Models\COA;
use App\Models\Jurnal;
use App\Models\JurnalAkun;
use App\Models\JurnalAkunKredit;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
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
        

        DB::transaction(function () use ($row) {
            // Check if Jurnal already exists or create a new one
            $jurnal = Jurnal::firstOrCreate(
                ['JurnalId' => $row['jurnalid'] ?? 1],
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
        
            if(isset($row['akundebit'])){
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
                $akunD = COA::where('Nama_akun', $debitEntry->akunD)->first();
                if ($akunD->keterangan == "Akun, Kredit") {
                    $akunD->jumlah_saldo += $debitEntry->rpD;
                } else {
                    $akunD->jumlah_saldo += $debitEntry->rpD;
                }
                $debitEntry->histori_saldo_debit = $akunD->jumlah_saldo;
                $akunD->save();
            }else{
                $debitEntry = new JurnalAkun([
                    'tanggal' => $date,
                    'transaksi' => $row['transaksi'],
                    'keterangan' => $row['keterangan'],
                    'bukti' => $row['bukti'],
                    'akunD' => '',
                    'rpD' => 0,
                    'histori_saldo_debit' => 0,
                    'JurnalId' => $row['jurnalid']
                ]);
            }
            // Process debit entries
            
            Log::info('Debit Entry:', ['akunD' => $debitEntry->JurnalId]);
        
            
            $debitEntry->save();
            $totalrpd += $row['saldodebit'];
        
            if(isset($row['akunkredit'])){
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
                $akunK = COA::where('Nama_akun', $kreditEntry->akunK)->first();
                if ($akunK->keterangan == "Akun, Kredit") {
                    $akunK->jumlah_saldo -= $kreditEntry->rpK;
                } else {
                    $akunK->jumlah_saldo -= $kreditEntry->rpK;
                }
                $kreditEntry->histori_saldo_kredit = $akunK->jumlah_saldo;
                $akunK->save();
            }else{
                $kreditEntry = new JurnalAkunKredit([
                    'tanggal' => $date,
                    'transaksi' => $row['transaksi'],
                    'keterangan' => $row['keterangan'],
                    'bukti' => $row['bukti'],
                    'akunK' => '',
                    'rpK' => 0,
                    'histori_saldo_kredit' => 0,
                    'JurnalId' => $row['jurnalid']
                ]);
            }
            // Process kredit entries
            
            Log::info('Kredit Entry:', ['akunK' => $kreditEntry->JurnalId]);
        
            
            $kreditEntry->save();
            $totalrpk += $row['saldokredit'];
        
            // Add entries to Jurnal's debit and kredit arrays
            $debits = json_decode($jurnal->debit) ?? [];
            $debits[] = $debitEntry;
            $jurnal->debit = json_encode($debits);
        
            $kredits = json_decode($jurnal->kredit) ?? [];
            $kredits[] = $kreditEntry;
            $jurnal->kredit = json_encode($kredits);
        
            // Update the total amount based on the greater of debit or kredit
            $jurnal->jumlah = max($totalrpd, $totalrpk);
            $jurnal->histori_saldo_debit = $totalrpd;
            $jurnal->histori_saldo_kredit = $totalrpk;
            $jurnal->save();
        });
    }
    public function chunkSize(): int
    {
        return 5000; // Set chunk size according to your need
    }

}
