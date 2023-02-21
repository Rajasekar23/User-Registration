<?php

namespace App\Domain\Users\AssignedUsers\Repository;

use App\Core\CoreValidator;

/**
 * AssignedUserValidator Class.
 */
class AssignedUserValidator extends CoreValidator
{
    /**
     * Hold the commonrules.
     *
     * @var array
     */
    public $commonRules = [
        'user_id' => 'required',
        // 'assigned_id' => 'required|different:user_id',
        'from_date' => 'required',
        'to_date' => 'required',
    ];

    /**
     * Boot function.
     *
     * @return void
     */
    protected function boot()
    {
        $this->rules = [];
    }

    /**
     * Custom validaiton goes here.
     *
     * @param mixed $action string
     *
     * @return bool
     */
    public function passes($action = null)
    {
        parent::passes($action);

        return true;
    }
}
