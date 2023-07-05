<?php

namespace App\Models\LMS\Api;

use App\Models\LMS\Follow as Model;
use App\Models\LMS\Scopes\ScopeDomain;

class Follow extends Model
{


    public function user()
    {
        return $this->belongsTo('App\Models\LMS\Api\User', 'user_id', 'id');
    }

    public function userFollower()
    {
        return $this->belongsTo('App\Models\LMS\Api\User', 'follower', 'id');
    }
}
