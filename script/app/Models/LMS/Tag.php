<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class Tag extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        Tag::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    public $timestamps = false;
    protected $table = 'lms_tags';

    protected $guarded = ['id'];
}
