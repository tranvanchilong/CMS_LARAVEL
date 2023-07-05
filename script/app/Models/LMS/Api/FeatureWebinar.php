<?php

namespace App\Models\LMS\Api;

use App\Models\LMS\FeatureWebinar as Model;
use App\Models\LMS\Scopes\ScopeDomain;

class FeatureWebinar extends Model
{
    public function webinar()
    {
        return $this->belongsTo('App\Models\LMS\Api\Webinar', 'webinar_id', 'id');
    }
    public function scopeHandleFilters($query)
    {
        $request=request() ;
        $offset = $request->get('offset', null);
        $limit = $request->get('limit', null);
        $category = $request->get('cat', null);

        if (!empty($category) and is_numeric($category)) {
            
            // $query->with('webinar')  ;//->where('webinar.category_id', $category);
            $query->whereHas('webinar', function ($q) use ($category) {
                $q->where('category_id', $category);
            });
        }

        if (!empty($offset) && !empty($limit)) {
            $query->skip($offset);
        }
        if (!empty($limit)) {
            $query->take($limit);
        }
        return $query;
    }
}
