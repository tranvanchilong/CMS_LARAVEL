<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class WebinarAssignmentAttachment extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        WebinarAssignmentAttachment::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_webinar_assignment_attachments';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public function getDownloadUrl()
    {
        return "/course/assignment/{$this->assignment_id}/download/{$this->id}/attach";
    }

    public function getFileSize()
    {
        $size = null;

        $file_path = get_public_path_lms($this->attach);

        if (file_exists($file_path)) {
            $size = formatSizeUnits(filesize($file_path));
        }

        return $size;
    }
}
