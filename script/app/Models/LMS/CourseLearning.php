<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class CourseLearning extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        CourseLearning::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_course_learning';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];


}
