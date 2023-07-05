<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class Certificate extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        Certificate::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = "lms_certificates";
    public $timestamps = false;
    protected $guarded = ['id'];

    public function quiz()
    {
        return $this->hasOne('App\Models\LMS\Quiz', 'id', 'quiz_id');
    }

    public function student()
    {
        return $this->hasOne('App\Models\LMS\User', 'id', 'student_id');
    }

    public function quizzesResult()
    {
        return $this->hasOne('App\Models\LMS\QuizzesResult', 'id', 'quiz_result_id');
    }

    public function webinar()
    {
        return $this->belongsTo('App\Models\LMS\Webinar', 'webinar_id', 'id');
    }
}
