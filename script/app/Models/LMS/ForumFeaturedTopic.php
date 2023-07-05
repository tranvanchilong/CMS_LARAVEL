<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class ForumFeaturedTopic extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        ForumFeaturedTopic::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_forum_featured_topics';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];


    public function topic()
    {
        return $this->belongsTo('App\Models\LMS\ForumTopic', 'topic_id', 'id');
    }
}
