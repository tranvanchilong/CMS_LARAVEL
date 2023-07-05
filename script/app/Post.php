<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'type',
        'image',
        'content',
        'excerpt',
        'status',
        'featured',
        'user_id',
        'category_id',
        'is_admin',
        'lang_id',
        'meta_keyword',
        'meta_description',
        'serial_number',
        'created_at',
        'updated_at',
    ];
    public function scopeBlog($query)
    {
        return $query->where('type','blog');
    }
    public function scopePage($query)
    {
        return $query->where('type','page');
    }
    public function scopeGuide($query)
    {
        return $query->where('type','guide');
    }

    public function scopeLanguage($query,$language='')
    {
        return $query->where('lang_id', '=', null)->orWhere('lang_id','LIKE', '%'.$language.'%');
    }

    public function bcategory()
    {
        return $this->belongsTo('App\Category','category_id');
    }

    public function guide_category()
    {
        return $this->belongsTo('App\Category','category_id');
    }

    public function user() {
        return $this->belongsTo('App\User','user_id');
    }
}
