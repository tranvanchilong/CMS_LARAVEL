<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Term;

class BlogController extends Controller
{
    public function get_blogs(Request $request)
    {
        $blogs = Helper::get_blogs_latest($request['limit'], $request['offset']);
        return response()->json($blogs, 200);
    }

    public function get_blog_detail($domain, $slug)
    {
        try {

            $blog_detail = Term::where('user_id',domain_info('user_id'))->where('status',1)->where('type','blog')->with('preview','bcategories')->where('slug', $slug)->first();

            return response()->json($blog_detail,200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }
}
