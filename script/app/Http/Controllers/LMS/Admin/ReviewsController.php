<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Reward;
use App\Models\LMS\RewardAccounting;
use App\Models\LMS\Webinar;
use App\Models\LMS\WebinarReview;
use Illuminate\Http\Request;

class ReviewsController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_reviews_lists');

        $query = WebinarReview::query();

        $totalReviews = deepClone($query)->count();
        $publishedReviews = deepClone($query)->where('status', 'active')->count();
        $ratesAverage = deepClone($query)->avg('rates');
        $classesWithoutReview = Webinar::where('status', Webinar::$active)->whereDoesntHave('reviews')->count();

        $query = $this->filters($query, $request);

        $reviews = $query->orderBy('created_at', 'desc')
            ->with([
                'webinar' => function ($query) {
                    $query->select('id', 'slug');
                },
                'bundle' => function ($query) {
                    $query->select('id', 'slug');
                },
                'creator' => function ($query) {
                    $query->select('id', 'full_name');
                },
            ])
            ->withCount([
                'comments'
            ])
            ->paginate(10);

        $data = [
            'pageTitle' => trans('lms/admin/main.reviews_list_title'),
            'totalReviews' => $totalReviews,
            'publishedReviews' => $publishedReviews,
            'ratesAverage' => round($ratesAverage, 2),
            'classesWithoutReview' => $classesWithoutReview,
            'reviews' => $reviews,
        ];

        $webinar_ids = $request->get('webinar_ids');
        if (!empty($webinar_ids)) {
            $data['webinars'] = Webinar::select('id')->whereIn('id', $webinar_ids)->get();
        }

        return view('lms.admin.reviews.lists', $data);
    }

    private function filters($query, $request)
    {
        $from = $request->get('from', null);
        $to = $request->get('to', null);
        $search = $request->get('search', null);
        $webinar_ids = $request->get('webinar_ids');
        $status = $request->get('status', null);

        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($search)) {
            $query->where('description', 'like', "%$search%");
        }

        if (!empty($webinar_ids)) {
            $query->whereIn('webinar_id', $webinar_ids);
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        return $query;
    }

    public function toggleStatus($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_reviews_status_toggle');

        $review = WebinarReview::findOrFail($id);

        $review->update([
            'status' => ($review->status == 'active') ? 'pending' : 'active',
        ]);

        if ($review->status == 'active') {
            $reviewReward = RewardAccounting::calculateScore(Reward::REVIEW_COURSES);
            RewardAccounting::makeRewardAccounting($review->creator_id, $reviewReward, Reward::REVIEW_COURSES, $review->id, true);
        }

        $toastData = [
            'title' => trans('lms/public.request_success'),
            'msg' => 'Review status changed successful',
            'status' => 'success'
        ];
        return back()->with(['toast' => $toastData]);
    }

    public function reply(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_reviews_reply');

        $review = WebinarReview::findOrFail($id);

        $data = [
            'pageTitle' => trans('lms/admin/pages/comments.reply_comment'),
            'review' => $review,
        ];

        return view('lms.admin.reviews.comment_reply', $data);
    }

    public function delete($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_reviews_status_toggle');

        $review = WebinarReview::findOrFail($id);

        $review->delete();

        $toastData = [
            'title' => trans('lms/public.request_success'),
            'msg' => 'Review deleted successful',
            'status' => 'success'
        ];
        return back()->with(['toast' => $toastData]);
    }
}
