<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\BlogCategory;
use Illuminate\Http\Request;

class BlogCategoriesController extends Controller
{
    public function index()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_blog_categories');

        $blogCategories = BlogCategory::withCount('blog')->get();

        $data = [
            'pageTitle' => trans('lms/admin/pages/blog.blog_categories'),
            'blogCategories' => $blogCategories
        ];

        return view('lms.admin.blog.categories', $data);
    }

    public function store(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_blog_categories_create');

        $this->validate($request, [
            'title' => 'required|string|unique:lms_blog_categories',
        ]);

        BlogCategory::create([
            'title' => $request->get('title')
        ]);

        return redirect('/lms/admin/blog/categories');
    }

    public function edit($category_id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_blog_categories_edit');

        $editCategory = BlogCategory::findOrFail($category_id);

        $data = [
            'pageTitle' => trans('lms/admin/pages/blog.blog_categories'),
            'editCategory' => $editCategory
        ];

        return view('lms.admin.blog.categories', $data);
    }

    public function update(Request $request, $category_id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_blog_categories_edit');

        $this->validate($request, [
            'title' => 'required',
        ]);

        $editCategory = BlogCategory::findOrFail($category_id);

        $editCategory->update([
            'title' => $request->get('title')
        ]);

        return redirect('/lms/admin/blog/categories');
    }

    public function delete($category_id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_blog_categories_delete');

        $editCategory = BlogCategory::findOrFail($category_id);

        $editCategory->delete();

        return redirect('/lms/admin/blog/categories');
    }
}
