<?php

namespace App\Models\LMS\Api;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class BundleWebinar extends Model
{
    public function webinar()
    {
        return $this->belongsTo('App\Models\LMS\Api\Webinar', 'webinar_id', 'id');
    }

    public function bundle()
    {
        return $this->belongsTo('App\Models\LMS\Api\Bundle', 'bundle_id', 'id');
    }
}
