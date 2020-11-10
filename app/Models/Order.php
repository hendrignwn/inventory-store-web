<?php

namespace App\Models;

use Carbon\Carbon;

class Order extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_number',
        'supplier_id',
        'grand_total',
        'note',
        'status',
    ];

    public function orderDetails() {
        return $this->hasMany(OrderDetail::class);
    }

    public function supplier() {
        return $this->hasOne(Supplier::class);
    }

    public static function generateOrderNumber() {
        $currentDate = Carbon::now()->format('Y-m-d');

        $left = 'INV-' . $currentDate . '-';
        $leftLen = strlen($left);
        $increment = 1;
        $padLength = 4;

        $last = self::where('created_at', 'like', "%$currentDate%")
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->first();

        if ($last) {
            $increment = (int) substr($last->order_number, $leftLen, $padLength);
            $increment++;
        }

        $number = str_pad($increment, $padLength, '0', STR_PAD_LEFT);

        return $left . $number;
    }
}
