<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ReviewCourse;
use Session;

class ReviewCourseController extends Controller
{
    public function store(Request $request)
    {

        $id=request()->route()->parameter('id');
        $validated = $request->validate([
            'name' => 'required|max:50',
            'rating' => 'required|max:50',
            'email' => 'required|email|max:50',
            'comment' => 'required|max:250',
        ]);


        $user_id=domain_info('user_id');

        $rating = new ReviewCourse;
        $rating->user_id = $user_id;
        $rating->course_id = $request->course_id;
        $rating->rating = $request->rating;
        $rating->name = $request->name;
        $rating->email = $request->email;
        $rating->comment = $request->comment;
        $rating->save();
        Session::flash('success', 'Thanks For Your Review');
        return redirect()->back();
    }
}
