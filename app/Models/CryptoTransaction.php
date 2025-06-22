<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CryptoTransaction extends Model
{
    protected $fillable = [
        'user_id', 'coin_uuid', 'symbol', 'price_usd',
        'amount_coin', 'total_usd', 'fees', 'type', 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
