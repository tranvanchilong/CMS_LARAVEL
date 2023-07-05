<?php

namespace App\Models\LMS;

use App\Mixins\Cart\CartItemInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use App\Models\LMS\Scopes\ScopeDomain;

class Gift extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ScopeDomain);
    }

    protected static function boot()
    {
        parent::boot();

        Gift::creating(function($model) {
            $model->domain_id = domain_info('domain_id');
        });
    }

    protected $table = "lms_gifts";

    public $timestamps = false;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo('App\Models\LMS\User', 'user_id', 'id');
    }

    public function receipt()
    {
        return $this->belongsTo('App\Models\LMS\User', 'email', 'email');
    }

    public function sale()
    {
        return $this->hasOne('App\Models\LMS\Sale', 'gift_id', 'id');
    }

    public function webinar()
    {
        return $this->belongsTo('App\Models\LMS\Webinar', 'webinar_id', 'id');
    }

    public function bundle()
    {
        return $this->belongsTo('App\Models\LMS\Bundle', 'bundle_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\LMS\Product', 'product_id', 'id');
    }


    public function getItemTitle()
    {
        $title = '';

        if (!empty($this->webinar_id)) {
            $title = $this->webinar->title;
        } else if (!empty($this->bundle_id)) {
            $title = $this->bundle->title;
        } else if (!empty($this->product_id)) {
            $title = $this->product->title;
        }

        return $title;
    }

    public function getItemType()
    {
        $type = 'course';

        if (!empty($this->bundle_id)) {
            $type = 'bundle';
        } else if (!empty($this->product_id)) {
            $type = 'product';
        }

        return $type;
    }

    public function sendNotificationsWhenActivated($amount)
    {
        $this->sendReminderToRecipient($amount);
        $this->sendReminderToSender($amount);
        $this->sendReminderToAdmin($amount, 'admin_gift_submission');
    }

    public function sendReminderToRecipient($amount)
    {
        if (empty($this->date) or $this->date < time()) {
            $receipt = $this->receipt;

            $notifyOptions = [
                '[u.name]' => $this->user->full_name,
                '[gift_title]' => $this->getItemTitle(),
                '[gift_type]' => $this->getItemType(),
                '[amount]' => (!empty($amount) and $amount > 0) ? handlePrice($amount) : trans('public.free'),
                '[gift_message]' => $this->description,
            ];

            if (!empty($receipt)) {
                sendNotification('reminder_gift_to_receipt', $notifyOptions, $receipt->id);
            } else if (!empty($this->email)) {
                sendNotificationToEmail('reminder_gift_to_receipt', $notifyOptions, $this->email);
            }
        }
    }

    public function sendReminderToSender($amount, $template = "gift_sender_confirmation")
    {
        $sender = $this->user;

        $notifyOptions = [
            '[u.name]' => $this->name,
            '[u.email]' => $this->email,
            '[gift_title]' => $this->getItemTitle(),
            '[gift_type]' => $this->getItemType(),
            '[amount]' => (!empty($amount) and $amount > 0) ? handlePrice($amount) : trans('public.free'),
            '[gift_message]' => $this->description,
            '[time.date]' => dateTimeFormat($this->created_at, "j M Y H:i"), // send date
            '[time.date.2]' => !empty($this->date) ? dateTimeFormat($this->date, "j M Y H:i") : trans('update.instantly'), // gift publish date
        ];

        if (!empty($sender)) {
            sendNotification($template, $notifyOptions, $sender->id);
        }
    }

    public function sendReminderToAdmin($amount, $template)
    {
        $sender = $this->user;

        $notifyOptions = [
            '[u.name]' => $this->name,
            '[u.name.2]' => $sender->full_name,
            '[u.email]' => $this->email,
            '[gift_title]' => $this->getItemTitle(),
            '[gift_type]' => $this->getItemType(),
            '[amount]' => (!empty($amount) and $amount > 0) ? handlePrice($amount) : trans('public.free'),
            '[time.date]' => dateTimeFormat($this->created_at, "j M Y H:i"), // send date
            '[time.date.2]' => !empty($this->date) ? dateTimeFormat($this->date, "j M Y H:i") : trans('update.instantly'), // gift publish date
        ];

        if (!empty($sender)) {
            sendNotification($template, $notifyOptions, 1);
        }
    }

}
