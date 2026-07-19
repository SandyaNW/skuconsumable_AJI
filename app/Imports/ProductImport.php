<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class ProductImport implements  ToModel, WithHeadingRow, WithCalculatedFormulas
{
    /**
     * Menentukan baris mana yang jadi header kolom (File lo baris ke-2)
     */
    public function headingRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        if (empty($row['part_number'])) return null;

        return Product::updateOrCreate(
            ['product_code' => trim($row['part_number'])], // Kunci pencarian
            [
                'sku_code'      => $row['sku'] ?? '-',
                'item_name'     => $row['part_name'],
                'uom'           => strtoupper(trim($row['uom'])),
                'category'      => $row['category'],
                'input_source'  => 'existing',
                'usage_month'   => $row['usage_month'] ?? null,
                'moq'           => $row['moq'] ?? null,
                'lot'           => $row['lot'] ?? null,
                'min'           => $row['min'] ?? null,
                'rop'           => $row['rop'] ?? null,
                'max'           => $row['max'] ?? null,
                'status'        => isset($row['status']) ? strtolower(trim($row['status'])) : 'active',
            ]
        );
    }
}