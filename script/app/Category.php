<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Attribute;
use DB;

class Category extends Model
{
    // protected $hidden = ['menu_status'];
    protected $appends = ['childes', 'translations', 'icon', 'image', 'photo', 'banner_type', 'content', 'status_add_money'];

    // public function getHomeStatusAttribute(){
    // 	return $this->menu_status;
    // }
    public function getPhotoAttribute()
    {
        return $this->name;
    }

    public function getBannerTypeAttribute()
    {
        return $this->type;
    }

    public function getTranslationsAttribute()
    {
        return [];
    }

    public function getChildesAttribute()
    {
        return [];
    }

    public function getIconAttribute()
    {
        return $this->preview ? $this->preview->content : null;
    }

    public function getImageAttribute()
    {
        return [$this->preview ? $this->preview->content : null];
    }

    public function getContentAttribute()
    {
        return $this->actives_deposit ? json_decode($this->actives_deposit->content) : null;
    }

    public function getStatusAddMoneyAttribute()
    {
        return $this->actives_deposit ? $this->actives_deposit->status_add_money : null;
    }

    // public function getUnitAttribute(){
    // 	return 'kg';
    // }

    public function category_ids()
    {
        return $this->belongsToMany('App\Category', 'postcategories')->where('type', 'category')->select('id', DB::raw("1 as position"));
    }

    public function products()
    {
        return $this->belongsToMany('App\Term', 'postcategories')->with('category_ids', 'options', 'stock', 'affiliate')->select('id', DB::raw("'admin' as added_by"), 'user_id', 'title as name', 'slug', 'status', 'featured', 'created_at', 'updated_at');
    }

    public function posts()
    {
        return $this->hasMany('App\Postcategory');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'p_id', 'id');
    }

    public function parent()
    {
        return $this->hasOne(Category::class, 'id', 'p_id');
    }

    public function childrenCategories()
    {
        return $this->hasMany(Category::class, 'p_id', 'id')->with('categories')->withCount('posts');
    }

    public function featured_child_attribute()
    {
        return $this->hasOne(Category::class, 'p_id', 'id')->where('featured', 1);
    }

    public function featured_child_with_post_count_attribute()
    {
        return $this->hasMany(Category::class, 'p_id', 'id')->where('featured', 1)->withCount('variations');
    }

    public function value()
    {
        return $this->hasMany(Category::class, 'p_id', 'id')->select('id', 'name', 'p_id')->where('featured', 1)->withCount('variations');
    }

    public function variations()
    {
        return $this->hasMany('App\Attribute', 'variation_id', 'id');
    }

    public function parent_variation()
    {
        return $this->hasMany('App\Attribute', 'category_id', 'id');
    }


    public function parent_relation()
    {
        return $this->hasMany(Categoryrelation::class, 'category_id', 'id');
    }
    public function child_relation()
    {
        return $this->belongsToMany(Category::class, Categoryrelation::class, 'relation_id');
    }

    public function gateway_users()
    {
        return $this->hasMany('App\Getway');
    }

    public function preview()
    {
        return $this->hasOne('App\Categorymeta')->where('type', 'preview')->select('category_id', 'type', 'content');
    }
    public function description()
    {
        return $this->hasOne('App\Categorymeta')->where('type', 'description')->select('category_id', 'type', 'content');
    }

    public function credentials()
    {
        return $this->hasOne('App\Categorymeta')->where('type', 'credentials')->select('category_id', 'type', 'content');
    }

    public function excerpt()
    {
        return $this->hasOne('App\Categorymeta')->where('type', 'excerpt')->select('category_id', 'type', 'content');
    }


    public function active_getway()
    {
        return $this->hasOne('App\Getway')->where('user_id', Auth::id());
    }

    public function actives_getway()
    {
        return $this->hasOne('App\Getway')->where('user_id', domain_info('user_id'))->where('status', 1);
    }

    public function actives_deposit()
    {
        return $this->hasOne('App\Getway')->where('user_id', domain_info('user_id'))->where('status_add_money', 1);
    }

    public function attributes()
    {
        return $this->hasMany('App\Attribute');
    }

    public function take_20_product()
    {
        return $this->belongsToMany('App\Term', 'postcategories')->with('preview', 'attributes')->take(15);
    }

    public function product()
    {
        return $this->belongsToMany('App\Term', 'postcategories')->with('preview', 'attributes');
    }
    public function blogs()
    {
        return $this->hasMany('App\Post', 'category_id');
    }
    public function portfolios()
    {
        return $this->hasMany('App\Portfolio', 'category_id');
    }
    public function courses()
    {
        return $this->hasMany('App\Course', 'category_id');
    }
    public function careers()
    {
        return $this->hasMany('App\Career', 'category_id');
    }
    public function scopeLanguage($query, $language = '')
    {
        return $query->where('lang_id', '=', null)->orWhere('lang_id', 'LIKE', '%' . $language . '%');
    }
    public function bookings()
    {
        return $this->hasMany('App\Booking', 'category_service_id', 'id');
    }
    public function services()
    {
        return $this->hasMany('App\Service', 'category_id', 'id');
    }
    public function category_gallery() {
        return $this->belongsTo('App\Category','p_id');
    }
    public function packages()
    {
        return $this->hasMany('App\Package', 'category_id');
    }
    public function services_booking()
    {
        return $this->hasMany('App\Service', 'category_id', 'id');
    }
    public function guides()
    {
        return $this->hasMany('App\Post', 'category_id', 'id');
    }
}
