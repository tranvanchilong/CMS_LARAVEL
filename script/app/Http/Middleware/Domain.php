<?php

namespace App\Http\Middleware;

use App\Useroption;
use Closure;
use Illuminate\Http\Request;
use Cache;
use Session;
use DB;
use Auth;

class Domain
{

    public static $domain;
    public static $full_domain;
    public static $autoload_static_data;
    public static $position;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $domain=\Request::getHost();
        $full_domain= url('/');


        Domain::$domain=$domain;
        Domain::$full_domain=$full_domain;


        if ($full_domain==env('APP_URL') || $full_domain==env('APP_URL_WITHOUT_WWW')) {
            return $next($request);
        }
        if ($domain==env('APP_PROTOCOLESS_URL') || str_replace('www.','',$domain)==env('APP_PROTOCOLESS_URL')) {
            return $next($request);
        }



        $domain=str_replace('www.','',$domain);
        Domain::$domain=$domain;

        $data = \App\Domain::where('domain',Domain::$domain)->where('will_expire','>',now())->where('status',1)->with('theme')->first();
        if(!empty($data) && $data->is_maintainance_mode == 1 && !request()->is('maintenance') && !request()->session()->has('maintenance')  && !request()->is('login-from-admin')){
            return redirect('/maintenance');
        }

        if (!Cache::has(Domain::$domain)) {

            $value = Cache::remember(Domain::$domain, 3600*24,function () {
                $data = \App\Domain::where('domain',Domain::$domain)->where('will_expire','>',now())->where('status',1)->with('theme')->first();
                $customdomain = \App\Models\Requestdomain::where('domain',Domain::$domain)->where('status',1)->with('user','parentdomain')->first();

                if (empty($data) && empty($customdomain)) {
                    abort(401);
                }
                if(!empty($data)){
                    $info['domain_id']=$data->id;
                    $info['user_id']=$data->user_id;
                    $info['domain_name']= Domain::$domain;
                    $info['full_domain']= Domain::$full_domain;
                    $info['view_path']= $data->theme ? $data->theme->src_path : '';
                    $info['asset_path']= $data->theme ? $data->theme->asset_path : '';
                    $info['shop_type']=$data->shop_type;
                    $info['plan']=json_decode($data->data);
                    $info['template_id'] = $data->template_id;
                    $user_option = Useroption::where('user_id', $data->user_id)->where('key', 'local')->first();
                    if($user_option){
                        Session::put('locale',\Session::get('locale') ?? $user_option->value);
                        \App::setlocale(\Session::get('locale'));
                    }

                    return $info;
                }
                $custom = json_decode($customdomain->parentdomain->data);

                if(!empty($customdomain)){
                    if ($customdomain->parentdomain->will_expire < now() || $custom->custom_domain == 'false') {
                        return "expire";
                    }
                    $subdomain['domain_id']=$customdomain->domain_id;
                    $subdomain['user_id']=$customdomain->user_id;
                    $subdomain['domain_name']= Domain::$domain;
                    $subdomain['full_domain']= Domain::$full_domain;
                    $subdomain['view_path']=$customdomain->parentdomain->theme ? $customdomain->parentdomain->theme->src_path : '';
                    $subdomain['asset_path']=$customdomain->parentdomain->theme ? $customdomain->parentdomain->theme->asset_path : '';
                    $subdomain['shop_type']=$customdomain->parentdomain->shop_type;
                    $subdomain['plan']=json_decode($customdomain->parentdomain->data);
                    $subdomain['template_id'] = $customdomain->parentdomain->template_id;

                    $user_option = Useroption::where('user_id', $customdomain->parentdomain->user_id)->where('key', 'local')->first();
                    if($user_option){
                        Session::put('locale',\Session::get('locale') ?? $user_option->value);
                        \App::setlocale(\Session::get('locale'));
                    }

                    return $subdomain;
                }

            });
        }

        // if(!Cache::get(Domain::$domain)['template_id']){
        //     return redirect('/seller/setting/template');
        // }

        return $next($request);
    }
}
