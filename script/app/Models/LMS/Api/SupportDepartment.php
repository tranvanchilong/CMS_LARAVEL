<?php
namespace App\Models\LMS\Api ;

use App\Models\LMS\SupportDepartment as Model ;
use App\Models\LMS\Scopes\ScopeDomain;

class SupportDepartment extends Model {

    public function getDetailsAttribute(){
        return [
            'id'=>$this->id ,
            'title'=>$this->title ,
        ] ;
    }
}