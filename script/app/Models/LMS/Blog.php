<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Jorenvh\Share\ShareFacade;
use App\Models\LMS\Scopes\ScopeDomain;

class Blog extends Model implements TranslatableContract
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        Blog::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    
    use Translatable;
    use Sluggable;

    protected $table = 'lms_blog';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public $translatedAttributes = ['title', 'description', 'meta_description', 'content'];

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

    public function category()
    {
        return $this->belongsTo('App\Models\LMS\BlogCategory', 'category_id', 'id');
    }

    public function author()
    {
        return $this->belongsTo('App\Models\LMS\User', 'author_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\LMS\Comment', 'blog_id', 'id');
    }

    public function getUrl()
    {
        return '/blog/' . $this->slug;
    }

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }

    public function getDescriptionAttribute()
    {
        return getTranslateAttributeValue($this, 'description');
    }

    public function getMetaDescriptionAttribute()
    {
        return getTranslateAttributeValue($this, 'meta_description');
    }

    public function getContentAttribute()
    {
        return getTranslateAttributeValue($this, 'content');
    }

    public function getShareLink($social)
    {
        $link = ShareFacade::page(url($this->getUrl()), $this->title)
            ->facebook()
            ->twitter()
            ->whatsapp()
            ->telegram()
            ->getRawLinks();

        return !empty($link[$social]) ? $link[$social] : '';
    }
}
