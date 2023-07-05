<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\DeleteAccountRequest;
use App\Models\LMS\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DeleteAccountRequestsController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_delete_account_requests');

        $query = DeleteAccountRequest::query();

        $query = $this->filters($query, $request);

        $requests = $query->orderBy('created_at', 'desc')
            ->with([
                'user'
            ])
            ->paginate(10);

        $data = [
            'pageTitle' => trans('lms/update.delete-account-requests'),
            'requests' => $requests
        ];

        return view('lms.admin.users.delete_account_requests', $data);
    }

    private function filters($query, $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $full_name = $request->get('full_name');

        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($full_name)) {
            $query->whereHas('user', function ($query) use ($full_name) {
                $query->where('full_name', 'like', "%$full_name%");
            });
        }

        return $query;
    }

    public function confirm($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_delete_account_requests_confirm');

        $request = DeleteAccountRequest::findOrFail($id);

        $user = User::where('id', $request->user_id)->first();

        if (!empty($user)) {
            $user->delete();

            Storage::disk('public')->deleteDirectory($user->id);

            $request->delete();
        }

        $toastData = [
            'title' => trans('lms/public.request_success'),
            'msg' => trans('lms/update.user_account_successful_deleted'),
            'status' => 'success'
        ];
        return back()->with(['toast' => $toastData]);
    }
}
