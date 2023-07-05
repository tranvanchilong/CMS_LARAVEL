<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class QuizzesResult extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        QuizzesResult::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    static $passed = 'passed';
    static $failed = 'failed';
    static $waiting = 'waiting';

    public $timestamps = false;
    protected $table = 'lms_quizzes_results';

    protected $guarded = ['id'];

    public function quiz()
    {
        return $this->belongsTo('App\Models\LMS\Quiz', 'quiz_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\LMS\User', 'user_id', 'id');
    }

    public function getQuestions()
    {
        $quiz = $this->quiz;

        if ($quiz->display_limited_questions and !empty($quiz->display_number_of_questions)) {

            $results = json_decode($this->results, true);
            $quizQuestionIds = [];

            if (!empty($results)) {
                foreach ($results as $id => $v) {
                    if (is_numeric($id)) {
                        $quizQuestionIds[] = $id;
                    }
                }
            }

            $quizQuestions = $quiz->quizQuestions()->whereIn('id',$quizQuestionIds)->get();
        } else {
            $quizQuestions = $quiz->quizQuestions;
        }

        return $quizQuestions;
    }
}
