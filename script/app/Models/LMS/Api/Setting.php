<?php
namespace App\Models\LMS\Api ;
use App\Models\LMS\Setting as WebSetting ;
use App\Models\LMS\Scopes\ScopeDomain;

class Setting extends WebSetting {

    public static $register_method ;
    public static $offline_bank_account ;
    public static $user_language ;
    public static $payment_channels ;
    public static $minimum_payout_amount ;
    public static $currency ;

   public function __construct()
   {
       self::$register_method= 'ff' ;
    }

    public static function getRegisterMethodAttribute(){
     return   self::$register_method= 'ff' ;
    }

     
}