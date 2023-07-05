<?php

namespace App\Models\LMS\Api;
use App\Models\LMS\Faq as Model ;
use App\Models\LMS\Scopes\ScopeDomain;

class Faq extends Model
{
   public function  getDetailsAttribute(){
        return [
            'id'=>$this->id ,
            'title'=>$this->title ,
            'answer'=>$this->answer ,
            'order'=>$this->order ,
            'created_at'=>$this->created_at ,
            'updated_at'=>$this->updated_at
        ] ;
    }
}
