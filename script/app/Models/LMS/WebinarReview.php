<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class WebinarReview extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        WebinarReview::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_webinar_reviews';
    public $timestamps = false;

    protected $guarded = ['id'];

    public function webinar()
    {
        return $this->belongsTo('App\Models\LMS\Webinar', 'webinar_id', 'id');
    }

    public function bundle()
    {
        return $this->belongsTo('App\Models\LMS\Bundle', 'bundle_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo('App\Models\LMS\User', 'creator_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\LMS\Comment', 'review_id', 'id');
    }
}
