<?php

namespace App\Models\LMS;

use App\Models\LMS\Traits\SequenceContent;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Models\LMS\Scopes\ScopeDomain;

class WebinarChapter extends Model implements TranslatableContract
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        WebinarChapter::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    use Translatable;
    use SequenceContent;

    protected $table = 'lms_webinar_chapters';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    static $chapterFile = 'file';
    static $chapterSession = 'session';
    static $chapterTextLesson = 'text_lesson';

    static $chapterActive = 'active';
    static $chapterInactive = 'inactive';

    static $chapterTypes = ['file', 'session', 'text_lesson'];

    static $chapterStatus = ['active', 'inactive'];

    public $translatedAttributes = ['title'];

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }


    public function sessions()
    {
        return $this->hasMany('App\Models\LMS\Session', 'chapter_id', 'id');
    }

    public function files()
    {
        return $this->hasMany('App\Models\LMS\File', 'chapter_id', 'id');
    }

    public function textLessons()
    {
        return $this->hasMany('App\Models\LMS\TextLesson', 'chapter_id', 'id');
    }

    public function assignments()
    {
        return $this->hasMany('App\Models\LMS\WebinarAssignment', 'chapter_id', 'id');
    }

    public function quizzes()
    {
        return $this->hasMany('App\Models\LMS\Quiz', 'chapter_id', 'id');
    }

    public function chapterItems()
    {
        return $this->hasMany('App\Models\LMS\WebinarChapterItem', 'chapter_id', 'id');
    }

    public function webinar()
    {
        return $this->hasOne('App\Models\LMS\Webinar', 'webinar_id', 'id');
    }

    public function getDuration()
    {
        $time = 0;

        $time += $this->sessions->sum('duration');

        $time += $this->textLessons->sum('study_time');

        return $time;
    }


    public function getTopicsCount($withQuiz = false)
    {
        $count = 0;

        $count += $this->files->where('status', 'active')->count();
        $count += $this->sessions->where('status', 'active')->count();
        $count += $this->textLessons->where('status', 'active')->count();
        $count += $this->assignments->where('status', 'active')->count();

        if ($withQuiz) {
            $count += $this->quizzes->where('status', 'active')->count();
        }



        return $count;
    }
}
