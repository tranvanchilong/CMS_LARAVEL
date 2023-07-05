<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Page;
use App\Models\LMS\PageTranslation;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index()
    {
        removeContentLocale();

        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_pages_list');

        $pages = Page::orderBy('created_at', 'desc')->paginate(10);

        $data = [
            'pageTitle' => trans('lms/admin/pages/setting.pages'),
            'pages' => $pages
        ];

        return view('lms.admin.pages.lists', $data);
    }

    public function create()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_pages_create');

        $data = [
            'pageTitle' => trans('lms/admin/pages/setting.new_pages')
        ];

        return view('lms.admin.pages.create', $data);
    }

    public function store(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_pages_create');

        $this->validate($request, [
            'locale' => 'required',
            'name' => 'required',
            'link' => 'required|unique:lms_pages,link',
            'title' => 'required',
            'seo_description' => 'nullable|string|max:255',
            'content' => 'required',
        ]);

        $data = $request->all();

        $firstCharacter = substr($data['link'], 0, 1);
        if ($firstCharacter !== '/') {
            $data['link'] = '/' . $data['link'];
        }

        $data['robot'] = (!empty($data['robot']) and $data['robot'] == '1');

        $page = Page::create([
            'link' => $data['link'],
            'name' => $data['name'],
            'robot' => $data['robot'],
            'status' => $data['status'],
            'created_at' => time(),
        ]);

        PageTranslation::updateOrCreate([
            'page_id' => $page->id,
            'locale' => mb_strtolower($data['locale'])
        ], [
            'title' => $data['title'],
            'seo_description' => $data['seo_description'] ?? null,
            'content' => $data['content'],
        ]);

        return redirect('/lms/admin/pages');
    }

    public function edit(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_pages_edit');

        $locale = $request->get('locale', app()->getLocale());

        $page = Page::findOrFail($id);

        storeContentLocale($locale, $page->getTable(), $page->id);

        $data = [
            'pageTitle' => trans('lms/admin/pages/setting.edit_pages') . $page->name,
            'page' => $page
        ];

        return view('lms.admin.pages.create', $data);
    }

    public function update(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_pages_edit');

        $page = Page::findOrFail($id);

        $this->validate($request, [
            'locale' => 'required',
            'name' => 'required',
            'link' => 'required|unique:lms_pages,link,' . $page->id,
            'title' => 'required',
            'seo_description' => 'nullable|string|max:255',
            'content' => 'required',
        ]);

        $data = $request->all();

        $firstCharacter = substr($data['link'], 0, 1);
        if ($firstCharacter !== '/') {
            $data['link'] = '/' . $data['link'];
        }

        $data['robot'] = (!empty($data['robot']) and $data['robot'] == '1');

        $page->update([
            'link' => $data['link'],
            'name' => $data['name'],
            'robot' => $data['robot'],
            'status' => $data['status'],
            'created_at' => time(),
        ]);

        PageTranslation::updateOrCreate([
            'page_id' => $page->id,
            'locale' => mb_strtolower($data['locale'])
        ], [
            'title' => $data['title'],
            'seo_description' => $data['seo_description'] ?? null,
            'content' => $data['content'],
        ]);

        removeContentLocale();

        return redirect('/lms/admin/pages');
    }

    public function delete($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_pages_delete');

        $page = Page::findOrFail($id);

        $page->delete();

        return redirect('/lms/admin/pages');
    }

    public function statusTaggle($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_pages_toggle');

        $page = Page::findOrFail($id);

        $page->update([
            'status' => ($page->status == 'draft') ? 'publish' : 'draft'
        ]);

        return redirect('/lms/admin/pages');
    }
}
