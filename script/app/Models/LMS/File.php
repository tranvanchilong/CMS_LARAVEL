<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Models\LMS\Traits\SequenceContent;
use App\Models\LMS\Scopes\ScopeDomain;

class File extends Model implements TranslatableContract
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        File::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    use Translatable;
    use SequenceContent;

    public $timestamps = false;
    protected $table = 'lms_files';
    protected $guarded = ['id'];

    static $accessibility = [
        'free', 'paid'
    ];

    static $videoTypes = ['mp4', 'mkv', 'avi', 'mov', 'wmv', 'avchd', 'flv', 'f4v', 'swf', 'mpeg-2', 'webm', 'video'];
    static $fileTypes = [
        'pdf', 'powerpoint', 'sound', 'video', 'image', 'archive', 'document', 'project'
    ];

    static $fileSources = [
        'upload', 'youtube', 'vimeo', 'external_link', 'google_drive', 'iframe', 's3', 'secure_host'
    ];

    static $Active = 'active';
    static $Inactive = 'inactive';
    static $fileStatus = ['active', 'inactive'];

    public $translatedAttributes = ['title', 'description'];

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }

    public function getDescriptionAttribute()
    {
        return getTranslateAttributeValue($this, 'description');
    }


    public function chapter()
    {
        return $this->belongsTo('App\Models\LMS\WebinarChapter', 'chapter_id', 'id');
    }

    public function learningStatus()
    {
        return $this->hasOne('App\Models\LMS\CourseLearning', 'file_id', 'id');
    }

    public function isVideo()
    {
        return (in_array($this->file_type, self::$videoTypes));
    }

    public function getFileDuration()
    {
        $duration = 0;

        if ($this->storage == 'upload') {
            $file_path = get_public_path_lms($this->file);

            $getID3 = new \getID3;
            $file = $getID3->analyze($file_path);

            if (!empty($file) and !empty($file['playtime_seconds'])) {
                $duration = $file['playtime_seconds'];
            }
        }

        return convertMinutesToHourAndMinute($duration);
    }

    public function getIconByType($type = null)
    {
        $icon = 'file';

        if (empty($type)) {
            $type = $this->file_type;
        }

        if (!empty($type)) {
            if (in_array($type, ['pdf', 'powerpoint', 'document'])) {
                $icon = 'file-text';
            } else if (in_array($type, ['sound'])) {
                $icon = 'volume-2';
            } else if (in_array($type, ['video'])) {
                $icon = 'film';
            } else if (in_array($type, ['image'])) {
                $icon = 'image';
            } else if (in_array($type, ['archive'])) {
                $icon = 'archive';
            }
        }

        return $icon;
    }

    public function checkPassedItem()
    {
        $result = false;

        if (auth()->guard('lms_user')->check()) {
            $check = $this->learningStatus()->where('user_id', auth()->guard('lms_user')->id())->count();

            $result = ($check > 0);
        }

        return $result;
    }
}
