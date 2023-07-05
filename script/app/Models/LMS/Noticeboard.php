<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class Noticeboard extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        Noticeboard::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_noticeboards';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    static $types = ['students', 'instructors', 'students_and_instructors'];
    static $adminTypes = ['organizations', 'students', 'instructors', 'students_and_instructors'];
    static $migrateTypes = ['all', 'organizations', 'students', 'instructors', 'students_and_instructors'];

    public function noticeboardStatus()
    {
        return $this->hasOne('App\Models\LMS\NoticeboardStatus', 'noticeboard_id', 'id');
    }

    public function webinar()
    {
        return $this->belongsTo('App\Models\LMS\Webinar', 'webinar_id', 'id');
    }
}
