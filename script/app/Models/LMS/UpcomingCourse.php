<?php

namespace App\Models\LMS;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Jorenvh\Share\ShareFacade;
use Spatie\CalendarLinks\Link;
use App\Models\LMS\Scopes\ScopeDomain;

class UpcomingCourse extends Model implements TranslatableContract
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        UpcomingCourse::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    use Translatable;
    use Sluggable;

    protected $table = "lms_upcoming_courses";
    public $timestamps = false;
    protected $guarded = ['id'];

    static $active = 'active';
    static $pending = 'pending';
    static $isDraft = 'is_draft';
    static $inactive = 'inactive';

    static $webinar = 'webinar';
    static $course = 'course';
    static $textLesson = 'text_lesson';

    public $translatedAttributes = ['title', 'description', 'seo_description'];

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }

    public function getDescriptionAttribute()
    {
        return getTranslateAttributeValue($this, 'description');
    }

    public function getSeoDescriptionAttribute()
    {
        return getTranslateAttributeValue($this, 'seo_description');
    }

    public function creator()
    {
        return $this->belongsTo('App\Models\LMS\User', 'creator_id', 'id');
    }

    public function teacher()
    {
        return $this->belongsTo('App\Models\LMS\User', 'teacher_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\LMS\Category', 'category_id', 'id');
    }

    public function webinar()
    {
        return $this->belongsTo('App\Models\LMS\Webinar', 'webinar_id', 'id');
    }

    public function filterOptions()
    {
        return $this->hasMany('App\Models\LMS\UpcomingCourseFilterOption', 'upcoming_course_id', 'id');
    }

    public function followers()
    {
        return $this->hasMany('App\Models\LMS\UpcomingCourseFollower', 'upcoming_course_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\LMS\Comment', 'upcoming_course_id', 'id');
    }

    public function tags()
    {
        return $this->hasMany('App\Models\LMS\Tag', 'upcoming_course_id', 'id');
    }

    public function faqs()
    {
        return $this->hasMany('App\Models\LMS\Faq', 'upcoming_course_id', 'id');
    }

    public function favorite()
    {
        return $this->hasMany('App\Models\LMS\Favorite', 'upcoming_course_id', 'id');
    }

    public function extraDescriptions()
    {
        return $this->hasMany('App\Models\LMS\WebinarExtraDescription', 'upcoming_course_id', 'id');
    }


    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public static function makeSlug($title)
    {
        return SlugService::createSlug(self::class, 'slug', $title);
    }

    public function canAccess($user = null)
    {
        if (!$user) {
            $user = auth()->guard('lms_user')->user();
        }

        if (!empty($user)) {
            return ($this->creator_id == $user->id or $this->teacher_id == $user->id);
        }

        return false;
    }

    public function getImageCover()
    {
        return $this->image_cover;
    }

    public function getImage()
    {
        return $this->thumbnail;
    }

    public function getUrl()
    {
        return '/upcoming_courses/' . $this->slug;
    }

    public function addToCalendarLink()
    {

        $date = \DateTime::createFromFormat('j M Y H:i', dateTimeFormat($this->publish_date, 'j M Y H:i', false));

        $link = Link::create($this->title, $date, $date); //->description('Cookies & cocktails!')

        return $link->google();
    }

    public function getShareLink($social)
    {
        $link = ShareFacade::page($this->getUrl(), $this->title)
            ->facebook()
            ->twitter()
            ->whatsapp()
            ->telegram()
            ->getRawLinks();

        return !empty($link[$social]) ? $link[$social] : '';
    }
}
