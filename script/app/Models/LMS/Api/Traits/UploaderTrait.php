<?php

namespace App\Models\LMS\Api\Traits;

trait UploaderTrait
{

    public function storage( $file)
    {
        if (!$file ) {
           return null;
        }
        $fileName = $file->getClientOriginalName();
        $path = apiauth()->guard('lms_user')->id;
        $storage_path = $file->storeAs($path, $fileName);
        return 'store/' . $storage_path;
    }
}
