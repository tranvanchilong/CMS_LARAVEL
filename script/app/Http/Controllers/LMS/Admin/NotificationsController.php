<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use App\Mail\SendNotifications;
use App\Models\LMS\Group;
use App\Models\LMS\Notification;
use App\Models\LMS\NotificationStatus;
use App\Models\LMS\User;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_notifications_list');

        $notifications = Notification::where('user_id', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = [
            'pageTitle' => trans('lms/admin/main.notifications'),
            'notifications' => $notifications
        ];

        return view('lms.admin.notifications.lists', $data);
    }

    public function posted()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_notifications_posted_list');

        $notifications = Notification::where('sender', Notification::$AdminSender)
            ->orderBy('created_at', 'desc')
            ->with([
                'senderUser' => function ($query) {
                    $query->select('id', 'full_name');
                },
                'user' => function ($query) {
                    $query->select('id', 'full_name');
                },
                'notificationStatus'
            ])
            ->paginate(10);

        $data = [
            'pageTitle' => trans('lms/admin/main.posted_notifications'),
            'notifications' => $notifications
        ];

        return view('lms.admin.notifications.posted', $data);
    }

    public function create()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_notifications_send');

        $userGroups = Group::all();

        $data = [
            'pageTitle' => trans('lms/notification.send_notification'),
            'userGroups' => $userGroups
        ];

        return view('lms.admin.notifications.send', $data);
    }

    public function store(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_notifications_send');

        $this->validate($request, [
            'title' => 'required|string',
            'type' => 'required|string',
            'user_id' => 'required_if:type,single',
            'group_id' => 'required_if:type,group',
            'webinar_id' => 'required_if:type,course_students',
            'message' => 'required|string',
        ]);

        $data = $request->all();

        Notification::create([
            'user_id' => !empty($data['user_id']) ? $data['user_id'] : null,
            'group_id' => !empty($data['group_id']) ? $data['group_id'] : null,
            'webinar_id' => !empty($data['webinar_id']) ? $data['webinar_id'] : null,
            'sender_id' => auth()->guard('lms_user')->id(),
            'title' => $data['title'],
            'message' => $data['message'],
            'sender' => Notification::$AdminSender,
            'type' => $data['type'],
            'created_at' => time()
        ]);

        if (!empty($data['user_id']) and env('APP_ENV') == 'production') {
            $user = \App\Models\LMS\User::where('id', $data['user_id'])->first();
            if (!empty($user) and !empty($user->email)) {
                \Mail::to($user->email)->send(new SendNotifications(['title' => $data['title'], 'message' => $data['message']]));
            }
        }


        return redirect('/lms/admin/notifications/posted');
    }

    public function edit($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_notifications_edit');

        $notification = Notification::where('id', $id)
            ->with([
                'user' => function ($query) {
                    $query->select('id', 'full_name');
                },
                'group'
            ])->first();

        if (!empty($notification)) {
            $userGroups = Group::all();

            $data = [
                'pageTitle' => trans('lms/notification.edit_notification'),
                'userGroups' => $userGroups,
                'notification' => $notification
            ];

            return view('lms.admin.notifications.send', $data);
        }

        abort(404);
    }

    public function update(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_notifications_edit');

        $this->validate($request, [
            'title' => 'required|string',
            'type' => 'required|string',
            'user_id' => 'required_if:type,single',
            'group_id' => 'required_if:type,group',
            'webinar_id' => 'required_if:type,course_students',
            'message' => 'required|string',
        ]);

        $data = $request->all();

        $notification = Notification::findOrFail($id);

        $notification->update([
            'user_id' => !empty($data['user_id']) ? $data['user_id'] : null,
            'group_id' => !empty($data['group_id']) ? $data['group_id'] : null,
            'webinar_id' => !empty($data['webinar_id']) ? $data['webinar_id'] : null,
            'title' => $data['title'],
            'message' => $data['message'],
            'type' => $data['type'],
            'created_at' => time()
        ]);

        return redirect('/lms/admin/notifications');
    }

    public function delete($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_notifications_delete');

        $notification = Notification::findOrFail($id);

        $notification->delete();

        return redirect('/lms/admin/notifications');
    }

    public function markAllRead()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_notifications_markAllRead');

        $adminUser = User::find(1);

        $unreadNotifications = $adminUser->getUnReadNotifications();

        if (!empty($unreadNotifications) and !$unreadNotifications->isEmpty()) {
            foreach ($unreadNotifications as $unreadNotification) {
                NotificationStatus::updateOrCreate(
                    [
                        'user_id' => $adminUser->id,
                        'notification_id' => $unreadNotification->id,
                    ],
                    [
                        'seen_at' => time()
                    ]
                );
            }
        }

        return back();
    }

    public function markAsRead($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_notifications_edit');

        $adminUser = User::find(1);

        NotificationStatus::updateOrCreate(
            [
                'user_id' => $adminUser->id,
                'notification_id' => $id,
            ],
            [
                'seen_at' => time()
            ]
        );


        return response()->json([], 200);
    }
}
