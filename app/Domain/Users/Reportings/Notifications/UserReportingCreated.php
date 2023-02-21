<?php

namespace App\Domain\Users\Reportings\Notifications;

use App\Core\Enums\DateFormatEnum;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserReportingCreated extends Notification
{
    use Queueable;

    private $userReporting;

    public function __construct($userReporting)
    {
        $this->userReporting = $userReporting;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $timezone = date_default_timezone_get();

        return [
            'message' => "Hi {$notifiable->name}, from now your reporting person will be ".$this->userReporting->reportingTo->name,
            'url' => '/users/myprofile',
            'id' => '',
            'type' => 'USER_MAPPING',
            'icon' => 'people',
            'assigned_by' => $this->userReporting->createdBy->name,
            'assigned_at' => Carbon::now()->setTimezone($timezone)->Format(DateFormatEnum::LONG12FORMATDD),
        ];
    }
}
