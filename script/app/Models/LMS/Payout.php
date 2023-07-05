<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class Payout extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        Payout::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    public static $waiting = 'waiting';
    public static $done = 'done';
    public static $reject = 'reject';

    public $timestamps = false;
    protected $table = 'lms_payouts';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo('App\Models\LMS\User', 'user_id', 'id');
    }

    public function userSelectedBank()
    {
        return $this->belongsTo('App\Models\LMS\UserSelectedBank', 'user_selected_bank_id', 'id');
    }
}
