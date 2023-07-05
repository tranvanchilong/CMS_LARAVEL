<?php

namespace App\Http\Controllers\LMS\Api\Panel;

use App\Http\Controllers\LMS\Controller;
use App\Http\Resources\BlogResource;
use App\Http\Resources\CommentResource;
use App\Models\LMS\Blog;
use App\Models\LMS\Api\Comment;
use Illuminate\Http\Request;

class BlogController extends Controller
{

    public function index()
    {
        $user = apiAuth();
        $query = Blog::where('author_id', $user->id);

        $posts = deepClone($query)
            ->orderBy('created_at', 'desc')
            ->get();

        $blogIds = deepClone($query)->pluck('id')->toArray();

        $postsCount = count($blogIds);
        $commentsCount = Comment::whereIn('blog_id', $blogIds)->count();
        $pendingPublishCount = deepClone($query)->where('status', 'pending')->count();

        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'),
            [
                'posts_count' => $postsCount,
                'comment_count' => $commentsCount,
                'pending_publish_count' => $pendingPublishCount,
                'blogs' => BlogResource::collection($posts)
            ]);

    }

    public function show(Blog $blog)
    {
        if ($blog->author_id != apiauth()->guard('lms_user')->id) {
            abort(404);
        }
        $resource = new BlogResource($blog);
        $resource->show = true;

        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'),
            [
                'blog' => $resource,

            ]);
    }


}
