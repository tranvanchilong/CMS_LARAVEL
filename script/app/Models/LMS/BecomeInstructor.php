<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class BecomeInstructor extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        BecomeInstructor::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_become_instructors';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo('App\Models\LMS\User', 'user_id', 'id');
    }

    public function registrationPackage()
    {
        return $this->belongsTo('App\Models\LMS\RegistrationPackage', 'package_id', 'id');
    }

    public function sendNotificationToUser($status)
    {
        $notifyOptions = [
            '[u.role]' => $this->role == 'teacher' ? trans('lms/admin/main.instructor') : trans('lms/admin/main.organization')
        ];

        if ($status == 'reject') {
            sendNotification("become_instructor_request_rejected", $notifyOptions, $this->user_id);
        } else {
            sendNotification("become_instructor_request_approved", $notifyOptions, $this->user_id);
        }
    }
}
