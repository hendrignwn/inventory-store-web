<?php

namespace App\Models;

class Item extends BaseModel
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'sell_price',
        'minimum_stock',
        'purchase_price',
        'description',
        'item_type_id',
        'status',
    ];

    public function itemType() {
        return $this->belongsTo(ItemType::class);
    }

    public function itemStocks() {
        return $this->hasMany(ItemStock::class);
    }

    public static function statusLabels()
    {
        return [
            self::STATUS_ACTIVE => 'Aktif',
            self::STATUS_INACTIVE => 'Non Aktif',
        ];
    }
}
