<?php

namespace App\Http\Controllers\LMS\Web;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Bundle;
use App\Models\LMS\Comment;
use App\Models\LMS\Webinar;
use App\Models\LMS\WebinarReview;
use Illuminate\Http\Request;

class BundleReviewController extends Controller
{

    public function store(Request $request)
    {
        $this->validate($request, [
            'bundle_id' => 'required',
            'content_quality' => 'required',
            'instructor_skills' => 'required',
            'purchase_worth' => 'required',
            'support_quality' => 'required',
        ]);

        $data = $request->all();
        $user = auth()->guard('lms_user')->user();

        $bundle = Bundle::where('id', $data['bundle_id'])
            ->where('status', 'active')
            ->first();

        if (!empty($bundle)) {
            if ($bundle->checkUserHasBought($user, false)) {
                $bundleReview = WebinarReview::where('creator_id', $user->id)
                    ->where('bundle_id', $bundle->id)
                    ->first();

                if (!empty($bundleReview)) {
                    $toastData = [
                        'title' => trans('lms/public.request_failed'),
                        'msg' => trans('lms/public.duplicate_review_for_webinar'),
                        'status' => 'error'
                    ];
                    return back()->with(['toast' => $toastData]);
                }

                $rates = 0;
                $rates += (int)$data['content_quality'];
                $rates += (int)$data['instructor_skills'];
                $rates += (int)$data['purchase_worth'];
                $rates += (int)$data['support_quality'];

                WebinarReview::create([
                    'bundle_id' => $bundle->id,
                    'creator_id' => $user->id,
                    'content_quality' => (int)$data['content_quality'],
                    'instructor_skills' => (int)$data['instructor_skills'],
                    'purchase_worth' => (int)$data['purchase_worth'],
                    'support_quality' => (int)$data['support_quality'],
                    'rates' => $rates > 0 ? $rates / 4 : 0,
                    'description' => $data['description'],
                    'status' => 'pending',
                    'created_at' => time(),
                ]);


                $notifyOptions = [
                    '[item_title]' => $bundle->title,
                    '[u.name]' => $user->full_name,
                    '[rate.count]' => $rates > 0 ? $rates / 4 : 0,
                    '[content_type]' => trans('lms/update.bundle'),
                ];
                sendNotification('new_review_for_bundle', $notifyOptions, $bundle->teacher_id);
                sendNotification('new_user_item_rating', $notifyOptions, 1);

                $toastData = [
                    'title' => trans('lms/public.request_success'),
                    'msg' => trans('lms/webinars.your_reviews_successfully_submitted_and_waiting_for_admin'),
                    'status' => 'success'
                ];
                return back()->with(['toast' => $toastData]);
            } else {
                $toastData = [
                    'title' => trans('lms/public.request_failed'),
                    'msg' => trans('lms/update.you_not_purchased_this_bundle'),
                    'status' => 'error'
                ];
                return back()->with(['toast' => $toastData]);
            }
        }

        $toastData = [
            'title' => trans('lms/public.request_failed'),
            'msg' => trans('lms/update.bundle_not_found'),
            'status' => 'error'
        ];
        return back()->with(['toast' => $toastData]);
    }

    public function storeReplyComment(Request $request)
    {
        $this->validate($request, [
            'reply' => 'nullable',
        ]);

        Comment::create([
            'user_id' => auth()->guard('lms_user')->user()->id,
            'comment' => $request->input('reply'),
            'review_id' => $request->input('comment_id'),
            'status' => $request->input('status') ?? Comment::$pending,
            'created_at' => time()
        ]);

        return redirect()->back();
    }

    public function destroy(Request $request, $id)
    {
        if (auth()->guard('lms_user')->check()) {
            $review = WebinarReview::where('id', $id)
                ->where('creator_id', auth()->guard('lms_user')->id())
                ->first();

            if (!empty($review)) {
                $review->delete();

                $toastData = [
                    'title' => trans('lms/public.request_success'),
                    'msg' => trans('lms/webinars.your_review_deleted'),
                    'status' => 'success'
                ];
                return back()->with(['toast' => $toastData]);
            }

            $toastData = [
                'title' => trans('lms/public.request_failed'),
                'msg' => trans('lms/webinars.you_not_access_review'),
                'status' => 'error'
            ];
            return back()->with(['toast' => $toastData]);
        }

        abort(404);
    }
}
