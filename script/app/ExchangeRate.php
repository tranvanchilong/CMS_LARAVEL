<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    protected $table = 'exchange_rates';
    protected $fillable = [
        'name',
        'code',
        'type',
        'rate',
    ];
}
