<?php
namespace App\Models\LMS\Api ;
use App\Models\LMS\TextLessonAttachment as PrimaryModel;
use App\Models\LMS\Scopes\ScopeDomain;

class TextLessonAttachment extends PrimaryModel {
    
    public function file()
    {
        return $this->belongsTo('App\Models\LMS\Api\File', 'file_id', 'id');
    }

    public function getDetailsAttribute(){
        return $this->file->details  ;
        return [
            'id'=>$this->id ,
            'file'=>$this->file->details 

        ] ;
    }


}