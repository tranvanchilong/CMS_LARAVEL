<?php

namespace App\Models\LMS\Api;

use App\Models\LMS\Prerequisite as Model;


class Prerequisite extends Model
{
    public function prerequisiteWebinar()
    {
        return $this->belongsTo('App\Models\LMS\Api\Webinar', 'prerequisite_id', 'id')
        ->where('status','active')->where('private',false) ;
        ;
    }
}
