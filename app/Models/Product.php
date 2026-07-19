<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    const CATEGORIES = [
        'B3' => 'B3',
        'CONSUMABLE' => 'Consumable',
        'CONSUMABLE PROD' => 'Consumable Prod',
        'CONSUMABLE PRODUKSI' => 'Consumable Produksi',
        'ELEKTRIK' => 'Elektrik',
        'HIDRAULIC & PIPING' => 'Hidraulic & Piping',
        'MEKANIK' => 'Mekanik',
        'PNEUMATIC' => 'Pneumatic',
    ];

    const UOMS = [
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

    const STATUSES = [
        'active' => 'Active',
        'inactive' => 'Inactive',
    ];

    protected $fillable = [
        'product_code',
        'sku_code',
        'item_name',
        'specification',
        'product_image',
        'uom',
        'category',
        'input_source',
        'sku_submission_id',
        'usage_month',
        'moq',
        'lot',
        'min',
        'rop',
        'max',
        'status',
    ];

    /**
     * Relasi ke Header Pengajuan SKU (Opsional)
     * Biar kita tau produk ini asalnya dari pengajuan yang mana.
     */
    public function submission()
    {
        return $this->belongsTo(SKUSubmission::class, 'sku_submission_id');
    }

    /**
     * Mutator: Memastikan Product Code selalu tersimpan huruf kapital
     */
    public function setProductCodeAttribute($value)
    {
        $this->attributes['product_code'] = strtoupper(trim($value));
    }

    /**
     * Mutator: Memastikan SKU Code selalu tersimpan huruf kapital
     */
    public function setSkuCodeAttribute($value)
    {
        $this->attributes['sku_code'] = strtoupper(trim($value));
    }
}
