<?php

namespace App\Models\LMS\Api;

use App\Models\LMS\Ticket as Model;
use App\Models\LMS\Scopes\ScopeDomain;

class Ticket extends Model
{
    //
    public function getDetailsAttribute(){

       // dd($this->webinar);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'sub_title' => $this->getSubTitle(),
            'discount' => $this->discount,
            //  'price_with_ticket_discount'=>$this->price -  ($ticket->discount) * $this->price/100 ,
          //  'price_with_ticket_discount' => $this->price - $this->getDiscount($ticket),
            'price_with_ticket_discount' => $this->webinar->price - $this->webinar->getDiscount($this),

            //  'order' => $ticket->order,
            'is_valid' => $this->isValid(),

        ];
    }

    public function webinar()
    {
        return $this->belongsTo('App\Models\LMS\Api\Webinar', 'webinar_id', 'id');
    }
}
