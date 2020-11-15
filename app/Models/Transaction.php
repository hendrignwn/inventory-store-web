<?php

namespace App\Models;

use Carbon\Carbon;

class Transaction extends BaseModel
{
    const STATUS_PAID = 1;
    const STATUS_YET_PAID = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'trx_number',
        'customer_id',
        'grand_total',
        'note',
        'status',
        'user_id',
    ];

    public function transactionDetails() {
        return $this->hasMany(TransactionDetail::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public static function generateTrxNumber() {
        $currentDate = Carbon::now()->format('Y-m-d');

        $left = 'TRX-' . $currentDate . '-';
        $leftLen = strlen($left);
        $increment = 1;
        $padLength = 4;

        $last = self::where('trx_number', 'like', "%$left%")
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->first();

        if ($last) {
            $increment = (int) substr($last->trx_number, $leftLen, $padLength);
            $increment++;
        }

        $number = str_pad($increment, $padLength, '0', STR_PAD_LEFT);

        return $left . $number;
    }

    public static function statusLabels()
    {
        return [
            self::STATUS_PAID => 'Lunas',
            self::STATUS_YET_PAID => 'Belum Lunas',
        ];
    }

    public function getStatusLabel() {
        return self::statusLabels()[$this->status] ? self::statusLabels()[$this->status] : '-';
    }
}
