<?php

namespace App\Http\Controllers\LMS\Api\Panel;

use App\Http\Controllers\LMS\Controller;
use App\Http\Resources\CommentResource;
use App\Models\LMS\Api\Comment;
use App\Models\LMS\Blog;
use Illuminate\Http\Request;

class BlogCommentController extends Controller
{

    public function index(Request $request)
    {
        $user = apiAuth();
        $posts = Blog::where('author_id', $user->id)->get();
        $blogIds = $posts->pluck('id')->toArray();
        $comments = Comment::whereIn('blog_id', $blogIds)->handleFilters()->orderBy('created_at', 'desc')
            ->get();

        $blogId = $request->get('blog_id', null);

        if (!empty($blogId) and is_numeric($blogId)) {
            $data['selectedPost'] = Blog::where('id', $blogId)
                ->where('author_id', $user->id)
                ->first();
        }
        $resource = CommentResource::collection($comments);
     //   $resource->panel = true;
        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'),
            [
                'comments' => $resource,

            ]);

    }
}
