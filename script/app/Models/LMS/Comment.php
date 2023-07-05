<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class Comment extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        Comment::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_comments';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    static $pending = 'pending';
    static $active = 'active';


    public function replies()
    {
        return $this->hasMany('App\Models\LMS\Comment', 'reply_id', 'id');
    }

    public function webinar()
    {
        return $this->belongsTo('App\Models\LMS\Webinar', 'webinar_id', 'id');
    }

    public function bundle()
    {
        return $this->belongsTo('App\Models\LMS\Bundle', 'bundle_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\LMS\User', 'user_id', 'id');
    }

    public function review()
    {
        return $this->belongsTo('App\Models\LMS\WebinarReview', 'review_id', 'id');
    }

    public function blog()
    {
        return $this->belongsTo('App\Models\LMS\Blog', 'blog_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\LMS\Product', 'product_id', 'id');
    }

    public function productReview()
    {
        return $this->belongsTo('App\Models\LMS\ProductReview', 'product_review_id', 'id');
    }
}
