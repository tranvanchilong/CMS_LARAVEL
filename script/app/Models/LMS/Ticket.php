<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Models\LMS\Scopes\ScopeDomain;

class Ticket extends Model implements TranslatableContract
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        Ticket::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    use Translatable;

    public $timestamps = false;
    protected $table = 'lms_tickets';
    protected $guarded = ['id'];

    public $translatedAttributes = ['title'];

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }

    public function isValid()
    {
        $now = time();
        $ticket = $this;
        $valid = true;

        if ($ticket->start_date > $now or $this->end_date < $now) {
            $valid = false;
        }

        if ($ticket->capacity) {
            $ticketUserCount = TicketUser::where('ticket_id', $ticket->id)->count();

            if ($ticketUserCount and $ticket->capacity <= $ticketUserCount) {
                $valid = false;
            }
        }

        return $valid;
    }

    public function getSubTitle()
    {
        $title = '';

        if (!empty($this->end_date) and !empty($this->capacity)) {
            $title = trans('public.ticket_for_students_until_date', ['students' => $this->capacity, 'date' => dateTimeFormat($this->end_date, 'j F Y')]);
        } elseif (!empty($this->end_date)) {
            $title = trans('public.ticket_until_date', ['date' => dateTimeFormat($this->end_date, 'j F Y')]);
        }

        return $title;
    }
}
