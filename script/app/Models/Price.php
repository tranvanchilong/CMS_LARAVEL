<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    protected $fillable = [
        'term_id',
        'variation_id_code',
        'price',
        'regular_price',
        'special_price',
        'price_type',
        'starting_date',
        'ending_date',
        'sku',

    ];
    protected $appends = ['translations'];
    protected $casts = [
        'variation_id_code' => 'array',
    ];

    public function getTranslationsAttribute(){
		return [];
	}

    public function term(){
        return $this->belongsTo('App\Term')->with('category', 'preview', 'reviews', 'attributes');
    }

    public function product(){
        return $this->belongsTo('App\Term')->with('category', 'preview', 'reviews', 'attributes');
    }

    public function category()
    {
        return $this->hasOne('App\Postcategory')->whereHas('category')->with('category');
    }

    public function preview()
    {
        return $this->hasOne('App\Postmedia')->with('media');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review');
    }

    public function attributes()
	{
		return $this->hasMany('App\Attribute','term_id')->with('attribute','variation');
	}
}
