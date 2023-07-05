<?php

namespace App\Models\LMS;

use App\Models\LMS\Traits\SequenceContent;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Models\LMS\Scopes\ScopeDomain;

class Quiz extends Model implements TranslatableContract
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        Quiz::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    use Translatable;
    use SequenceContent;

    const ACTIVE = 'active';
    const INACTIVE = 'inactive';

    public $timestamps = false;
    protected $table = 'lms_quizzes';
    protected $guarded = ['id'];

    public $translatedAttributes = ['title'];

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }


    public function quizQuestions()
    {
        return $this->hasMany('App\Models\LMS\QuizzesQuestion', 'quiz_id', 'id');
    }

    public function quizResults()
    {
        return $this->hasMany('App\Models\LMS\QuizzesResult', 'quiz_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo('App\Models\LMS\User', 'creator_id', 'id');
    }

    public function webinar()
    {
        return $this->belongsTo('App\Models\LMS\Webinar', 'webinar_id', 'id');
    }

    public function teacher()
    {
        return $this->belongsTo('App\Models\LMS\User', 'creator_id', 'id');
    }

    public function certificates()
    {
        return $this->hasMany('App\Models\LMS\Certificate', 'quiz_id', 'id');
    }

    public function chapter()
    {
        return $this->belongsTo('App\Models\LMS\WebinarChapter', 'chapter_id', 'id');
    }


    public function increaseTotalMark($grade)
    {
        $total_mark = $this->total_mark + $grade;
        return $this->update(['total_mark' => $total_mark]);
    }

    public function decreaseTotalMark($grade)
    {
        $total_mark = $this->total_mark - $grade;
        return $this->update(['total_mark' => $total_mark]);
    }

    public function getUserCertificate($user, $quiz_result)
    {
        if (!empty($user) and !empty($quiz_result)) {
            return Certificate::where('quiz_id', $this->id)
                ->where('student_id', $user->id)
                ->where('quiz_result_id', $quiz_result->id)
                ->first();
        }

        return null;
    }
}
