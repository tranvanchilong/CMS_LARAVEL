<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewCourse extends Model
{
    use HasFactory;
    protected $filltable = 'review_courses';
    protected $fillable = [
        'user_id',
        'course_id',
        'rating',
        'name',
        'email',
		'comment'
    ];

    public function getHumanDate()
	{
		return $this->created_at->diffForHumans();
	}
    public function course(){
		return $this->belongsTo('App\Course','course_id','id');
	}
}
