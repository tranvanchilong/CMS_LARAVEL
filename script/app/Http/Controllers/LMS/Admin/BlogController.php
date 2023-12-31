<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Blog;
use App\Models\LMS\BlogCategory;
use App\Models\LMS\BlogTranslation;
use App\Models\LMS\Role;
use App\Models\LMS\User;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        removeContentLocale();

        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_blog_lists');

        $query = Blog::query();

        $blog = $this->filters($query, $request)
            ->with(['category', 'author' => function ($query) {
                $query->select('id', 'full_name');
            }])
            ->withCount('comments')
            ->orderBy('updated_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $blogCategories = BlogCategory::all();
        $adminRoleIds = Role::where('is_admin', true)->pluck('id')->toArray();
        $authors = User::select('id', 'full_name', 'role_id')->whereIn('role_id', $adminRoleIds)->get();

        $data = [
            'pageTitle' => trans('lms/admin/pages/blog.blog'),
            'blog' => $blog,
            'blogCategories' => $blogCategories,
            'authors' => $authors,
        ];

        return view('lms.admin.blog.lists', $data);
    }

    private function filters($query, $request)
    {
        $from = $request->get('from', null);
        $to = $request->get('to', null);
        $title = $request->get('title', null);
        $category_id = $request->get('category_id', null);
        $author_id = $request->get('author_id', null);
        $status = $request->get('status', null);

        $query = fromAndToDateFilter($from, $to, $query, 'created_at');


        if (!empty($title)) {
            $query->whereTranslationLike('title', '%' . $title . '%');
        }

        if (!empty($category_id)) {
            $query->where('category_id', $category_id);
        }

        if (!empty($author_id)) {
            $query->where('author_id', $author_id);
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        return $query;
    }

    public function create()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_blog_create');

        $categories = BlogCategory::all();

        $data = [
            'pageTitle' => trans('lms/admin/pages/blog.create_blog'),
            'categories' => $categories
        ];

        return view('lms.admin.blog.create', $data);
    }

    public function store(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_blog_create');

        $this->validate($request, [
            'locale' => 'required',
            'title' => 'required|string|max:255',
            'category_id' => 'required|numeric',
            'image' => 'required|string',
            'description' => 'required|string',
            'content' => 'required|string',
        ]);

        $data = $request->all();

        $blog = Blog::create([
            'slug' => Blog::makeSlug($data['title']),
            'category_id' => $data['category_id'],
            'author_id' => !empty($data['author_id']) ? $data['author_id'] : auth()->guard('lms_user')->id(),
            'image' => $data['image'],
            'enable_comment' => (!empty($data['enable_comment']) and $data['enable_comment'] == 'on'),
            'status' => (!empty($data['status']) and $data['status'] == 'on') ? 'publish' : 'pending',
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        if ($blog) {
            BlogTranslation::updateOrCreate([
                'blog_id' => $blog->id,
                'locale' => mb_strtolower($data['locale']),
            ], [
                'title' => $data['title'],
                'description' => $data['description'],
                'meta_description' => $data['meta_description'],
                'content' => $data['content'],
            ]);

            if ($blog->status == 'publish' and $blog->author_id != auth()->guard('lms_user')->id()) {
                $notifyOptions = [
                    '[blog_title]' => $blog->title,
                ];
                sendNotification('publish_instructor_blog_post', $notifyOptions, $blog->author_id);
            }
        }

        return redirect('/lms/admin/blog');
    }

    public function edit(Request $request, $post_id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_blog_edit');

        $post = Blog::findOrFail($post_id);

        $locale = $request->get('locale', app()->getLocale());
        storeContentLocale($locale, $post->getTable(), $post->id);

        $categories = BlogCategory::all();

        $data = [
            'pageTitle' => trans('lms/admin/pages/blog.create_blog'),
            'categories' => $categories,
            'post' => $post,
        ];

        return view('lms.admin.blog.create', $data);
    }

    public function update(Request $request, $post_id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_blog_edit');

        $this->validate($request, [
            'title' => 'required|string|max:255',
            'category_id' => 'required|numeric',
            'image' => 'required|string',
            'description' => 'required|string',
            'content' => 'required|string',
        ]);

        $data = $request->all();
        $post = Blog::findOrFail($post_id);

        $post->update([
            'category_id' => $data['category_id'],
            'author_id' => !empty($data['author_id']) ? $data['author_id'] : $post->author_id,
            'image' => $data['image'],
            'enable_comment' => (!empty($data['enable_comment']) and $data['enable_comment'] == 'on'),
            'status' => (!empty($data['status']) and $data['status'] == 'on') ? 'publish' : 'pending',
            'updated_at' => time(),
        ]);


        BlogTranslation::updateOrCreate([
            'blog_id' => $post->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
            'description' => $data['description'],
            'meta_description' => $data['meta_description'],
            'content' => $data['content'],
        ]);

        removeContentLocale();

        if ($post->status == 'publish' and $post->author_id != auth()->guard('lms_user')->id()) {
            $notifyOptions = [
                '[blog_title]' => $post->title,
            ];
            sendNotification('publish_instructor_blog_post', $notifyOptions, $post->author_id);
        }

        return redirect('/lms/admin/blog');
    }

    public function delete($post_id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_blog_delete');

        $post = Blog::findOrFail($post_id);

        $post->delete();

        return redirect('/lms/admin/blog');
    }

    public function search(Request $request)
    {
        $term = $request->get('term');
        $blog = Blog::select('id')
            ->whereTranslationLike('title', "%$term%")
            ->get();

        return response()->json($blog, 200);
    }
}
