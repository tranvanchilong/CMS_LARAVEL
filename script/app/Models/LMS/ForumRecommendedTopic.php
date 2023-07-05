<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class ForumRecommendedTopic extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        ForumRecommendedTopic::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_forum_recommended_topics';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];


    public function topics()
    {
        return $this->belongsToMany('App\Models\LMS\ForumTopic', 'lms_forum_recommended_topic_items',
            'recommended_topic_id', 'topic_id');
    }
}
