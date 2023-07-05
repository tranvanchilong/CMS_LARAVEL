<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class Notification extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        Notification::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_notifications';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    static $AdminSender = 'admin';
    static $SystemSender = 'system';

    static $notificationsType = ['single', 'all_users', 'students', 'instructors', 'organizations', 'group', 'course_students'];

    public function user()
    {
        return $this->belongsTo('App\Models\LMS\User', 'user_id', 'id');
    }

    public function senderUser()
    {
        return $this->belongsTo('App\Models\LMS\User', 'sender_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo('App\Models\LMS\Group', 'group_id', 'id');
    }

    public function webinar()
    {
        return $this->belongsTo('App\Models\LMS\Webinar', 'webinar_id', 'id');
    }

    public function notificationStatus()
    {
        return $this->hasOne('App\Models\LMS\NotificationStatus', 'notification_id', 'id');
    }
}
