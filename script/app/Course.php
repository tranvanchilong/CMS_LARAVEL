<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $table = 'courses';
    protected $fillable = [
        'title',
        'slug',
        'image',
        'content',
        'summary',
        'video_link',
        'overview',
        'featured',
        'video_link',
        'current_price',
        'previous_price'
    ];


    public function user() {
        return $this->belongsTo('App\User','user_id');
    }
    public function category() {
        return $this->belongsTo('App\Category','category_id');
    }
    public function scopeLanguage($query,$language='')
    {
        return $query->where('lang_id', '=', null)->orWhere('lang_id','LIKE', '%'.$language.'%');
    }
    public function modules()
  {
    return $this->hasMany('App\Module');
  }
  public function instructor(){
    return $this->belongsTo('App\Team');
  }
  public function reviews()
{
    return $this->hasMany(ReviewCourse::class);
}
}
