<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CryptoPosition extends Model
{
    protected $fillable = ['user_id', 'crypto_id', 'crypto_name', 'amount', 'average_price', 'invested_usd'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(CryptoTransaction::class);
    }
}
