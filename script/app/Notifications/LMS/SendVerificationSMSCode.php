<?php

namespace App\Notifications\LMS;

use App\Channels\TwilioSMSChannel;
use Illuminate\Notifications\Notification;

class SendVerificationSMSCode extends Notification
{
    private $notifiable;

    /**
     * Create a new notification instance.
     *
     * @param $notifiable
     */
    public function __construct($notifiable)
    {
        $this->notifiable = $notifiable;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [TwilioSMSChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     */
    public function toTwilioSMS($notifiable)
    {
        $generalSettings = getGeneralSettings();
        $siteName = $generalSettings['site_name'] ?? '';

        $content = trans('lms/update.code') . ': ' . $notifiable->code;
        $content .= PHP_EOL;
        $content .= trans('lms/update.your_validation_code_on_the_site', ['site' => $siteName]);

        return [
            'to' => $notifiable->mobile,
            'content' => $content,
        ];
    }
}
