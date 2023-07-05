<?php

namespace App\Models\LMS;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class ForumTopic extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        ForumTopic::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    use Sluggable;

    protected $table = 'lms_forum_topics';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public static function makeSlug($title)
    {
        return SlugService::createSlug(self::class, 'slug', $title);
    }

    public function creator()
    {
        return $this->belongsTo('App\Models\LMS\User', 'creator_id', 'id');
    }

    public function forum()
    {
        return $this->belongsTo('App\Models\LMS\Forum', 'forum_id', 'id');
    }

    public function attachments()
    {
        return $this->hasMany('App\Models\LMS\ForumTopicAttachment', 'topic_id', 'id');
    }

    public function likes()
    {
        return $this->hasMany('App\Models\LMS\ForumTopicLike', 'topic_id', 'id');
    }

    public function posts()
    {
        return $this->hasMany('App\Models\LMS\ForumTopicPost', 'topic_id', 'id');
    }

    public function getPostsUrl()
    {
        return "/forums/{$this->forum->slug}/topics/{$this->slug}/posts";
    }

    public function getLikeUrl()
    {
        return "/forums/{$this->forum->slug}/topics/{$this->slug}/likeToggle";
    }

    public function getBookmarkUrl()
    {
        return "/forums/{$this->forum->slug}/topics/{$this->slug}/bookmark";
    }

    public function getEditUrl()
    {
        return "/forums/{$this->forum->slug}/topics/{$this->slug}/edit";
    }
}
