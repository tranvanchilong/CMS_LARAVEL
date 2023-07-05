<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\CourseNoticeboard;
use App\Models\LMS\CourseNoticeboardStatus;
use App\Models\LMS\Webinar;
use App\Models\LMS\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseNoticeboardController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_course_noticeboards_list');

        $query = $this->filters(CourseNoticeboard::query(), $request);

        $noticeboards = $query->with([
            'webinar',
            'creator' => function ($query) {
                $query->select('id', 'full_name');
            }
        ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = [
            'pageTitle' => trans('lms/update.course_notices'),
            'noticeboards' => $noticeboards,
            'isCourseNotice' => true,
        ];

        $senderId = $request->get('sender_id');
        if (!empty($senderId)) {
            $data['sender'] = User::find($senderId);
        }

        return view('lms.admin.noticeboards.lists', $data);
    }

    private function filters($query, $request)
    {
        $from = $request->get('from', null);
        $to = $request->get('to', null);
        $search = $request->get('search', null);
        $senderId = $request->get('sender_id', null);
        $color = $request->get('color', null);

        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($search)) {
            $query->where('title', 'like', "%$search%");
        }

        if (!empty($senderId)) {
            $query->where('creator_id', $senderId);
        }

        if (!empty($color)) {
            $query->where('color', $color);
        }

        return $query;
    }

    public function create()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_course_noticeboards_send');

        $data = [
            'pageTitle' => trans('lms/panel.new_noticeboard'),
            'isCourseNotice' => true,
        ];

        return view('lms.admin.noticeboards.send', $data);
    }

    public function store(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_course_noticeboards_send');

        $data = $request->all();

        $this->validate($request, [
            'title' => 'required|string|max:255',
            'webinar_id' => 'required',
            'color' => 'required',
            'message' => 'required',
        ]);

        $webinar = Webinar::findOrFail($data['webinar_id']);

        CourseNoticeboard::create([
            'creator_id' => $webinar->teacher_id,
            'webinar_id' => $webinar->id,
            'color' => $data['color'],
            'title' => $data['title'],
            'message' => $data['message'],
            'created_at' => time()
        ]);

        $studentsIds = $webinar->getStudentsIds();
        if (count($studentsIds)) {
            $notifyOptions = [
                '[c.title]' => $webinar->title,
                '[item_title]' => $data['title'],
                '[time.date]' => dateTimeFormat(time(), 'j M Y H:i')
            ];

            foreach ($studentsIds as $studentId) {
                sendNotification("new_course_notice", $notifyOptions, $studentId);
            }
        }

        $toastData = [
            'title' => trans('lms/public.request_success'),
            'msg' => trans('lms/admin/main.send_noticeboard_success'),
            'status' => 'success'
        ];
        return redirect('/lms/admin/course-noticeboards')->with(['toast' => $toastData]);
    }

    public function edit($noticeboard_id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_course_noticeboards_edit');

        $noticeboard = CourseNoticeboard::where('id', $noticeboard_id)
            ->first();

        if (!empty($noticeboard)) {
            $data = [
                'pageTitle' => trans('lms/panel.noticeboards'),
                'noticeboard' => $noticeboard,
                'isCourseNotice' => true,
            ];

            return view('lms.admin.noticeboards.send', $data);
        }

        abort(404);
    }

    public function update(Request $request, $noticeboard_id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_course_noticeboards_edit');

        $noticeboard = CourseNoticeboard::where('id', $noticeboard_id)
            ->first();

        if (!empty($noticeboard)) {
            $data = $request->all();

            $this->validate($request, [
                'title' => 'required|string|max:255',
                'webinar_id' => 'required',
                'color' => 'required',
                'message' => 'required',
            ]);

            $webinar = Webinar::where('id', $data['webinar_id'])->first();

            if (!empty($webinar)) {

                $noticeboard->update([
                    'webinar_id' => $webinar->id,
                    'color' => $data['color'],
                    'title' => $data['title'],
                    'message' => $data['message'],
                    'created_at' => time()
                ]);

                CourseNoticeboardStatus::where('noticeboard_id', $noticeboard->id)->delete();

                $toastData = [
                    'title' => trans('lms/public.request_success'),
                    'msg' => trans('lms/admin/main.edit_noticeboard_success'),
                    'status' => 'success'
                ];
                return redirect('/lms/admin/course-noticeboards')->with(['toast' => $toastData]);
            }
        }

        abort(404);
    }

    public function delete($noticeboard_id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_course_noticeboards_delete');

        $noticeboard = CourseNoticeboard::where('id', $noticeboard_id)
            ->first();

        if (!empty($noticeboard)) {
            $noticeboard->delete();

            $toastData = [
                'title' => trans('lms/public.request_success'),
                'msg' => trans('lms/admin/main.delete_noticeboard_success'),
                'status' => 'success'
            ];
            return redirect('/lms/admin/course-noticeboards')->with(['toast' => $toastData]);
        }

        abort(404);
    }
}
