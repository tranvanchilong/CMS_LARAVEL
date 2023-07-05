<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class ForumTopicPost extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        ForumTopicPost::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_forum_topic_posts';
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

    public function likes()
    {
        return $this->hasMany('App\Models\LMS\ForumTopicLike', 'topic_post_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\LMS\ForumTopicPost', 'parent_id', 'id');
    }

    public function getLikeUrl($forumSlug, $topicSlug)
    {
        return "/forums/{$forumSlug}/topics/{$topicSlug}/posts/{$this->id}/likeToggle";
    }

    public function getEditUrl($forumSlug, $topicSlug)
    {
        return "/forums/{$forumSlug}/topics/{$topicSlug}/posts/{$this->id}/edit";
    }

    public function getAttachmentUrl($forumSlug, $topicSlug)
    {
        return "/forums/{$forumSlug}/topics/{$topicSlug}/posts/{$this->id}/downloadAttachment";
    }

    public function getAttachmentName()
    {
        $name = "";

        if (!empty($this->attach)) {
            $attach = explode('/',$this->attach);

            $name = $attach[array_key_last($attach)];
        }

        return $name;
    }
}
