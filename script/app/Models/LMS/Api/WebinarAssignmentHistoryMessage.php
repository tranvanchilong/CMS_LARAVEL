<?php

namespace App\Models\LMS\Api;

use App\Models\LMS\Api\Traits\UploaderTrait;
use App\Models\LMS\WebinarAssignmentHistoryMessage as Model;
use App\Models\LMS\Scopes\ScopeDomain;

class WebinarAssignmentHistoryMessage extends Model
{
    use UploaderTrait;

    public function setFilePathAttribute($value)
    {
        $path = $this->storage($value);
        $this->attributes['file_path'] = $path;
    }
}
