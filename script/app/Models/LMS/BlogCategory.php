<?php

namespace App\Models\LMS;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class BlogCategory extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        BlogCategory::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = 'lms_blog_categories';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];


    use Sluggable;

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

    public function blog()
    {
        return $this->hasMany('App\Models\LMS\Blog', 'category_id', 'id');
    }

    public function getUrl()
    {
        return '/blog/categories/' . $this->slug;
    }
}
