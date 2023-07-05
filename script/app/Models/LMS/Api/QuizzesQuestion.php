<?php
namespace App\Models\LMS\Api ;
use App\Models\LMS\QuizzesQuestion as WebQuizzesQuestion;
use App\Models\LMS\Scopes\ScopeDomain;

class QuizzesQuestion extends WebQuizzesQuestion{

    public function quizzesQuestionsAnswers()
    {
        return $this->hasMany('App\Models\LMS\Api\QuizzesQuestionsAnswer', 'question_id', 'id');
    }

    public function getAnswersAttribute(){

        return $this->quizzesQuestionsAnswers->map(function($answer){
            return $answer->details ;
        }) ;
    }

    public function getDetailsAttribute(){

        return [
            'id'=>$this->id ,
            'title'=>$this->title ,
            'type'=>$this->type ,
            'descriptive_correct_answer'=>$this->correct  ,
            'grade'=>$this->grade ,
            'created_at'=>$this->created_at ,
            'answers'=>$this->answers ,
            'updated_at'=>$this->updated_at ,

            
        ] ;
    }
}
