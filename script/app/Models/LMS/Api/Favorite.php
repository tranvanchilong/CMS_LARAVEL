<?php
namespace App\Models\LMS\Api ;
use App\Models\LMS\Favorite as WebFavorite;
use App\Models\LMS\Scopes\ScopeDomain;

class Favorite extends WebFavorite{

    public function webinar()
    {
        return $this->belongsTo('App\Models\LMS\Api\Webinar', 'webinar_id', 'id');
    }
    
    public function user()
    {
        return $this->belongsTo('App\Models\LMS\Api\User', 'user_id', 'id');
    }
}