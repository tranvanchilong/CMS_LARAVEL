<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
class Stock extends Model
{
   protected $fillable = [
      'term_id',
      'stock_manage',
      'stock_status',
      'stock_qty',
      'sku',
      'variation_id_code',

  ];
   protected $casts = [
      'variation_id_code' => 'array',
  ];
   public $timestamps = false;

   public function attribute()
   {
   	return $this->hasOne('App\Attribute','id','attribute_id');
   }
   
   public function attributes()
   {
   	return $this->hasOne('App\Attribute','id','attribute_id')->where('user_id',Auth::id())->with('product');
   }

   public function term()
   {
   	return $this->belongsTo('App\Term')->with('preview');
   }
}
