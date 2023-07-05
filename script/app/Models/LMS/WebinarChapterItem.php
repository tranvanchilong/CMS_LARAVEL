<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class WebinarChapterItem extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        WebinarChapterItem::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_webinar_chapter_items';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    static $chapterFile = 'file';
    static $chapterSession = 'session';
    static $chapterTextLesson = 'text_lesson';
    static $chapterQuiz = 'quiz';
    static $chapterAssignment = 'assignment';

    static public function makeItem($userId, $chapterId, $itemId, $type)
    {
        $order = WebinarChapterItem::where('chapter_id', $chapterId)->count() + 1;

        WebinarChapterItem::updateOrCreate([
            'user_id' => $userId,
            'chapter_id' => $chapterId,
            'item_id' => $itemId,
            'type' => $type,
        ], [
            'order' => $order,
            'created_at' => time()
        ]);
    }

    static public function changeChapter($userId, $oldChapterId, $newChapterId, $itemId, $type)
    {
        $chapterItem = WebinarChapterItem::query()
            ->where('user_id', $userId)
            ->where('chapter_id', $oldChapterId)
            ->where('item_id', $itemId)
            ->where('type', $type)
            ->first();

        if (!empty($chapterItem)) {
            $order = WebinarChapterItem::where('chapter_id', $newChapterId)->count() + 1;

            $chapterItem->update([
                'chapter_id' => $newChapterId,
                'order' => $order,
            ]);
        } else {
            WebinarChapterItem::makeItem($userId, $newChapterId, $itemId, $type);
        }
    }

    public function session()
    {
        return $this->belongsTo('App\Models\LMS\Session', 'item_id', 'id');
    }

    public function file()
    {
        return $this->belongsTo('App\Models\LMS\File', 'item_id', 'id');
    }

    public function textLesson()
    {
        return $this->belongsTo('App\Models\LMS\TextLesson', 'item_id', 'id');
    }

    public function assignment()
    {
        return $this->belongsTo('App\Models\LMS\WebinarAssignment', 'item_id', 'id');
    }

    public function quiz()
    {
        return $this->belongsTo('App\Models\LMS\Quiz', 'item_id', 'id');
    }

    public function chapter()
    {
        return $this->belongsTo('App\Models\LMS\WebinarChapter', 'chapter_id', 'id');
    }
}
