<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class Prerequisite extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        Prerequisite::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    public $timestamps = false;
    protected $table = 'lms_prerequisites';

    protected $guarded = ['id'];

    public function prerequisiteWebinar()
    {
        return $this->belongsTo('App\Models\LMS\Webinar', 'prerequisite_id', 'id');
    }
}
