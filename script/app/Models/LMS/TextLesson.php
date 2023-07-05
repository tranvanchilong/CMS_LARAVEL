<?php

namespace App\Models\LMS;

use App\Models\LMS\Traits\SequenceContent;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Models\LMS\Scopes\ScopeDomain;

class TextLesson extends Model implements TranslatableContract
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        TextLesson::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    use Translatable;
    use SequenceContent;

    protected $table = 'lms_text_lessons';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    static $Active = 'active';
    static $Inactive = 'inactive';
    static $Status = ['active', 'inactive'];

    public $translatedAttributes = ['title', 'summary', 'content'];

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }

    public function getSummaryAttribute()
    {
        return getTranslateAttributeValue($this, 'summary');
    }

    public function getContentAttribute()
    {
        return getTranslateAttributeValue($this, 'content');
    }


    public function attachments()
    {
        return $this->hasMany('App\Models\LMS\TextLessonAttachment', 'text_lesson_id', 'id');
    }

    public function learningStatus()
    {
        return $this->hasOne('App\Models\LMS\CourseLearning', 'text_lesson_id', 'id');
    }

    public function chapter()
    {
        return $this->belongsTo('App\Models\LMS\WebinarChapter', 'chapter_id', 'id');
    }

    public function checkPassedItem()
    {
        $result = false;

        if (auth()->guard('lms_user')->check()) {
            $check = $this->learningStatus()->where('user_id', auth()->guard('lms_user')->id())->count();

            $result = ($check > 0);
        }

        return $result;
    }
}
