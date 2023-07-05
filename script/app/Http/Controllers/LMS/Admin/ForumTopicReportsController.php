<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\ForumTopicReport;
use Illuminate\Http\Request;

class ForumTopicReportsController extends Controller
{
    public function index()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_forum_topic_post_reports');

        $reports = ForumTopicReport::with([
            'user' => function ($query) {
                $query->select('id', 'full_name');
            },
            'topic',
            'topicPost'
        ])->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = [
            'pageTitle' => trans('lms/update.topic_and_post_reports'),
            'reports' => $reports
        ];

        return view('lms.admin.forums.topics.reports', $data);
    }

    public function delete($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_forum_topic_post_reports');

        $report = ForumTopicReport::findOrFail($id);

        $report->delete();

        return back();
    }
}
