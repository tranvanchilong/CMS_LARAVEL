<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\ForumFeaturedTopic;
use Illuminate\Http\Request;

class FeaturedTopicsController extends Controller
{
    public function index()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_featured_topics_list');

        $featuredTopics = ForumFeaturedTopic::orderBy('created_at', 'desc')
            ->with([
                'topic'
            ])
            ->paginate(10);

        $data = [
            'pageTitle' => trans('lms/update.featured_topics'),
            'featuredTopics' => $featuredTopics
        ];

        return view('lms.admin.forums.featured_topics.lists', $data);
    }

    public function create()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_featured_topics_create');

        $data = [
            'pageTitle' => trans('lms/update.new_featured_topic'),
        ];

        return view('lms.admin.forums.featured_topics.create', $data);
    }

    public function store(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_featured_topics_create');

        $this->validate($request, [
            'topic_id' => 'required|exists:lms_forum_topics,id',
            'icon' => 'required'
        ]);

        $data = $request->all();

        ForumFeaturedTopic::create([
            'topic_id' => $data['topic_id'],
            'icon' => $data['icon'],
            'created_at' => time()
        ]);

        return redirect('/lms/admin/featured-topics');
    }

    public function edit($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_featured_topics_edit');

        $feature = ForumFeaturedTopic::where('id', $id)
            ->with([
                'topic'
            ])
            ->first();

        if (!empty($feature)) {
            $data = [
                'pageTitle' => trans('lms/update.edit_featured_topic'),
                'feature' => $feature
            ];

            return view('lms.admin.forums.featured_topics.create', $data);
        }

        abort(404);
    }

    public function update(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_featured_topics_edit');

        $this->validate($request, [
            'topic_id' => 'required|exists:lms_forum_topics,id',
            'icon' => 'required'
        ]);

        $feature = ForumFeaturedTopic::findOrFail($id);

        $data = $request->all();

        $feature->update([
            'topic_id' => $data['topic_id'],
            'icon' => $data['icon'],
        ]);

        return redirect('/lms/admin/featured-topics');
    }

    public function destroy($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_featured_topics_delete');

        $feature = ForumFeaturedTopic::findOrFail($id);

        $feature->delete();

        return back();
    }
}
