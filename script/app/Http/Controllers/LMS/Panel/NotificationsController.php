<?php

namespace App\Http\Controllers\LMS\Panel;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Notification;
use App\Models\LMS\NotificationStatus;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index()
    {
        $user = auth()->guard('lms_user')->user();

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

        /* Get Course Students Notifications */
        $userBoughtWebinarsIds = $user->getAllPurchasedWebinarsIds();

        if (!empty($userBoughtWebinarsIds)) {
            $notifications->orWhere(function ($query) use ($userBoughtWebinarsIds) {
                $query->whereIn('webinar_id', $userBoughtWebinarsIds)
                    ->where('type', 'course_students');
            });
        }

        $notifications = $notifications->leftJoin('lms_notifications_status', 'lms_notifications.id', '=', 'lms_notifications_status.notification_id')
            ->selectRaw('lms_notifications.*, count(lms_notifications_status.notification_id) AS `count`')
            ->with(['notificationStatus'])
            ->groupBy('lms_notifications.id')
            ->orderBy('count', 'asc')
            ->orderBy('lms_notifications.created_at', 'DESC')
            ->paginate(10);

        $data = [
            'pageTitle' => trans('lms/panel.notifications'),
            'notifications' => $notifications
        ];

        return view('lms.'. getTemplate() . '.panel.notifications.index', $data);
    }

    public function saveStatus($id)
    {
        $user = auth()->guard('lms_user')->user();

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
                }
            }
        }

        return response()->json([], 200);
    }

    public function markAllAsRead()
    {
        $user = auth()->guard('lms_user')->user();

        if (!empty($user)) {
            $unReadNotifications = $user->getUnReadNotifications();

            if (!empty($unReadNotifications) and !$unReadNotifications->isEmpty()) {
                foreach ($unReadNotifications as $notification) {
                    $status = NotificationStatus::where('user_id', $user->id)
                        ->where('notification_id', $notification->id)
                        ->first();

                    if (empty($status)) {
                        NotificationStatus::create([
                            'user_id' => $user->id,
                            'notification_id' => $notification->id,
                            'seen_at' => time()
                        ]);
                    }
                }
            }
        }

        return response()->json([
            'code' => 200,
            'title' => trans('lms/public.request_success'),
            'text' => trans('lms/update.all_your_notifications_have_been_marked_as_read'),
            'timeout' => 2000
        ]);
    }
}
