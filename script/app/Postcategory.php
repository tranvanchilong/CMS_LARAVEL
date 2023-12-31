<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Postcategory extends Model
{
    public $timestamps = false;
    protected $fillable = ['term_id','category_id'];
    protected $primaryKey = 'term_id';

    public function category()
    {
    	return $this->belongsTo('App\Category')->where('type','category');
    }
}
