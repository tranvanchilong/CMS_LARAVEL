<?php
namespace App\Models\LMS\Api ;
use App\Models\LMS\QuizzesQuestionsAnswer as WebQuizzesQuestionsAnswer;
use App\Models\LMS\Scopes\ScopeDomain;

class QuizzesQuestionsAnswer extends WebQuizzesQuestionsAnswer{

    public function getDetailsAttribute(){

        return [
            'id'=>$this->id ,
            'title'=>$this->title ,
            'correct'=>$this->correct  ,
            'image'=>($this->image)?url($this->image):null ,
            'created_at'=>$this->created_at ,
            'updated_at'=>$this->updated_at ,

        ] ;
    }

}

