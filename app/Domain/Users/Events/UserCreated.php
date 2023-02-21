<?php

namespace App\Domain\Users\Events;

use Illuminate\Queue\SerializesModels;

class UserCreated
{
    use SerializesModels;

    /**
     * The authenticated user.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    public $user;

    public $password;


    /**
     * Create a new event instance.
     *
     * @param mixed $lead
     * @param mixed $user
     *
     * @return void
     */
    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password;

    }
}
