<?php

namespace App\Http\Controllers\LMS\Api\Panel;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Comment;
use App\Models\LMS\Webinar;
use App\Models\LMS\Api\WebinarReview;
use Illuminate\Http\Request;

class WebinarReviewController extends Controller
{
    public function list(Request $request)
    {
        $user = apiAuth();
        //  $user=User::find(1) ;
        $webinarReview = WebinarReview::where('creator_id', $user->id)->get()->map(function ($review) {
            return $review->details;
        });
        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'), $webinarReview);
    }

    public function store(Request $request)
    {
        $rules = [
            'webinar_id' => 'required',
            'content_quality' => 'required',
            'instructor_skills' => 'required',
            'purchase_worth' => 'required',
            'support_quality' => 'required',
            'description' => 'required',
        ];
        validateParam($request->all(), $rules);
        $user = apiAuth();
        $webinar = Webinar::where('id', $request->input('webinar_id'))
            ->where('status', 'active')
            ->first();

        if (!$webinar) {
            abort(404);
        }

        if (!$webinar->checkUserHasBought($user)) {
            return apiResponse2(0, 'not_purchased',
                trans('lms/cart.you_not_purchased_this_course'), null,
                trans('lms/public.request_failed')
            );
        }

        $webinarReview = WebinarReview::where('creator_id', $user->id)
            ->where('webinar_id', $webinar->id)
            ->first();

        if (!empty($webinarReview)) {
            return apiResponse2(0, 'already_sent',
                trans('lms/public.duplicate_review_for_webinar'),
                null,
                trans('lms/public.request_failed')
            );
        }

        $rates = 0;
        $rates += (int)$request->input('content_quality');
        $rates += (int)$request->input('instructor_skills');
        $rates += (int)$request->input('purchase_worth');
        $rates += (int)$request->input('support_quality');

        WebinarReview::create([
            'webinar_id' => $webinar->id,
            'creator_id' => $user->id,
            'content_quality' => (int)$request->input('content_quality'),
            'instructor_skills' => (int)$request->input('instructor_skills'),
            'purchase_worth' => (int)$request->input('purchase_worth'),
            'support_quality' => (int)$request->input('support_quality'),
            'rates' => $rates > 0 ? $rates / 4 : 0,
            'description' => $request->input('description'),
            'created_at' => time(),
        ]);

        $notifyOptions = [
            '[c.title]' => $webinar->title,
            '[student.name]' => $user->full_name,
            '[rate.count]' => $rates / 4
        ];
        sendNotification('new_rating', $notifyOptions, $webinar->teacher_id);

        return apiResponse2(1, 'stored',
            trans('lms/webinars.your_reviews_successfully_submitted_and_waiting_for_admin') ,
            trans('lms/public.request_success')
        );


    }

    public function reply(Request $request, $id)
    {
        validateParam($request->all(), [
            'reply' => 'required'
        ]);
        if (empty(WebinarReview::find($id))) {
            abort(404);
        }

        Comment::create([
            'user_id' => apiauth()->guard('lms_user')->id,
            'comment' => $request->input('reply'),
            'review_id' => $id,
            'status' => $request->input('status') ?? Comment::$pending,
            'created_at' => time()
        ]);

        return apiResponse2(1, 'stored', trans('lms/api.public.stored'));

    }

    public function destroy(Request $request, $id)
    {
        $user = apiAuth();
        $review = WebinarReview::where('id', $id)
            ->where('creator_id', $user->id)
            ->first();
        if (!$review) {
            abort(404);
        }
        $review->delete();
        return apiResponse2(1, 'deleted',trans('lms/webinars.your_review_deleted'),null,
            trans('lms/public.request_success')
        );

    }
}
