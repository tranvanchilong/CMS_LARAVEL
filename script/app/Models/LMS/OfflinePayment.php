<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class OfflinePayment extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        OfflinePayment::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    public static $waiting = 'waiting';
    public static $approved = 'approved';
    public static $reject = 'reject';

    public $timestamps = false;
    protected $table = 'lms_offline_payments';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo('App\Models\LMS\User', 'user_id', 'id');
    }

    public function offlineBank()
    {
        return $this->belongsTo('App\Models\LMS\OfflineBank', 'offline_bank_id', 'id');
    }

    public function getAttachmentPath()
    {
        return '/store/' . $this->user_id . '/offlinePayments/' . $this->attachment;
    }
}
