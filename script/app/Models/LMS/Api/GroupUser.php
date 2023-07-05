<?php

namespace App\Models\LMS\Api;

use App\Models\LMS\GroupUser as Model;
use App\Models\LMS\Scopes\ScopeDomain;

class GroupUser extends Model
{
    //

    public function getBriefAttribute(){

        if(!$this->group){
            return null ;
        }
        return [
            'id'=>$this->group->id ,
            'name'=>$this->group->name ,
            'status'=>$this->group->status ,
            'commission'=>$this->group->commission ,
            'discount'=>$this->group->discount 
        ] ;
    }
}
