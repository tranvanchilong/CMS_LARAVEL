<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use App\Useroption;
use App\Domain;

class SocialLoginServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $parsedUrl = parse_url(url()->current());
        $host =  $parsedUrl['host'];
        $domain = Domain::where('domain', $host)->first();
        try {
            
            $social_login = Useroption::where('user_id', $domain->user_id)->where('key','social_login')->first();
            $social_login=json_decode($social_login->value ?? '');
            if ($social_login->status == 1 && $social_login->medium == 'google') {
                $google_config = array(
                    'client_id' => $social_login->client_id,
                    'client_secret' => $social_login->client_secret,
                    'redirect' => url('customer/google/callback'),
                );
                Config::set('services.google', $google_config);
            } 
        }catch(\Exception $exception){}
    }
}
