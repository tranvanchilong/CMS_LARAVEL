<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class ForumTopicReport extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        ForumTopicReport::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_forum_topic_reports';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo('App\Models\LMS\User', 'user_id', 'id');
    }

    public function topic()
    {
        return $this->belongsTo('App\Models\LMS\ForumTopic', 'topic_id', 'id');
    }

    public function topicPost()
    {
        return $this->belongsTo('App\Models\LMS\ForumTopicPost', 'topic_post_id', 'id');
    }
}
