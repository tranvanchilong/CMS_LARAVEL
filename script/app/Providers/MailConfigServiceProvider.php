<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use App\Useroption;
use Illuminate\Support\Facades\Auth;
use App\Domain;
use App\Models\User;

class MailConfigServiceProvider extends ServiceProvider
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
                $status_mail_config = User::where('id', $domain->user_id)->first();
                $mail_configs = Useroption::where('user_id', $domain->user_id)->where('key','mail_config')->first();
                $mail_configs=json_decode($mail_configs->value ?? '');
                if($status_mail_config->mail_configuration == 0){
                    $config = array(
                        'driver' => 'SMTP',
                        'host' => env('MAIL_HOST', 'smtp.mailgun.org'),
                        'port' => env('MAIL_PORT', 587),
                        'username' => env('MAIL_USERNAME'),
                        'password' => env('MAIL_PASSWORD'),
                        'encryption' => env('MAIL_ENCRYPTION', 'tls'),
                        'from' => array('address' => env('MAIL_FROM_ADDRESS'), 'name' => env('MAIL_FROM_NAME')),
                        'sendmail' => '/usr/sbin/sendmail -bs',
                        'pretend' => false,
                    );
                    Config::set('mail', $config);
                }

                if($status_mail_config->mail_configuration == 1){
                    $config = array(
                        'driver' => $mail_configs->driver,
                        'host' => $mail_configs->host,
                        'port' => $mail_configs->port,
                        'username' => $mail_configs->username,
                        'password' => $mail_configs->password,
                        'encryption' => $mail_configs->encryption,
                        'from' => array('address' => $mail_configs->mail_from_address, 'name' => $mail_configs->mail_from_name),
                        'sendmail' => '/usr/sbin/sendmail -bs',
                        'pretend' => false,
                    );
                    Config::set('mail', $config);
                }
            } catch (\Exception $ex) {

            }
        
    }
}
