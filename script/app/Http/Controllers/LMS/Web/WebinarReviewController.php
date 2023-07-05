<?php

namespace App\Http\Controllers\LMS\Web;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Comment;
use App\Models\LMS\Webinar;
use App\Models\LMS\WebinarReview;
use Illuminate\Http\Request;

class WebinarReviewController extends Controller
{

    public function store(Request $request)
    {
        $this->validate($request, [
            'webinar_id' => 'required',
            'content_quality' => 'required',
            'instructor_skills' => 'required',
            'purchase_worth' => 'required',
            'support_quality' => 'required',
        ]);

        $data = $request->all();
        $user = auth()->guard('lms_user')->user();

        $webinar = Webinar::where('id', $data['webinar_id'])
            ->where('status', 'active')
            ->first();

        if (!empty($webinar)) {
            if ($webinar->checkUserHasBought($user, false)) {
                $webinarReview = WebinarReview::where('creator_id', $user->id)
                    ->where('webinar_id', $webinar->id)
                    ->first();

                if (!empty($webinarReview)) {
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

                $status = Comment::$pending;
                if (!empty(getGeneralOptionsSettings('direct_publication_of_reviews'))) {
                    $status = Comment::$active;
                }

                WebinarReview::create([
                    'webinar_id' => $webinar->id,
                    'creator_id' => $user->id,
                    'content_quality' => (int)$data['content_quality'],
                    'instructor_skills' => (int)$data['instructor_skills'],
                    'purchase_worth' => (int)$data['purchase_worth'],
                    'support_quality' => (int)$data['support_quality'],
                    'rates' => $rates > 0 ? $rates / 4 : 0,
                    'description' => $data['description'],
                    'status' => $status,
                    'created_at' => time(),
                ]);


                $notifyOptions = [
                    '[c.title]' => $webinar->title,
                    '[item_title]' => $webinar->title,
                    '[student.name]' => $user->full_name,
                    '[u.name]' => $user->full_name,
                    '[rate.count]' => $rates > 0 ? $rates / 4 : 0,
                    '[content_type]' => trans('lms/admin/main.course'),
                ];
                sendNotification('new_rating', $notifyOptions, $webinar->teacher_id);
                sendNotification('new_user_item_rating', $notifyOptions, 1);

                $toastData = [
                    'title' => trans('lms/public.request_success'),
                    'msg' => ($status == Comment::$active) ? trans('lms/webinars.your_reviews_successfully_submitted') : trans('lms/webinars.your_reviews_successfully_submitted_and_waiting_for_admin'),
                    'status' => 'success'
                ];
                return back()->with(['toast' => $toastData]);
            } else {
                $toastData = [
                    'title' => trans('lms/public.request_failed'),
                    'msg' => trans('lms/cart.you_not_purchased_this_course'),
                    'status' => 'error'
                ];
                return back()->with(['toast' => $toastData]);
            }
        }

        $toastData = [
            'title' => trans('lms/public.request_failed'),
            'msg' => trans('lms/cart.course_not_found'),
            'status' => 'error'
        ];
        return back()->with(['toast' => $toastData]);
    }

    public function storeReplyComment(Request $request)
    {
        $this->validate($request, [
            'reply' => 'nullable',
        ]);

        $status = Comment::$pending;
        if (!empty(getGeneralOptionsSettings('direct_publication_of_comments'))) {
            $status = Comment::$active;
        }

        Comment::create([
            'user_id' => auth()->guard('lms_user')->user()->id,
            'comment' => $request->input('reply'),
            'review_id' => $request->input('comment_id'),
            'status' => $status,
            'created_at' => time()
        ]);

        $toastData = [
            'title' => trans('lms/product.comment_success_store'),
            'msg' => trans('lms/product.comment_success_store_msg'),
            'status' => 'success'
        ];
        return redirect()->back()->with(['toast' => $toastData]);
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
