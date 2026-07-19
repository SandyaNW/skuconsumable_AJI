<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ProductTemplateExport implements FromArray, WithColumnFormatting
{
    public function array(): array
    {
        return [
            // Baris 1: Petunjuk
            ['TEMPLATE IMPORT MASTER DATA PRODUCT - SILAKAN ISI DATA DI BARIS 3 DAN SETERUSNYA (JANGAN UBAH BARIS 2)'],
            // Baris 2: Kolom Header
            [
                'PART NUMBER',
                'SKU',
                'PART NAME',
                'UOM',
                'CATEGORY',
                'USAGE MONTH',
                'MOQ',
                'LOT',
                'MIN',
                'ROP',
                'MAX',
                'STATUS'
            ],
            // Baris 3: Contoh Pengisian
            [
                'PART-001',
                'SKU-001',
                'Contoh Barang A',
                'PCS',
                'CONSUMABLE',
                '150',
                '50',
                '10',
                '20',
                '30',
                '100',
                'ACTIVE'
            ]
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
        ];
    }
}
