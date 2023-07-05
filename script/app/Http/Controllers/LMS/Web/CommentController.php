<?php

namespace App\Http\Controllers\LMS\Web;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Comment;
use App\Models\LMS\CommentReport;
use App\Models\LMS\Reward;
use App\Models\LMS\RewardAccounting;
use App\Models\LMS\Webinar;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'item_id' => 'required',
            'comment' => 'required|string',
        ]);

        $user = auth()->guard('lms_user')->user();
        $item_name = $request->get('item_name');
        $item_id = $request->get('item_id');

        $comment = Comment::create([
            $item_name => $item_id,
            'user_id' => $user->id,
            'comment' => $request->input('comment'),
            'reply_id' => $request->input('reply_id'),
            'status' => $this->getStatus(),
            'created_at' => time()
        ]);

        if ($item_name == 'webinar_id') {
            $webinar = Webinar::FindOrFail($item_id);
            $notifyOptions = [
                '[c.title]' => $webinar->title,
                '[u.name]' => $user->full_name
            ];
            sendNotification('new_comment', $notifyOptions, 1);
        } elseif ($item_name == 'product_id') {
            $product = $comment->product;

            $notifyOptions = [
                '[p.title]' => $product->title,
                '[u.name]' => $user->full_name
            ];
            sendNotification('product_new_comment', $notifyOptions, 1);
        } elseif ($item_name == 'blog_id') {
            $blog = $comment->blog;

            if (!empty($blog) and !$blog->author->isAdmin()) {
                $notifyOptions = [
                    '[blog_title]' => $blog->title,
                    '[u.name]' => $user->full_name
                ];
                sendNotification('new_comment_for_instructor_blog_post', $notifyOptions, $blog->author->id);

                $buyStoreReward = RewardAccounting::calculateScore(Reward::COMMENT_FOR_INSTRUCTOR_BLOG);
                RewardAccounting::makeRewardAccounting($comment->user_id, $buyStoreReward, Reward::COMMENT_FOR_INSTRUCTOR_BLOG, $comment->id);
            }
        }

        $toastData = [
            'title' => trans('lms/product.comment_success_store'),
            'msg' => trans('lms/product.comment_success_store_msg'),
            'status' => 'success'
        ];
        return redirect()->back()->with(['toast' => $toastData]);
    }

    public function storeReply(Request $request)
    {
        $this->validate($request, [
            'item_id' => 'required',
            'reply' => 'required|string',
        ]);

        $item_name = $request->get('item_name');
        $item_id = $request->get('item_id');

        Comment::create([
            $item_name => $item_id,
            'user_id' => auth()->guard('lms_user')->user()->id,
            'comment' => $request->input('reply'),
            'reply_id' => $request->input('comment_id'),
            'status' => $this->getStatus(),
            'created_at' => time()
        ]);

        $toastData = [
            'title' => trans('lms/product.comment_success_store'),
            'msg' => trans('lms/product.comment_success_store_msg'),
            'status' => 'success'
        ];
        return back()->with(['toast' => $toastData]);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->guard('lms_user')->user();

        $this->validate($request, [
            'webinar_id' => 'required',
            'comment' => 'nullable',
        ]);

        $comment = Comment::where('id', $id)
            ->where('user_id', $user->id)
            ->first();
        if (!empty($comment)) {
            $comment->update([
                'webinar_id' => $request->input('webinar_id'),
                'user_id' => $user->id,
                'comment' => $request->input('comment'),
                'reply_id' => $request->input('reply_id'),
                'status' => $this->getStatus(),
                'created_at' => time()
            ]);

            return redirect()->back();
        }

        abort(404);
    }

    public function getStatus()
    {
        $status = Comment::$pending;
        if (!empty(getGeneralOptionsSettings('direct_publication_of_comments'))) {
            $status = Comment::$active;
        }

        return $status;
    }

    public function destroy(Request $request, $id)
    {
        $user = auth()->guard('lms_user')->user();
        $comment = Comment::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!empty($comment)) {
            $comment->delete();
        }

        return redirect()->back();
    }

    public function report(Request $request, $id)
    {
        $comment = comment::findOrFail($id);

        $this->validate($request, [
            'item_id' => 'required',
            'message' => 'required',
        ]);

        $item_name = $request->get('item_name');
        $item_id = $request->get('item_id');
        $data = $request->all();

        $user = auth()->guard('lms_user')->user();

        if (!empty($user)) {
            CommentReport::create([
                $item_name => $item_id,
                'user_id' => $user->id,
                'comment_id' => $comment->id,
                'message' => $data['message'],
                'created_at' => time()
            ]);

            $notifyOptions = [
                '[u.name]' => $user->full_name,
                '[content_type]' => trans('lms/admin/main.comment')
            ];
            sendNotification("new_report_item_for_admin", $notifyOptions, 1);
        }

        return response()->json([
            'code' => 200
        ], 200);
    }
}
