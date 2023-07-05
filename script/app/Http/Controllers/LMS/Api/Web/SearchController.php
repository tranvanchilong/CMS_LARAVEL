<?php

namespace App\Http\Controllers\LMS\Api\Web;

use App\Http\Controllers\LMS\Api\Objects\UserObj;
use App\Http\Controllers\LMS\Api\Objects\WebinarObj;
use App\Http\Controllers\LMS\Controller;
use App\Models\LMS\Role;
use App\Models\LMS\Api\Webinar;
use App\Models\LMS\Api\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function list(Request $request)
    {
        $data = [];
        $search = $request->get('search', null);

        if(strlen($search) < 3){
         //   return apiResponse2(1, 'too_short', trans('lms/api.search.too_short'));

        }

        $webinars=[] ;
        $users=[] ;
        $teachers=[] ;
        $organizations=[] ;

        if (!empty($search) and strlen($search) >= 3) {

            $webinars = Webinar::where('status', 'active')
                ->where('private', false)
                ->whereTranslationLike('title', "%$search%")
                ->get()->map(function($webinar){
                    return $webinar->brief ;
                });

 
            $all_users = User::where('status', 'active')
                ->where('full_name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('mobile', 'like', "%$search%") ;


            $users = $all_users->get()->map(function($user){
                return $user->brief ;
            });


            $teachers = $all_users->where('role_name', Role::$teacher)->get()
            ->map(function($teacher){

                return $teacher->brief ;
            }) ;
            ;
          
 
            $organizations = $all_users->where('role_name', Role::$organization)->get()
            ->map(function($organization){
                  return $organization->brief ;
            })
            ;
 


        }
        $data = [
            
            'webinars' =>
        [ 'webinars'=>$webinars ,
           'count'=>count($webinars)
        
        ]
        ,

        'users' =>
        [ 'users'=>$users ,
           'count'=>count($users)
        
        ]
        ,

        'teachers' =>
        [ 'teachers'=>$teachers ,
           'count'=>count($teachers)
        
        ]
        ,

        'organizations' =>
        [ 'organizations'=>$organizations ,
           'count'=>count($organizations)
        ]
        ,
        
        
        ];

        return apiResponse2(1, 'retrieved', trans('lms/api.public.retrieved'), $data);

    }

}
