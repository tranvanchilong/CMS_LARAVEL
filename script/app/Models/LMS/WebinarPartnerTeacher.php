<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class WebinarPartnerTeacher extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        WebinarPartnerTeacher::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_webinar_partner_teacher';

    public $timestamps = false;

    protected $guarded = ['id'];

    public function teacher()
    {
        return $this->belongsTo('App\Models\LMS\User', 'teacher_id', 'id');
    }

    public function webinar()
    {
        return $this->belongsTo('App\Models\LMS\User', 'webinar_id', 'id');
    }
}

