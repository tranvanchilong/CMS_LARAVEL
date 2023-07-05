<?php

namespace App\Http\Controllers\LMS\Panel;

use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\File;
use App\Models\LMS\Quiz;
use App\Models\LMS\Session;
use App\Models\LMS\TextLesson;
use App\Models\LMS\WebinarChapterTranslation;
use App\Models\LMS\Webinar;
use App\Models\LMS\WebinarAssignment;
use App\Models\LMS\WebinarChapter;
use App\Models\LMS\WebinarChapterItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ChapterController extends Controller
{
    public function getChapter(Request $request, $id)
    {
        $user = auth()->guard('lms_user')->user();

        $chapter = WebinarChapter::where('id', $id)->first();

        if (!empty($chapter)) {
            $webinar = Webinar::query()->find($chapter->webinar_id);

            if ($chapter->user_id == $user->id or (!empty($webinar) and $webinar->canAccess($user))) {

                $locale = $request->get('locale', app()->getLocale());

                foreach ($chapter->translatedAttributes as $attribute) {
                    try {
                        $chapter->$attribute = $chapter->translate(mb_strtolower($locale))->$attribute;
                    } catch (\Exception $e) {
                        $chapter->$attribute = null;
                    }
                }

                $data = [
                    'chapter' => $chapter
                ];

                return response()->json($data, 200);
            }
        }

        abort(403);
    }

    public function getAllByWebinarId($webinar_id)
    {
        $user = auth()->guard('lms_user')->user();

        $webinar = Webinar::find($webinar_id);

        if (!empty($webinar) and $webinar->canAccess($user)) {

            $chapters = $webinar->chapters->where('status', WebinarChapter::$chapterActive);

            $data = [
                'chapters' => [],
            ];

            if (!empty($chapters) and count($chapters)) {
                // for translate send on array of data

                foreach ($chapters as $chapter) {
                    $data['chapters'][] = [
                        'user_id' => $chapter->user_id,
                        'webinar_id' => $chapter->webinar_id,
                        'id' => $chapter->id,
                        'order' => $chapter->order,
                        'status' => $chapter->status,
                        'title' => $chapter->title,
                        'type' => $chapter->type,
                        'created_at' => $chapter->created_at,
                    ];
                }
            }

            return response()->json($data, 200);
        }

        abort(403);
    }

    public function store(Request $request)
    {
        $user = auth()->guard('lms_user')->user();
        $data = $request->get('ajax')['chapter'];

        $validator = Validator::make($data, [
            'webinar_id' => 'required',
            //'type' => 'required|' . Rule::in(WebinarChapter::$chapterTypes),
            'title' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $webinar = Webinar::find($data['webinar_id']);

        if (!empty($webinar) and $webinar->canAccess($user)) {
            $status = (!empty($data['status']) and $data['status'] == 'on') ? WebinarChapter::$chapterActive : WebinarChapter::$chapterInactive;

            $chapter = WebinarChapter::create([
                'user_id' => $user->id,
                'webinar_id' => $webinar->id,
                //'type' => $data['type'],
                'status' => $status,
                'check_all_contents_pass' => (!empty($data['check_all_contents_pass']) and $data['check_all_contents_pass'] == 'on'),
                'created_at' => time(),
            ]);

            if (!empty($chapter)) {
                WebinarChapterTranslation::updateOrCreate([
                    'webinar_chapter_id' => $chapter->id,
                    'locale' => mb_strtolower($data['locale']),
                ], [
                    'title' => $data['title'],
                ]);
            }

            return response()->json([
                'code' => 200,
            ], 200);
        }

        abort(403);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->guard('lms_user')->user();

        $data = $request->get('ajax')['chapter'];

        $validator = Validator::make($data, [
            'webinar_id' => 'required',
            //'type' => 'required|' . Rule::in(WebinarChapter::$chapterTypes),
            'title' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }


        $webinar = Webinar::find($data['webinar_id']);

        if (!empty($webinar) and $webinar->canAccess($user)) {

            $chapter = WebinarChapter::where('id', $id)
                ->where(function ($query) use ($user, $webinar) {
                    $query->where('user_id', $user->id);
                    $query->orWhere('webinar_id', $webinar->id);
                })
                ->first();

            if (!empty($chapter)) {
                $status = (!empty($data['status']) and $data['status'] == 'on') ? WebinarChapter::$chapterActive : WebinarChapter::$chapterInactive;

                $chapter->update([
                    'status' => $status,
                    'check_all_contents_pass' => (!empty($data['check_all_contents_pass']) and $data['check_all_contents_pass'] == 'on'),
                ]);

                WebinarChapterTranslation::updateOrCreate([
                    'webinar_chapter_id' => $chapter->id,
                    'locale' => mb_strtolower($data['locale']),
                ], [
                    'title' => $data['title'],
                ]);

                return response()->json([
                    'code' => 200
                ], 200);
            }
        }

        abort(403);
    }

    public function destroy($id)
    {
        $user = auth()->guard('lms_user')->user();

        $chapter = WebinarChapter::where('id', $id)->first();

        if (!empty($chapter)) {

            $webinar = Webinar::query()->find($chapter->webinar_id);

            if ($chapter->user_id == $user->id or (!empty($webinar) and $webinar->canAccess($user))) {

                $chapter->delete();

                return response()->json([
                    'code' => 200
                ], 200);
            }
        }

        abort(403);
    }

    public function change(Request $request)
    {
        $user = auth()->guard('lms_user')->user();
        $data = $request->get('ajax');

        $validator = Validator::make($data, [
            'item_id' => 'required',
            'item_type' => 'required',
            'chapter_id' => 'required',
            'webinar_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $item = null;

        $webinar = Webinar::find($data['webinar_id']);

        if (!empty($webinar) and $webinar->canAccess($user)) {

            switch ($data['item_type']) {
                case WebinarChapterItem::$chapterSession:
                    $item = Session::where('id', $data['item_id'])
                        ->where('webinar_id', $data['webinar_id'])
                        ->first();
                    break;

                case WebinarChapterItem::$chapterFile:
                    $item = File::where('id', $data['item_id'])
                        ->where('webinar_id', $data['webinar_id'])
                        ->first();
                    break;

                case WebinarChapterItem::$chapterTextLesson:
                    $item = TextLesson::where('id', $data['item_id'])
                        ->where('webinar_id', $data['webinar_id'])
                        ->first();
                    break;

                case WebinarChapterItem::$chapterQuiz:
                    $item = Quiz::where('id', $data['item_id'])
                        ->where('webinar_id', $data['webinar_id'])
                        ->first();
                    break;

                case WebinarChapterItem::$chapterAssignment:
                    $item = WebinarAssignment::where('id', $data['item_id'])
                        ->where('webinar_id', $data['webinar_id'])
                        ->first();
                    break;
            }
        }

        if (!empty($item)) {
            $item->update([
                'chapter_id' => !empty($data['chapter_id']) ? $data['chapter_id'] : null
            ]);

            WebinarChapterItem::where('item_id', $item->id)
                ->where('type', $data['item_type'])
                ->delete();

            if (!empty($data['chapter_id'])) {
                WebinarChapterItem::makeItem($user->id, $data['chapter_id'], $item->id, $data['item_type']);
            }
        }

        return response()->json([
            'code' => 200
        ], 200);
    }
}
