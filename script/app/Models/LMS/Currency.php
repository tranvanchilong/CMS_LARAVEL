<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Jorenvh\Share\ShareFacade;
use App\Models\LMS\Scopes\ScopeDomain;

class Currency extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        Currency::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_currencies';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    static $currencyPositions = ['left', 'right', 'left_with_space', 'right_with_space'];
}
