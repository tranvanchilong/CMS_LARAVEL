<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class AgoraHistory extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        AgoraHistory::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_agora_history';
    public $timestamps = false;
    protected $dateFormat = "U";
    protected $guarded = ['id'];

    public function session()
    {
        return $this->belongsTo('App\Models\LMS\Session', 'session_id', 'id');
    }
}
