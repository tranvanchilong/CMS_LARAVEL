<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use App\Models\LMS\Scopes\ScopeDomain;

class TicketUser extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        TicketUser::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    public $timestamps = false;
    protected $table = 'lms_ticket_users';

    protected $guarded = ['id'];

    public static function useTicket($orderItem)
    {
        if ($orderItem->ticket_id) {
            TicketUser::create([
                'ticket_id' => $orderItem->ticket_id,
                'user_id' => $orderItem->user_id,
                'created_at' => time()
            ]);
        }
    }
}
