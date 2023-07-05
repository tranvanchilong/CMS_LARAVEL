<?php

namespace App\Models\LMS\Api;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class WebinarReport extends Model
{
    //
    public function getDetailsAttribute(){
        return [
            'id'=>$this->id ,
            'reason'=>$this->reason ,
            'message'=>$this->message ,
            'created_at'=>$this->created_at ,
            'user'=>$this->user->brief??null ,
            'webinar'=>$this->webinar->brief??null 

        ] ;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\LMS\Api\User', 'user_id', 'id');
    }

    public function webinar()
    {
        return $this->belongsTo('App\Models\LMS\Api\Webinar', 'webinar_id', 'id');
    }
}
