<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Models\LMS\Traits\SequenceContent;
use App\Models\LMS\Scopes\ScopeDomain;

class WebinarAssignment extends Model implements TranslatableContract
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        WebinarAssignment::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    use Translatable;
    use SequenceContent;

    protected $table = 'lms_webinar_assignments';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public $translatedAttributes = ['title', 'description'];

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }

    public function getDescriptionAttribute()
    {
        return getTranslateAttributeValue($this, 'description');
    }


    public function webinar()
    {
        return $this->belongsTo('App\Models\LMS\Webinar', 'webinar_id', 'id');
    }

    public function chapter()
    {
        return $this->belongsTo('App\Models\LMS\WebinarChapter', 'chapter_id', 'id');
    }

    public function attachments()
    {
        return $this->hasMany('App\Models\LMS\WebinarAssignmentAttachment', 'assignment_id', 'id');
    }

    public function assignmentHistory()
    {
        return $this->hasOne('App\Models\LMS\WebinarAssignmentHistory', 'assignment_id', 'id');
    }

    public function instructorAssignmentHistories()
    {
        return $this->hasMany('App\Models\LMS\WebinarAssignmentHistory', 'assignment_id', 'id');
    }

    public function getAssignmentHistoryByStudentId($studentId)
    {
        return $this->assignmentHistory()
            ->where('student_id', $studentId)
            ->first();
    }

    public function getDeadlineTimestamp($user = null)
    {
        $deadline = null; // default can access

        if (empty($user)) {
            $user = auth()->guard('lms_user')->user();
        }

        if (!empty($this->deadline)) {
            $sale = Sale::where('buyer_id', $user->id)
                ->where('webinar_id', $this->webinar_id)
                ->whereNull('refund_at')
                ->first();

            if (!empty($sale)) {
                $deadline = strtotime("+{$this->deadline} days", $sale->created_at);
            } else {
                $deadline = false;
            }
        }

        return $deadline;
    }
}
