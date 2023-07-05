<?php

namespace App\Models\LMS\Api;

use App\Http\Controllers\Api\UploadFileManager;
use App\Models\LMS\Api\Traits\UploaderTrait;
use App\Models\LMS\CourseForum as Model;
use App\Models\LMS\Scopes\ScopeDomain;

class CourseForum extends Model
{
    use UploaderTrait;

    public function setAttachAttribute($value)
    {
        $path = $this->storage($value);
        $this->attributes['attach'] = $path ?: $this->attributes['attach']??null;
    }

    public function scopeHandleFilters($query)
    {
        $search = request()->get('search');

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', "%$search%");
                $query->orWhere('description', 'like', "%$search%");
                $query->orWhereHas('answers', function ($query) use ($search) {
                    $query->where('description', 'like', "%$search%");
                });
            });
        }

        return $query;
    }
}
