<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
	use HasFactory;

	protected $fillable = [
        'user_id',
        'term_id',
        'rating',
        'name',
        'email',
		'comment'
    ];

	public function getHumanDate()
	{
		return $this->created_at->diffForHumans();
	}

	public function post(){
		return $this->belongsTo('App\Term','term_id','id');
	}
}
