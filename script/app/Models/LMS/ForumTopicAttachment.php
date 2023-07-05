<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class ForumTopicAttachment extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        ForumTopicAttachment::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_forum_topic_attachments';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public function getDownloadUrl($forumSlug, $topicSlug)
    {
        return "/forums/{$forumSlug}/topics/{$topicSlug}/downloadAttachment/{$this->id}";
    }

    public function getName()
    {
        $name = "";

        if (!empty($this->path)) {
            $path = explode('/',$this->path);

            $name = $path[array_key_last($path)];
        }


        return $name;
    }
}
