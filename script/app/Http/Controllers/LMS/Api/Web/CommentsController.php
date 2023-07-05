<?php

namespace App\Http\Controllers\LMS\Api\Web;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Comment;
use App\Models\LMS\CommentReport;
use App\Models\LMS\Webinar;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CommentsController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'item_id' => 'required',
            'item_name' => ['required', Rule::in(['blog', 'webinar'])],
            'comment' => 'required|string',
        ]);

        $user = auth()->guard('lms_user')->user();
        $item_name = $request->input('item_name');
        $item_id = $request->input('item_id');
        if ($item_name == 'webinar') {


        } elseif ($item_name == 'blog') {
            $rules['item_id'] = 'required|exists:blog';

        }
        $item_name = $item_name . '_id';

        Comment::create([
            $item_name => $item_id,
            'user_id' => $user->id,
            'comment' => $request->input('comment'),
            'reply_id' => $request->input('reply_id'),
            'status' => $request->input('status') ?? Comment::$pending,
            'created_at' => time()
        ]);

        if ($item_name == 'webinar_id') {
            $webinar = Webinar::FindOrFail($item_id);
            $notifyOptions = [
                '[c.title]' => $webinar->title,
                '[u.name]' => $user->full_name
            ];
            sendNotification('new_comment', $notifyOptions, 1);
        }

        return apiResponse2(1, 'stored', trans('lms/public.stored'));
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
            'status' => $request->input('status') ?? Comment::$pending,
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
                'status' => $request->input('status') ?? Comment::$pending,
                'created_at' => time()
            ]);

            return redirect()->back();
        }

        abort(404);
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

        CommentReport::create([
            $item_name => $item_id,
            'user_id' => auth()->guard('lms_user')->id(),
            'comment_id' => $comment->id,
            'message' => $data['message'],
            'created_at' => time()
        ]);

        return response()->json([
            'code' => 200
        ], 200);
    }
}
