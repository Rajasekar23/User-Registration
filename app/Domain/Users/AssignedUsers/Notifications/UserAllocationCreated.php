<?php

namespace App\Domain\Users\AssignedUsers\Notifications;

use App\Core\Enums\DateFormatEnum;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserAllocationCreated extends Notification
{
    use Queueable;

    private $assignedUser;

    public function __construct($assignedUser)
    {
        $this->assignedUser = $assignedUser;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $timezone = date_default_timezone_get();

        return [
            'message' => "Hi {$notifiable->name}, ".$this->assignedUser->user->name.' work has been assigned to you',
            'url' => '/users/myprofile',
            'id' => '',
            'type' => 'userAllocations',
            'icon' => 'people',
            'assigned_by' => $this->assignedUser->createdBy->name,
            'assigned_at' => Carbon::now()->setTimezone($timezone)->Format(DateFormatEnum::LONG12FORMATDD),
        ];
    }
}
