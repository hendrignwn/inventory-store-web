<?php

namespace App\Models;

class ItemStock extends BaseModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id',
        'stock',
        'supplier_id',
    ];

    public function item() {
        return $this->belongsTo(Item::class);
    }

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }
}
