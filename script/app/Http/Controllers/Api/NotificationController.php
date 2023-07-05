<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notifications;

class NotificationController extends Controller
{
    public function get_notifications()
    {
        try {
            return response()->json(Notifications::where('user_id', domain_info('user_id'))->orderBy('id','DESC')->get(), 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }
}
