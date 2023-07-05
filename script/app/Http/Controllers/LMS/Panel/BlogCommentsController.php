<?php

namespace App\Http\Controllers\LMS\Panel;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Blog;
use App\Models\LMS\Comment;
use Illuminate\Http\Request;

class BlogCommentsController extends Controller
{
    private function handleAuthorize($user)
    {
        if (!$user->isTeacher()) {
            abort(403);
        }
    }

    public function index(Request $request)
    {
        $user = auth()->guard('lms_user')->user();

        $this->handleAuthorize($user);

        $posts = Blog::select('id', 'author_id')->where('author_id', $user->id)
            ->get();

        $blogIds = $posts->pluck('id')->toArray();

        $query = Comment::whereIn('blog_id', $blogIds);

        $comments = $this->handleFilters($request, $query)->orderBy('created_at', 'desc')
            ->with([
                'blog'
            ])
            ->paginate(10);

        $data = [
            'pageTitle' => trans('lms/panel.comments'),
            'posts' => $posts,
            'comments' => $comments,
        ];

        $blogId = $request->get('blog_id', null);

        if (!empty($blogId) and is_numeric($blogId)) {
            $data['selectedPost'] = Blog::where('id', $blogId)
                ->where('author_id', $user->id)
                ->first();
        }

        return view('lms.web.default.panel.blog.comments.index', $data);
    }

    private function handleFilters(Request $request, $query)
    {
        $from = $request->get('from', null);
        $to = $request->get('to', null);
        $blogId = $request->get('blog_id', null);

        fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($blogId) and is_numeric($blogId)) {
            $query->where('blog_id', $blogId);
        }

        return $query;
    }
}
