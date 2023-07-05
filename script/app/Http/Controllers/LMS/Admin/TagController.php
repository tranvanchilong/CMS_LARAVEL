<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_tags_list');

        $tags = Tag::orderBy('id','desc')
            ->paginate(10);;
        $data = [
            'pageTitle' => trans('lms/admin/pages/tags.tags_list_page_title'),
            'tags' => $tags
        ];

        return view('lms.admin.tags.lists', $data);
    }

    public function create()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_tags_create');

        $data = [
            'pageTitle' => trans('lms/admin/main.tag_new_page_title'),
        ];

        return view('lms.admin.tags.create', $data);
    }

    public function store(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_tags_create');

        $this->validate($request, [
            'title' => 'required|min:3|max:128',
        ]);

        $tag = Tag::create([
            'title' => $request->input('title'),
        ]);


        return redirect('/lms/admin/tags');
    }

    public function edit($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_tags_edit');

        $tag = Tag::findOrFail($id);
        $data = [
            'pageTitle' => trans('lms/admin/pages/tags.edit_page_title'),
            'tag' => $tag,
        ];

        return view('lms.admin.tags.create', $data);
    }

    public function update(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_tags_edit');

        $this->validate($request, [
            'title' => 'required|min:3|max:128',
        ]);
        $tag = Tag::findOrFail($id);
        $tag->update([
            'title' => $request->input('title'),
        ]);

        return redirect('/lms/admin/tags');
    }

    public function destroy(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_tags_delete');

        Tag::find($id)->delete();

        return redirect('/lms/admin/tags');
    }
}
