<?php

namespace App\Models\LMS\Api;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class BlogCategory extends Model
{
    //
    public function getDetailsAttribute(){
        return [
            'id'=>$this->id ,
            'title'=>$this->title 
        ] ;
    }
}
