<?php

namespace App\Http\Controllers\LMS\Admin\Store;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Product;
use App\Models\LMS\ProductReview;
use App\Models\LMS\Reward;
use App\Models\LMS\RewardAccounting;
use Illuminate\Http\Request;

class ReviewsController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_store_products_reviews');

        $query = ProductReview::query();

        $totalReviews = deepClone($query)->count();
        $publishedReviews = deepClone($query)->where('status', 'active')->count();
        $ratesAverage = deepClone($query)->avg('rates');
        $productsWithoutReview = Product::where('status', Product::$active)->whereDoesntHave('reviews')->count();

        $query = $this->filters($query, $request);

        $reviews = $query->orderBy('created_at', 'desc')
            ->with([
                'product' => function ($query) {
                    $query->select('id', 'slug');
                },
                'creator' => function ($query) {
                    $query->select('id', 'full_name');
                },
            ])
            ->withCount('comments')
            ->paginate(10);

        $data = [
            'pageTitle' => trans('lms/update.admin_store_reviews_list_title'),
            'totalReviews' => $totalReviews,
            'publishedReviews' => $publishedReviews,
            'ratesAverage' => round($ratesAverage, 2),
            'productsWithoutReview' => $productsWithoutReview,
            'reviews' => $reviews,
        ];

        $product_ids = $request->get('product_ids');
        if (!empty($product_ids)) {
            $data['products'] = Product::select('id')->whereIn('id', $product_ids)->get();
        }

        return view('lms.admin.store.reviews.lists', $data);
    }

    private function filters($query, $request)
    {
        $from = $request->get('from', null);
        $to = $request->get('to', null);
        $search = $request->get('search', null);
        $product_ids = $request->get('product_ids');
        $status = $request->get('status', null);

        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($search)) {
            $query->where('description', 'like', "%$search%");
        }

        if (!empty($product_ids)) {
            $query->whereIn('product_id', $product_ids);
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        return $query;
    }

    public function toggleStatus($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_store_products_reviews_status_toggle');

        $review = ProductReview::findOrFail($id);

        $review->update([
            'status' => ($review->status == 'active') ? 'pending' : 'active',
        ]);

        /*if ($review->status == 'active') {
            $reviewReward = RewardAccounting::calculateScore(Reward::REVIEW_COURSES);
            RewardAccounting::makeRewardAccounting($review->creator_id, $reviewReward, Reward::REVIEW_COURSES, $review->id, true);
        }*/

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

        $review = ProductReview::findOrFail($id);

        $data = [
            'pageTitle' => trans('lms/admin/pages/comments.reply_comment'),
            'review' => $review,
        ];

        return view('lms.admin.store.reviews.comment_reply', $data);
    }

    public function delete($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_store_products_reviews_delete');

        $review = ProductReview::findOrFail($id);

        $review->delete();

        $toastData = [
            'title' => trans('lms/public.request_success'),
            'msg' => 'Review deleted successful',
            'status' => 'success'
        ];
        return back()->with(['toast' => $toastData]);
    }
}
