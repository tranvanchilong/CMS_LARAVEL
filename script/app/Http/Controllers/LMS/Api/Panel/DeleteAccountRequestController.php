<?php

namespace App\Http\Controllers\LMS\Api\Panel;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\DeleteAccountRequest;
use Illuminate\Http\Request;

class DeleteAccountRequestController extends Controller
{
    public function store()
    {
        DeleteAccountRequest::updateOrCreate([
            'user_id' => apiauth()->guard('lms_user')->id,
        ], [
            'created_at' => time()
        ]);

        return apiResponse2(1, 'stored', trans('lms/update.delete_account_request_stored_msg'));

    }
}
