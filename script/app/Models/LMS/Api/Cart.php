<?php

namespace App\Models\LMS\Api;

use App\Models\LMS\Cart as Model;
use App\Models\LMS\Scopes\ScopeDomain;

class Cart extends Model
{

    public function getDetailsAttribute(){
       // dd($this->webinar->brief ) ;
        return [
            'id'=>$this->id  ,
            'user'=>$this->user->brief ,
            'webinar'=>$this->webinar->brief??null ,
            'price'=>$this->price ,
            'discount'=>$this->discount ,
            'meeting'=>$this->reserveMeeting->details??null


        ] ;
    }

    public function getDiscountAttribute(){
        if($this->webinar_id){
        return $this->webinar->price - $this->webinar->getDiscount($this->ticket) ;
        }
        return null ;
      //  $cart->webinar->price - $cart->webinar->getDiscount($cart->ticket), 2, ".", ""
    }
    public function getPriceAttribute(){
        if($this->webinar_id){
            return $this->webinar->price  ;
        }
        return $this->reserveMeeting->paid_amount ;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\LMS\Api\User', 'creator_id', 'id');
    }

    public function webinar()
    {
        return $this->belongsTo('App\Models\LMS\Api\Webinar', 'webinar_id', 'id');
    }

    public function reserveMeeting()
    {
        return $this->belongsTo('App\Models\LMS\Api\ReserveMeeting', 'reserve_meeting_id', 'id');
    }

    public function ticket()
    {
        return $this->belongsTo('App\Models\LMS\Ticket', 'ticket_id', 'id');
    }

}
