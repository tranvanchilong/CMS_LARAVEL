<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cache;
class Useroption extends Model
{
	public $timestamps = true;
    
    public $guarded = [];

    public function scopeOfType($query, $type)
    {
        return $query->where('key', $type)->first()->value ?? '';
    }

    public static function boot()
    {
        parent::boot();

        self::created(function ($model) {
            Cache::flush();
        });

        self::updated(function ($model) {
            Cache::flush();
        });
    }
}
