<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use League\CommonMark\Extension\CommonMark\Parser\Inline\BangParser;

class SKUDetail extends Model
{
    use HasFactory;

    protected $table = 'sku_details';

    protected $fillable = [
        'sku_submission_id',
        'item_name',
        'specification',
        'product_code',
        'sku',
        'qty',
        'uom',
        'category',
        'usage',
        'lampiran_foto',
        'keperluan',
        'due_date'
    ];

    public static function getCategories() {
        return [
            'B3' => 'B3',
            'CONSUMABLE' => 'Consumable',
            'CONSUMABLE PROD' => 'Consumable Prod',
            'CONSUMABLE PRODUKSI' => 'Consumable Produksi',
            'ELEKTRIK' => 'Elektrik',
            'HIDRAULIC & PIPING' => 'Hidraulic & Piping',
            'MEKANIK' => 'Mekanik',
            'PNEUMATIC' => 'Pneumatic',
        ];
    }

    public static function getUoms() {
        return [
            'PCS'    => 'Pieces',
            'PACK'   => 'Pack',
            'BOX'    => 'Box',
            'SET'    => 'Set',
            'ROLL'   => 'Roll',
            'MTR'    => 'Meter',
            'KG'     => 'Kilogram',
            'LTR'    => 'Liter',
            'BTL'    => 'Bottle',
            'PAIL'   => 'Pail',
            'CAN'    => 'Can',
            'UNIT'   => 'Unit',
            'BAG'    => 'Bag',
            'LSN'    => 'Lusin',
            'BTG'    => 'Batang',
            'KLG'    => 'Kaleng',
            'LMBR'   => 'Lembar',
            'PAIRS'  => 'Pairs',
            'TUBE'   => 'Tube',
            'TBNG'   => 'Tabung',
        ];
    }

    // Relasi: Detail ini milik salah satu pengajuan SKU
    public function submission()
    {
        return $this->hasMany(SKUSubmission::class, 'sku_submission_id', 'id');
    }
}
