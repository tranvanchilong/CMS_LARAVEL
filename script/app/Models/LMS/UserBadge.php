<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class UserBadge extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        UserBadge::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    // custom user badges

    protected $table = 'lms_users_badges';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public function badge()
    {
        return $this->belongsTo('App\Models\LMS\Badge', 'badge_id', 'id');
    }
}
