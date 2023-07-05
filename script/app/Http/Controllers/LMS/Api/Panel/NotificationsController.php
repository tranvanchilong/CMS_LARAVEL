<?php

namespace App\Http\Controllers\LMS\Api\Panel;

use App\Http\Controllers\LMS\Api\Controller;
use App\Models\LMS\Notification;
use App\Models\LMS\NotificationStatus;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function list(Request $request)
    {
        $status = $request->input('status');
        if ($status == 'unread') {
            $notifications = $this->unRead();
        } elseif ($status == 'read') {
            $notifications = $this->read();
        }else{
            $notifications=$this->all() ;
        }
        $notifications = self::brief($notifications);
        return apiResponse2(1, 'retrieved', trans('lms/public.retrieved'), $notifications);
    }

    public static function brief($notifications)
    {
        $notifications = $notifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'title' => $notification->title,
                'message' => $notification->message,
                'type' => $notification->type,
                'status' => ($notification->notificationStatus) ? 'read' : 'unread',
                'created_at'=>$notification->created_at
            ];
        });
        return [
            'count' => count($notifications),
            'lms_notifications' => $notifications,
        ];

    }

    public function seen($id)
    {
        $user = apiAuth();
        $notification = Notification::where('id', $id)->first();
        if (!$notification) {
            abort(404);
        }
        $unReadNotifications = $user->getUnReadNotifications();

        if (!empty($unReadNotifications) and !$unReadNotifications->isEmpty()) {
            $notification = $unReadNotifications->where('id', $id)->first();

            if (!empty($notification)) {
                $status = NotificationStatus::where('user_id', $user->id)
                    ->where('notification_id', $notification->id)
                    ->first();

                if (empty($status)) {
                    NotificationStatus::create([
                        'user_id' => $user->id,
                        'notification_id' => $notification->id,
                        'seen_at' => time()
                    ]);
                    return apiResponse2(1, 'seen', trans('lms/api.notification.seen'));

                }
            }

            return apiResponse2(0, 'already_seen', trans('lms/api.notification.already_seen'));
        }

        return apiResponse2(0, 'already_seen', trans('lms/api.notification.already_seen'));
    }

    public function unRead()
    {
        $user = apiAuth();
        $unReadNotifications = $user->getUnReadNotifications();
        return $unReadNotifications;

    }

    public function read()
    {
        return $this->all()->diff($this->unRead());
    }

    public function all()
    {
        $user = apiAuth();
        $notifications = Notification::where(function ($query) use ($user) {
            $query->where('lms_notifications.user_id', $user->id)
                ->where('lms_notifications.type', 'single');
        })->orWhere(function ($query) use ($user) {
            if (!$user->isAdmin()) {
                $query->whereNull('lms_notifications.user_id')
                    ->whereNull('lms_notifications.group_id')
                    ->where('lms_notifications.type', 'all_users');
            }
        });

        $userGroup = $user->userGroup()->first();
        if (!empty($userGroup)) {
            $notifications->orWhere(function ($query) use ($userGroup) {
                $query->where('lms_notifications.group_id', $userGroup->group_id)
                    ->where('lms_notifications.type', 'group');
            });
        }

        $notifications->orWhere(function ($query) use ($user) {
            $query->whereNull('lms_notifications.user_id')
                ->whereNull('lms_notifications.group_id')
                ->where(function ($query) use ($user) {
                    if ($user->isUser()) {
                        $query->where('lms_notifications.type', 'students');
                    } elseif ($user->isTeacher()) {
                        $query->where('lms_notifications.type', 'instructors');
                    } elseif ($user->isOrganization()) {
                        $query->where('lms_notifications.type', 'organizations');
                    }
                });
        });

        $notifications = $notifications->orderBy('lms_notifications.created_at', 'DESC')->get();
        return $notifications;
        /*$notifications = $notifications->leftJoin('lms_notifications_status', 'lms_notifications.id', '=', 'lms_notifications_status.notification_id')
            ->selectRaw('lms_notifications.*, count(notifications_status.notification_id) AS `count`')
            ->groupBy('lms_notifications.id')
            ->orderBy('count', 'asc')
            ->orderBy('lms_notifications.created_at', 'DESC')
            ->get();*/
    }
}
