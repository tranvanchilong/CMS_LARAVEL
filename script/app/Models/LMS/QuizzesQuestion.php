<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Models\LMS\Scopes\ScopeDomain;

class QuizzesQuestion extends Model implements TranslatableContract
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        QuizzesQuestion::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    use Translatable;

    protected $table = 'lms_quizzes_questions';
    public $timestamps = false;
    protected $guarded = ['id'];

    static $multiple = 'multiple';
    static $descriptive = 'descriptive';

    public $translatedAttributes = ['title', 'correct'];

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }

    public function getCorrectAttribute()
    {
        return getTranslateAttributeValue($this, 'correct');
    }


    public function quizzesQuestionsAnswers()
    {
        return $this->hasMany('App\Models\LMS\QuizzesQuestionsAnswer', 'question_id', 'id');
    }
}
