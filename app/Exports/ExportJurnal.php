<?php

namespace App\Exports;

use App\Models\Jurnal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportJurnal implements FromCollection, WithHeadings, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $jurnals = Jurnal::all();

        $exportData = [];
        foreach ($jurnals as $jurnal) {
            $jurnal->debit = json_decode($jurnal->debit);
            $jurnal->kredit = json_decode($jurnal->kredit);           // Extract debit and kredit entries
            foreach ($jurnal->debit as $debit) {
                $exportData[] = [
                    'tanggal' => $debit['tanggal'],
                    'transaksi' => $debit['transaksi'],
                    'keterangan' => $debit['keterangan'],
                    'bukti' => $debit['bukti'],
                    'jumlah' => $jurnal->jumlah,
                    'akunD' => $debit['akunD'],
                    'rpD' => $debit['rpD'],
                    'akunK' => null,
                    'rpK' => null,
                ];
            }
            foreach ($jurnal->kredit as $kredit) {
                $exportData[] = [
                    'tanggal' => $kredit['tanggal'],
                    'transaksi' => $kredit['transaksi'],
                    'keterangan' => $kredit['keterangan'],
                    'bukti' => $kredit['bukti'],
                    'jumlah' => $jurnal->jumlah,
                    'akunD' => null,
                    'rpD' => null,
                    'akunK' => $kredit['akunK'],
                    'rpK' => $kredit['rpK'],
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
            'Jumlah',
            'Akun Debit',
            'Jumlah Debit',
            'Akun Kredit',
            'Jumlah Kredit',
        ];
    }
    public function styles(Worksheet $sheet)
    {
        // Style header row
        $headerRow = 1; // Header row index

        return [
            // Apply styles to header row
            $headerRow => [
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => Color::COLOR_YELLOW], // Background color (yellow)
                ],
            ],
        ];
    }
}


