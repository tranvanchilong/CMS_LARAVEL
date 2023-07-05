<?php

namespace App\Models\LMS;

use App\Notifications\LMS\SendVerificationEmailCode;
use App\Notifications\LMS\SendVerificationSMSCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Models\LMS\Scopes\ScopeDomain;

class Verification extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        Verification::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    use Notifiable;

    protected $table = 'lms_verifications';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    const EXPIRE_TIME = 3600; // second => 1 hour

    public function user()
    {
        return $this->belongsTo('App\Models\LMS\User');
    }

    public function sendEmailCode()
    {
        $this->notify(new SendVerificationEmailCode($this));
    }

    public function sendSMSCode()
    {
        $this->notify(new SendVerificationSMSCode($this));
    }
}
