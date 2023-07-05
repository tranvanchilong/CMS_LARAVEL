<?php

namespace App\Http\Controllers\LMS\Admin;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\ForumRecommendedTopic;
use App\Models\LMS\ForumRecommendedTopicItem;
use Illuminate\Http\Request;

class RecommendedTopicsController extends Controller
{
    public function index()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_recommended_topics_list');

        $recommendedTopics = ForumRecommendedTopic::orderBy('created_at', 'desc')
            ->with([
                'topics'
            ])
            ->paginate(10);

        $data = [
            'pageTitle' => trans('lms/update.recommended_topics'),
            'recommendedTopics' => $recommendedTopics
        ];

        return view('lms.admin.forums.recommended_topics.lists', $data);
    }

    public function create()
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_recommended_topics_create');

        $data = [
            'pageTitle' => trans('lms/update.new_recommended_topic'),
        ];

        return view('lms.admin.forums.recommended_topics.create', $data);
    }

    public function store(Request $request)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_recommended_topics_create');

        $this->validate($request, [
            'topic_ids' => 'required|array|min:1',
            'title' => 'required|max:255',
            'icon' => 'required|max:255',
        ]);

        $data = $request->all();

        $recommended = ForumRecommendedTopic::create([
            'title' => $data['title'],
            'icon' => $data['icon'],
            'created_at' => time()
        ]);

        $this->handleTopicItems($recommended, $data['topic_ids']);

        return redirect('/lms/admin/recommended-topics');
    }

    private function handleTopicItems($recommended, $topicIds)
    {
        ForumRecommendedTopicItem::where('recommended_topic_id', $recommended->id)
            ->delete();

        if (!empty($topicIds)) {
            foreach ($topicIds as $topicId) {
                ForumRecommendedTopicItem::create([
                    'recommended_topic_id' => $recommended->id,
                    'topic_id' => $topicId,
                    'created_at' => time(),
                ]);
            }
        }
    }

    public function edit($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_recommended_topics_edit');

        $recommended = ForumRecommendedTopic::where('id', $id)
            ->with([
                'topics'
            ])
            ->first();

        if (!empty($recommended)) {
            $data = [
                'pageTitle' => trans('lms/update.edit_recommended_topic'),
                'recommended' => $recommended
            ];

            return view('lms.admin.forums.recommended_topics.create', $data);
        }

        abort(404);
    }

    public function update(Request $request, $id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_recommended_topics_edit');

        $this->validate($request, [
            'topic_ids' => 'required|array|min:1',
            'title' => 'required|max:255',
            'icon' => 'required|max:255',
        ]);

        $recommended = ForumRecommendedTopic::findOrFail($id);

        $data = $request->all();

        $recommended->update([
            'title' => $data['title'],
            'icon' => $data['icon'],
        ]);

        $this->handleTopicItems($recommended, $data['topic_ids']);

        return redirect('/lms/admin/recommended-topics');
    }

    public function destroy($id)
    {
        $this->authorizeForUser(auth()->guard('lms_user')->user(),'admin_recommended_topics_delete');

        $recommended = ForumRecommendedTopic::findOrFail($id);

        $recommended->delete();

        return back();
    }
}
