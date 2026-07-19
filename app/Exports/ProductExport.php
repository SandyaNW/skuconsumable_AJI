<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping; // Tambahkan interface ini
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date; // Tambahkan shared date helper
use Carbon\Carbon;

class ProductExport implements FromCollection, WithHeadings, WithColumnFormatting, WithMapping
{
    public function collection() {
        return Product::select(
            'product_code',
            'sku_code',
            'item_name',
            'uom',
            'category',
            'usage_month',
            'moq',
            'lot',
            'min',
            'rop',
            'max',
            'input_source',
            'status',
            'created_at'
        )->get();
    }

    public function headings(): array {
        return [
            'Part Number',
            'SKU Code',
            'Item Name',
            'UOM',
            'Category',
            'Usage/Month',
            'MOQ',
            'LOT',
            'MIN',
            'ROP',
            'MAX',
            'Source',
            'Status',
            'Date Added'
        ];
    }

    /**
    * Fungsi ini untuk memetakan dan memformat data sebelum masuk ke baris Excel
    */
    public function map($product): array
    {
        return [
            $product->product_code,
            $product->sku_code,
            $product->item_name,
            $product->uom,
            $product->category,
            $product->usage_month,
            $product->moq,
            $product->lot,
            $product->min,
            $product->rop,
            $product->max,
            $product->input_source,
            strtoupper($product->status),
            // Mengubah string ISO-8601 tanggal menjadi format yang dimengerti Excel
            $product->created_at ? Date::dateTimeToExcel(Carbon::parse($product->created_at)) : '',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT, // Part Number
            'B' => NumberFormat::FORMAT_TEXT, // SKU Code
            'N' => 'yyyy-mm-dd hh:mm:ss',     // Kolom N (Date Added) diformat rapi
        ];
    }
}