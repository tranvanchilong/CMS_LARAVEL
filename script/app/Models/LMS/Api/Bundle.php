<?php

namespace App\Models\LMS\Api;

use App\Models\LMS\Api\Traits\CheckForSaleTrait;
use App\Models\LMS\Favorite;
use App\Models\LMS\Bundle as Model;
use App\Models\LMS\Scopes\ScopeDomain;

class Bundle extends Model
{
    use CheckForSaleTrait;

    public function getIsFavoriteAttribute()
    {
        if (!apiAuth()) {
            return null;
        }
        return (bool)Favorite::where('bundle_id', $this->id)
            ->where('user_id', apiauth()->guard('lms_user')->id)
            ->first();
    }

    public function bundleWebinars()
    {
        return $this->hasMany('App\Models\LMS\Api\BundleWebinar', 'bundle_id', 'id');
    }

    public function webinars()
    {
        //  return $this->hasManyThrough('App\Models\LMS\Webinar', 'App\Models\LMS\BundleWebinar', 'bundle_id', 'id');
    }
    public function teacher()
    {
        return $this->belongsTo('App\Models\LMS\Api\User', 'teacher_id', 'id');
    }
}
