<?php

namespace App\Http\Controllers\LMS\Api\Web;

use App\Http\Controllers\LMS\Api\Objects\UserObj;
use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Api\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request){
        $blog = Blog::where('status', 'publish')->handleFilters()

        ->orderBy('updated_at', 'desc')
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function($blog){
            return $blog->details;
        }) ;



        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'), $blog);


    }
    public function show($id){
        $blog=Blog::find($id) ;
        abort_unless($blog,404) ;


        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'), [
            'blog'=>$blog->details
        ]);



    }
    public function list(Request $request, $id = null)
    {

        $query = Blog::where('status', 'publish')
            ->orderBy('updated_at', 'desc')
            ->orderBy('created_at', 'desc');

        if (isset($id)) {
            $query = $query->where('id', $id)->get();
            if (!$query->count()) {
                abort(404);
            }
            $blogs = self::details($query,true);

        } else {
            $query = $this->handleFilters($request, $query);
            $blogs = self::details($query->get());
        }

        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'), $blogs);

    }

    public function handleFilters($request, $query)
    {
        $offset = $request->get('offset', null);
        $limit = $request->get('limit', null);

        if (!empty($offset) && !empty($limit)) {
            $query->skip($offset);
        }
        if (!empty($limit)) {
            $query->take($limit);
        }
        return $query;
    }

    public static function details($blogs,$single=false)
    {
        $blogs = $blogs->map(function ($blog) {

            return [
                'id' => $blog->id,
                'title' => $blog->title,
                'image' => url(get_path_lms().$blog->image),
                'description' => truncate($blog->description, 160),
                'content' => $blog->content,
                'created_at' => $blog->created_at,
                'author' => UserObj::brief($blog->author, true),
                'comment_count' => $blog->comments->count(),
                'comments' => $blog->comments->map(function ($item) {
                    return [
                        'user' => [
                            'full_name' => $item->user->full_name,
                            'avatar' => url(get_path_lms().$item->user->getAvatar()),
                        ],
                        'create_at' => $item->created_at,
                        'comment' => $item->comment,
                        'replies' => $item->replies->map(function ($reply) {
                            return [
                                'user' => [
                                    'full_name' => $reply->user->full_name,
                                    'avatar' => url(get_path_lms().$reply->user->getAvatar()),
                                ],
                                'create_at' => $reply->created_at,
                                'comment' => $reply->comment,
                            ];

                        })
                    ];
                }),
                'category'=>$blog->category->title ,
            ];

        });

        if ($single){
            return [
                'blog' => $blogs->first()
            ];
        }
        return [
            'count' => count($blogs),
            'blogs' => $blogs
        ];
    }

 }
