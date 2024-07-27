<?php

namespace App\Exports;

use App\Models\Jurnal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JurnalExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $jurnals = Jurnal::all();

        $exportData = [];
        foreach ($jurnals as $jurnal) {
            // Extract debit and kredit entries
            foreach ($jurnal->debit as $debit) {
                $exportData[] = [
                    'tanggal' => $jurnal->tanggal,
                    'transaksi' => $jurnal->transaksi,
                    'keterangan' => $debit['keterangan'],
                    'bukti' => $debit['bukti'],
                    'akunD' => $debit['akunD'],
                    'rpD' => $debit['rpD'],
                    'histori_saldo_debit' => $debit['histori_saldo_debit'],
                    'akunK' => null,
                    'rpK' => null,
                    'histori_saldo_kredit' => null
                ];
            }
            foreach ($jurnal->kredit as $kredit) {
                $exportData[] = [
                    'tanggal' => $jurnal->tanggal,
                    'transaksi' => $jurnal->transaksi,
                    'keterangan' => $kredit['keterangan'],
                    'bukti' => $kredit['bukti'],
                    'akunD' => null,
                    'rpD' => null,
                    'histori_saldo_debit' => null,
                    'akunK' => $kredit['akunK'],
                    'rpK' => $kredit['rpK'],
                    'histori_saldo_kredit' => $kredit['histroi_saldo_kredit']
                ];
            }
        }

        return collect($exportData);
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Transaksi',
            'Keterangan',
            'Bukti',
            'Akun Debit',
            'Jumlah Debit',
            'Histori Saldo Debit',
            'Akun Kredit',
            'Jumlah Kredit',
            'Histori Saldo Kredit'
        ];
    }
}
